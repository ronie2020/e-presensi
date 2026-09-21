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

        /* Container Scanner */
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
            background: #38bdf8; 
            box-shadow: 0 0 15px #38bdf8;
            top: 0; 
            animation: scanMove 2.5s infinite linear;
            z-index: 10; 
            opacity: 0.8;
            pointer-events: none;
        }

        /* Efek Flash Saat Berhasil */
        @keyframes flashSuccess {
            0% { box-shadow: inset 0 0 0 transparent; border-color: transparent; }
            50% { box-shadow: inset 0 0 40px rgba(16, 185, 129, 0.5); border-color: #10b981; }
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
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-sky-600/10 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <a href="{{ route('library.circulation.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-sky-400 mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Sirkulasi
            </a>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-rose-400 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-300">Gagal Memproses Distribusi</h3>
                        <ul class="list-disc list-inside text-xs text-rose-400 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-2xl flex items-center gap-3 shadow-sm">
                    <i class="ph-fill ph-check-circle text-emerald-400 text-xl"></i>
                    <p class="text-sm font-bold text-emerald-300">{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 rounded-2xl flex items-start gap-3 shadow-sm animate-fade-in-down">
                    <i class="ph-fill ph-warning-circle text-rose-400 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-300">Distribusi Terhenti</h3>
                        <p class="text-xs font-bold text-rose-400 mt-1">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                
                <div class="bg-gradient-to-r from-[#031d3d] to-[#021124] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-barcode"></i>
                    </div>
                    <div class="relative z-10 flex items-center gap-3 mb-2">
                        <span class="px-3 py-1 bg-sky-500/20 rounded-lg text-[10px] font-black uppercase tracking-widest border border-sky-500/30 text-sky-400">
                            Mode Pindai Eksemplar
                        </span>
                    </div>
                    <h2 class="text-3xl font-black relative z-10 tracking-tight text-white">Distribusi Buku Paket</h2>
                    <p class="text-slate-300 text-sm font-semibold relative z-10 mt-2 max-w-xl leading-relaxed">
                        Pindai barcode unik dari stiker masing-masing fisik buku dan pasangkan dengan nama siswa. Ini mencegah siswa menukar buku saat pengembalian.
                    </p>
                </div>

                <div class="p-8">
                    <form action="{{ route('library.circulation.storeBulk') }}" method="POST" id="bulkBorrowForm">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            
                            {{-- KOLOM KIRI: SETTING --}}
                            <div class="lg:col-span-1 space-y-6">
                                <div class="bg-slate-900/80 p-6 rounded-[2rem] border border-white/10">
                                    <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="ph-fill ph-sliders text-sky-400 text-lg"></i> Pengaturan
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Pilih Kelas <span class="text-rose-400">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-users-three absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                <select name="class_id" id="class_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-white/10 bg-slate-900 font-bold text-white focus:ring-2 focus:ring-[#56bbf1]/30 focus:border-[#56bbf1] transition-all shadow-sm">
                                                    <option value="">-- Pilih Kelas --</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" class="bg-slate-900 text-white">{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Buku Paket <span class="text-rose-400">*</span></label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-books absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                <select name="book_id" id="book_id" required class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-white/10 bg-slate-900 font-bold text-white focus:ring-2 focus:ring-[#56bbf1]/30 focus:border-[#56bbf1] transition-all shadow-sm">
                                                    <option value="">-- Pilih Buku Paket --</option>
                                                    @foreach($textbooks as $book)
                                                        <option value="{{ $book->id }}" data-stock="{{ $book->stock }}" class="bg-slate-900 text-white">
                                                            {{ $book->title }} (Stok: {{ $book->stock }})
                                                        </option>
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
                            </div>

                            {{-- KOLOM KANAN: DAFTAR SISWA & SCAN --}}
                            <div class="lg:col-span-2 flex flex-col">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="text-lg font-black text-white">Daftar Penerima</h3>
                                    
                                    {{-- TOMBOL CAMERA SCANNER & COUNTER --}}
                                    <div class="flex items-center gap-2">
                                        <button type="button" onclick="openScannerModal()" class="px-4 py-2 bg-sky-600 hover:bg-sky-500 text-white rounded-xl text-xs font-bold transition-all shadow-md flex items-center gap-2 border border-transparent transform active:scale-95">
                                            <i class="ph-bold ph-camera text-base"></i> Scan Kamera
                                        </button>

                                        <div class="px-3 py-2 bg-sky-500/10 text-sky-400 rounded-xl border border-sky-500/20 text-xs font-bold flex items-center gap-2 shadow-sm">
                                            <i class="ph-bold ph-check-circle"></i> Terisi: <span id="scannedCount">0</span> Siswa
                                        </div>
                                    </div>
                                </div>

                                <div class="bg-slate-900/80 border border-white/10 rounded-[2rem] flex-1 overflow-hidden flex flex-col shadow-sm relative min-h-[400px]">
                                    
                                    <div id="emptyState" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/90 z-10 transition-opacity">
                                        <div class="w-20 h-20 bg-slate-800 rounded-full flex items-center justify-center shadow-sm mb-4 border border-white/10 text-sky-400">
                                            <i class="ph-duotone ph-student text-4xl"></i>
                                        </div>
                                        <h4 class="font-black text-slate-400">Pilih Kelas Terlebih Dahulu</h4>
                                    </div>

                                    <div id="loadingState" class="absolute inset-0 flex flex-col items-center justify-center bg-slate-900/90 backdrop-blur-sm z-20 hidden">
                                        <i class="ph-bold ph-spinner animate-spin text-4xl text-sky-400 mb-2"></i>
                                    </div>

                                    <div class="overflow-y-auto custom-scrollbar flex-1">
                                        <table class="w-full text-left border-collapse" id="studentTable">
                                            <thead class="sticky top-0 z-10">
                                                <tr class="bg-slate-950 text-xs uppercase tracking-wider text-sky-400 font-bold border-b border-white/10">
                                                    <th class="px-6 py-4">Nama Siswa</th>
                                                    <th class="px-6 py-4 w-1/2">Scan Barcode (Stiker Buku)</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-white/5" id="studentListContainer">
                                                <!-- Via JS -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8 pt-6 border-t border-white/10 flex items-center justify-between">
                            <p class="text-xs text-slate-400 font-medium max-w-sm">
                                <i class="ph-fill ph-info text-sky-400"></i> Pastikan tidak ada barcode merah (ganda). Tekan Enter setelah scan untuk lanjut ke baris berikutnya.
                            </p>
                            <button type="button" id="btnSubmit" onclick="confirmBulkSubmit()" disabled class="px-8 py-3.5 bg-sky-600 text-white font-bold rounded-2xl hover:bg-sky-500 shadow-lg shadow-sky-600/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed border border-transparent">
                                <i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan Distribusi
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
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Arahkan kamera ke QR / Barcode buku</p>
                </div>
                <button type="button" onclick="closeScannerModal()" class="w-10 h-10 rounded-full bg-slate-800 border border-white/10 flex items-center justify-center text-slate-300 hover:text-rose-400 hover:bg-rose-500/20 transition-colors shadow-sm">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>
            
            <div class="p-6 bg-slate-950">
                {{-- Container target untuk kamera dari JS --}}
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
                <i class="ph-fill ph-check-circle text-emerald-400 text-base"></i> Sistem mengisi nama siswa otomatis (dari atas).
            </div>
        </div>
    </div>

    <script>
        let html5QrCode = null;
        let currentFacingMode = "environment"; 

        // Audio Beep
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(type = 'success') {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            osc.type = type === 'error' ? 'sawtooth' : 'sine';
            const freq = type === 'error' ? 150 : 880;
            osc.frequency.setValueAtTime(freq, audioCtx.currentTime);
            if(type === 'success') osc.frequency.exponentialRampToValueAtTime(freq * 2, audioCtx.currentTime + 0.1);
            gain.gain.setValueAtTime(0.1, audioCtx.currentTime);
            gain.gain.exponentialRampToValueAtTime(0.0001, audioCtx.currentTime + 0.3);
            osc.start(audioCtx.currentTime);
            osc.stop(audioCtx.currentTime + 0.3);
        }

        document.addEventListener('DOMContentLoaded', function() {
            const classSelect = document.getElementById('class_id');
            const studentContainer = document.getElementById('studentListContainer');
            const emptyState = document.getElementById('emptyState');
            const loadingState = document.getElementById('loadingState');
            const scannedCountDisplay = document.getElementById('scannedCount');
            const btnSubmit = document.getElementById('btnSubmit');

            document.body.addEventListener('click', () => { if (audioCtx.state === 'suspended') audioCtx.resume(); }, { once: true });

            classSelect.addEventListener('change', async function() {
                const classId = this.value;
                if(!classId) {
                    emptyState.classList.remove('hidden');
                    studentContainer.innerHTML = '';
                    updateCounter();
                    return;
                }

                emptyState.classList.add('hidden');
                loadingState.classList.remove('hidden');

                try {
                    const response = await fetch(`{{ url('/library/tools/api/students-by-class') }}/${classId}`);
                    const data = await response.json();
                    
                    if(data.success && data.students.length > 0) {
                        renderStudents(data.students);
                    } else {
                        studentContainer.innerHTML = `<tr><td colspan="2" class="px-6 py-10 text-center text-slate-400">Data siswa kosong.</td></tr>`;
                    }
                } catch (error) {
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal memuat data.', background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' } });
                } finally {
                    loadingState.classList.add('hidden');
                    updateCounter();
                }
            });

            function renderStudents(students) {
                let html = '';
                students.forEach((student, index) => {
                    html += `
                        <tr class="hover:bg-white/5 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-200 text-sm group-hover:text-sky-400">${student.name}</div>
                                <div class="text-xs text-slate-400 font-mono">${student.nisn || student.student_id || '-'}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="relative">
                                    <i class="ph-bold ph-barcode absolute left-3 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                    <input type="text" name="item_codes[${student.id}]" class="item-code-input w-full pl-9 pr-4 py-2.5 rounded-xl border border-white/10 bg-slate-900 focus:bg-slate-950 focus:border-[#56bbf1] focus:ring-2 focus:ring-[#56bbf1]/30 font-mono font-bold text-white text-sm transition-all shadow-sm placeholder-slate-500" placeholder="Scan barcode stiker..." oninput="updateCounter()" onkeydown="focusNext(event, ${index})">
                                </div>
                            </td>
                        </tr>
                    `;
                });
                studentContainer.innerHTML = html;
            }

            window.focusNext = function(event, currentIndex) {
                if(event.key === 'Enter') {
                    event.preventDefault();
                    const inputs = document.querySelectorAll('.item-code-input');
                    if(inputs[currentIndex + 1]) {
                        inputs[currentIndex + 1].focus();
                    }
                }
            }

            window.updateCounter = function() {
                const inputs = document.querySelectorAll('.item-code-input');
                let filledCount = 0;
                let scannedCodes = [];
                let hasDuplicate = false;

                inputs.forEach(input => { 
                    const val = input.value.trim();
                    if(val !== '') { 
                        filledCount++; 
                        
                        if(scannedCodes.includes(val)) {
                            hasDuplicate = true;
                            input.classList.add('border-rose-500', 'bg-rose-500/20', 'text-rose-300');
                            input.classList.remove('border-white/10', 'bg-slate-900', 'text-white');
                        } else {
                            scannedCodes.push(val);
                            input.classList.remove('border-rose-500', 'bg-rose-500/20', 'text-rose-300');
                            input.classList.add('border-white/10', 'bg-slate-900', 'text-white');
                        }
                    } else {
                        input.classList.remove('border-rose-500', 'bg-rose-500/20', 'text-rose-300');
                        input.classList.add('border-white/10', 'bg-slate-900', 'text-white');
                    }
                });
                
                scannedCountDisplay.innerText = filledCount;
                const bookSelected = document.getElementById('book_id').value !== '';
                
                if (hasDuplicate) {
                    btnSubmit.disabled = true;
                    btnSubmit.innerHTML = '<i class="ph-bold ph-warning text-lg"></i> Ada Barcode Ganda';
                    btnSubmit.classList.replace('bg-sky-600', 'bg-rose-600');
                    btnSubmit.classList.replace('hover:bg-sky-500', 'hover:bg-rose-700');
                } else {
                    btnSubmit.disabled = filledCount === 0 || !bookSelected;
                    btnSubmit.innerHTML = '<i class="ph-bold ph-paper-plane-right text-lg"></i> Simpan Distribusi';
                    btnSubmit.classList.replace('bg-rose-600', 'bg-sky-600');
                    btnSubmit.classList.replace('hover:bg-rose-700', 'hover:bg-sky-500');
                }
            }
            document.getElementById('book_id').addEventListener('change', updateCounter);
        });

        function startCameraScanner() {
            document.getElementById('reader-loader').style.display = 'flex';
            document.getElementById('scanner-laser').classList.add('hidden');
            
            if (!html5QrCode) {
                html5QrCode = new Html5Qrcode("reader");
            }
            
            const config = { 
                fps: 15, 
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0
            };

            html5QrCode.start(
                { facingMode: currentFacingMode }, 
                config, 
                onScanSuccess, 
                onScanFailure
            ).then(() => {
                document.getElementById('reader-loader').style.display = 'none';
                document.getElementById('scanner-laser').classList.remove('hidden');
            }).catch((err) => {
                document.getElementById('reader-loader').style.display = 'none';
                Swal.fire({ 
                    icon: 'error', 
                    title: 'Kamera Gagal Akses', 
                    text: 'Pastikan Anda memberikan izin akses kamera pada browser Anda.', 
                    background: '#021124', color: '#fff',
                    confirmButtonColor: '#0d52a1', 
                    customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' } 
                });
                closeScannerModal();
            });
        }

        window.openScannerModal = function() {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            
            const classSelect = document.getElementById('class_id').value;
            if(!classSelect) {
                Swal.fire({ icon: 'warning', title: 'Pilih Kelas Dulu', text: 'Silakan pilih kelas sebelum mengaktifkan kamera.', background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' } });
                return;
            }

            document.getElementById('scannerModal').classList.remove('hidden');
            startCameraScanner();
        }

        window.closeScannerModal = function() {
            document.getElementById('scannerModal').classList.add('hidden');
            document.getElementById('scanner-laser').classList.add('hidden');
            
            if (html5QrCode) {
                html5QrCode.stop().then(() => {}).catch((err) => { console.log("Kamera sudah berhenti", err); });
            }
        }

        window.switchCameraMode = function() {
            currentFacingMode = currentFacingMode === "environment" ? "user" : "environment";
            
            if (html5QrCode) {
                html5QrCode.stop().then(() => { startCameraScanner(); }).catch(err => { startCameraScanner(); });
            }
        }

        function triggerFlashEffect(isDuplicate) {
            const flash = document.getElementById('scanner-flash');
            
            if(isDuplicate) {
                flash.style.animation = 'none';
                flash.offsetHeight;
                flash.style.animation = 'flashSuccess 1s ease-out';
                flash.style.borderColor = '#f43f5e';
                flash.style.boxShadow = 'inset 0 0 40px rgba(244, 63, 94, 0.5)';
            } else {
                flash.className = 'scan-success-flash';
                setTimeout(() => { flash.className = ''; }, 1000);
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            const inputs = document.querySelectorAll('.item-code-input');
            let isDuplicate = false;
            let targetInput = null;

            for(let i=0; i < inputs.length; i++) {
                if(inputs[i].value.trim() === decodedText) {
                    isDuplicate = true;
                    break;
                }
                if(inputs[i].value.trim() === '' && targetInput === null) {
                    targetInput = inputs[i];
                }
            }

            if (isDuplicate) {
                playBeep('error');
                triggerFlashEffect(true);
                
                Swal.fire({
                    toast: true, position: 'top', icon: 'error',
                    title: 'Barcode sudah di-scan!',
                    showConfirmButton: false, timer: 2000,
                    background: '#021124', color: '#fff'
                });
                return;
            }

            if (targetInput) {
                targetInput.value = decodedText;
                
                playBeep('success');
                triggerFlashEffect(false);
                
                targetInput.classList.add('ring-4', 'ring-emerald-400', 'bg-emerald-500/20');
                setTimeout(() => targetInput.classList.remove('ring-4', 'ring-emerald-400', 'bg-emerald-500/20'), 1000);

                targetInput.scrollIntoView({ behavior: "smooth", block: "center" });

                updateCounter();

                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: 'Stiker Ditambahkan',
                    text: decodedText,
                    showConfirmButton: false, timer: 1500,
                    background: '#021124', color: '#fff'
                });
            } else {
                closeScannerModal();
                Swal.fire({
                    icon: 'success', title: 'Selesai!',
                    text: 'Semua siswa di kelas ini telah mendapatkan buku.',
                    background: '#021124', color: '#fff',
                    confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' }
                });
            }
        }

        function onScanFailure(error) {}

        window.confirmBulkSubmit = function() {
            const inputs = document.querySelectorAll('.item-code-input');
            let filledCount = 0;
            inputs.forEach(input => { if(input.value.trim() !== '') filledCount++; });

            const bookSelect = document.getElementById('book_id');
            const stockAvailable = parseInt(bookSelect.options[bookSelect.selectedIndex].getAttribute('data-stock'));

            if (filledCount > stockAvailable) {
                Swal.fire({ icon: 'error', title: 'Stok Kurang!', text: `Anda men-scan ${filledCount} buku, tapi stok tersisa ${stockAvailable}.`, background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' } });
                return;
            }

            Swal.fire({
                title: 'Simpan Distribusi?',
                html: `Memproses peminjaman untuk <strong class="text-sky-400">${filledCount} Siswa</strong>.`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan!',
                background: '#021124', color: '#fff',
                confirmButtonColor: '#0d52a1',
                customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, background: '#021124', color: '#fff', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' }, didOpen: () => Swal.showLoading() });
                    document.getElementById('bulkBorrowForm').submit();
                }
            });
        }
    </script>
</x-app-layout>