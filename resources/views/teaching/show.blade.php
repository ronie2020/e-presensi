<x-app-layout>
    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    @php
        $allStudents = $session->schedule->schoolClass->students->sortBy('name');
        $attendances = $session->attendances->keyBy('student_id');
        $isOpen = $session->status == 'open';
    @endphp

    <div class="py-8 sm:py-10 font-sans text-slate-800" 
         x-data="teachingSession({ 
            sessionId: {{ $session->id }}, 
            presentCount: {{ $presentCount }} 
         })">
         
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER NAVIGASI --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                <a href="{{ route('teaching.index') }}" class="group inline-flex items-center gap-2 text-sm text-slate-500 hover:text-blue-600 transition font-bold">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:border-blue-200 group-hover:bg-blue-50 transition">
                        <i class="ph-bold ph-arrow-left"></i>
                    </div>
                    Kembali ke Jadwal
                </a>
                
                @if(session('error'))
                    <div class="bg-rose-50 text-rose-600 px-4 py-2 rounded-xl text-sm font-bold flex items-center gap-2 border border-rose-100 animate-pulse">
                        <i class="ph-fill ph-warning-circle"></i> {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- HEADER SESI (DARK BLUE PREMIUM) --}}
            <div class="relative bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 rounded-[2.5rem] p-8 md:p-10 text-white shadow-2xl shadow-blue-900/30 mb-8 overflow-hidden group border border-white/10">
                <!-- Decorative Elements -->
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute right-0 top-0 h-full w-2/3 bg-gradient-to-l from-blue-600/10 to-transparent skew-x-12 transform origin-bottom-right"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div class="space-y-4">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="bg-blue-500 shadow-lg shadow-blue-500/40 text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider border border-blue-400/50">
                                {{ $session->schedule->schoolClass->name }}
                            </span>
                            <span class="bg-white/10 backdrop-blur-md border border-white/20 text-blue-100 text-[10px] font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                <i class="ph-bold ph-clock"></i> Mulai: {{ \Carbon\Carbon::parse($session->started_at)->format('H:i') }}
                            </span>
                            @if(!$isOpen)
                                <span class="bg-slate-700/80 backdrop-blur text-slate-300 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-slate-600/50 flex items-center gap-1">
                                    <i class="ph-fill ph-lock-key"></i> Sesi Selesai
                                </span>
                            @else
                                <span class="bg-emerald-500/80 backdrop-blur text-white text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-emerald-400/50 animate-pulse flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span> Live
                                </span>
                            @endif
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-none text-white drop-shadow-sm">
                            {{ $session->schedule->subject->name }}
                        </h1>
                    </div>
                    
                    @if($isOpen)
                        {{-- Tombol Tutup Kelas --}}
                        <form id="close-session-form" action="{{ route('teaching.close', $session->id) }}" method="POST">
                            @csrf
                            <button type="button" onclick="confirmCloseClass()" class="group relative overflow-hidden bg-white hover:bg-rose-50 text-rose-600 pl-5 pr-6 py-3.5 rounded-2xl font-bold shadow-xl shadow-slate-900/20 transition-all active:scale-95 flex items-center gap-3 border border-white/20">
                                <div class="bg-rose-100 p-2 rounded-xl group-hover:bg-rose-200 transition-colors">
                                    <i class="ph-bold ph-power text-xl"></i>
                                </div>
                                <div class="text-left">
                                    <div class="text-[9px] uppercase opacity-60 font-black tracking-widest text-slate-500">Selesai Mengajar</div>
                                    <div class="text-base leading-none font-black">Tutup Kelas</div>
                                </div>
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-8">
                
                {{-- KOLOM KIRI (JURNAL & SCANNER) - Width 4/12 --}}
                <div class="xl:col-span-4 space-y-8 h-fit sticky top-6">
                    
                    {{-- 1. SCANNER KARTU (Modern Dark Style) --}}
                    @if($isOpen)
                        <div class="bg-slate-900 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 p-6 text-center text-white relative overflow-hidden group border border-slate-800">
                            {{-- Background Decoration --}}
                            <div class="absolute inset-0 opacity-20 bg-[radial-gradient(#3b82f6_1px,transparent_1px)] [background-size:16px_16px]"></div>
                            
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-6">
                                    <div class="flex items-center gap-2 text-blue-400 bg-slate-800/50 px-3 py-1 rounded-full border border-slate-700">
                                        <i class="ph-fill ph-wifi-high text-lg animate-pulse"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest">RFID Ready</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[10px] font-mono text-slate-500">LIVE</span>
                                        <div class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_10px_#22c55e] animate-ping"></div>
                                    </div>
                                </div>

                                <div class="mb-6 relative group/input">
                                    <div class="absolute inset-0 bg-blue-500/10 blur-xl rounded-full group-hover/input:bg-blue-500/20 transition-all"></div>
                                    <input type="text" id="rfidInput" x-model="rfidCode" @keydown.enter.prevent="submitScan()"
                                        class="relative w-full bg-slate-800/80 border-2 border-slate-700 focus:border-blue-500 text-white rounded-2xl text-center font-mono text-xl tracking-[0.2em] py-5 transition-all focus:ring-4 focus:ring-blue-500/20 uppercase placeholder:text-slate-600 shadow-inner"
                                        placeholder="TAP KARTU..." autocomplete="off" autofocus>
                                    <i class="ph-duotone ph-scan text-slate-500 absolute right-5 top-1/2 -translate-y-1/2 text-2xl group-focus-within/input:text-blue-500 transition-colors"></i>
                                </div>

                                <button @click="toggleCamera()" type="button" class="w-full py-3 bg-slate-800 hover:bg-slate-700 text-blue-300 font-bold rounded-xl border border-slate-700 hover:border-blue-500/50 transition flex items-center justify-center gap-2 text-sm shadow-lg shadow-black/20">
                                    <i class="ph-bold ph-camera text-lg"></i>
                                    <span x-text="showCamera ? 'Tutup Kamera' : 'Scan QR Code'"></span>
                                </button>

                                <div x-show="showCamera" x-transition class="mt-4 bg-black rounded-2xl overflow-hidden border-2 border-slate-700 relative shadow-inner">
                                    <div id="reader" class="w-full h-56 bg-black"></div>
                                </div>

                                <p class="mt-4 text-xs font-mono text-slate-500" x-text="statusMessage">Menunggu input kartu atau QR...</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-white border border-slate-200 rounded-[2.5rem] p-10 text-center shadow-sm">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                <i class="ph-duotone ph-lock-key text-4xl"></i>
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg">Absensi Terkunci</h3>
                            <p class="text-sm text-slate-500">Sesi kelas telah berakhir.</p>
                        </div>
                    @endif

                    {{-- 2. FORM JURNAL --}}
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                        <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-sm">
                                <i class="ph-fill ph-notebook"></i>
                            </div>
                            <h3 class="font-bold text-slate-800 text-lg">Jurnal Mengajar</h3>
                        </div>
                        <div class="p-8">
                            <fieldset {{ !$isOpen ? 'disabled' : '' }}>
                                <form action="{{ route('teaching.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Topik / Materi <span class="text-rose-500">*</span></label>
                                            <input type="text" name="topic" value="{{ old('topic', $session->topic) }}" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500/20 font-bold text-slate-700 py-3 px-4 disabled:bg-slate-50 disabled:text-slate-500 transition-all bg-slate-50" placeholder="Contoh: Aljabar Linear" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Catatan Kegiatan</label>
                                            <textarea name="activities" rows="3" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500/20 text-sm text-slate-600 py-3 px-4 disabled:bg-slate-50 font-medium bg-slate-50 transition-all" placeholder="Deskripsi kegiatan pembelajaran...">{{ old('activities', $session->activities) }}</textarea>
                                        </div>
                                        
                                        {{-- Upload Foto --}}
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Foto Dokumentasi</label>
                                            @if($session->photo_proof)
                                                <div class="relative group h-48 rounded-2xl overflow-hidden border border-slate-200 mb-4 shadow-sm">
                                                    <img src="{{ asset('storage/' . $session->photo_proof) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                                    <a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 text-white font-bold text-sm gap-2">
                                                        <i class="ph-bold ph-eye text-xl"></i> Lihat Foto
                                                    </a>
                                                </div>
                                            @endif
                                            @if($isOpen)
                                                <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-200 rounded-2xl cursor-pointer hover:bg-blue-50 hover:border-blue-300 transition-all group/upload bg-slate-50">
                                                    <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                                        <i class="ph-duotone ph-image text-2xl text-slate-300 group-hover/upload:text-blue-500 mb-1 transition-colors"></i>
                                                        <p class="text-xs text-slate-400 group-hover/upload:text-slate-600"><span class="font-bold">Klik upload</span> atau drag & drop</p>
                                                    </div>
                                                    <input type="file" name="photo_proof" accept="image/*" class="hidden" />
                                                </label>
                                            @endif
                                        </div>

                                        @if($isOpen)
                                            <button type="submit" class="w-full bg-blue-900 text-white hover:bg-blue-800 hover:shadow-lg font-bold py-4 rounded-2xl transition-all shadow-md flex justify-center items-center gap-2 transform active:scale-95">
                                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Jurnal
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
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col h-full min-h-[800px] overflow-hidden">
                        
                        {{-- Header List --}}
                        <div class="p-8 border-b border-slate-100 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/30">
                            <div>
                                <h3 class="font-black text-slate-800 text-2xl">Kehadiran Siswa</h3>
                                <p class="text-sm text-slate-500 font-medium mt-1">Kelola absensi siswa di kelas ini.</p>
                            </div>
                            <div class="flex items-center gap-4 bg-white p-3 rounded-2xl border border-slate-100 shadow-sm">
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-2xl shadow-inner">
                                    <i class="ph-fill ph-users-three"></i>
                                </div>
                                <div class="text-right pr-2">
                                    <span class="text-[10px] uppercase font-black text-slate-400 tracking-wider block">Hadir</span>
                                    <div class="flex items-baseline gap-1 justify-end">
                                        <p class="text-3xl font-black text-slate-800 leading-none" x-text="presentCount">0</p>
                                        <span class="text-sm font-bold text-slate-400">/ {{ $allStudents->count() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- List Siswa --}}
                        <div class="flex-1 p-6 md:p-8 bg-slate-50/30 overflow-y-auto max-h-[800px] custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($allStudents as $student)
                                    @php
                                        $att = $attendances[$student->id] ?? null;
                                        $status = $att ? $att->status : null;
                                        
                                        // Warna Card Modern
                                        $baseClass = "relative border rounded-2xl p-4 flex items-center gap-4 transition-all duration-300 group hover:shadow-md";
                                        $colorClass = 'bg-white border-slate-200';
                                        
                                        if ($status == 'present') $colorClass = 'bg-emerald-50/40 border-emerald-200 ring-1 ring-emerald-500/20';
                                        elseif ($status == 'sick') $colorClass = 'bg-blue-50/40 border-blue-200';
                                        elseif ($status == 'permission') $colorClass = 'bg-amber-50/40 border-amber-200';
                                        elseif ($status == 'alpha') $colorClass = 'bg-rose-50/40 border-rose-200';
                                    @endphp

                                    <div class="{{ $baseClass }} {{ $colorClass }}" id="student-row-{{ $student->id }}">
                                        
                                        {{-- Avatar / Status Icon --}}
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-sm shrink-0 shadow-sm transition-transform group-hover:scale-110
                                            {{ $status ? 'bg-white' : 'bg-slate-100 text-slate-500' }}">
                                            @if($status == 'present') <i class="ph-fill ph-check-circle text-emerald-500 text-2xl"></i>
                                            @elseif($status == 'sick') <span class="text-blue-600 font-black">S</span>
                                            @elseif($status == 'permission') <span class="text-amber-600 font-black">I</span>
                                            @elseif($status == 'alpha') <span class="text-rose-600 font-black">A</span>
                                            @else {{ substr($student->name, 0, 1) }}
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-slate-800 text-sm truncate group-hover:text-blue-600 transition-colors">{{ $student->name }}</p>
                                            <p class="text-xs text-slate-500 font-mono tracking-wide">{{ $student->student_id }}</p>
                                        </div>

                                        {{-- Tombol Aksi (Hanya jika Open) --}}
                                        @if($isOpen)
                                            <div class="flex items-center gap-1">
                                                {{-- Tombol Hadir --}}
                                                <button @click="setManual({{ $student->id }}, 'present')" 
                                                        class="w-10 h-10 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-90
                                                        {{ $status == 'present' ? 'bg-emerald-500 text-white shadow-emerald-200' : 'bg-white border border-slate-200 text-slate-300 hover:border-emerald-500 hover:text-emerald-500' }}"
                                                        title="Hadir">
                                                    <i class="ph-bold ph-check text-lg"></i>
                                                </button>
                                                
                                                {{-- Dropdown Kecil untuk S/I/A --}}
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.outside="open = false"
                                                            class="w-10 h-10 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-90
                                                            {{ in_array($status, ['sick', 'permission', 'alpha']) ? 'bg-slate-800 text-white' : 'bg-white border border-slate-200 text-slate-300 hover:border-slate-800 hover:text-slate-800' }}">
                                                        <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                                    </button>
                                                    
                                                    <div x-show="open" x-transition.origin.top.right class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-xl border border-slate-100 z-20 py-1 overflow-hidden ring-1 ring-black/5">
                                                        <button @click="setManual({{ $student->id }}, 'sick'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-blue-600 hover:bg-blue-50 flex items-center gap-2"><div class="w-2 h-2 bg-blue-500 rounded-full"></div> Sakit</button>
                                                        <button @click="setManual({{ $student->id }}, 'permission'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-amber-600 hover:bg-amber-50 flex items-center gap-2"><div class="w-2 h-2 bg-amber-500 rounded-full"></div> Izin</button>
                                                        <button @click="setManual({{ $student->id }}, 'alpha'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-600 hover:bg-rose-50 flex items-center gap-2"><div class="w-2 h-2 bg-rose-500 rounded-full"></div> Alpha</button>
                                                        <div class="border-t border-slate-100 my-1"></div>
                                                        <button @click="setManual({{ $student->id }}, null); open=false" class="w-full text-left px-4 py-2.5 text-xs text-slate-400 hover:bg-slate-50 font-medium">Reset Absen</button>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            {{-- Tampilan Status Read-Only (Jika Closed) --}}
                                            <div>
                                                @if($status == 'present') <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 text-[10px] font-black uppercase tracking-wider rounded-lg">Hadir</span>
                                                @elseif($status == 'sick') <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-[10px] font-black uppercase tracking-wider rounded-lg">Sakit</span>
                                                @elseif($status == 'permission') <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-[10px] font-black uppercase tracking-wider rounded-lg">Izin</span>
                                                @elseif($status == 'alpha') <span class="px-3 py-1.5 bg-rose-100 text-rose-700 text-[10px] font-black uppercase tracking-wider rounded-lg">Alpha</span>
                                                @else <span class="text-xs text-slate-300 italic"> - </span>
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
        // Fungsi Konfirmasi Tutup Kelas via SweetAlert (STYLE UPDATED)
        function confirmCloseClass() {
            Swal.fire({
                title: 'Akhiri Sesi Kelas?',
                text: "Pastikan seluruh siswa telah diabsen. Aksi ini akan mengunci jurnal dan absensi.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48', // rose-600
                cancelButtonColor: '#64748b', // slate-500
                confirmButtonText: 'Ya, Tutup Kelas',
                cancelButtonText: 'Batal',
                background: '#fff',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold shadow-lg shadow-rose-500/30',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold',
                    title: 'font-black text-slate-800',
                    htmlContainer: 'text-slate-500'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('close-session-form').submit();
                }
            })
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('teachingSession', (config) => ({
                rfidCode: '',
                sessionId: config.sessionId,
                presentCount: config.presentCount,
                loading: false,
                statusMessage: 'Siap memindai...',
                showCamera: false,
                html5QrcodeScanner: null,

                // Helper Toast SweetAlert (STYLE UPDATED)
                showToast(icon, title) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 2000,
                        timerProgressBar: true,
                        background: '#fff',
                        customClass: { popup: 'rounded-xl shadow-xl' },
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal.resumeTimer)
                        }
                    })
                    Toast.fire({ icon: icon, title: title })
                },

                async setManual(studentId, status) {
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
                        if(data.status === 'success') {
                            this.showToast('success', 'Status siswa diperbarui');
                            setTimeout(() => window.location.reload(), 500);
                        }
                    } catch (e) { 
                        this.showToast('error', 'Gagal menghubungi server.'); 
                    }
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
                            Swal.fire({
                                icon: 'success',
                                title: 'Hadir!',
                                text: data.student.name,
                                timer: 1500,
                                showConfirmButton: false,
                                backdrop: `rgba(0,0,0,0.4)`,
                                customClass: { popup: 'rounded-3xl' }
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            this.statusMessage = 'GAGAL: ' + data.message;
                            this.showToast('error', data.message);
                            document.getElementById('rfidInput').focus();
                        }
                    } catch (error) { 
                        this.statusMessage = 'Error koneksi'; 
                    }
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