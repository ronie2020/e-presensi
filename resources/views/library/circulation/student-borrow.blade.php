<x-app-layout>
    {{-- Tambahkan Library HTML5 QR Code Scanner --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @push('styles')
    <style>
        /* Animasi Garis Scanner */
        @keyframes scanMove { 
            0% { top: 0; opacity: 0; } 
            10% { opacity: 1; } 
            90% { opacity: 1; } 
            100% { top: 100%; opacity: 0; } 
        }

        /* Container Scanner HTML5 QRCode */
        #reader { 
            width: 100% !important; 
            border: none !important; 
            border-radius: 1.5rem; 
            overflow: hidden; 
            background: #020b18; 
            position: relative;
        }

        #reader video { 
            width: 100% !important; 
            object-fit: cover !important; 
            border-radius: 1.5rem;
            display: block !important;
        }

        /* Garis Laser Biru */
        .scanner-line {
            position: absolute; 
            width: 100%; 
            height: 3px;
            background: #56bbf1; 
            box-shadow: 0 0 15px #56bbf1;
            top: 0; 
            animation: scanMove 2.5s infinite linear;
            z-index: 10; 
            opacity: 0.8;
            pointer-events: none;
        }

        /* Efek Flash Saat Berhasil Scan via Kamera */
        @keyframes flashSuccess {
            0% { box-shadow: inset 0 0 0 transparent; border-color: transparent; }
            50% { box-shadow: inset 0 0 40px rgba(56, 189, 248, 0.5); border-color: #38bdf8; } 
            100% { box-shadow: inset 0 0 0 transparent; border-color: transparent; }
        }
        .scan-success-flash {
            animation: flashSuccess 1s ease-out;
            border: 2px solid transparent;
            border-radius: 1.5rem;
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 20;
        }

        #reader__dashboard_section_csr span, 
        #reader__dashboard_section_swaplink,
        #reader__dashboard_section_csr div { display: none !important; }
    </style>
    @endpush

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-sky-600/10 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <a href="{{ route('library.circulation.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-sky-400 mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Sirkulasi
            </a>

            {{-- Menampilkan Error/Success dari Session jika ada --}}
            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in-down">
                    <i class="ph-fill ph-warning-circle text-rose-400 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-300">Peminjaman Gagal</h3>
                        <p class="text-xs font-bold text-rose-400 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-emerald-400 text-xl"></i>
                    <p class="text-sm font-bold text-emerald-300">{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                
                {{-- HEADER --}}
                <div class="bg-gradient-to-r from-[#031d3d] to-[#021124] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-student"></i>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-sky-500/20 rounded-lg text-[10px] font-black uppercase tracking-widest border border-sky-500/30 text-sky-400">
                            Mode Individu
                        </span>
                    </div>
                    <h2 class="text-3xl font-black relative z-10 tracking-tight text-white">Peminjaman Paket Siswa</h2>
                    <p class="text-slate-300 text-sm font-semibold relative z-10 mt-2 max-w-xl leading-relaxed">
                        Pilih satu siswa dan pindai beberapa buku sekaligus. Maksimal 11 Buku per Siswa.
                    </p>
                </div>

                <div class="p-8">
                    <form action="{{ route('library.circulation.storeStudentBulk') }}" method="POST" id="studentBorrowForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            {{-- KOLOM KIRI: PILIH SISWA & SETTING --}}
                            <div class="lg:col-span-1 space-y-6">
                                <div class="bg-slate-900/80 p-6 rounded-[2rem] border border-white/10">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="ph-fill ph-user-focus text-sky-400 text-lg"></i> Data Peminjam
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Pilih Siswa <span class="text-rose-400">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                <select name="student_id" id="student_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-white/10 bg-slate-900 font-bold text-white focus:ring-2 focus:ring-[#56bbf1]/30 focus:border-[#56bbf1] transition-all shadow-sm">
                                                    <option value="">-- Cari / Pilih Siswa --</option>
                                                    @php
                                                        $groupedStudents = collect($students)->sortBy([
                                                            ['class_name', 'asc'],
                                                            ['name', 'asc']
                                                        ])->groupBy('class_name');
                                                    @endphp
                                                    
                                                    @foreach($groupedStudents as $className => $classStudents)
                                                        <optgroup label="=== Kelas {{ $className }} ===" class="bg-slate-900 text-sky-400">
                                                            @foreach($classStudents as $student)
                                                                <option value="{{ $student->id }}" class="bg-slate-900 text-white">{{ $student->name }} (NISN: {{ $student->student_id }})</option>
                                                            @endforeach
                                                        </optgroup>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tenggat Waktu <span class="text-rose-400">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                @php $defaultDueDate = \Carbon\Carbon::create(date('Y') + 1, 6, 15)->format('Y-m-d'); @endphp
                                                <input type="date" name="due_date" value="{{ $defaultDueDate }}" required class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900 font-bold text-white focus:ring-2 focus:ring-[#56bbf1]/30 focus:border-[#56bbf1] [color-scheme:dark] transition-all shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- AREA INPUT SCAN BARCODE FISIK --}}
                                <div class="bg-slate-900/80 p-6 rounded-[2rem] border border-white/10 shadow-lg relative overflow-hidden">
                                    <div class="absolute top-0 right-0 p-4 opacity-5 text-sky-400 text-6xl pointer-events-none"><i class="ph-fill ph-barcode"></i></div>
                                    <label class="block text-sm font-black text-sky-400 mb-3 relative z-10">Scan Barcode Buku Disini</label>
                                    <div class="relative z-10">
                                        <input type="text" id="mainScannerInput" class="w-full px-4 py-4 rounded-xl border border-white/10 bg-slate-950 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-2 focus:ring-[#56bbf1]/30 font-mono font-bold text-white text-lg transition-all shadow-sm placeholder-slate-500" placeholder="Arahkan kursor & Scan..." autofocus>
                                        
                                        <div class="mt-3 flex gap-2">
                                            <button type="button" onclick="processManualInput()" class="flex-1 bg-sky-600 text-white py-2 rounded-lg text-xs font-bold hover:bg-sky-500 transition shadow-md">Tambahkan</button>
                                            <button type="button" onclick="openScannerModal()" class="flex-none px-4 bg-sky-500/10 text-sky-400 border border-sky-500/20 py-2 rounded-lg text-xs font-bold hover:bg-sky-500/20 transition shadow-sm" title="Pakai Kamera HP">
                                                <i class="ph-bold ph-camera text-base"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-3 relative z-10">Gunakan alat Scanner Barcode. Tekan enter untuk memasukkan buku ke dalam daftar.</p>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: DAFTAR BUKU YANG DI-SCAN (KERANJANG) --}}
                            <div class="lg:col-span-2 flex flex-col">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-black text-white">Keranjang Peminjaman</h3>
                                    
                                    <div class="px-4 py-2 bg-sky-500/10 text-sky-400 rounded-xl border border-sky-500/20 text-sm font-black flex items-center gap-2 shadow-sm" id="counterWrapper">
                                        <i class="ph-bold ph-books"></i> Total: <span id="totalBooks">0</span>/11 Buku
                                    </div>
                                </div>

                                <div class="bg-slate-900/80 border border-white/10 rounded-[2rem] flex-1 overflow-hidden flex flex-col shadow-sm relative min-h-[400px]">
                                    
                                    <div id="emptyCart" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/90 z-10 transition-opacity">
                                        <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center shadow-sm mb-4 border border-white/10 text-sky-400">
                                            <i class="ph-duotone ph-shopping-cart text-4xl"></i>
                                        </div>
                                        <h4 class="font-black text-slate-300">Keranjang Masih Kosong</h4>
                                        <p class="text-xs text-slate-400 mt-1">Scan buku untuk menambahkannya ke daftar</p>
                                    </div>

                                    <div class="overflow-y-auto custom-scrollbar flex-1 relative z-20">
                                        <table class="w-full text-left border-collapse" id="scannedTable">
                                            <thead class="sticky top-0 z-30 bg-slate-950">
                                                <tr class="bg-slate-950 text-xs uppercase tracking-wider text-sky-400 font-bold border-b border-white/10">
                                                    <th class="px-6 py-4 w-16 text-center">No</th>
                                                    <th class="px-6 py-4">Informasi Buku</th>
                                                    <th class="px-6 py-4">Kode / Barcode</th>
                                                    <th class="px-6 py-4 w-20 text-center">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-white/5" id="cartContainer">
                                                <!-- Baris buku akan ditambahkan via JS -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between">
                            <p class="text-xs text-slate-400 font-medium max-w-sm">
                                <i class="ph-fill ph-info text-sky-400"></i> Pastikan siswa dan buku sudah benar sebelum menyimpan. Maksimal 11 Buku.
                            </p>
                            <button type="button" id="btnSubmit" onclick="confirmCheckout()" disabled class="px-8 py-3.5 bg-sky-600 text-white font-bold rounded-2xl hover:bg-sky-500 shadow-lg shadow-sky-600/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed border border-transparent disabled:transform-none">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Proses Peminjaman
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL CAMERA SCANNER --}}
    <div id="scannerModal" class="fixed inset-0 bg-slate-950/80 backdrop-blur-md z-[100] hidden flex items-center justify-center p-4">
        <div class="bg-[#021124] rounded-[2.5rem] w-full max-w-md shadow-2xl overflow-hidden flex flex-col relative border border-white/10">
            
            <div class="bg-slate-900 p-5 border-b border-white/10 flex justify-between items-center relative z-20">
                <div>
                    <h3 class="font-black text-white text-lg">Kamera Pemindai</h3>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Arahkan kamera ke Barcode buku</p>
                </div>
                <button type="button" onclick="closeScannerModal()" class="w-10 h-10 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center text-slate-300 hover:text-rose-400 hover:bg-rose-500/20 transition-colors shadow-sm">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 bg-slate-950">
                <div class="relative w-full rounded-[1.5rem] border-4 border-slate-800 shadow-inner overflow-hidden bg-black min-h-[250px] flex items-center justify-center">
                    
                    <div id="reader-loader" class="text-slate-400 font-bold text-sm flex flex-col items-center gap-2 absolute z-10">
                        <i class="ph-bold ph-spinner animate-spin text-3xl text-sky-400"></i>
                        Membuka Kamera...
                    </div>
                    
                    {{-- Elemen kamera Html5Qrcode --}}
                    <div id="reader" class="w-full"></div>
                    
                    {{-- Overlay Garis Laser --}}
                    <div id="scanner-laser" class="hidden">
                        <div class="scanner-line"></div>
                    </div>
                    
                    {{-- Flash Effect saat berhasil --}}
                    <div id="scanner-flash"></div>
                </div>
                
                {{-- Toggle Putar Kamera --}}
                <div class="mt-4 flex justify-center">
                    <button type="button" onclick="switchCameraMode()" class="py-2.5 px-4 rounded-xl border border-white/10 text-slate-300 font-bold text-xs uppercase tracking-wider hover:bg-sky-500/20 hover:text-white hover:border-sky-500/40 transition-all flex items-center justify-center gap-2 shadow-sm bg-slate-900 active:scale-95">
                        <i class="ph-bold ph-camera-rotate text-lg"></i> Ganti Kamera
                    </button>
                </div>
            </div>

            <div class="bg-slate-900 p-4 border-t border-white/10 text-center text-xs text-slate-400 font-medium flex items-center justify-center gap-2 relative z-20">
                <i class="ph-fill ph-magic-wand text-sky-400 text-base"></i> Sistem otomatis menambahkan ke keranjang jika sukses.
            </div>
        </div>
    </div>

    {{-- SCRIPT LOGIC --}}
    <script>
        let scannedCodesList = []; 
        const MAX_BOOKS = 11;
        const cartContainer = document.getElementById('cartContainer');
        const emptyCart = document.getElementById('emptyCart');
        const totalDisplay = document.getElementById('totalBooks');
        const counterWrapper = document.getElementById('counterWrapper');
        const btnSubmit = document.getElementById('btnSubmit');
        const mainInput = document.getElementById('mainScannerInput');

        // BEEP SOUND
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(type = 'success') {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain); gain.connect(audioCtx.destination);
            osc.type = type === 'error' ? 'sawtooth' : 'sine';
            const freq = type === 'error' ? 150 : 880;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            if(type === 'success') osc.frequency.exponentialRampToValueAtTime(freq * 2, audioCtx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.3);
            osc.start(audioCtx.currentTime); osc.stop(audioCtx.currentTime + 0.3);
        }

        // DETEKSI ENTER PADA INPUT SCANNER FISIK
        mainInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); 
                processScannedCode(this.value.trim());
            }
        });

        function processManualInput() {
            processScannedCode(mainInput.value.trim());
        }

        // ==========================================
        // FUNGSI UTAMA: MEMPROSES KODE (DARI FISIK / KAMERA)
        // ==========================================
        async function processScannedCode(code, fromCamera = false) {
            if (!code) return;
            
            if(!fromCamera) {
                mainInput.value = '';
                mainInput.focus();
                mainInput.placeholder = "Mencari buku...";
                mainInput.disabled = true;
            }

            if (scannedCodesList.length >= MAX_BOOKS) {
                playBeep('error');
                if(fromCamera) triggerFlashEffect(true);
                Swal.fire({ toast: true, position: 'top', icon: 'error', title: `Maksimal ${MAX_BOOKS} Buku!`, showConfirmButton: false, timer: 2500, background: '#021124', color: '#fff' });
                resetInputState(fromCamera);
                return;
            }

            if (scannedCodesList.includes(code)) {
                playBeep('error');
                if(fromCamera) triggerFlashEffect(true);
                Swal.fire({ toast: true, position: 'top', icon: 'error', title: 'Buku sudah ada di daftar!', showConfirmButton: false, timer: 2000, background: '#021124', color: '#fff' });
                resetInputState(fromCamera);
                return;
            }

            try {
                const response = await fetch(`{{ url('/library/tools/api/book-by-code') }}?code=${code}`);
                const data = await response.json();

                if (data.success) {
                    playBeep('success');
                    if(fromCamera) triggerFlashEffect(false);
                    addToCartUI(data.book, code);
                } else {
                    playBeep('error');
                    if(fromCamera) triggerFlashEffect(true);
                    Swal.fire({ toast: true, position: 'top', icon: 'warning', title: data.message || 'Buku tidak ditemukan', showConfirmButton: false, timer: 3000, background: '#021124', color: '#fff' });
                }
            } catch (error) {
                playBeep('error');
                console.error("Error fetching book:", error);
            } finally {
                resetInputState(fromCamera);
            }
        }

        function resetInputState(fromCamera) {
            if(!fromCamera) {
                mainInput.disabled = false;
                mainInput.placeholder = "Arahkan kursor & Scan...";
                mainInput.focus();
            }
        }

        // FUNGSI MENAMBAH BARIS KE TABEL KERANJANG
        function addToCartUI(book, code) {
            scannedCodesList.push(code);
            emptyCart.style.opacity = '0';
            setTimeout(() => emptyCart.classList.add('hidden'), 300);

            let index = scannedCodesList.length;
            
            const tr = document.createElement('tr');
            tr.id = `row-${code}`;
            tr.className = "hover:bg-white/5 transition-colors animate-fade-in-down group";
            tr.innerHTML = `
                <td class="px-6 py-4 text-center font-bold text-slate-400 number-cell">${index}</td>
                <td class="px-6 py-4">
                    <div class="font-bold text-slate-200 text-sm group-hover:text-sky-400 transition-colors">${book.title}</div>
                    <div class="text-[10px] uppercase tracking-wider text-slate-400 font-bold mt-1">Kategori: ${book.category || '-'}</div>
                    <input type="hidden" name="item_codes[]" value="${code}">
                </td>
                <td class="px-6 py-4">
                    <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md bg-slate-800 border border-white/10 font-mono text-xs font-bold text-sky-400 shadow-sm">
                        <i class="ph-bold ph-barcode text-slate-400"></i> ${code}
                    </div>
                </td>
                <td class="px-6 py-4 text-center">
                    <button type="button" onclick="removeBook('${code}')" class="w-8 h-8 rounded-full bg-rose-500/20 text-rose-400 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-all mx-auto shadow-sm">
                        <i class="ph-bold ph-trash"></i>
                    </button>
                </td>
            `;
            
            cartContainer.appendChild(tr);
            updateCartCounter();
            
            cartContainer.parentElement.scrollTop = cartContainer.parentElement.scrollHeight;
        }

        window.removeBook = function(code) {
            const index = scannedCodesList.indexOf(code);
            if (index > -1) {
                scannedCodesList.splice(index, 1);
                document.getElementById(`row-${code}`).remove();
                
                const numberCells = document.querySelectorAll('.number-cell');
                numberCells.forEach((cell, i) => { cell.innerText = i + 1; });
                updateCartCounter();
            }
        }

        function updateCartCounter() {
            const count = scannedCodesList.length;
            totalDisplay.innerText = count;
            
            if (count === 0) {
                emptyCart.classList.remove('hidden');
                setTimeout(() => emptyCart.style.opacity = '1', 10);
                btnSubmit.disabled = true;
            } else {
                btnSubmit.disabled = false;
            }

            if (count >= MAX_BOOKS) {
                counterWrapper.classList.replace('bg-sky-500/10', 'bg-rose-500/20');
                counterWrapper.classList.replace('text-sky-400', 'text-rose-400');
                counterWrapper.classList.replace('border-sky-500/20', 'border-rose-500/30');
            } else {
                counterWrapper.classList.replace('bg-rose-500/20', 'bg-sky-500/10');
                counterWrapper.classList.replace('text-rose-400', 'text-sky-400');
                counterWrapper.classList.replace('border-rose-500/30', 'border-sky-500/20');
            }
        }

        window.confirmCheckout = function() {
            const studentId = document.getElementById('student_id').value;
            if(!studentId) {
                Swal.fire({ icon: 'warning', title: 'Siswa Belum Dipilih', text: 'Mohon pilih peminjam terlebih dahulu.', background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' } });
                return;
            }

            Swal.fire({
                title: 'Proses Peminjaman?',
                html: `Meminjamkan <strong class="text-sky-400">${scannedCodesList.length} Buku Paket</strong> ke siswa yang dipilih.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses!',
                background: '#021124', color: '#fff',
                confirmButtonColor: '#0d52a1',
                cancelButtonColor: '#f43f5e',
                customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, background: '#021124', color: '#fff', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' }, didOpen: () => Swal.showLoading() });
                    document.getElementById('studentBorrowForm').submit();
                }
            });
        }

        // LOGIC KAMERA HTML5 QR CODE SCANNER
        let html5QrCode = null;
        let currentFacingMode = "environment";
        let lastScannedCode = "";
        let lastScanTime = 0;

        function startCameraScanner() {
            document.getElementById('reader-loader').style.display = 'flex';
            document.getElementById('scanner-laser').classList.add('hidden');
            
            if (!html5QrCode) { html5QrCode = new Html5Qrcode("reader"); }
            
            const config = { fps: 15, qrbox: { width: 250, height: 250 }, aspectRatio: 1.0 };

            html5QrCode.start({ facingMode: currentFacingMode }, config, onScanSuccess, onScanFailure)
            .then(() => {
                document.getElementById('reader-loader').style.display = 'none';
                document.getElementById('scanner-laser').classList.remove('hidden');
            }).catch((err) => {
                document.getElementById('reader-loader').style.display = 'none';
                Swal.fire({ icon: 'error', title: 'Kamera Gagal Akses', text: 'Pastikan browser diizinkan mengakses kamera.', background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' } });
                closeScannerModal();
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            const now = Date.now();
            if (decodedText === lastScannedCode && (now - lastScanTime) < 2000) {
                return; 
            }
            lastScannedCode = decodedText;
            lastScanTime = now;

            processScannedCode(decodedText, true);
        }

        function onScanFailure(error) { }

        function triggerFlashEffect(isError) {
            const flash = document.getElementById('scanner-flash');
            flash.style.animation = 'none';
            flash.offsetHeight;
            flash.style.animation = 'flashSuccess 1s ease-out';
            
            if(isError) {
                flash.style.borderColor = '#f43f5e';
                flash.style.boxShadow = 'inset 0 0 40px rgba(244, 63, 94, 0.5)';
            } else {
                flash.style.borderColor = '#38bdf8';
                flash.style.boxShadow = 'inset 0 0 40px rgba(56, 189, 248, 0.5)';
            }
        }

        window.openScannerModal = function() {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            document.getElementById('scannerModal').classList.remove('hidden');
            startCameraScanner();
        }

        window.closeScannerModal = function() {
            document.getElementById('scannerModal').classList.add('hidden');
            document.getElementById('scanner-laser').classList.add('hidden');
            if (html5QrCode) {
                html5QrCode.stop().catch(err => console.log("Kamera sudah berhenti", err));
            }
        }

        window.switchCameraMode = function() {
            currentFacingMode = currentFacingMode === "environment" ? "user" : "environment";
            if (html5QrCode) {
                html5QrCode.stop().then(() => startCameraScanner()).catch(() => startCameraScanner());
            }
        }
    </script>
</x-app-layout>