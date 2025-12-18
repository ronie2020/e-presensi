<x-app-layout>
    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    @endpush

    @php
        $allStudents = $session->schedule->schoolClass->students->sortBy('name');
        $attendances = $session->attendances->keyBy('student_id');
        $isOpen = $session->status == 'open';
    @endphp

    <div class="py-6 sm:py-8" 
         x-data="teachingSession({ 
            sessionId: {{ $session->id }}, 
            presentCount: {{ $presentCount }} 
         })">
         
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGASI --}}
            <div class="flex items-center gap-2 text-sm text-slate-500 mb-6">
                <a href="{{ route('teaching.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-bold">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Jadwal
                </a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-800 font-bold">Sesi Mengajar</span>
            </div>

            {{-- ALERT NOTIFIKASI --}}
            @if(session('error'))
                <div class="bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl shadow-sm mb-6 flex items-center gap-3">
                    <i class="ph-fill ph-warning-circle text-2xl text-rose-500"></i>
                    <div>
                        <h4 class="font-bold text-rose-800">Terjadi Kesalahan</h4>
                        <p class="text-sm text-rose-700">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            {{-- HEADER SESI (GLASSY) --}}
            <div class="relative bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl p-8 text-white shadow-2xl shadow-slate-200 mb-8 overflow-hidden">
                <div class="absolute right-0 top-0 h-full w-1/2 bg-white/5 skew-x-12 transform origin-bottom-right"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <div class="flex flex-wrap items-center gap-3 mb-3">
                            <span class="bg-blue-500 shadow-lg shadow-blue-500/40 text-white text-xs font-bold px-3 py-1 rounded-lg uppercase tracking-wider">
                                {{ $session->schedule->schoolClass->name }}
                            </span>
                            <span class="bg-white/10 border border-white/20 text-blue-100 text-xs font-bold px-3 py-1 rounded-lg flex items-center gap-1.5">
                                <i class="ph-bold ph-clock"></i> Mulai: {{ \Carbon\Carbon::parse($session->started_at)->format('H:i') }}
                            </span>
                            @if(!$isOpen)
                                <span class="bg-slate-500 text-white text-xs font-bold px-3 py-1 rounded-lg uppercase">Selesai</span>
                            @endif
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight leading-none text-white">
                            {{ $session->schedule->subject->name }}
                        </h1>
                    </div>
                    
                    @if($isOpen)
                        <form action="{{ route('teaching.close', $session->id) }}" method="POST" 
                              onsubmit="return confirm('Apakah Anda yakin ingin menutup sesi kelas ini? Pastikan semua siswa sudah diabsen.')">
                            @csrf
                            <button type="submit" class="group bg-rose-600 hover:bg-rose-500 text-white pl-5 pr-6 py-3 rounded-2xl font-bold shadow-lg shadow-rose-900/40 border border-rose-500 transition-all active:scale-95 flex items-center gap-3">
                                <div class="bg-white/20 p-2 rounded-xl group-hover:rotate-90 transition-transform duration-300">
                                    <i class="ph-bold ph-power text-xl"></i>
                                </div>
                                <div class="text-left">
                                    <div class="text-[10px] uppercase opacity-80 font-bold tracking-wider">Selesai Mengajar</div>
                                    <div class="text-base leading-none">Tutup Kelas</div>
                                </div>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                {{-- KOLOM KIRI (JURNAL & SCANNER) - Width 4/12 --}}
                <div class="xl:col-span-4 space-y-8">
                    
                    {{-- 1. SCANNER KARTU (Hanya jika Open) --}}
                    @if($isOpen)
                        <div class="bg-slate-900 rounded-[2rem] shadow-xl p-6 text-center text-white relative overflow-hidden group border border-slate-700">
                            {{-- Background Decoration --}}
                            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-2 text-blue-400">
                                        <i class="ph-fill ph-wifi-high text-xl animate-pulse"></i>
                                        <span class="text-xs font-bold uppercase tracking-widest">Scanner Aktif</span>
                                    </div>
                                    <div class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_#22c55e]"></div>
                                </div>

                                <div class="mb-6 relative">
                                    <input type="text" id="rfidInput" x-model="rfidCode" @keydown.enter.prevent="submitScan()"
                                        class="w-full bg-slate-800/50 border-2 border-slate-600 focus:border-blue-500 text-white rounded-2xl text-center font-mono text-xl tracking-[0.2em] py-4 transition-all focus:ring-4 focus:ring-blue-500/20 uppercase placeholder:text-slate-600"
                                        placeholder="TAP KARTU..." autocomplete="off">
                                    <i class="ph-duotone ph-scan text-slate-500 absolute right-4 top-1/2 -translate-y-1/2 text-2xl"></i>
                                </div>

                                <button @click="toggleCamera()" type="button" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-blue-300 font-bold rounded-xl border border-slate-600 transition flex items-center justify-center gap-2 text-sm group-hover:border-blue-500/50">
                                    <i class="ph-bold ph-camera text-lg"></i>
                                    <span x-text="showCamera ? 'Tutup Kamera' : 'Scan QR Code'"></span>
                                </button>

                                <div x-show="showCamera" x-transition class="mt-4 bg-black rounded-2xl overflow-hidden border-2 border-slate-700 relative shadow-inner">
                                    <div id="reader" class="w-full h-56 bg-black"></div>
                                </div>

                                <p class="mt-4 text-xs font-mono text-slate-400" x-text="statusMessage">Menunggu input kartu atau QR...</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2rem] p-8 text-center">
                            <i class="ph-duotone ph-lock-key text-4xl text-slate-300 mb-3"></i>
                            <h3 class="font-bold text-slate-600">Absensi Terkunci</h3>
                            <p class="text-sm text-slate-400">Kelas sudah ditutup.</p>
                        </div>
                    @endif

                    {{-- 2. FORM JURNAL --}}
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                            <h3 class="font-bold text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-notebook text-blue-500"></i> Jurnal Mengajar
                            </h3>
                        </div>
                        <div class="p-6">
                            <fieldset {{ !$isOpen ? 'disabled' : '' }}>
                                <form action="{{ route('teaching.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Topik / Materi <span class="text-rose-500">*</span></label>
                                            <input type="text" name="topic" value="{{ old('topic', $session->topic) }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 disabled:bg-slate-50 disabled:text-slate-500" placeholder="Contoh: Aljabar Linear" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Catatan Kegiatan</label>
                                            <textarea name="activities" rows="3" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm text-slate-600 disabled:bg-slate-50" placeholder="Deskripsi kegiatan pembelajaran...">{{ old('activities', $session->activities) }}</textarea>
                                        </div>
                                        
                                        {{-- Upload Foto --}}
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Foto Dokumentasi</label>
                                            @if($session->photo_proof)
                                                <div class="relative group h-40 rounded-xl overflow-hidden border border-slate-200 mb-3">
                                                    <img src="{{ asset('storage/' . $session->photo_proof) }}" class="w-full h-full object-cover">
                                                    <a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition text-white font-bold text-sm">
                                                        <i class="ph-bold ph-eye mr-2"></i> Lihat Foto
                                                    </a>
                                                </div>
                                            @endif
                                            @if($isOpen)
                                                <input type="file" name="photo_proof" accept="image/*" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer">
                                            @endif
                                        </div>

                                        @if($isOpen)
                                            <button type="submit" class="w-full bg-slate-800 text-white hover:bg-slate-700 font-bold py-3.5 rounded-xl transition shadow-lg shadow-slate-200 flex justify-center items-center gap-2">
                                                <i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (DAFTAR SISWA) - Width 8/12 --}}
                <div class="xl:col-span-8">
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 flex flex-col h-full min-h-[800px]">
                        
                        {{-- Header List --}}
                        <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/30 rounded-t-[2rem]">
                            <div>
                                <h3 class="font-black text-slate-800 text-lg">Kehadiran Siswa</h3>
                                <p class="text-sm text-slate-500 font-medium mt-1">Total: {{ $allStudents->count() }} Siswa</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="text-right hidden sm:block">
                                    <span class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Hadir</span>
                                    <p class="text-2xl font-black text-emerald-600 leading-none" x-text="presentCount">0</p>
                                </div>
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-xl shadow-sm">
                                    <i class="ph-fill ph-users-three"></i>
                                </div>
                            </div>
                        </div>

                        {{-- List Siswa --}}
                        <div class="flex-1 p-6 bg-slate-50/50 overflow-y-auto max-h-[800px] custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($allStudents as $student)
                                    @php
                                        $att = $attendances[$student->id] ?? null;
                                        $status = $att ? $att->status : null;
                                        
                                        // Warna Card berdasarkan Status
                                        $cardClass = 'bg-white border-slate-200';
                                        if ($status == 'present') $cardClass = 'bg-emerald-50/50 border-emerald-200 ring-1 ring-emerald-500/20';
                                        elseif ($status == 'sick') $cardClass = 'bg-blue-50/50 border-blue-200';
                                        elseif ($status == 'permission') $cardClass = 'bg-amber-50/50 border-amber-200';
                                        elseif ($status == 'alpha') $cardClass = 'bg-rose-50/50 border-rose-200';
                                    @endphp

                                    <div class="relative border rounded-2xl p-4 flex items-center gap-4 transition-all duration-300 {{ $cardClass }}" 
                                         id="student-row-{{ $student->id }}">
                                        
                                        {{-- Avatar --}}
                                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-sm shrink-0 shadow-sm
                                            {{ $status ? 'bg-white' : 'bg-slate-100 text-slate-500' }}">
                                            @if($status == 'present') <i class="ph-fill ph-check text-emerald-500 text-xl"></i>
                                            @elseif($status == 'sick') <span class="text-blue-600">S</span>
                                            @elseif($status == 'permission') <span class="text-amber-600">I</span>
                                            @elseif($status == 'alpha') <span class="text-rose-600">A</span>
                                            @else {{ substr($student->name, 0, 1) }}
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 text-sm truncate">{{ $student->name }}</p>
                                            <p class="text-xs text-slate-500 font-mono">{{ $student->student_id }}</p>
                                        </div>

                                        {{-- Tombol Aksi (Hanya jika Open) --}}
                                        @if($isOpen)
                                            <div class="flex items-center gap-1">
                                                {{-- Tombol Hadir --}}
                                                <button @click="setManual({{ $student->id }}, 'present')" 
                                                        class="w-9 h-9 rounded-xl flex items-center justify-center transition-all shadow-sm
                                                        {{ $status == 'present' ? 'bg-emerald-500 text-white shadow-emerald-200' : 'bg-white border border-slate-200 text-slate-400 hover:border-emerald-500 hover:text-emerald-500' }}"
                                                        title="Hadir">
                                                    <i class="ph-bold ph-check"></i>
                                                </button>
                                                
                                                {{-- Dropdown Kecil untuk S/I/A --}}
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.outside="open = false"
                                                            class="w-9 h-9 rounded-xl flex items-center justify-center transition-all shadow-sm
                                                            {{ in_array($status, ['sick', 'permission', 'alpha']) ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-400 hover:border-slate-800 hover:text-slate-800' }}">
                                                        <i class="ph-bold ph-dots-three-vertical"></i>
                                                    </button>
                                                    
                                                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-32 bg-white rounded-xl shadow-xl border border-slate-100 z-20 py-1 overflow-hidden">
                                                        <button @click="setManual({{ $student->id }}, 'sick'); open=false" class="w-full text-left px-4 py-2 text-xs font-bold text-blue-600 hover:bg-blue-50 flex items-center gap-2"><div class="w-2 h-2 bg-blue-500 rounded-full"></div> Sakit</button>
                                                        <button @click="setManual({{ $student->id }}, 'permission'); open=false" class="w-full text-left px-4 py-2 text-xs font-bold text-amber-600 hover:bg-amber-50 flex items-center gap-2"><div class="w-2 h-2 bg-amber-500 rounded-full"></div> Izin</button>
                                                        <button @click="setManual({{ $student->id }}, 'alpha'); open=false" class="w-full text-left px-4 py-2 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2"><div class="w-2 h-2 bg-rose-500 rounded-full"></div> Alpha</button>
                                                        <div class="border-t border-slate-100 my-1"></div>
                                                        <button @click="setManual({{ $student->id }}, null); open=false" class="w-full text-left px-4 py-2 text-xs text-slate-400 hover:bg-slate-50">Reset</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Tampilan Status Read-Only (Jika Closed) --}}
                                            <div>
                                                @if($status == 'present') <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-xs font-bold rounded-lg">Hadir</span>
                                                @elseif($status == 'sick') <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-lg">Sakit</span>
                                                @elseif($status == 'permission') <span class="px-3 py-1 bg-amber-100 text-amber-700 text-xs font-bold rounded-lg">Izin</span>
                                                @elseif($status == 'alpha') <span class="px-3 py-1 bg-rose-100 text-rose-700 text-xs font-bold rounded-lg">Alpha</span>
                                                @else <span class="text-xs text-slate-400 italic"> - </span>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
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
                statusMessage: 'Siap memindai...',
                showCamera: false,
                html5QrcodeScanner: null,

                async setManual(studentId, status) {
                    // Logic kirim data via fetch (Sama seperti sebelumnya, hanya UI berubah)
                    try {
                        const response = await fetch('{{ route("teaching.manual") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ session_id: this.sessionId, student_id: studentId, status: status })
                        });
                        const data = await response.json();
                        if(data.status === 'success') window.location.reload();
                    } catch (e) { alert('Gagal menghubungi server.'); }
                },

                async submitScan() {
                    if(this.rfidCode.length < 3) return;
                    this.statusMessage = 'Memproses...';
                    try {
                        const response = await fetch('{{ route("teaching.scan") }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: JSON.stringify({ rfid: this.rfidCode, session_id: this.sessionId })
                        });
                        const data = await response.json();
                        if(data.status === 'success') {
                            this.statusMessage = 'OK: ' + data.student.name;
                            window.location.reload();
                        } else {
                            this.statusMessage = 'GAGAL: ' + data.message;
                        }
                    } catch (error) { this.statusMessage = 'Error koneksi'; }
                    this.rfidCode = '';
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