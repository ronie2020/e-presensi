<x-app-layout>
    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    @endpush

    <!-- Mengambil daftar semua siswa di kelas ini untuk ditampilkan -->
    @php
        // Ambil semua siswa di kelas, urutkan nama
        $allStudents = $session->schedule->schoolClass->students->sortBy('name');
        // Ambil data absensi yang sudah ada, key by student_id biar gampang dicek
        $attendances = $session->attendances->keyBy('student_id');
    @endphp

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
                            <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded shadow-sm">SELESAI</span>
                        @endif
                    </div>
                    <h1 class="text-2xl md:text-3xl font-black tracking-tight leading-tight">
                        {{ $session->schedule->subject->name }}
                    </h1>
                </div>
                
                @if($session->status == 'open')
                    <form action="{{ route('teaching.close', $session->id) }}" method="POST" 
                          onsubmit="return confirm('PERINGATAN: Siswa yang statusnya masih kosong akan otomatis dianggap ALPHA. Lanjutkan?')">
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
                        <fieldset {{ $session->status == 'closed' ? 'disabled' : '' }}>
                            <form action="{{ route('teaching.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Topik / Bahasan</label>
                                        <input type="text" name="topic" value="{{ $session->topic }}" class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500 font-semibold text-gray-800 disabled:bg-slate-50" placeholder="Misal: Aljabar Linear Pert. 1">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Catatan / Tugas</label>
                                        <textarea name="activities" rows="3" class="w-full rounded-xl border-gray-200 focus:ring-blue-500 focus:border-blue-500 text-sm text-gray-700 disabled:bg-slate-50" placeholder="Catatan untuk siswa...">{{ $session->activities }}</textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Link Materi</label>
                                            <input type="url" name="reference_link" value="{{ $session->reference_link }}" class="w-full rounded-lg border-gray-200 text-xs disabled:bg-slate-50" placeholder="https://...">
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Link Video</label>
                                            <input type="url" name="video_link" value="{{ $session->video_link }}" class="w-full rounded-lg border-gray-200 text-xs disabled:bg-slate-50" placeholder="https://...">
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Foto Bukti</label>
                                        @if($session->photo_proof)
                                            <div class="mb-2 relative group w-full h-24 rounded-lg overflow-hidden border border-slate-200">
                                                <img src="{{ asset('storage/' . $session->photo_proof) }}" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                    <a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="text-white text-xs font-bold underline">Lihat</a>
                                                </div>
                                            </div>
                                        @endif
                                        @if($session->status == 'open')
                                            <input type="file" name="photo_proof" accept="image/*" class="w-full text-xs text-slate-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-full file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        @endif
                                    </div>
                                    @if($session->status == 'open')
                                        <button type="submit" class="w-full bg-slate-800 text-white hover:bg-slate-700 font-bold py-2.5 rounded-xl transition shadow-lg shadow-slate-200 flex justify-center items-center gap-2 text-sm">
                                            <i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal
                                        </button>
                                    @endif
                                </div>
                            </form>
                        </fieldset>
                    </div>
                </div>

                <!-- 2. SCANNER INPUT -->
                @if($session->status == 'open')
                    <div class="bg-slate-800 rounded-2xl shadow-lg p-6 text-center text-white relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition"><i class="ph-fill ph-scan text-9xl"></i></div>
                        <div class="relative z-10">
                            <div class="mb-3 inline-block p-2 rounded-full bg-slate-700/50 ring-2 ring-slate-600">
                                <i class="ph-duotone ph-qr-code text-3xl text-blue-400" :class="loading ? 'animate-pulse' : ''"></i>
                            </div>
                            <h3 class="font-bold text-base mb-1">Absensi Scan</h3>
                            <div class="relative mb-3">
                                <input type="text" id="rfidInput" x-model="rfidCode" @keydown.enter.prevent="submitScan()"
                                    class="w-full bg-slate-900 border-2 border-slate-700 text-white rounded-xl text-center font-mono text-lg tracking-widest focus:ring-blue-500 focus:border-blue-500 focus:outline-none py-2 transition-colors uppercase"
                                    placeholder="NISN / KODE..." autocomplete="off">
                            </div>
                            <button @click="toggleCamera()" type="button" class="w-full py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-900/50 transition flex items-center justify-center gap-2 mb-2 text-sm">
                                <i class="ph-bold ph-camera"></i> <span x-text="showCamera ? 'Tutup Kamera' : 'Buka Kamera'"></span>
                            </button>
                            <div x-show="showCamera" class="mt-4 bg-black rounded-xl overflow-hidden border-2 border-slate-600 relative">
                                <div id="reader" class="w-full h-48 bg-black"></div>
                            </div>
                            <p class="mt-2 text-[10px] font-bold text-slate-500" x-text="statusMessage">Menunggu input...</p>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-100 rounded-2xl p-6 text-center border-2 border-dashed border-gray-300">
                        <i class="ph-duotone ph-lock-key text-3xl text-gray-400 mb-2"></i>
                        <h3 class="font-bold text-gray-600 text-sm">Kelas Selesai</h3>
                        <p class="text-xs text-gray-500">Absensi terkunci.</p>
                    </div>
                @endif
            </div>

            <!-- KOLOM KANAN: LIST ABSENSI LENGKAP -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col h-full min-h-[600px]">
                    <div class="p-5 border-b border-gray-100 flex justify-between items-center bg-gray-50/50 rounded-t-2xl">
                        <div>
                            <h3 class="font-bold text-gray-800">Daftar Siswa Kelas {{ $session->schedule->schoolClass->name }}</h3>
                            <p class="text-xs text-gray-500">Total: {{ $totalStudents }} Siswa</p>
                        </div>
                        <div class="text-right">
                            <span class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Hadir</span>
                            <p class="text-2xl font-black text-blue-600 leading-none" x-text="presentCount">{{ $presentCount }}</p>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto p-4 bg-gray-50/30">
                        <div class="space-y-3">
                            @foreach($allStudents as $student)
                                @php
                                    $att = $attendances[$student->id] ?? null;
                                    $status = $att ? $att->status : null;
                                @endphp

                                <div class="bg-white border border-gray-200 rounded-xl p-3 flex items-center justify-between shadow-sm hover:shadow-md transition-shadow group" 
                                     id="student-row-{{ $student->id }}">
                                    
                                    {{-- Info Siswa --}}
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm shrink-0 {{ $status == 'present' ? 'bg-green-100 text-green-700' : ($status == 'sick' ? 'bg-blue-100 text-blue-700' : ($status == 'permission' ? 'bg-amber-100 text-amber-700' : ($status == 'alpha' ? 'bg-rose-100 text-rose-700' : 'bg-gray-100 text-gray-500'))) }}">
                                            {{ substr($student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-800 text-sm line-clamp-1">{{ $student->name }}</p>
                                            <p class="text-[10px] text-gray-500">{{ $student->student_id }}</p>
                                        </div>
                                    </div>

                                    {{-- Status / Tombol Aksi --}}
                                    <div class="flex items-center gap-2" id="action-{{ $student->id }}">
                                        @if($status)
                                            {{-- Jika Sudah Ada Status (Tampilkan Badge) --}}
                                            <div class="flex flex-col items-end">
                                                @if($status == 'present')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-green-50 text-green-700 text-xs font-bold border border-green-100">
                                                        <i class="ph-fill ph-check-circle"></i> Hadir
                                                    </span>
                                                    <span class="text-[10px] text-green-600 mt-0.5">{{ \Carbon\Carbon::parse($att->scanned_at)->format('H:i') }}</span>
                                                @elseif($status == 'sick')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold border border-blue-100">
                                                        <i class="ph-fill ph-first-aid"></i> Sakit
                                                    </span>
                                                @elseif($status == 'permission')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-50 text-amber-700 text-xs font-bold border border-amber-100">
                                                        <i class="ph-fill ph-hand-waving"></i> Izin
                                                    </span>
                                                @elseif($status == 'alpha')
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-rose-50 text-rose-700 text-xs font-bold border border-rose-100">
                                                        <i class="ph-fill ph-x-circle"></i> Alpha
                                                    </span>
                                                @endif
                                                
                                                {{-- Tombol Ubah (Hanya jika sesi Open) --}}
                                                @if($session->status == 'open')
                                                    <button @click="resetStatus({{ $student->id }})" class="text-[10px] text-slate-400 hover:text-blue-500 underline mt-1">Ubah</button>
                                                @endif
                                            </div>
                                        @else
                                            {{-- Jika Belum Ada Status (Tampilkan Tombol) --}}
                                            @if($session->status == 'open')
                                                <div class="flex gap-1">
                                                    <button @click="setManual({{ $student->id }}, 'present')" class="w-8 h-8 rounded-lg bg-green-50 text-green-600 hover:bg-green-600 hover:text-white border border-green-200 transition flex items-center justify-center" title="Hadir Manual">
                                                        <i class="ph-bold ph-check"></i>
                                                    </button>
                                                    <button @click="setManual({{ $student->id }}, 'sick')" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-200 transition flex items-center justify-center" title="Sakit">
                                                        <span class="font-bold text-xs">S</span>
                                                    </button>
                                                    <button @click="setManual({{ $student->id }}, 'permission')" class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white border border-amber-200 transition flex items-center justify-center" title="Izin">
                                                        <span class="font-bold text-xs">I</span>
                                                    </button>
                                                    <button @click="setManual({{ $student->id }}, 'alpha')" class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-200 transition flex items-center justify-center" title="Alpha">
                                                        <span class="font-bold text-xs">A</span>
                                                    </button>
                                                </div>
                                            @else
                                                <span class="text-xs text-slate-400 italic">Belum absen</span>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
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

                // --- FUNGSI BARU: ABSENSI MANUAL ---
                async setManual(studentId, status) {
                    try {
                        const response = await fetch('{{ route("teaching.manual") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({
                                session_id: this.sessionId,
                                student_id: studentId,
                                status: status
                            })
                        });
                        
                        const data = await response.json();
                        if(data.status === 'success') {
                            // Reload halaman agar UI terupdate (Paling aman & mudah)
                            // Atau update DOM secara manual jika ingin SPA-like
                            window.location.reload(); 
                        }
                    } catch (e) {
                        alert('Gagal menyimpan status.');
                    }
                },

                // Reset Status (Hapus absensi agar bisa dipilih ulang) - Opsional logic
                resetStatus(studentId) {
                    // Logic ini bisa ditambahkan jika ingin fitur "Batal Absen"
                    // Untuk sekarang cukup reload atau setManual ke status lain
                    alert('Silakan pilih status baru.');
                    this.setManual(studentId, 'alpha'); // Default reset ke Alpha atau logic delete
                },

                // --- FUNGSI SCAN OTOMATIS (Existing) ---
                async submitScan() {
                    if(this.rfidCode.length < 3) return;
                    this.loading = true;
                    this.statusMessage = 'Memproses...';
                    
                    try {
                        const response = await fetch('{{ route("teaching.scan") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: JSON.stringify({ rfid: this.rfidCode, session_id: this.sessionId })
                        });
                        
                        const data = await response.json();
                        if(data.status === 'success') {
                            this.statusMessage = 'BERHASIL: ' + data.student.name;
                            window.location.reload(); // Reload agar status di list kanan berubah
                        } else {
                            this.statusMessage = 'GAGAL: ' + data.message;
                        }
                    } catch (error) { this.statusMessage = 'Error koneksi'; }

                    this.loading = false;
                    this.rfidCode = '';
                    if(!this.showCamera) this.$nextTick(() => { document.getElementById('rfidInput').focus(); });
                },

                toggleCamera() {
                    this.showCamera = !this.showCamera;
                    if (this.showCamera) this.$nextTick(() => { this.startScanner(); }); else this.stopScanner();
                },

                startScanner() {
                    this.html5QrcodeScanner = new Html5Qrcode("reader");
                    this.html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: 250 },
                        (decodedText) => { if (!this.loading) { this.rfidCode = decodedText; this.submitScan(); } }
                    );
                },

                stopScanner() {
                    if (this.html5QrcodeScanner) this.html5QrcodeScanner.stop().then(() => this.html5QrcodeScanner.clear());
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>