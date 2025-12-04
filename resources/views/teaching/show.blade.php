<x-app-layout>
    <!-- Load Library HTML5-QRCode -->
    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    @endpush

    <!-- Alpine Data untuk Logic Scan -->
    <div class="space-y-6 pb-20" 
         x-data="teachingSession({ 
            sessionId: {{ $session->id }}, 
            presentCount: {{ $presentCount }} 
         })">
        
        <!-- HEADER KELAS -->
        <div class="bg-gradient-to-r from-slate-800 to-slate-900 rounded-2xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute right-0 top-0 h-full w-1/3 bg-white/5 transform skew-x-12"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="bg-blue-500 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                            {{ $session->schedule->schoolClass->name }}
                        </span>
                        <span class="bg-white/20 text-blue-100 text-xs font-bold px-2 py-1 rounded flex items-center gap-1">
                            <i class="ph-bold ph-clock"></i> 
                            Mulai: {{ \Carbon\Carbon::parse($session->started_at)->format('H:i') }}
                        </span>
                        @if($session->status == 'closed')
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">
                                SELESAI
                            </span>
                        @endif
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight leading-tight">
                        {{ $session->schedule->subject->name }}
                    </h1>
                </div>
                
                <!-- [LOGIKA] Tombol Tutup Kelas HANYA Muncul Jika Status Masih OPEN -->
                @if($session->status == 'open')
                    <form action="{{ route('teaching.close', $session->id) }}" method="POST" 
                          onsubmit="return confirm('PERINGATAN: Siswa yang belum absen akan otomatis dianggap ALPHA dan mendapat poin pelanggaran. Lanjutkan?')">
                        @csrf
                        <button type="submit" class="group bg-rose-600 hover:bg-rose-700 text-white pl-4 pr-6 py-3 rounded-xl font-bold shadow-lg shadow-rose-900/30 transition flex items-center gap-3 border border-rose-500">
                            <div class="bg-white/20 p-1.5 rounded-lg group-hover:scale-110 transition-transform">
                                <i class="ph-bold ph-stop-circle text-xl"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-[10px] uppercase opacity-80 leading-tight">Selesai Mengajar</div>
                                <div class="text-sm">Tutup Kelas</div>
                            </div>
                        </button>
                    </form>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- KOLOM KIRI: INPUT JURNAL & SCANNER -->
            <div class="lg:col-span-1 space-y-6">
                
                <!-- 1. FORM JURNAL -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="bg-gray-50 px-6 py-3 border-b border-gray-100 flex items-center gap-2">
                        <i class="ph-fill ph-notebook text-blue-600"></i>
                        <h3 class="font-bold text-gray-800 text-sm">Jurnal & Bukti Kegiatan</h3>
                    </div>
                    
                    <div class="p-6">
                        <!-- [LOGIKA] Form Disabled Jika Closed -->
                        <fieldset {{ $session->status == 'closed' ? 'disabled' : '' }}>
                            <form action="{{ route('teaching.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="space-y-4">
                                    {{-- Topik --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Topik / Bahasan</label>
                                        <input type="text" name="topic" value="{{ $session->topic }}" 
                                            class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500 font-semibold text-gray-800 disabled:bg-slate-50 disabled:text-slate-500"
                                            placeholder="Misal: Aljabar Linear Pert. 1">
                                    </div>

                                    {{-- Aktivitas --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Catatan / Tugas</label>
                                        <textarea name="activities" rows="3" 
                                            class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700 disabled:bg-slate-50 disabled:text-slate-500"
                                            placeholder="Catatan untuk siswa...">{{ $session->activities }}</textarea>
                                    </div>

                                    {{-- Link Materi --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Link Materi (PDF/Drive)</label>
                                        <input type="url" name="reference_link" value="{{ $session->reference_link }}" 
                                            class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm text-blue-600 disabled:bg-slate-50 disabled:text-slate-500"
                                            placeholder="https://...">
                                    </div>

                                    {{-- Foto Bukti --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Foto Bukti Kegiatan</label>
                                        
                                        @if($session->photo_proof)
                                            <div class="mb-2 relative group w-full h-32 rounded-xl overflow-hidden border border-slate-200">
                                                <img src="{{ asset('storage/' . $session->photo_proof) }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                    <a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="text-white text-xs font-bold underline">Lihat Full</a>
                                                </div>
                                            </div>
                                        @endif

                                        @if($session->status == 'open')
                                            <input type="file" name="photo_proof" accept="image/*"
                                                class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @endif
                                    </div>

                                    {{-- Link Video --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Link Video (Youtube/Tiktok)</label>
                                        <input type="url" name="video_link" value="{{ $session->video_link }}" 
                                            class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm text-blue-600 disabled:bg-slate-50 disabled:text-slate-500"
                                            placeholder="https://youtube.com/...">
                                    </div>

                                    @if($session->status == 'open')
                                        <button type="submit" class="w-full bg-slate-800 text-white hover:bg-slate-700 font-bold py-3 rounded-xl transition shadow-lg shadow-slate-200 flex justify-center items-center gap-2">
                                            <i class="ph-bold ph-floppy-disk"></i> Simpan Data
                                        </button>
                                    @else
                                        <div class="p-3 bg-gray-100 rounded-xl text-center border border-gray-200">
                                            <p class="text-xs font-bold text-gray-500 uppercase flex items-center justify-center gap-2">
                                                <i class="ph-bold ph-lock"></i> Sesi Telah Selesai
                                            </p>
                                        </div>
                                    @endif
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>

                <!-- 2. SCANNER INPUT -->
                <!-- [LOGIKA] Scanner Hanya Muncul Jika Status OPEN -->
                @if($session->status == 'open')
                    <div class="bg-slate-800 rounded-2xl shadow-lg p-6 text-center text-white relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition"><i class="ph-fill ph-scan text-9xl"></i></div>
                        
                        <div class="relative z-10">
                            <div class="mb-4 inline-block p-3 rounded-full bg-slate-700/50 ring-2 ring-slate-600">
                                <i class="ph-duotone ph-qr-code text-4xl text-blue-400" :class="loading ? 'animate-pulse' : ''"></i>
                            </div>
                            
                            <h3 class="font-bold text-lg mb-1">Absensi Siswa</h3>
                            <p class="text-slate-400 text-xs mb-4">Scan QR Kartu Pelajar atau ketik NISN</p>
                            
                            <div class="relative mb-4">
                                <input type="text" id="rfidInput" x-model="rfidCode" @keydown.enter.prevent="submitScan()"
                                    class="w-full bg-slate-900 border-2 border-slate-700 text-white rounded-xl text-center font-mono text-lg tracking-widest focus:ring-blue-500 focus:border-blue-500 focus:outline-none py-3 transition-colors uppercase"
                                    :class="scanStatus == 'success' ? 'border-green-500' : (scanStatus == 'error' ? 'border-red-500' : 'border-slate-700')"
                                    placeholder="NISN / KODE..." autocomplete="off">
                                    
                                <div x-show="loading" class="absolute right-3 top-3.5" style="display: none;">
                                    <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                </div>
                            </div>

                            <button @click="toggleCamera()" type="button" 
                                class="w-full py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-900/50 transition flex items-center justify-center gap-2 mb-2">
                                <i class="ph-bold ph-camera"></i> <span x-text="showCamera ? 'Tutup Kamera' : 'Buka Kamera Scan'"></span>
                            </button>

                            <div x-show="showCamera" x-transition class="mt-4 bg-black rounded-xl overflow-hidden border-2 border-slate-600 relative">
                                <div id="reader" class="w-full h-64 bg-black"></div>
                                <p class="absolute bottom-2 inset-x-0 text-center text-[10px] text-white/70 bg-black/50 py-1">Arahkan kamera ke QR Code Siswa</p>
                            </div>
                                
                            <p class="mt-3 text-xs font-bold transition-colors duration-300 min-h-[1.5rem]" 
                               :class="scanStatus == 'success' ? 'text-green-400' : (scanStatus == 'error' ? 'text-red-400' : 'text-slate-500')"
                               x-text="statusMessage">Menunggu input...</p>
                        </div>
                    </div>
                @else
                    {{-- Tampilan Jika Kelas Sudah Ditutup --}}
                    <div class="bg-gray-100 rounded-2xl p-8 text-center border-2 border-dashed border-gray-300 flex flex-col items-center justify-center h-full min-h-[300px]">
                        <div class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center text-gray-400 mb-4">
                            <i class="ph-duotone ph-lock-key text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-gray-600 text-lg">Absensi Ditutup</h3>
                        <p class="text-sm text-gray-500 mt-1 max-w-[200px]">Sesi pembelajaran ini telah selesai. Anda tidak dapat melakukan scan siswa lagi.</p>
                    </div>
                @endif
            </div>

            <!-- KOLOM KANAN: LIST KEHADIRAN -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full min-h-[600px]">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
                        <div>
                            <h3 class="font-bold text-gray-800">Daftar Kehadiran</h3>
                            <p class="text-xs text-gray-500">
                                {{ $session->status == 'open' ? 'Realtime update.' : 'Rekapitulasi akhir.' }}
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="text-right">
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Hadir</p>
                                <p class="text-2xl font-black text-blue-600 leading-none" x-text="presentCount">{{ $presentCount }}</p>
                            </div>
                            <div class="h-8 w-px bg-gray-200"></div>
                            <div class="text-right">
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Total</p>
                                <p class="text-2xl font-black text-gray-400 leading-none">{{ $totalStudents }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 bg-gray-50/30">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="scanResults">
                            @foreach($session->attendances->sortByDesc('scanned_at') as $att)
                                <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-green-200 shadow-sm relative overflow-hidden">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500"></div>
                                    <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-sm shrink-0">
                                        {{ substr($att->student->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-bold text-gray-800 text-sm truncate">{{ $att->student->name }}</p>
                                        <p class="text-[10px] text-gray-500 flex items-center gap-1">
                                            <i class="ph-fill ph-check-circle text-green-500"></i>
                                            {{ $att->scanned_at ? \Carbon\Carbon::parse($att->scanned_at)->format('H:i:s') : 'Manual' }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                            
                            {{-- Tampilkan Siswa Alpha jika sesi ditutup --}}
                            @if($session->status == 'closed')
                                @php
                                    // Logic ini agak berat ditaruh di view, tapi untuk simpel kita taruh sini
                                    // Idealnya di controller
                                    $classId = $session->schedule->school_class_id; // Pastikan relasi schedule->school_class_id benar
                                    // Kita butuh akses data siswa yang alpha. 
                                    // Di controller 'show', kita belum kirim data alpha.
                                    // Namun, di database 'class_attendances' sudah tercatat status 'alpha'.
                                    // Jadi kita cukup loop attendances yang statusnya 'alpha'.
                                    $alphas = $session->attendances->where('status', 'alpha');
                                @endphp

                                @foreach($alphas as $att)
                                    <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-rose-200 shadow-sm relative overflow-hidden opacity-80">
                                        <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
                                        <div class="w-10 h-10 rounded-full bg-rose-100 text-rose-700 flex items-center justify-center font-bold text-sm shrink-0">
                                            {{ substr($att->student->name ?? '?', 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-bold text-gray-800 text-sm truncate">{{ $att->student->name ?? 'Siswa' }}</p>
                                            <p class="text-[10px] text-rose-500 flex items-center gap-1">
                                                <i class="ph-fill ph-x-circle"></i>
                                                Tidak Hadir (Alpha)
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    {{-- Script Alpine/Scanner sama seperti sebelumnya, tidak perlu diubah --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('teachingSession', (config) => ({
                rfidCode: '',
                sessionId: config.sessionId,
                presentCount: config.presentCount,
                loading: false,
                scanStatus: 'idle',
                statusMessage: 'Siap memindai...',
                showCamera: false,
                html5QrcodeScanner: null,

                async submitScan() {
                    if(this.rfidCode.length < 3) return;
                    
                    this.loading = true;
                    this.scanStatus = 'idle';
                    this.statusMessage = 'Memproses...';
                    
                    try {
                        const response = await fetch('{{ route("teaching.scan") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                rfid: this.rfidCode,
                                session_id: this.sessionId
                            })
                        });
                        
                        const data = await response.json();
                        
                        if(data.status === 'success') {
                            this.addStudentToUI(data.student, data.time);
                            this.presentCount++;
                            this.scanStatus = 'success';
                            this.statusMessage = 'BERHASIL: ' + data.student.name;
                            
                            if(this.showCamera) {
                                await new Promise(r => setTimeout(r, 1500));
                                this.statusMessage = 'Siap scan berikutnya...';
                                this.scanStatus = 'idle';
                            }
                            
                        } else if(data.status === 'warning') {
                            this.scanStatus = 'error'; 
                            this.statusMessage = 'INFO: ' + data.message;
                        } else {
                            this.scanStatus = 'error';
                            this.statusMessage = 'GAGAL: ' + data.message;
                        }
                    } catch (error) {
                        console.error(error);
                        this.scanStatus = 'error';
                        this.statusMessage = 'Kesalahan koneksi server.';
                    }

                    this.loading = false;
                    
                    if(!this.showCamera) {
                        this.rfidCode = ''; 
                        this.$nextTick(() => { document.getElementById('rfidInput').focus(); });
                    } else {
                        this.rfidCode = '';
                    }
                },

                toggleCamera() {
                    this.showCamera = !this.showCamera;
                    if (this.showCamera) {
                        this.$nextTick(() => { this.startScanner(); });
                    } else {
                        this.stopScanner();
                    }
                },

                startScanner() {
                    this.html5QrcodeScanner = new Html5Qrcode("reader");
                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                    
                    this.html5QrcodeScanner.start(
                        { facingMode: "environment" },
                        config,
                        (decodedText, decodedResult) => {
                            if (this.loading) return;
                            this.rfidCode = decodedText;
                            this.submitScan();
                        },
                        (errorMessage) => {}
                    ).catch(err => {
                        console.log("Error starting scanner", err);
                        this.statusMessage = "Gagal akses kamera";
                    });
                },

                stopScanner() {
                    if (this.html5QrcodeScanner) {
                        this.html5QrcodeScanner.stop().then(() => {
                            this.html5QrcodeScanner.clear();
                        }).catch(err => {
                            console.log("Failed to stop scanner", err);
                        });
                    }
                },

                addStudentToUI(student, time) {
                    const container = document.getElementById('scanResults');
                    const html = `
                        <div class="flex items-center gap-3 p-3 rounded-xl bg-white border border-green-200 shadow-sm relative overflow-hidden animate-fade-in-up transition-all duration-500">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-green-500"></div>
                            <div class="w-10 h-10 rounded-full bg-green-100 text-green-700 flex items-center justify-center font-bold text-sm shrink-0">
                                ${student.name.charAt(0)}
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-800 text-sm truncate">${student.name}</p>
                                <p class="text-[10px] text-gray-500 flex items-center gap-1">
                                    <i class="ph-fill ph-check-circle text-green-500"></i>
                                    ${time} (Baru)
                                </p>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('afterbegin', html);
                }
            }));
        });
    </script>
    <style>
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }
    </style>
    @endpush
</x-app-layout>