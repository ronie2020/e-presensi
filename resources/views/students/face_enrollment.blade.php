<x-app-layout>
    {{-- Scripts Penunjang SweetAlert2 & Face-API.js Lokal --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/face-api.min.js') }}"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden" x-data="faceEnrollmentApp()">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[450px] bg-gradient-to-b from-[#0d52a1]/25 via-[#031d3d]/15 to-transparent opacity-40 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO HEADER --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-6 md:p-8 mb-8 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden">
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="space-y-3">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('students.index') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white border border-white/15 text-xs font-bold transition-all shadow-sm">
                                <i class="ph-bold ph-arrow-left text-sky-400"></i>
                                <span>Kembali ke Data Siswa</span>
                            </a>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                                </span>
                                AI Biometric Studio
                            </div>
                        </div>

                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight text-white">
                            Perekaman Biometrik <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">Wajah Siswa</span>
                        </h1>
                        <p class="text-slate-300 text-xs sm:text-sm max-w-2xl font-medium leading-relaxed">
                            Daftarkan wajah siswa untuk diintegrasikan dengan Sistem Absensi Kiosk dan Kamera HP Guru. Data disimpan dalam bentuk 128-vektor matematis aman.
                        </p>
                    </div>

                    {{-- STATISTIK KARTU KECIL --}}
                    <div class="flex items-center gap-2 sm:gap-3 bg-slate-900/80 border border-white/10 rounded-2xl p-3 sm:p-4 backdrop-blur-md shrink-0 flex-wrap sm:flex-nowrap">
                        <div class="text-center px-2.5 sm:px-3 border-r border-slate-700">
                            <span class="text-xl sm:text-2xl font-black text-white">{{ $totalActive }}</span>
                            <span class="block text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider">Aktif</span>
                        </div>
                        <div class="text-center px-2.5 sm:px-3 border-r border-slate-700">
                            <span class="text-xl sm:text-2xl font-black text-emerald-400">{{ $totalRegistered }}</span>
                            <span class="block text-[9px] sm:text-[10px] font-bold text-emerald-300 uppercase tracking-wider">Wajah AI</span>
                        </div>
                        <div class="text-center px-2.5 sm:px-3 border-r border-slate-700">
                            <span class="text-xl sm:text-2xl font-black text-amber-400">{{ $totalUnregistered }}</span>
                            <span class="block text-[9px] sm:text-[10px] font-bold text-amber-300 uppercase tracking-wider">Belum</span>
                        </div>
                        <div class="text-center px-2.5 sm:px-3">
                            <span class="text-xl sm:text-2xl font-black text-cyan-400">{{ $totalCandidates }}</span>
                            <span class="block text-[9px] sm:text-[10px] font-bold text-cyan-300 uppercase tracking-wider">Foto Siap</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- KONTEN UTAMA: 2 KOLOM --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                {{-- KOLOM KIRI (7 KANVAS): KAMERA LIVE & DETEKSI AI --}}
                <div class="lg:col-span-7 flex flex-col gap-6">
                    
                    {{-- BOX KAMERA --}}
                    <div class="bg-slate-900/80 border border-slate-800 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden flex flex-col">
                        
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center text-xl">
                                    <i class="ph-bold ph-camera"></i>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white">Live Kamera AI Enrolment</h3>
                                    <p class="text-xs text-slate-400">Posisikan wajah siswa di dalam bingkai</p>
                                </div>
                            </div>
                            
                            {{-- Status AI Badge --}}
                            <div id="ai-model-status" class="px-3.5 py-1.5 rounded-full text-xs font-bold border transition-all flex items-center gap-2 bg-amber-500/10 border-amber-500/30 text-amber-300">
                                <i class="ph-bold ph-circle-notch animate-spin"></i>
                                <span>Memuat Model AI...</span>
                            </div>
                        </div>

                        {{-- AREA VIDEO STREAM --}}
                        <div class="relative w-full aspect-[4/3] bg-black rounded-2xl overflow-hidden border-2 border-slate-700 shadow-inner group">
                            
                            {{-- Elemen Video & Canvas --}}
                            <video id="webcam-video" autoplay muted playsinline class="w-full h-full object-cover transition-transform duration-300" :class="facingMode === 'user' ? 'transform -scale-x-100' : ''"></video>
                            <canvas id="overlay-canvas" class="absolute inset-0 w-full h-full pointer-events-none transition-transform duration-300" :class="facingMode === 'user' ? 'transform -scale-x-100' : ''"></canvas>

                            {{-- Floating Bar: Status Mode Kamera & Tombol Switch Cepat --}}
                            <div class="absolute top-4 inset-x-4 flex items-center justify-between pointer-events-auto z-20">
                                <div class="px-3 py-1.5 rounded-full bg-slate-900/80 backdrop-blur-md border border-white/10 text-[11px] font-bold text-slate-200 flex items-center gap-1.5 shadow-lg">
                                    <i class="ph-bold" :class="facingMode === 'environment' ? 'ph-camera text-sky-400' : 'ph-user-focus text-amber-400'"></i>
                                    <span x-text="facingMode === 'environment' ? 'Kamera Belakang' : 'Kamera Depan (Selfie)'"></span>
                                </div>

                                <button type="button" @click="switchCamera()" 
                                    title="Putar Kamera (Depan / Belakang)"
                                    class="w-10 h-10 sm:w-11 sm:h-11 rounded-full bg-slate-900/85 hover:bg-sky-600 active:scale-95 text-white backdrop-blur-md border border-white/20 flex items-center justify-center text-lg sm:text-xl shadow-xl transition-all hover:scale-105 group/flip">
                                    <i class="ph-bold ph-camera-rotate group-hover/flip:rotate-180 transition-transform duration-300"></i>
                                </button>
                            </div>

                            {{-- Bingkai Panduan Oval / Lingkaran Wajah --}}
                            <div class="absolute inset-0 pointer-events-none flex items-center justify-center">
                                <div class="w-56 h-72 border-2 border-dashed border-sky-400/50 rounded-[4rem] flex flex-col items-center justify-between py-4 shadow-[0_0_30px_rgba(56,189,248,0.15)] transition-all group-hover:border-sky-400">
                                    <span class="text-[10px] font-bold text-sky-300 bg-black/60 px-2.5 py-1 rounded-full uppercase tracking-widest backdrop-blur-sm">Area Kepala</span>
                                    <span class="text-[10px] font-bold text-sky-300 bg-black/60 px-2.5 py-1 rounded-full uppercase tracking-widest backdrop-blur-sm">Area Dagu</span>
                                </div>
                            </div>

                            {{-- Banner Indikator Deteksi Realtime --}}
                            <div class="absolute bottom-4 inset-x-4 flex justify-center pointer-events-none z-20">
                                <div id="detection-banner" class="bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 text-xs font-bold text-slate-300 flex items-center gap-2 shadow-lg transition-all">
                                    <i class="ph-bold ph-scan text-sky-400 animate-pulse text-base"></i>
                                    <span>Menunggu deteksi wajah...</span>
                                </div>
                            </div>

                            {{-- Multi-Sample Countdown & Capture Progress Overlay --}}
                            <div id="countdown-overlay" class="hidden absolute inset-0 bg-sky-950/85 backdrop-blur-md z-30 flex flex-col items-center justify-center p-6 text-center">
                                <div id="countdown-container" class="flex flex-col items-center justify-center">
                                    <span id="countdown-number" class="text-8xl font-black text-white animate-pulse drop-shadow-[0_0_25px_rgba(56,189,248,0.8)]">2</span>
                                    <p class="text-xs font-bold text-sky-300 uppercase tracking-widest mt-3">Bersiap Di Depan Kamera</p>
                                </div>
                                <div id="multisample-container" class="hidden flex flex-col items-center justify-center space-y-3">
                                    <div class="relative w-20 h-20 flex items-center justify-center">
                                        <div class="absolute inset-0 rounded-full border-4 border-sky-500/20"></div>
                                        <div class="absolute inset-0 rounded-full border-4 border-sky-400 border-t-transparent animate-spin"></div>
                                        <i class="ph-bold ph-scan text-3xl text-sky-300"></i>
                                    </div>
                                    <div>
                                        <h4 id="multisample-status" class="text-base sm:text-lg font-black text-white">Mengambil Sampel 1/3...</h4>
                                        <p id="multisample-subtext" class="text-xs text-slate-300 font-medium">Harap jangan bergerak</p>
                                    </div>
                                    <div class="flex items-center gap-2.5 mt-2">
                                        <span id="sample-dot-1" class="w-3.5 h-3.5 rounded-full bg-slate-700 transition-all border border-white/10"></span>
                                        <span id="sample-dot-2" class="w-3.5 h-3.5 rounded-full bg-slate-700 transition-all border border-white/10"></span>
                                        <span id="sample-dot-3" class="w-3.5 h-3.5 rounded-full bg-slate-700 transition-all border border-white/10"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- TOMBOL KONTROL & AKSI DI BAWAH KAMERA --}}
                        <div class="mt-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                            
                            {{-- Info Target Siswa Terpilih --}}
                            <div class="flex items-center gap-3 w-full sm:w-auto">
                                <div class="w-12 h-12 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center text-slate-400 overflow-hidden shrink-0">
                                    <template x-if="selectedStudent && selectedStudent.photo">
                                        <img :src="selectedStudent.photo" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="!selectedStudent || !selectedStudent.photo">
                                        <i class="ph-bold ph-user text-2xl"></i>
                                    </template>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-sky-400 uppercase tracking-wider">Target Siswa:</p>
                                    <h4 class="text-sm font-black text-white truncate" x-text="selectedStudent ? selectedStudent.name : 'Pilih Siswa di Kolom Kanan'"></h4>
                                    <p class="text-[11px] text-slate-400" x-text="selectedStudent ? (selectedStudent.nisn || selectedStudent.student_id) + ' • ' + selectedStudent.class_name : 'Belum ada siswa terpilih'"></p>
                                </div>
                            </div>

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 w-full sm:w-auto justify-end flex-wrap">
                                {{-- Tombol Putar Kamera di Kontrol Bawah --}}
                                <button type="button" @click="switchCamera()" class="px-4 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold text-xs flex items-center gap-2 border border-slate-700 transition-all active:scale-95 shadow-sm">
                                    <i class="ph-bold ph-camera-rotate text-base text-sky-400"></i>
                                    <span x-text="facingMode === 'environment' ? 'Putar ke Depan' : 'Putar ke Belakang'"></span>
                                </button>

                                <button type="button" @click="toggleCamera()" class="px-4 py-3 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold text-xs flex items-center gap-2 border border-slate-700 transition-all">
                                    <i class="ph-bold ph-arrows-clockwise text-base"></i>
                                    <span x-text="cameraActive ? 'Restart Cam' : 'Buka Cam'"></span>
                                </button>

                                <button type="button" id="btn-capture" @click="startCaptureProcess()" :disabled="!canCapture" class="px-6 py-3 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black text-xs uppercase tracking-wider flex items-center gap-2 shadow-lg shadow-sky-500/25 transition-all active:scale-95">
                                    <i class="ph-bold ph-camera-plus text-lg"></i>
                                    <span>Rekam Wajah</span>
                                </button>
                            </div>
                        </div>

                        {{-- STATUS PROTEKSI FOTO RESMI & OPSI PROFIL SEMENTARA --}}
                        <div x-show="selectedStudent" class="mt-4 p-3.5 rounded-2xl bg-slate-950/70 border border-slate-800 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 text-xs">
                            <div class="flex items-center gap-2.5">
                                <template x-if="selectedStudent && selectedStudent.photo">
                                    <div class="flex items-center gap-2 text-emerald-400">
                                        <div class="w-7 h-7 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center shrink-0">
                                            <i class="ph-bold ph-shield-check text-base"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-emerald-300">Foto Profil Resmi Terpasang</span>
                                            <p class="text-[11px] text-slate-400">Pas foto resmi terlindungi & aman (kamera hanya merekam pola AI tanpa menimpa foto).</p>
                                        </div>
                                    </div>
                                </template>
                                <template x-if="selectedStudent && !selectedStudent.photo">
                                    <div class="flex items-center gap-2 text-amber-300">
                                        <div class="w-7 h-7 rounded-lg bg-amber-500/10 border border-amber-500/30 flex items-center justify-center shrink-0">
                                            <i class="ph-bold ph-warning-circle text-base"></i>
                                        </div>
                                        <div>
                                            <span class="font-bold text-amber-300">Belum Ada Pas Foto Resmi</span>
                                            <p class="text-[11px] text-slate-400">Default: Kamera hanya merekam biometrik AI untuk menjaga kerapian dokumen resmi sekolah.</p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <template x-if="selectedStudent && !selectedStudent.photo">
                                <label class="inline-flex items-center gap-2 cursor-pointer select-none bg-slate-900 hover:bg-slate-850 px-3 py-1.5 rounded-xl border border-slate-700/80 transition-all shrink-0">
                                    <input type="checkbox" x-model="saveAsProfilePhoto" class="rounded border-slate-600 bg-slate-950 text-sky-500 focus:ring-sky-500 h-4 w-4">
                                    <span class="text-[11px] font-bold text-slate-300">Gunakan sebagai foto profil sementara</span>
                                </label>
                            </template>
                        </div>

                        {{-- FITUR EXTRA: Ekstrak dari Foto Profil Lama --}}
                        <div x-show="selectedStudent && selectedStudent.photo" class="mt-4 pt-4 border-t border-slate-800 flex items-center justify-between">
                            <span class="text-xs text-slate-400">Siswa ini memiliki foto profil resmi di database.</span>
                            <button type="button" @click="extractFromProfilePhoto()" class="text-xs font-bold text-sky-400 hover:text-sky-300 flex items-center gap-1.5 underline underline-offset-4">
                                <i class="ph-bold ph-magic-wand"></i> Ekstrak dari Foto Profil
                            </button>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (5 KANVAS): DAFTAR SISWA & FILTER --}}
                <div class="lg:col-span-5 flex flex-col gap-4">
                    
                    <div class="bg-slate-900/80 border border-slate-800 rounded-[2rem] p-6 shadow-2xl flex flex-col h-[700px]">
                        
                        <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-800">
                            <div>
                                <h3 class="text-base font-black text-white">Daftar Antrean Siswa</h3>
                                <p class="text-xs text-slate-400">Pilih siswa untuk direkam wajahnya</p>
                            </div>
                            <div class="text-xs bg-slate-800 px-3 py-1 rounded-lg text-slate-300 font-bold border border-slate-700">
                                <span x-text="studentsList.length"></span> Siswa
                            </div>
                        </div>

                        {{-- TOMBOL EKSTRAKSI MASSAL DARI FOTO RESMI (1-KLIK) --}}
                        <div class="mb-3">
                            <button type="button" @click="startBatchExtraction()" 
                                    class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold text-xs shadow-lg shadow-emerald-950/40 border border-emerald-400/30 flex items-center justify-between transition-all active:scale-[0.98]">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-7 h-7 rounded-lg bg-white/20 flex items-center justify-center text-white shrink-0 shadow-inner">
                                        <i class="ph-bold ph-sparkle text-base"></i>
                                    </div>
                                    <div class="text-left">
                                        <span class="block text-[11px] font-black uppercase tracking-wider">Ekstrak Wajah Massal</span>
                                        <span class="block text-[10px] text-emerald-100 font-normal">Auto-scan biometrik dari pas foto resmi</span>
                                    </div>
                                </div>
                                <span class="px-2.5 py-1 rounded-full bg-white/20 text-[10px] font-black tracking-wide border border-white/20">
                                    {{ $totalCandidates }} Siap
                                </span>
                            </button>
                        </div>

                        {{-- FILTER KELAS & SEARCH --}}
                        <div class="space-y-3 mb-4">
                            <form method="GET" action="{{ route('face-recognition.index') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <select name="class_id" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:ring-sky-500 focus:border-sky-500 font-bold">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>Kelas {{ $c->name }}</option>
                                    @endforeach
                                </select>

                                <select name="status" onchange="this.form.submit()" class="w-full bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-xs text-slate-200 focus:ring-sky-500 focus:border-sky-500 font-bold">
                                    <option value="">Semua Status</option>
                                    <option value="unregistered" {{ $statusFilter == 'unregistered' ? 'selected' : '' }}>Belum Direkam</option>
                                    <option value="registered" {{ $statusFilter == 'registered' ? 'selected' : '' }}>Sudah Direkam</option>
                                </select>
                            </form>

                            <div class="relative">
                                <input type="text" x-model="searchQuery" placeholder="Cari nama siswa / NISN..." class="w-full bg-slate-950 border border-slate-700 rounded-xl pl-9 pr-4 py-2.5 text-xs text-white placeholder-slate-500 focus:ring-sky-500 focus:border-sky-500">
                                <i class="ph-bold ph-magnifying-glass absolute left-3 top-3 text-slate-500 text-sm"></i>
                            </div>
                        </div>

                        {{-- DAFTAR ITEM SISWA (SCROLLABLE) --}}
                        <div class="flex-1 overflow-y-auto space-y-2 pr-1 custom-scrollbar">
                            <template x-for="student in filteredStudents" :key="student.id">
                                <div @click="selectStudent(student)" 
                                     :class="{
                                         'border-sky-500 bg-sky-500/10 shadow-lg shadow-sky-500/10': selectedStudent && selectedStudent.id === student.id,
                                         'border-slate-800 bg-slate-950/60 hover:bg-slate-800/60': !selectedStudent || selectedStudent.id !== student.id
                                     }" 
                                     class="p-3.5 rounded-2xl border transition-all cursor-pointer flex items-center justify-between group">
                                    
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-11 h-11 rounded-xl bg-slate-800 border border-slate-700 overflow-hidden shrink-0 flex items-center justify-center text-slate-400">
                                            <template x-if="student.photo">
                                                <img :src="student.photo" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!student.photo">
                                                <span class="text-sm font-black" x-text="student.name.charAt(0)"></span>
                                            </template>
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="text-xs font-bold text-white group-hover:text-sky-300 transition-colors truncate" x-text="student.name"></h4>
                                            <p class="text-[10px] text-slate-400 font-mono mt-0.5" x-text="(student.nisn || student.student_id) + ' • ' + (student.class_name || '-')"></p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 shrink-0">
                                        <template x-if="student.has_face">
                                            <span class="px-2.5 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                                <i class="ph-fill ph-check-circle"></i> Terdaftar
                                            </span>
                                        </template>
                                        <template x-if="!student.has_face">
                                            <span class="px-2.5 py-1 rounded-lg bg-slate-800 border border-slate-700 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                                                Belum
                                            </span>
                                        </template>

                                        {{-- Tombol Hapus Biometrik jika sudah ada --}}
                                        <template x-if="student.has_face">
                                            <button type="button" @click.stop="deleteFace(student)" class="w-7 h-7 rounded-lg bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 flex items-center justify-center border border-rose-500/20 transition-all" title="Hapus Biometrik">
                                                <i class="ph-bold ph-trash text-xs"></i>
                                            </button>
                                        </template>
                                    </div>
                                </div>
                            </template>

                            <template x-if="filteredStudents.length === 0">
                                <div class="text-center py-12 text-slate-500">
                                    <i class="ph-duotone ph-user-circle text-4xl mb-2"></i>
                                    <p class="text-xs font-bold">Tidak ada data siswa ditemukan.</p>
                                </div>
                            </template>
                        </div>

                        {{-- Pagination Khusus AI Studio (Kompak, Responsif & Tidak Overflow) --}}
                        @if ($students->hasPages())
                            <div class="mt-3 pt-3 border-t border-slate-800/80 flex flex-col gap-2 shrink-0">
                                {{-- Baris 1: Info Ringkasan Siswa & Dropdown Lompat Halaman Cepat --}}
                                <div class="flex items-center justify-between text-[11px] text-slate-400">
                                    <span>
                                        Data <strong class="text-white">{{ $students->firstItem() }}-{{ $students->lastItem() }}</strong> dari <strong class="text-sky-400">{{ $students->total() }}</strong>
                                    </span>

                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[10px] text-slate-400">Hal:</span>
                                        <select onchange="window.location.href=this.value" class="bg-slate-950 border border-slate-700 rounded-lg px-2 py-0.5 text-[10px] text-sky-400 font-bold focus:ring-sky-500 focus:border-sky-500 cursor-pointer">
                                            @for ($p = 1; $p <= $students->lastPage(); $p++)
                                                <option value="{{ $students->url($p) }}" {{ $students->currentPage() == $p ? 'selected' : '' }}>
                                                    {{ $p }}
                                                </option>
                                            @endfor
                                        </select>
                                        <span class="text-[10px] text-slate-400">/ {{ $students->lastPage() }}</span>
                                    </div>
                                </div>

                                {{-- Baris 2: Tombol Navigasi Halaman yang Rapi & Pas di Kolom --}}
                                <div class="flex items-center justify-center gap-1 flex-wrap">
                                    {{-- Tombol Pertama & Sebelumnya --}}
                                    @if ($students->onFirstPage())
                                        <span class="w-7 h-7 rounded-lg bg-slate-950/60 border border-slate-800/70 text-slate-600 flex items-center justify-center text-xs cursor-not-allowed" title="Halaman Pertama">
                                            <i class="ph-bold ph-caret-double-left"></i>
                                        </span>
                                        <span class="w-7 h-7 rounded-lg bg-slate-950/60 border border-slate-800/70 text-slate-600 flex items-center justify-center text-xs cursor-not-allowed" title="Halaman Sebelumnya">
                                            <i class="ph-bold ph-caret-left"></i>
                                        </span>
                                    @else
                                        <a href="{{ $students->url(1) }}" class="w-7 h-7 rounded-lg bg-slate-800/90 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-xs transition-all shadow-sm active:scale-95" title="Halaman Pertama">
                                            <i class="ph-bold ph-caret-double-left"></i>
                                        </a>
                                        <a href="{{ $students->previousPageUrl() }}" class="w-7 h-7 rounded-lg bg-slate-800/90 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-xs transition-all shadow-sm active:scale-95" title="Halaman Sebelumnya">
                                            <i class="ph-bold ph-caret-left"></i>
                                        </a>
                                    @endif

                                    {{-- Nomor Halaman (Maksimal 3-5 nomor di sekitar halaman saat ini) --}}
                                    @php
                                        $cur = $students->currentPage();
                                        $last = $students->lastPage();
                                        $start = max(1, $cur - 1);
                                        $end = min($last, $cur + 1);

                                        if ($cur <= 2) {
                                            $end = min($last, 3);
                                        }
                                        if ($cur >= $last - 1) {
                                            $start = max(1, $last - 2);
                                        }
                                    @endphp

                                    @if ($start > 1)
                                        <a href="{{ $students->url(1) }}" class="w-7 h-7 rounded-lg bg-slate-800/90 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-[11px] font-bold transition-all">
                                            1
                                        </a>
                                        @if ($start > 2)
                                            <span class="w-3 text-center text-slate-600 text-[10px]">..</span>
                                        @endif
                                    @endif

                                    @for ($page = $start; $page <= $end; $page++)
                                        @if ($page == $cur)
                                            <span class="w-7 h-7 rounded-lg bg-gradient-to-r from-sky-500 to-blue-600 text-white font-black flex items-center justify-center text-[11px] shadow-md shadow-sky-500/25 border border-sky-400">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $students->url($page) }}" class="w-7 h-7 rounded-lg bg-slate-800/90 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-[11px] font-bold transition-all shadow-sm active:scale-95">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endfor

                                    @if ($end < $last)
                                        @if ($end < $last - 1)
                                            <span class="w-3 text-center text-slate-600 text-[10px]">..</span>
                                        @endif
                                        <a href="{{ $students->url($last) }}" class="w-7 h-7 rounded-lg bg-slate-800/90 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-[11px] font-bold transition-all">
                                            {{ $last }}
                                        </a>
                                    @endif

                                    {{-- Tombol Berikutnya & Terakhir --}}
                                    @if ($students->hasMorePages())
                                        <a href="{{ $students->nextPageUrl() }}" class="w-7 h-7 rounded-lg bg-slate-800/90 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-xs transition-all shadow-sm active:scale-95" title="Halaman Berikutnya">
                                            <i class="ph-bold ph-caret-right"></i>
                                        </a>
                                        <a href="{{ $students->url($last) }}" class="w-7 h-7 rounded-lg bg-slate-800/90 hover:bg-slate-700 border border-slate-700 text-slate-300 hover:text-white flex items-center justify-center text-xs transition-all shadow-sm active:scale-95" title="Halaman Terakhir">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </a>
                                    @else
                                        <span class="w-7 h-7 rounded-lg bg-slate-950/60 border border-slate-800/70 text-slate-600 flex items-center justify-center text-xs cursor-not-allowed" title="Halaman Berikutnya">
                                            <i class="ph-bold ph-caret-right"></i>
                                        </span>
                                        <span class="w-7 h-7 rounded-lg bg-slate-950/60 border border-slate-800/70 text-slate-600 flex items-center justify-center text-xs cursor-not-allowed" title="Halaman Terakhir">
                                            <i class="ph-bold ph-caret-double-right"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- LOGIKA ALPINE.JS & FACE-API.JS --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('faceEnrollmentApp', () => ({
                studentsList: @json($students->items()).map(s => ({
                    id: s.id,
                    student_id: s.student_id,
                    nisn: s.nisn,
                    name: s.name,
                    class_name: s.school_class ? s.school_class.name : '-',
                    photo: s.photo_path ? (s.photo_path.startsWith('http') ? s.photo_path : '/storage/' + s.photo_path) : null,
                    has_face: !!s.face_descriptor
                })),
                searchQuery: '',
                selectedStudent: null,
                saveAsProfilePhoto: false, // Flag opsi simpan foto ke profil
                facingMode: (window.innerWidth <= 768) ? 'environment' : 'user', // Default belakang di HP, depan di PC/Laptop
                cameraActive: false,
                modelsLoaded: false,
                isDetecting: false,
                currentDetection: null,
                canCapture: false,
                detectionInterval: null,

                get filteredStudents() {
                    if (!this.searchQuery) return this.studentsList;
                    const q = this.searchQuery.toLowerCase();
                    return this.studentsList.filter(s => 
                        s.name.toLowerCase().includes(q) || 
                        (s.nisn && s.nisn.toLowerCase().includes(q)) || 
                        (s.student_id && s.student_id.toLowerCase().includes(q))
                    );
                },

                async init() {
                    // Otomatis pilih siswa pertama yang belum direkam
                    const firstUnregistered = this.studentsList.find(s => !s.has_face);
                    this.selectedStudent = firstUnregistered || this.studentsList[0] || null;

                    // Muat Model Face-API dari folder /models lokal
                    await this.loadFaceModels();

                    // Buka Kamera
                    await this.startCamera();
                },

                async loadFaceModels() {
                    const statusEl = document.getElementById('ai-model-status');
                    try {
                        const MODEL_URL = '{{ asset("models") }}';
                        await faceapi.nets.tinyFaceDetector.loadFromUri(MODEL_URL);
                        await faceapi.nets.faceLandmark68Net.loadFromUri(MODEL_URL);
                        await faceapi.nets.faceRecognitionNet.loadFromUri(MODEL_URL);

                        this.modelsLoaded = true;
                        statusEl.className = 'px-3.5 py-1.5 rounded-full text-xs font-bold border flex items-center gap-2 bg-emerald-500/10 border-emerald-500/30 text-emerald-400';
                        statusEl.innerHTML = '<i class="ph-bold ph-check-circle"></i><span>Model AI Siap</span>';
                    } catch (err) {
                        console.error('Gagal memuat model:', err);
                        statusEl.className = 'px-3.5 py-1.5 rounded-full text-xs font-bold border flex items-center gap-2 bg-rose-500/10 border-rose-500/30 text-rose-400';
                        statusEl.innerHTML = '<i class="ph-bold ph-warning-circle"></i><span>Gagal Muat AI</span>';
                    }
                },

                async startCamera() {
                    const video = document.getElementById('webcam-video');
                    
                    // Matikan stream lama jika masih berjalan
                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                        video.srcObject = null;
                    }

                    try {
                        let stream;
                        try {
                            stream = await navigator.mediaDevices.getUserMedia({
                                video: { 
                                    width: { ideal: 640 }, 
                                    height: { ideal: 480 }, 
                                    facingMode: { ideal: this.facingMode } 
                                }
                            });
                        } catch (constraintErr) {
                            console.warn('Gagal dengan constraint facingMode, fallback ke video default:', constraintErr);
                            stream = await navigator.mediaDevices.getUserMedia({ video: true });
                        }

                        video.srcObject = stream;
                        this.cameraActive = true;

                        video.onloadedmetadata = () => {
                            video.play();
                            this.startDetectionLoop();
                        };
                    } catch (err) {
                        console.error('Akses kamera gagal:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Kamera Tidak Dapat Diakses',
                            text: 'Pastikan izin akses kamera telah diberikan di browser Anda.',
                            background: '#0f172a',
                            color: '#fff'
                        });
                    }
                },

                async switchCamera() {
                    const banner = document.getElementById('detection-banner');
                    if (banner) {
                        banner.className = 'bg-sky-950/90 backdrop-blur-md px-4 py-2 rounded-xl border border-sky-500/40 text-xs font-bold text-sky-300 flex items-center gap-2 shadow-lg';
                        banner.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin text-base text-sky-400"></i> Memutar kamera...';
                    }

                    // Balik facingMode
                    this.facingMode = (this.facingMode === 'environment') ? 'user' : 'environment';

                    // Restart kamera
                    await this.startCamera();
                },

                toggleCamera() {
                    const video = document.getElementById('webcam-video');
                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                        video.srcObject = null;
                        this.cameraActive = false;
                        if (this.detectionInterval) {
                            clearInterval(this.detectionInterval);
                            this.detectionInterval = null;
                        }
                    } else {
                        this.startCamera();
                    }
                },

                selectStudent(student) {
                    this.selectedStudent = student;
                    this.saveAsProfilePhoto = false; // Reset toggle saat berganti siswa
                    this.updateCaptureButtonState();
                },

                startDetectionLoop() {
                    const video = document.getElementById('webcam-video');
                    const canvas = document.getElementById('overlay-canvas');
                    const banner = document.getElementById('detection-banner');

                    if (this.detectionInterval) {
                        clearInterval(this.detectionInterval);
                        this.detectionInterval = null;
                    }
                    this.isDetecting = false;

                    this.detectionInterval = setInterval(async () => {
                        // Guard Mutex: Hindari penumpukan antrean tensor di HP
                        if (this.isDetecting || !this.modelsLoaded || !this.cameraActive || video.paused || video.ended || video.videoWidth === 0) return;

                        this.isDetecting = true;

                        try {
                            // Sesuaikan ukuran kanvas dengan video
                            const displaySize = { width: video.videoWidth || 640, height: video.videoHeight || 480 };
                            faceapi.matchDimensions(canvas, displaySize);

                            // OPTIMASI: inputSize 160 (50% lebih hemat kalkulasi dari 224)
                            const detection = await faceapi.detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 160, scoreThreshold: 0.5 }))
                                .withFaceLandmarks()
                                .withFaceDescriptor();

                            const ctx = canvas.getContext('2d');
                            ctx.clearRect(0, 0, canvas.width, canvas.height);

                            if (detection) {
                                this.currentDetection = detection;
                                this.updateCaptureButtonState();

                                // Gambar kotak deteksi halus
                                const resized = faceapi.resizeResults(detection, displaySize);
                                const box = resized.detection.box;
                                
                                ctx.strokeStyle = '#38bdf8';
                                ctx.lineWidth = 3;
                                ctx.strokeRect(box.x, box.y, box.width, box.height);

                                banner.className = 'bg-emerald-950/90 backdrop-blur-md px-4 py-2 rounded-xl border border-emerald-500/40 text-xs font-black text-emerald-300 flex items-center gap-2 shadow-lg';
                                banner.innerHTML = '<i class="ph-fill ph-check-circle text-base text-emerald-400"></i> Wajah Terdeteksi Siap Direkam!';
                            } else {
                                this.currentDetection = null;
                                this.updateCaptureButtonState();

                                banner.className = 'bg-slate-900/90 backdrop-blur-md px-4 py-2 rounded-xl border border-white/10 text-xs font-bold text-slate-400 flex items-center gap-2 shadow-lg';
                                banner.innerHTML = '<i class="ph-bold ph-scan text-sky-400 animate-pulse text-base"></i> Hadapkan wajah tegak ke kamera...';
                            }
                        } catch (err) {
                            console.warn('Face loop error:', err);
                        } finally {
                            this.isDetecting = false;
                        }
                    }, 240);
                },

                updateCaptureButtonState() {
                    this.canCapture = !!(this.selectedStudent && this.currentDetection);
                },

                async startCaptureProcess() {
                    if (!this.canCapture) return;

                    const overlay = document.getElementById('countdown-overlay');
                    const countContainer = document.getElementById('countdown-container');
                    const numberEl = document.getElementById('countdown-number');
                    const multiContainer = document.getElementById('multisample-container');
                    const multiStatus = document.getElementById('multisample-status');
                    const multiSub = document.getElementById('multisample-subtext');
                    const dots = [
                        document.getElementById('sample-dot-1'),
                        document.getElementById('sample-dot-2'),
                        document.getElementById('sample-dot-3')
                    ];
                    const video = document.getElementById('webcam-video');

                    // 1. Tampilkan Countdown Awal
                    overlay.classList.remove('hidden');
                    countContainer.classList.remove('hidden');
                    multiContainer.classList.add('hidden');

                    for (let i = 2; i >= 1; i--) {
                        numberEl.textContent = i;
                        await new Promise(r => setTimeout(r, 550));
                    }

                    // 2. Alihkan ke Indikator Multi-Sample (3 Sampel AI)
                    countContainer.classList.add('hidden');
                    multiContainer.classList.remove('hidden');

                    const totalSamples = 3;
                    const collectedDescriptors = [];
                    let bestImageBase64 = null;

                    dots.forEach(d => { if (d) d.className = 'w-3.5 h-3.5 rounded-full bg-slate-700 transition-all border border-white/10'; });

                    for (let sample = 1; sample <= totalSamples; sample++) {
                        if (multiStatus) multiStatus.textContent = `Mengambil Sampel ${sample}/${totalSamples}...`;
                        if (multiSub) {
                            multiSub.textContent = sample === 1 
                                ? 'Menganalisis koordinat kontur wajah...' 
                                : (sample === 2 ? 'Menstabilkan sudut dan pencahayaan...' : 'Menghitung akurasi 128-vektor biometrik...');
                        }
                        if (dots[sample - 1]) dots[sample - 1].className = 'w-3.5 h-3.5 rounded-full bg-sky-400 animate-pulse border border-sky-300';

                        try {
                            // Deteksi langsung dari frame webcam saat ini
                            const detection = await faceapi.detectSingleFace(
                                video, 
                                new faceapi.TinyFaceDetectorOptions({ inputSize: 224, scoreThreshold: 0.45 })
                            ).withFaceLandmarks().withFaceDescriptor();

                            if (detection && detection.descriptor) {
                                collectedDescriptors.push(detection.descriptor);
                                if (dots[sample - 1]) dots[sample - 1].className = 'w-3.5 h-3.5 rounded-full bg-emerald-400 shadow-lg shadow-emerald-500/50 border border-emerald-300';

                                // Simpan frame foto saat sampel ke-2 (posisi paling stabil)
                                if (!bestImageBase64 || sample === 2) {
                                    const snapCanvas = document.createElement('canvas');
                                    snapCanvas.width = video.videoWidth || 640;
                                    snapCanvas.height = video.videoHeight || 480;
                                    const sCtx = snapCanvas.getContext('2d');
                                    if (this.facingMode === 'user') {
                                        sCtx.translate(snapCanvas.width, 0);
                                        sCtx.scale(-1, 1);
                                    }
                                    sCtx.drawImage(video, 0, 0, snapCanvas.width, snapCanvas.height);
                                    bestImageBase64 = snapCanvas.toDataURL('image/jpeg', 0.85);
                                }
                            } else {
                                if (dots[sample - 1]) dots[sample - 1].className = 'w-3.5 h-3.5 rounded-full bg-amber-400 border border-amber-300';
                            }
                        } catch (err) {
                            console.warn(`Error on capture sample ${sample}:`, err);
                        }

                        // Jeda singkat antar sampel agar variasi mikro-ekspresi & sensor noise cancelation optimal
                        if (sample < totalSamples) {
                            await new Promise(r => setTimeout(r, 220));
                        }
                    }

                    overlay.classList.add('hidden');

                    // 3. Kalkulasi Vektor Rata-Rata (Mathematical Mean of 128-D Coordinate Vector)
                    let finalDescriptor = null;
                    if (collectedDescriptors.length > 0) {
                        const count = collectedDescriptors.length;
                        const avgVector = new Float32Array(128);
                        for (let i = 0; i < 128; i++) {
                            let sum = 0;
                            for (let k = 0; k < count; k++) {
                                sum += collectedDescriptors[k][i];
                            }
                            avgVector[i] = sum / count;
                        }
                        finalDescriptor = avgVector;
                    } else if (this.currentDetection) {
                        // Fallback ke deteksi terakhir loop realtime jika proses multi-sampel gagal
                        finalDescriptor = this.currentDetection.descriptor;
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Wajah Tidak Tertangkap',
                            text: 'AI tidak dapat menangkap wajah dengan jelas saat pengambilan sampel. Pastikan pencahayaan cukup dan hadapkan wajah tegak ke kamera.',
                            background: '#0f172a',
                            color: '#fff'
                        });
                        return;
                    }

                    // Fallback gambar jika belum terbentuk
                    if (!bestImageBase64) {
                        const snapCanvas = document.createElement('canvas');
                        snapCanvas.width = video.videoWidth || 640;
                        snapCanvas.height = video.videoHeight || 480;
                        const sCtx = snapCanvas.getContext('2d');
                        if (this.facingMode === 'user') {
                            sCtx.translate(snapCanvas.width, 0);
                            sCtx.scale(-1, 1);
                        }
                        sCtx.drawImage(video, 0, 0, snapCanvas.width, snapCanvas.height);
                        bestImageBase64 = snapCanvas.toDataURL('image/jpeg', 0.85);
                    }

                    // Kirim Descriptor Vektor Rata-Rata ke Server
                    await this.saveDescriptorToServer(finalDescriptor, bestImageBase64);
                },

                async extractFromProfilePhoto() {
                    if (!this.selectedStudent || !this.selectedStudent.photo) return;

                    Swal.fire({
                        title: 'Mengekstrak Wajah...',
                        text: 'Memproses foto profil siswa melalui model AI...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                        background: '#0f172a',
                        color: '#fff'
                    });

                    try {
                        const img = new Image();
                        img.crossOrigin = 'anonymous';
                        img.src = this.selectedStudent.photo;
                        await new Promise((resolve, reject) => {
                            img.onload = resolve;
                            img.onerror = () => reject('Gagal memuat foto profil');
                        });

                        const detection = await faceapi.detectSingleFace(img, new faceapi.TinyFaceDetectorOptions({ inputSize: 320 }))
                            .withFaceLandmarks()
                            .withFaceDescriptor();

                        if (!detection) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Wajah Tidak Terdeteksi',
                                text: 'Model AI tidak dapat mendeteksi wajah dengan jelas pada foto profil ini. Silakan gunakan webcam langsung.',
                                background: '#0f172a',
                                color: '#fff'
                            });
                            return;
                        }

                        // Simpan descriptor dari foto
                        await this.saveDescriptorToServer(detection.descriptor, null);

                    } catch (e) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Ekstraksi Gagal',
                            text: e.toString(),
                            background: '#0f172a',
                            color: '#fff'
                        });
                    }
                },

                async saveDescriptorToServer(descriptorArray, imageBase64) {
                    Swal.fire({
                        title: 'Menyimpan Biometrik...',
                        text: 'Menyimpan 128-vektor wajah ke database...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                        background: '#0f172a',
                        color: '#fff'
                    });

                    try {
                        const payload = {
                            student_id: this.selectedStudent.id,
                            descriptor: Array.from(descriptorArray),
                            image: imageBase64,
                            set_as_profile: this.saveAsProfilePhoto
                        };

                        const res = await fetch('{{ route("face-recognition.store") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await res.json();

                        if (res.ok) {
                            // Tandai siswa ini sebagai sudah memiliki wajah
                            this.selectedStudent.has_face = true;
                            
                            // Sinkronkan ke daftar siswa
                            const targetInList = this.studentsList.find(s => s.id === this.selectedStudent.id);
                            if (targetInList) {
                                targetInList.has_face = true;
                            }

                            // Perbarui foto hanya jika operator mencentang opsi simpan profil
                            if (this.saveAsProfilePhoto && data.student && data.student.photo_path) {
                                this.selectedStudent.photo = data.student.photo_path;
                                if (targetInList) {
                                    targetInList.photo = data.student.photo_path;
                                }
                            }
                            this.saveAsProfilePhoto = false;

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                timer: 2000,
                                showConfirmButton: false,
                                background: '#0f172a',
                                color: '#fff'
                            });

                            // AUTO-ADVANCE: Alihkan ke siswa berikutnya yang belum terdaftar!
                            const nextStudent = this.studentsList.find(s => !s.has_face);
                            if (nextStudent) {
                                this.selectedStudent = nextStudent;
                                this.saveAsProfilePhoto = false;
                            }

                        } else {
                            throw new Error(data.message || 'Gagal menyimpan.');
                        }
                    } catch (err) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal Menyimpan',
                            text: err.message,
                            background: '#0f172a',
                            color: '#fff'
                        });
                    }
                },

                async startBatchExtraction() {
                    if (!this.modelsLoaded) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Model AI Belum Siap',
                            text: 'Mohon tunggu beberapa detik hingga file model AI selesai dimuat.',
                            background: '#0f172a',
                            color: '#fff'
                        });
                        return;
                    }

                    const currentClassId = '{{ $selectedClassId }}';
                    const totalCandidateCount = {{ $totalCandidates }};

                    const { value: scope } = await Swal.fire({
                        title: 'Ekstraksi Biometrik Massal',
                        html: `
                            <p class="text-xs text-slate-300 mb-4 text-left leading-relaxed">
                                AI akan membaca pas foto resmi siswa secara otomatis, mendeteksi koordinat wajah, dan mendaftarkan 128-vektor biometrik untuk absensi kiosk.
                            </p>
                            <div class="text-left space-y-2 text-xs font-bold text-slate-200">
                                <label class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-800/80 border border-slate-700 cursor-pointer hover:border-emerald-500 transition-all">
                                    <input type="radio" name="batch_scope" value="class" checked class="text-emerald-500 focus:ring-emerald-400 h-4 w-4">
                                    <div>
                                        <span class="block">${currentClassId ? 'Hanya Kelas Terpilih Saat Ini' : 'Sesuai Filter Kelas Aktif'}</span>
                                        <span class="text-[10px] font-normal text-slate-400">Memproses siswa di kelas ini yang memiliki pas foto resmi.</span>
                                    </div>
                                </label>
                                <label class="flex items-center gap-2.5 p-3 rounded-xl bg-slate-800/80 border border-slate-700 cursor-pointer hover:border-emerald-500 transition-all">
                                    <input type="radio" name="batch_scope" value="all" class="text-emerald-500 focus:ring-emerald-400 h-4 w-4">
                                    <div>
                                        <span class="block">Seluruh Siswa Sekolah (${totalCandidateCount} Siswa Siap)</span>
                                        <span class="text-[10px] font-normal text-slate-400">Memindai semua siswa dari seluruh kelas sekaligus.</span>
                                    </div>
                                </label>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: '<i class="ph-bold ph-play mr-1"></i> Mulai Ekstrak AI',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: '#10b981',
                        cancelButtonColor: '#475569',
                        background: '#0f172a',
                        color: '#fff',
                        preConfirm: () => {
                            const checked = document.querySelector('input[name="batch_scope"]:checked');
                            return checked ? checked.value : 'class';
                        }
                    });

                    if (!scope) return;

                    let url = '{{ route("face-recognition.batch-candidates") }}';
                    if (scope === 'class' && currentClassId) {
                        url += `?class_id=${currentClassId}`;
                    }

                    Swal.fire({
                        title: 'Menyiapkan Data...',
                        text: 'Mengambil daftar antrean siswa dengan pas foto...',
                        allowOutsideClick: false,
                        didOpen: () => Swal.showLoading(),
                        background: '#0f172a',
                        color: '#fff'
                    });

                    let candidates = [];
                    try {
                        const res = await fetch(url);
                        const data = await res.json();
                        candidates = data.students || [];
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengambil data: ' + e.message, background: '#0f172a', color: '#fff' });
                        return;
                    }

                    if (candidates.length === 0) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Tidak Ada Antrean',
                            text: 'Semua siswa yang memiliki pas foto sudah terdaftar biometrik wajahnya!',
                            background: '#0f172a',
                            color: '#fff'
                        });
                        return;
                    }

                    let isCancelled = false;
                    let successCount = 0;
                    let failedCount = 0;
                    let failedList = [];

                    Swal.fire({
                        title: 'Ekstraksi AI Sedang Berjalan...',
                        html: `
                            <div class="space-y-4 my-2 text-left">
                                <div class="w-full bg-slate-800 rounded-full h-3 overflow-hidden border border-slate-700">
                                    <div id="batch-progress-bar" class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                <div class="flex justify-between items-center text-xs">
                                    <span id="batch-progress-text" class="text-slate-300 font-bold">0 / ${candidates.length}</span>
                                    <span id="batch-progress-percent" class="text-emerald-400 font-black font-mono">0%</span>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-900 border border-slate-800 flex items-center gap-3">
                                    <img id="batch-current-img" src="" class="w-11 h-11 rounded-lg object-cover bg-slate-800 border border-slate-700 shrink-0">
                                    <div class="min-w-0 flex-1">
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wider">Sedang Memproses:</p>
                                        <h4 id="batch-current-name" class="text-xs font-bold text-white truncate">-</h4>
                                    </div>
                                </div>
                                <div class="flex justify-between text-[11px] font-mono">
                                    <span class="text-emerald-400">✓ Berhasil: <strong id="batch-stat-success">0</strong></span>
                                    <span class="text-rose-400">✗ Gagal/Buram: <strong id="batch-stat-fail">0</strong></span>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        cancelButtonText: 'Hentikan Proses',
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        background: '#0f172a',
                        color: '#fff',
                        didOpen: async () => {
                            const pBar = document.getElementById('batch-progress-bar');
                            const pText = document.getElementById('batch-progress-text');
                            const pPercent = document.getElementById('batch-progress-percent');
                            const cImg = document.getElementById('batch-current-img');
                            const cName = document.getElementById('batch-current-name');
                            const sSuccess = document.getElementById('batch-stat-success');
                            const sFail = document.getElementById('batch-stat-fail');

                            for (let i = 0; i < candidates.length; i++) {
                                if (isCancelled) break;

                                const student = candidates[i];
                                const currentIdx = i + 1;
                                const percent = Math.round((currentIdx / candidates.length) * 100);

                                if (pBar) pBar.style.width = `${percent}%`;
                                if (pText) pText.textContent = `${currentIdx} / ${candidates.length}`;
                                if (pPercent) pPercent.textContent = `${percent}%`;
                                if (cName) cName.textContent = `${student.name} (${student.class_name})`;
                                if (cImg) cImg.src = student.photo_url;

                                try {
                                    // 1. Muat Pas Foto Resmi Siswa
                                    const img = new Image();
                                    img.crossOrigin = 'anonymous';
                                    img.src = student.photo_url;
                                    await new Promise((resolve, reject) => {
                                        img.onload = resolve;
                                        img.onerror = () => reject(new Error('Gagal memuat foto'));
                                    });

                                    // 2. Deteksi Titik Wajah via AI
                                    const detection = await faceapi.detectSingleFace(
                                        img, 
                                        new faceapi.TinyFaceDetectorOptions({ inputSize: 320, scoreThreshold: 0.35 })
                                    ).withFaceLandmarks().withFaceDescriptor();

                                    if (!detection) {
                                        failedCount++;
                                        failedList.push(`${student.name} (Wajah tidak terdeteksi)`);
                                        if (sFail) sFail.textContent = failedCount;
                                        continue;
                                    }

                                    // 3. Simpan ke Server (set_as_profile: false agar pas foto resmi tetap terjaga)
                                    const payload = {
                                        student_id: student.id,
                                        descriptor: Array.from(detection.descriptor),
                                        image: null,
                                        set_as_profile: false
                                    };

                                    const storeRes = await fetch('{{ route("face-recognition.store") }}', {
                                        method: 'POST',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            'Accept': 'application/json'
                                        },
                                        body: JSON.stringify(payload)
                                    });

                                    if (storeRes.ok) {
                                        successCount++;
                                        if (sSuccess) sSuccess.textContent = successCount;

                                        // Tandai di daftar siswa lokal jika ada
                                        const local = this.studentsList.find(s => s.id === student.id);
                                        if (local) local.has_face = true;
                                    } else {
                                        failedCount++;
                                        failedList.push(`${student.name} (Gagal simpan server)`);
                                        if (sFail) sFail.textContent = failedCount;
                                    }

                                } catch (err) {
                                    failedCount++;
                                    failedList.push(`${student.name} (${err.message || 'Error'})`);
                                    if (sFail) sFail.textContent = failedCount;
                                }

                                // Jeda 30ms agar rendering UI tetap mulus
                                await new Promise(r => setTimeout(r, 30));
                            }

                            // Tampilkan Laporan Hasil
                            Swal.fire({
                                icon: successCount > 0 ? 'success' : 'warning',
                                title: 'Ekstraksi Massal Selesai!',
                                html: `
                                    <div class="space-y-3 text-left text-xs my-2">
                                        <div class="p-3 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 flex items-center justify-between">
                                            <span>Berhasil Didaftarkan Biometrik:</span>
                                            <strong class="text-sm font-black">${successCount} Siswa</strong>
                                        </div>
                                        ${failedCount > 0 ? `
                                            <div class="p-3 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300">
                                                <span class="font-bold block mb-1">Dilewati / Gagal Deteksi (${failedCount} Siswa):</span>
                                                <div class="max-h-28 overflow-y-auto custom-scrollbar text-[11px] space-y-1">
                                                    ${failedList.map(item => `<div>• ${item}</div>`).join('')}
                                                </div>
                                            </div>
                                        ` : ''}
                                    </div>
                                `,
                                confirmButtonText: 'Segarkan Halaman',
                                confirmButtonColor: '#0284c7',
                                background: '#0f172a',
                                color: '#fff'
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.cancel) {
                            isCancelled = true;
                        }
                    });
                },

                async deleteFace(student) {
                    const result = await Swal.fire({
                        title: 'Hapus Biometrik?',
                        text: `Yakin ingin menghapus data biometrik wajah siswa ${student.name}?`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#475569',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#0f172a',
                        color: '#fff'
                    });

                    if (result.isConfirmed) {
                        try {
                            const res = await fetch(`/face-recognition/${student.id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            const data = await res.json();
                            if (res.ok) {
                                student.has_face = false;
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Terhapus',
                                    text: data.message,
                                    timer: 1500,
                                    showConfirmButton: false,
                                    background: '#0f172a',
                                    color: '#fff'
                                });
                            }
                        } catch (err) {
                            Swal.fire({ icon: 'error', title: 'Gagal', text: err.message });
                        }
                    }
                }
            }));
        });
    </script>
</x-app-layout>
