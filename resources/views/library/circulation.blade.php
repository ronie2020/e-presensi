<x-app-layout>
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-10 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <a href="{{ route('library.dashboard') }}" class="px-3 py-1 bg-white/10 hover:bg-white/20 rounded-lg text-xs font-bold text-blue-100 transition flex items-center gap-2">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-blue-300 text-xs font-bold uppercase tracking-wider">Modul Transaksi</span>
                        </div>
                        <h1 class="text-3xl font-black tracking-tight flex items-center gap-3">
                            <span class="text-4xl">🔄</span> Sirkulasi Buku
                        </h1>
                        <p class="text-blue-200 text-sm font-medium mt-2 max-w-lg">
                            Proses peminjaman dan pengembalian buku secara cepat menggunakan pemindai barcode atau input manual.
                        </p>
                    </div>
                    <div class="hidden md:block">
                        <div class="bg-white/10 backdrop-blur-md p-4 rounded-2xl border border-white/10">
                            <i class="ph-duotone ph-barcode text-4xl text-white opacity-80"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- PANEL PEMINJAMAN (KIRI) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                    
                    {{-- Header --}}
                    <div class="p-8 pb-0">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-blue-100">
                                <i class="ph-fill ph-hand-holding"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Mode Peminjaman</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Transaksi Keluar</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 pt-2 space-y-8 flex-1">
                        <!-- Step 1: Anggota -->
                        <div class="relative group/step">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-3 ml-1 flex justify-between">
                                <span>1. Identitas Peminjam</span>
                                <span class="bg-slate-100 text-slate-500 px-2 py-0.5 rounded text-[10px]">Wajib</span>
                            </label>
                            <div class="flex gap-3">
                                <div id="member-scan-wrapper" class="flex-1 flex items-center px-5 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl focus-within:border-blue-500 focus-within:bg-white focus-within:shadow-lg transition-all group-hover/step:border-slate-300">
                                    <i class="ph-bold ph-identification-card text-slate-400 mr-3 text-xl"></i>
                                    <input type="text" id="memberInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-bold placeholder-slate-400 text-sm" placeholder="Scan Kartu / Ketik NISN" autofocus>
                                </div>
                                <button type="button" onclick="openScanner('memberInput')" class="p-4 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-600 rounded-2xl transition-all shadow-sm border border-blue-100 hover:border-blue-600 hover:shadow-lg hover:shadow-blue-500/30" title="Buka Kamera">
                                    <i class="ph-bold ph-qr-code text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Result Card: Anggota -->
                        <div id="memberInfo" class="hidden animate-fade-in-down">
                            <!-- Diisi via JS -->
                        </div>

                        <!-- Step 2: Buku -->
                        <div id="bookInputSection" class="opacity-50 pointer-events-none transition-all duration-300">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-wider mb-3 ml-1">2. Data Buku</label>
                            <div class="flex gap-3">
                                <div id="book-borrow-scan-wrapper" class="flex-1 flex items-center px-5 py-4 bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl focus-within:border-blue-500 focus-within:bg-white focus-within:shadow-lg transition-all">
                                    <i class="ph-bold ph-book-open text-slate-400 mr-3 text-xl"></i>
                                    <input type="text" id="bookBorrowInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-bold placeholder-slate-400 text-sm" placeholder="Scan Barcode Buku">
                                </div>
                                <button type="button" onclick="openScanner('bookBorrowInput')" class="p-4 bg-blue-50 hover:bg-blue-600 hover:text-white text-blue-600 rounded-2xl transition-all shadow-sm border border-blue-100 hover:border-blue-600 hover:shadow-lg hover:shadow-blue-500/30">
                                    <i class="ph-bold ph-barcode text-2xl"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="pt-6 mt-auto flex gap-4 border-t border-slate-50">
                            <button type="button" onclick="resetBorrow()" class="px-6 py-4 rounded-2xl border border-slate-200 text-slate-500 font-bold text-sm hover:bg-slate-50 hover:text-slate-700 transition-colors">Reset</button>
                            <button type="button" id="btnProcessBorrow" onclick="processBorrow()" disabled class="flex-1 py-4 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 shadow-xl shadow-blue-900/20 transition-all transform active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed disabled:shadow-none flex items-center justify-center gap-2">
                                <i class="ph-bold ph-check-circle text-lg"></i> Konfirmasi
                            </button>
                        </div>
                    </div>
                </div>

                <!-- PANEL PENGEMBALIAN (KANAN) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 h-full flex flex-col relative overflow-hidden group hover:shadow-2xl hover:shadow-emerald-900/10 transition-all duration-300">
                    
                    <div class="p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-emerald-100">
                                <i class="ph-fill ph-arrow-u-down-left"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-slate-800">Pengembalian Cepat</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Transaksi Masuk</p>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-slate-50 to-slate-100 rounded-[2rem] p-8 border border-slate-200 text-center mb-8 relative overflow-hidden">
                            <div class="absolute top-0 right-0 -mr-4 -mt-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl"></div>
                            
                            <p class="text-sm text-slate-500 font-medium mb-6 relative z-10">Scan barcode buku untuk memproses pengembalian secara instan.</p>
                            
                            <div class="relative max-w-sm mx-auto z-10">
                                <div id="return-scan-wrapper" class="flex items-center px-5 py-4 bg-white border-2 border-emerald-400 rounded-2xl shadow-lg shadow-emerald-500/10 focus-within:ring-4 focus-within:ring-emerald-100 transition-all">
                                    <i class="ph-bold ph-barcode text-emerald-500 mr-3 text-2xl"></i>
                                    <input type="text" id="returnInput" class="w-full bg-transparent border-none focus:ring-0 text-slate-800 font-black text-lg placeholder-slate-300" placeholder="Scan Buku..." autofocus>
                                </div>
                                <button onclick="openScanner('returnInput')" class="absolute right-3 top-3 p-2 text-emerald-600 hover:bg-emerald-50 rounded-xl transition-colors">
                                    <i class="ph-bold ph-camera text-xl"></i>
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
    </div>

    {{-- MODAL SCANNER --}}
    <div id="scannerModal" class="fixed inset-0 z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity" onclick="stopScanner()"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl w-full max-w-md relative z-10">
                <div class="p-8">
                    <h3 class="text-xl font-black text-slate-800 text-center mb-6">Pindai Kode</h3>
                    <div class="relative bg-black rounded-3xl overflow-hidden aspect-square border-4 border-slate-100 shadow-inner">
                        <div id="reader" class="w-full h-full"></div>
                        {{-- Overlay Frame Scanner --}}
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                            <div class="w-64 h-64 border-2 border-white/30 rounded-2xl relative">
                                <div class="absolute top-0 left-0 w-8 h-8 border-t-4 border-l-4 border-blue-500 rounded-tl-xl -mt-1 -ml-1"></div>
                                <div class="absolute top-0 right-0 w-8 h-8 border-t-4 border-r-4 border-blue-500 rounded-tr-xl -mt-1 -mr-1"></div>
                                <div class="absolute bottom-0 left-0 w-8 h-8 border-b-4 border-l-4 border-blue-500 rounded-bl-xl -mb-1 -ml-1"></div>
                                <div class="absolute bottom-0 right-0 w-8 h-8 border-b-4 border-r-4 border-blue-500 rounded-br-xl -mb-1 -mr-1"></div>
                            </div>
                        </div>
                    </div>
                    <button onclick="stopScanner()" class="mt-8 w-full py-4 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition text-sm">Batalkan Scan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Script JavaScript --}}
    <script>
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
                    wrapper.classList.remove('border-slate-200', 'bg-slate-50', 'border-rose-500', 'bg-rose-50');
                    
                    infoBox.classList.remove('hidden');
                    // UPDATE: Styling Member Info Baru
                    infoBox.innerHTML = `
                        <div class="bg-blue-900 rounded-3xl p-6 text-white relative overflow-hidden shadow-lg shadow-blue-900/30">
                            <div class="absolute top-0 right-0 -mt-2 -mr-2 w-20 h-20 bg-white/10 rounded-full blur-2xl"></div>
                            <div class="flex items-center gap-5 relative z-10">
                                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center text-2xl font-black text-white border border-white/20">
                                    ${data.student.name.charAt(0)}
                                </div>
                                <div>
                                    <h3 class="text-lg font-black leading-tight">${data.student.name}</h3>
                                    <p class="text-blue-200 text-sm font-mono mt-1">${data.student.student_id}</p>
                                    <div class="mt-3">
                                        ${data.has_overdue 
                                            ? '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-500 text-white text-[10px] font-bold uppercase shadow-sm"><i class="ph-bold ph-warning"></i> Ada Tunggakan</span>' 
                                            : '<span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500 text-white text-[10px] font-bold uppercase shadow-sm"><i class="ph-bold ph-check"></i> Status Aman</span>'}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;

                    if(!data.has_overdue) {
                        const bookSection = document.getElementById('bookInputSection');
                        bookSection.classList.remove('opacity-50', 'pointer-events-none');
                        document.getElementById('bookBorrowInput').focus();
                    } else {
                        Swal.fire({ icon: 'error', title: 'Terblokir', text: 'Siswa memiliki buku yang belum dikembalikan melewati tenggat.', customClass: { popup: 'rounded-[2rem]' }});
                    }
                } else {
                    wrapper.classList.add('border-rose-500', 'bg-rose-50');
                    Swal.fire({ icon: 'error', title: 'Gagal', text: 'Siswa tidak ditemukan.', customClass: { popup: 'rounded-[2rem]' }});
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
                    wrapper.classList.remove('border-slate-200', 'bg-slate-50', 'border-rose-500', 'bg-rose-50');
                    
                    document.getElementById('btnProcessBorrow').disabled = false;
                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success', 
                        title: 'Buku siap dipinjam: ' + data.book.title, showConfirmButton: false, timer: 2000,
                        customClass: { popup: 'rounded-2xl' }
                    });
                } else {
                    wrapper.classList.add('border-rose-500', 'bg-rose-50');
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.success ? 'Stok buku habis' : 'Buku tidak ditemukan', customClass: { popup: 'rounded-[2rem]' }});
                }
            } catch(err) {}
        });

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
                    Swal.fire({ icon: 'success', title: 'Berhasil', text: 'Transaksi peminjaman sukses!', customClass: { popup: 'rounded-[2rem]' }}).then(() => {
                        resetBorrow();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal', text: data.message, customClass: { popup: 'rounded-[2rem]' }});
                }
            } catch (err) {}
        }

        function resetBorrow() {
            location.reload(); 
        }

        // Logic Pengembalian
        document.getElementById('returnInput').addEventListener('change', async function(e) {
            const query = e.target.value;
            e.target.value = '';
            const infoBox = document.getElementById('returnInfo');
            
            infoBox.classList.remove('hidden');
            infoBox.innerHTML = '<div class="text-center py-8"><div class="w-8 h-8 border-4 border-emerald-500 border-t-transparent rounded-full animate-spin mx-auto"></div></div>';

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
                        ? `<div class="p-4 bg-rose-50 border border-rose-100 rounded-2xl mb-4"><p class="text-[10px] font-bold text-rose-400 uppercase tracking-wider">Denda Keterlambatan</p><p class="text-2xl font-black text-rose-600 mt-1">Rp ${new Intl.NumberFormat('id-ID').format(data.fine)}</p></div>`
                        : `<div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl mb-4"><p class="text-sm font-bold text-emerald-600 flex items-center justify-center gap-2"><i class="ph-fill ph-check-circle text-xl"></i> Pengembalian Tepat Waktu</p></div>`;

                    // UPDATE: Styling Result Return
                    infoBox.innerHTML = `
                        <div class="bg-white rounded-[2rem] border-2 border-slate-100 p-6 text-center mt-6 shadow-xl shadow-slate-200/50 animate-fade-in-up">
                            <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500 font-black border-4 border-white shadow-lg text-2xl">
                                ${data.student_name.charAt(0)}
                            </div>
                            <h3 class="font-black text-slate-800 text-lg leading-tight mb-1">${data.student_name}</h3>
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-6">Mengembalikan Buku</p>
                            ${dendaHtml}
                            <button onclick="confirmReturn('${query}')" class="w-full py-4 bg-slate-900 text-white font-bold rounded-2xl hover:bg-slate-800 transition shadow-lg shadow-slate-900/20 transform active:scale-95">
                                Konfirmasi Pengembalian
                            </button>
                        </div>
                    `;
                } else {
                    infoBox.innerHTML = `<div class="p-5 bg-rose-50 text-rose-600 font-bold text-center rounded-[1.5rem] border border-rose-100 mt-6 shadow-sm"><i class="ph-bold ph-warning-circle text-2xl mb-2 block"></i> ${result.message}</div>`;
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
                    Swal.fire({ icon: 'success', title: 'Sukses', text: 'Buku berhasil dikembalikan.', customClass: { popup: 'rounded-[2rem]' }});
                    document.getElementById('returnInfo').innerHTML = '';
                    document.getElementById('returnInfo').classList.add('hidden');
                }
            } catch(err) {}
        }
    </script>
</x-app-layout>