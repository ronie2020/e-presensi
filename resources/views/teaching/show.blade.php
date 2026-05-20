<x-app-layout>
    @push('styles')
    <style>
        /* FIX KAMERA RESPONSIVE (ANTI GEPENG & ANTI LOMPAT) */
        #reader { 
            width: 100% !important; 
            height: 300px !important; 
            border: none !important; 
            border-radius: 1.5rem !important; 
            overflow: hidden; 
            position: relative; 
            background: #0f172a; 
        }
        #reader__scan_region { 
            width: 100% !important; 
            height: 100% !important; 
            background: transparent !important; 
        }
        #reader video, #reader canvas { 
            width: 100% !important; 
            height: 100% !important; 
            object-fit: cover !important; 
            display: block !important; 
            border-radius: 1.5rem !important; 
            position: absolute !important; 
            top: 0 !important; 
            left: 0 !important; 
        }
        #reader__dashboard_section_csr span, #reader__dashboard_section_swaplink { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        
        /* Hide scrollbar for filter pills but keep functionality */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    {{-- TAMBAHAN: state filterTab ditambahkan ke komponen Alpine induk --}}
    <div class="py-6 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20" 
         x-data="teachingSession({ sessionId: {{ $session->id }}, stats: {{ json_encode($stats) }} })">
         
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- 1. HEADER NAVIGASI --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 sm:mb-6">
                <a href="{{ route('teaching.index') }}" class="group inline-flex items-center gap-2 text-sm text-elevate-dark hover:text-elevate-primary transition font-bold bg-white/60 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/60 shadow-sm w-fit">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i>
                    Kembali ke Jadwal
                </a>

                @if(session('error'))
                    <div class="bg-[#FDE7E9] text-[#D13438] px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2 border border-[#F4C3C9] animate-pulse shadow-sm">
                        <i class="ph-fill ph-warning-circle text-lg"></i> {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="bg-[#DFF6DD] text-[#107C10] px-5 py-3 rounded-2xl text-xs sm:text-sm font-bold flex items-center gap-2 border border-[#B7DFB9] shadow-sm">
                        <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
                    </div>
                @endif
            </div>

            {{-- 2. HEADER SESI KELAS ELEVATE --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-6 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 mb-8 overflow-hidden group border border-white/60">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div class="space-y-3 w-full">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="bg-elevate-dark shadow-sm text-white text-[10px] font-black px-4 py-2 rounded-xl uppercase tracking-widest border border-transparent">
                                {{ $session->schoolClass->name ?? 'Kelas' }}
                            </span>
                            <span class="bg-white/60 backdrop-blur-md border border-white/60 text-elevate-dark text-xs font-bold px-4 py-2 rounded-xl flex items-center gap-1.5 shadow-sm">
                                <i class="ph-bold ph-clock"></i> {{ $session->started_at->format('H:i') }}
                            </span>

                            @if(!$isOpen)
                                <span class="bg-slate-100/90 backdrop-blur text-slate-500 text-xs font-bold px-4 py-2 rounded-xl uppercase border border-slate-200 flex items-center gap-1.5 shadow-sm">
                                    <i class="ph-fill ph-lock-key"></i> Selesai
                                </span>
                            @else
                                <span class="bg-[#107C10]/90 backdrop-blur text-white text-xs font-bold px-4 py-2 rounded-xl uppercase border border-[#B7DFB9] flex items-center gap-1.5 shadow-sm">
                                    <span class="w-2 h-2 bg-white rounded-full animate-pulse"></span> Live Session
                                </span>
                            @endif
                        </div>
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-tight text-elevate-dark break-words">
                            {{ $session->subject->name ?? 'Mata Pelajaran' }}
                        </h1>
                    </div>
                    
                    @if($isOpen)
                        <form id="close-session-form" action="{{ route('teaching.close', $session->id) }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="button" onclick="confirmCloseClass()" class="w-full md:w-auto group/btn relative overflow-hidden bg-white hover:bg-[#FDE7E9] text-[#D13438] pl-4 pr-6 py-3 rounded-2xl font-bold shadow-lg transition-all active:scale-95 flex items-center justify-center md:justify-start gap-3 border border-white/60">
                                <div class="bg-[#FDE7E9] p-2.5 rounded-xl group-hover/btn:bg-[#F4C3C9] transition-colors border border-[#F4C3C9]">
                                    <i class="ph-bold ph-power text-xl"></i>
                                </div>
                                <div class="text-left">
                                    <div class="text-[10px] uppercase opacity-80 font-black tracking-widest text-[#D13438]">Selesai</div>
                                    <div class="text-sm font-black">Tutup Kelas</div>
                                </div>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('teaching.edit', $session->id) }}" class="w-full md:w-auto group/btn relative overflow-hidden bg-elevate-dark hover:bg-elevate-primary text-white pl-4 pr-6 py-3 rounded-2xl font-bold shadow-lg shadow-elevate-dark/30 transition-all active:scale-95 flex items-center justify-center md:justify-start gap-3 border border-transparent">
                            <div class="bg-white/20 p-2.5 rounded-xl group-hover/btn:bg-white/30 transition-colors">
                                <i class="ph-bold ph-pencil-simple text-xl"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-[10px] uppercase opacity-90 font-black tracking-widest text-white">Ada Kesalahan?</div>
                                <div class="text-sm font-black">Edit Data</div>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 xl:gap-8">
                
                {{-- KOLOM KIRI (SCANNER & JURNAL) --}}
                <div class="xl:col-span-4 space-y-6 h-fit xl:sticky xl:top-6 order-1">
                    
                    {{-- 3. BOX SCANNER --}}
                    @if($isOpen)
                        <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 p-6 sm:p-8 relative overflow-hidden group">
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-5">
                                    <h3 class="font-black text-elevate-dark flex items-center gap-3 text-lg">
                                        <div class="w-10 h-10 rounded-xl bg-elevate-peach/20 text-elevate-peach-dark flex items-center justify-center border border-elevate-peach/30"><i class="ph-bold ph-scan text-xl"></i></div>
                                        Scanner
                                    </h3>
                                    <button @click="toggleScanMode()" 
                                            class="text-[10px] font-bold px-3 py-1.5 rounded-xl border transition-all flex items-center gap-2"
                                            :class="isScanMode ? 'bg-elevate-primary text-white border-elevate-primary shadow-sm' : 'bg-elevate-soft text-slate-500 border-slate-200'">
                                        <span class="w-2 h-2 rounded-full" :class="isScanMode ? 'bg-white animate-pulse' : 'bg-slate-400'"></span>
                                        <span x-text="isScanMode ? 'AUTO FOCUS' : 'MANUAL'"></span>
                                    </button>
                                </div>

                                <div class="mb-5 relative group/input">
                                    <input type="text" id="rfidInput" x-model="rfidCode" @keydown.enter.prevent="submitScan()"
                                        @blur="keepFocus($event)" :disabled="!isScanMode && !showCamera"
                                        class="w-full bg-elevate-soft border border-slate-200 focus:bg-white focus:border-elevate-accent text-elevate-dark rounded-2xl text-center font-mono text-xl tracking-[0.2em] py-5 transition-all focus:ring-elevate-accent/30 uppercase placeholder:text-slate-400 shadow-inner disabled:opacity-50 font-black"
                                        placeholder="TAP KARTU / NIS..." autocomplete="off">
                                </div>

                                <button @click="toggleCamera()" type="button" class="w-full py-4 bg-white hover:bg-elevate-soft text-elevate-dark font-bold rounded-2xl border-2 border-slate-100 transition-colors flex items-center justify-center gap-2 text-sm shadow-sm mb-4 active:scale-95">
                                    <i class="ph-bold ph-camera text-xl"></i>
                                    <span x-text="showCamera ? 'Tutup Kamera' : 'Buka Kamera HP'"></span>
                                </button>

                                <div x-show="showCamera" x-transition class="mt-4 bg-slate-900 rounded-[1.5rem] overflow-hidden border border-slate-200 relative shadow-inner">
                                    <div id="reader" class="w-full bg-slate-900"></div>
                                </div>

                                <p class="mt-4 text-xs font-mono font-bold text-slate-500 text-center bg-elevate-soft p-3 rounded-xl border border-slate-100" x-text="statusMessage">Menunggu input...</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-white border border-slate-100 rounded-[2rem] p-8 text-center shadow-xl shadow-slate-200/40">
                            <div class="w-16 h-16 bg-slate-100 text-slate-400 rounded-full flex items-center justify-center mx-auto mb-3"><i class="ph-bold ph-lock-key text-2xl"></i></div>
                            <h3 class="font-black text-elevate-dark text-lg mb-1">Absensi Terkunci</h3>
                            <p class="text-xs font-medium text-slate-500">Sesi kelas telah berakhir. Gunakan tombol Edit di atas jika ada kesalahan.</p>
                        </div>
                    @endif

                    {{-- 4. FORM JURNAL MENGAJAR --}}
                    <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden" x-data="{ photoPreview: null }">
                         <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-elevate-gradient-card flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white text-elevate-primary flex items-center justify-center text-xl shadow-sm border border-slate-100">
                                <i class="ph-bold ph-notebook"></i>
                            </div>
                            <h3 class="font-black text-elevate-dark text-lg">Jurnal Mengajar</h3>
                        </div>
                        <div class="p-6 sm:p-8">
                            <fieldset {{ !$isOpen ? 'disabled' : '' }}>
                                {{-- TAMBAHAN: Event @submit untuk menghapus localStorage --}}
                                <form action="{{ route('teaching.update', $session->id) }}" method="POST" enctype="multipart/form-data" @submit="clearJournalDraft()">
                                    @csrf @method('PUT')
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Topik / Materi <span class="text-[#D13438]">*</span></label>
                                            {{-- TAMBAHAN: Atribut x-model untuk Auto-Save --}}
                                            <input type="text" name="topic" x-model="journalTopic"
                                                class="journal-input w-full rounded-2xl border-slate-200 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 font-bold text-elevate-dark py-4 px-5 text-sm bg-elevate-soft transition-all" 
                                                placeholder="Contoh: Aljabar Linear" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Catatan</label>
                                            {{-- TAMBAHAN: Atribut x-model untuk Auto-Save --}}
                                            <textarea name="activities" rows="3" x-model="journalActivities"
                                                class="journal-input w-full rounded-2xl border-slate-200 focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 text-sm text-elevate-dark font-medium py-4 px-5 bg-elevate-soft transition-all" 
                                                placeholder="Deskripsi kegiatan..."></textarea>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Foto Dokumentasi</label>
                                            @if($session->photo_proof)
                                                <div class="relative group h-40 rounded-2xl overflow-hidden border border-slate-200 mb-4 shadow-sm" x-show="!photoPreview">
                                                    <img src="{{ asset('storage/' . $session->photo_proof) }}" class="w-full h-full object-cover">
                                                    <a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="absolute inset-0 bg-elevate-dark/60 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 text-white font-bold text-sm gap-2">
                                                        <i class="ph-bold ph-eye text-xl"></i> Lihat Foto
                                                    </a>
                                                </div>
                                            @endif

                                            <div class="relative h-40 rounded-2xl overflow-hidden border border-elevate-accent/50 mb-4 shadow-sm bg-elevate-primary/10" x-show="photoPreview" x-cloak>
                                                <img :src="photoPreview" class="w-full h-full object-cover">
                                                <div class="absolute bottom-0 left-0 right-0 bg-elevate-primary/90 text-white text-[10px] font-bold py-2 text-center backdrop-blur-sm">Foto Baru</div>
                                            </div>

                                            @if($isOpen)
                                                <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-300 rounded-2xl cursor-pointer hover:bg-elevate-peach-light/20 hover:border-elevate-peach transition-all group/upload bg-elevate-soft/50">
                                                    <div class="flex flex-col items-center justify-center pt-2">
                                                        <i class="ph-duotone ph-image text-3xl text-slate-400 group-hover/upload:text-elevate-peach-dark mb-2 transition-colors"></i>
                                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 group-hover/upload:text-elevate-peach-dark transition-colors">Upload Foto Baru</p>
                                                    </div>
                                                    <input type="file" name="photo_proof" accept="image/*" class="hidden" @change="photoPreview = URL.createObjectURL($event.target.files[0])" />
                                                </label>
                                            @endif
                                        </div>
                                        
                                        @if($isOpen)
                                            <button type="submit" class="w-full bg-elevate-dark text-white hover:bg-elevate-primary font-bold py-4 rounded-2xl shadow-lg shadow-elevate-dark/30 transition-all active:scale-95 flex items-center justify-center gap-2 border border-transparent">
                                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Jurnal
                                            </button>
                                        @endif
                                    </div>
                                </form>
                            </fieldset>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (DAFTAR SISWA) --}}
                <div class="xl:col-span-8 order-2" x-data="{ searchQuery: '' }">
                    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 flex flex-col h-full min-h-[600px] overflow-hidden">
                        
                        {{-- Header List & Search --}}
                        <div class="p-6 md:p-8 border-b border-slate-100 flex flex-col gap-6 bg-elevate-gradient-card">
                            <div>
                                <h3 class="font-black text-elevate-dark text-2xl mb-1">Kehadiran Siswa</h3>
                                <p class="text-sm text-elevate-dark/70 font-semibold">Kelola absensi siswa secara manual atau melalui scan.</p>
                            </div>
                            
                            {{-- Statistik Ringkas --}}
                            <div class="grid grid-cols-4 gap-3 md:gap-4">
                                <div class="px-2 py-4 bg-[#DFF6DD] text-[#107C10] rounded-2xl text-center border border-[#B7DFB9] shadow-sm">
                                    <div class="text-[10px] font-bold uppercase tracking-widest opacity-80 mb-1">Hadir</div>
                                    <div class="text-2xl md:text-3xl font-black leading-none" x-text="stats.present">0</div>
                                </div>
                                <div class="px-2 py-4 bg-elevate-soft text-elevate-primary rounded-2xl text-center border border-slate-200 shadow-sm">
                                    <div class="text-[10px] font-bold uppercase tracking-widest opacity-80 mb-1">Sakit</div>
                                    <div class="text-2xl md:text-3xl font-black leading-none" x-text="stats.sick">0</div>
                                </div>
                                <div class="px-2 py-4 bg-[#FFEFD6] text-[#D83B01] rounded-2xl text-center border border-[#FFD8A8] shadow-sm">
                                    <div class="text-[10px] font-bold uppercase tracking-widest opacity-80 mb-1">Izin</div>
                                    <div class="text-2xl md:text-3xl font-black leading-none" x-text="stats.permission">0</div>
                                </div>
                                <div class="px-2 py-4 bg-[#FDE7E9] text-[#D13438] rounded-2xl text-center border border-[#F4C3C9] shadow-sm">
                                    <div class="text-[10px] font-bold uppercase tracking-widest opacity-80 mb-1">Alpha</div>
                                    <div class="text-2xl md:text-3xl font-black leading-none" x-text="stats.alpha">0</div>
                                </div>
                            </div>

                            <div class="flex flex-col gap-3">
                                {{-- Search Bar --}}
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                        <i class="ph-bold ph-magnifying-glass text-slate-400 group-focus-within:text-elevate-primary transition-colors text-lg"></i>
                                    </div>
                                    <input type="text" x-model="searchQuery" class="journal-input block w-full pl-14 pr-5 py-4 border-slate-200 rounded-2xl bg-white focus:bg-white focus:border-elevate-accent focus:ring-elevate-accent/30 placeholder-slate-400 text-sm font-bold shadow-sm transition-colors text-elevate-dark" placeholder="Cari nama atau NIS siswa...">
                                </div>

                                {{-- TAMBAHAN: Filter Pills Status & Tombol Bulk Action --}}
                                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
                                    {{-- PERBAIKAN: Menambahkan flex-1 min-w-0 agar kontainer scroll aman --}}
                                    <div class="flex items-center gap-2 overflow-x-auto no-scrollbar py-1 w-full flex-1 min-w-0">
                                        {{-- PERBAIKAN: Menambahkan class 'shrink-0' pada semua tombol --}}
                                        <button @click="filterTab = 'all'" :class="filterTab === 'all' ? 'bg-elevate-dark text-white border-transparent shadow-md' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50'" class="shrink-0 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border">
                                            Semua Siswa
                                        </button>
                                        <button @click="filterTab = 'unmarked'" :class="filterTab === 'unmarked' ? 'bg-slate-500 text-white border-transparent shadow-md' : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50'" class="shrink-0 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full" :class="filterTab === 'unmarked' ? 'bg-white' : 'bg-slate-300'"></span> Belum Absen
                                        </button>
                                        <button @click="filterTab = 'present'" :class="filterTab === 'present' ? 'bg-[#107C10] text-white border-transparent shadow-md' : 'bg-white text-[#107C10] border-[#B7DFB9] hover:bg-[#DFF6DD]/50'" class="shrink-0 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border flex items-center gap-2">
                                            <i class="ph-bold ph-check"></i> Hadir
                                        </button>
                                        <button @click="filterTab = 'alpha'" :class="filterTab === 'alpha' ? 'bg-[#D13438] text-white border-transparent shadow-md' : 'bg-white text-[#D13438] border-[#F4C3C9] hover:bg-[#FDE7E9]/50'" class="shrink-0 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border flex items-center gap-2">
                                            <i class="ph-bold ph-x"></i> Alpha
                                        </button>
                                    </div>
                                    
                                    @if($isOpen)
                                        <button @click="markRestAsAlpha()" type="button" class="shrink-0 w-full sm:w-auto px-4 py-2 bg-[#FDE7E9] text-[#D13438] hover:bg-[#F4C3C9] font-bold rounded-xl text-xs flex items-center justify-center gap-2 border border-[#F4C3C9] transition-colors shadow-sm active:scale-95">
                                            <i class="ph-bold ph-users-three"></i> Tandai Sisanya Alpha
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- 5. LIST SISWA --}}
                        <div class="flex-1 p-5 md:p-8 bg-white overflow-y-auto max-h-[800px] custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-1 2xl:grid-cols-2 gap-3 sm:gap-4">
                                @foreach($allStudents as $student)
                                    @php
                                        $att = $attendances[$student->id] ?? null;
                                        $initialStatus = $att ? $att->status : null; 
                                        $initials = Str::upper(Str::substr(trim($student->name), 0, 1));
                                    @endphp

                                    <div class="relative border-2 rounded-2xl p-3 sm:p-4 flex items-center gap-3 sm:gap-4 transition-all duration-300" 
                                         id="student-row-{{ $student->id }}"
                                         x-data="{ name: '{{ strtolower($student->name) }}', id: '{{ $student->student_id }}', status: '{{ $initialStatus }}' }"
                                         @update-status-{{ $student->id }}.window="status = $event.detail.status"
                                         {{-- TAMBAHAN: Logika filter dikombinasikan dengan pencarian --}}
                                         x-show="(name.includes(searchQuery.toLowerCase()) || id.includes(searchQuery.toLowerCase())) &&
                                                 (filterTab === 'all' ||
                                                 (filterTab === 'unmarked' && !status) ||
                                                 (filterTab === 'present' && (status === 'Hadir' || status === 'present')) ||
                                                 (filterTab === 'sick' && (status === 'Sakit' || status === 'sick')) ||
                                                 (filterTab === 'permission' && (status === 'Izin' || status === 'permission')) ||
                                                 (filterTab === 'alpha' && (status === 'Alfa' || status === 'alpha')))"
                                         :class="{
                                            'bg-[#DFF6DD]/20 border-[#B7DFB9]': status === 'Hadir' || status === 'present',
                                            'bg-elevate-soft/40 border-slate-200': status === 'Sakit' || status === 'sick',
                                            'bg-[#FFEFD6]/20 border-[#FFD8A8]': status === 'Izin' || status === 'permission',
                                            'bg-[#FDE7E9]/20 border-[#F4C3C9]': status === 'Alfa' || status === 'alpha',
                                            'bg-white border-slate-100 hover:border-slate-300 shadow-sm': !status
                                         }">
                                        
                                        {{-- Avatar Status --}}
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center font-black text-sm shrink-0 shadow-sm transition-all border"
                                             :class="{ 
                                                 'bg-[#107C10] text-white border-[#107C10]': status === 'Hadir' || status === 'present',
                                                 'bg-elevate-primary text-white border-elevate-primary': status === 'Sakit' || status === 'sick',
                                                 'bg-[#D83B01] text-white border-[#D83B01]': status === 'Izin' || status === 'permission',
                                                 'bg-[#D13438] text-white border-[#D13438]': status === 'Alfa' || status === 'alpha',
                                                 'bg-slate-100 text-slate-400 border-slate-200': !status 
                                             }">
                                             <template x-if="status === 'Hadir' || status === 'present'"> <i class="ph-bold ph-check text-lg sm:text-xl"></i> </template>
                                             <template x-if="status === 'Sakit' || status === 'sick'"> <span>S</span> </template>
                                             <template x-if="status === 'Izin' || status === 'permission'"> <span>I</span> </template>
                                             <template x-if="status === 'Alfa' || status === 'alpha'"> <span>A</span> </template>
                                             <template x-if="!status"> <span>{{ $initials }}</span> </template>
                                        </div>

                                        <div class="flex-1 min-w-0 pr-1">
                                            <p class="font-black text-elevate-dark text-sm sm:text-base leading-snug line-clamp-2" title="{{ $student->name }}">{{ $student->name }}</p>
                                            <p class="text-[10px] sm:text-xs text-slate-500 font-bold tracking-wide mt-1">{{ $student->student_id }}</p>
                                        </div>

                                        @if($isOpen)
                                            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                                                {{-- Tombol Hadir Cepat --}}
                                                <button @click="setManual({{ $student->id }}, 'present')" 
                                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-95 border border-transparent"
                                                        :class="status === 'Hadir' || status === 'present' ? 'bg-[#107C10] text-white' : 'bg-slate-100 text-slate-400 hover:bg-[#DFF6DD] hover:text-[#107C10]'">
                                                    <i class="ph-bold ph-check text-base sm:text-lg"></i>
                                                </button>
                                                
                                                {{-- Dropdown Pilihan --}}
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.outside="open = false"
                                                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-95 border border-transparent"
                                                            :class="['sick', 'permission', 'alpha', 'Sakit', 'Izin', 'Alfa'].includes(status) ? 'bg-elevate-dark text-white' : 'bg-slate-100 text-slate-400 hover:bg-elevate-dark hover:text-white'">
                                                        <i class="ph-bold ph-dots-three-vertical text-lg sm:text-xl"></i>
                                                    </button>
                                                    
                                                    <div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-2 w-40 bg-white rounded-2xl shadow-xl shadow-elevate-dark/20 border border-slate-100 z-50 p-2 overflow-hidden">
                                                        <button @click="setManual({{ $student->id }}, 'sick'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-elevate-primary hover:bg-elevate-soft rounded-lg flex items-center gap-2"><div class="w-2 h-2 bg-elevate-primary rounded-full"></div> Sakit</button>
                                                        <button @click="setManual({{ $student->id }}, 'permission'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#D83B01] hover:bg-[#FFEFD6] rounded-lg flex items-center gap-2"><div class="w-2 h-2 bg-[#D83B01] rounded-full"></div> Izin</button>
                                                        <button @click="setManual({{ $student->id }}, 'alpha'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#D13438] hover:bg-[#FDE7E9] rounded-lg flex items-center gap-2"><div class="w-2 h-2 bg-[#D13438] rounded-full"></div> Alpha</button>
                                                        <div class="border-t border-slate-100 my-1"></div>
                                                        <button @click="setManual({{ $student->id }}, null); open=false" class="w-full text-left px-4 py-2.5 text-xs text-slate-500 hover:bg-slate-100 font-bold rounded-lg">Reset Status</button>
                                                    </div>
                                                </div>
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

    {{-- LOGIKA JAVASCRIPT --}}
    @push('scripts')
    <script>
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(type) {
            if (audioCtx.state === 'suspended') audioCtx.resume();
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.connect(gain);
            gain.connect(audioCtx.destination);
            if (type === 'success') { osc.type = 'sine'; osc.frequency.setValueAtTime(880, audioCtx.currentTime); } 
            else { osc.type = 'sawtooth'; osc.frequency.setValueAtTime(150, audioCtx.currentTime); }
            gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.3);
            osc.start(); osc.stop(audioCtx.currentTime + 0.3);
        }

        function confirmCloseClass() {
            Swal.fire({
                title: 'Akhiri Sesi?',
                text: "Anda yakin ingin menutup kelas ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D13438',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Tutup Kelas',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem] shadow-2xl border-slate-100', confirmButton: 'rounded-xl px-6 py-3 font-bold', cancelButton: 'rounded-xl px-6 py-3 font-bold' }
            }).then((result) => { if (result.isConfirmed) document.getElementById('close-session-form').submit(); })
        }

        document.addEventListener('alpine:init', () => {
            Alpine.data('teachingSession', (config) => ({
                rfidCode: '',
                sessionId: config.sessionId,
                stats: config.stats,
                statusMessage: 'Siap memindai...',
                showCamera: false,
                html5QrcodeScanner: null,
                isScanMode: true,
                filterTab: 'all', // TAMBAHAN: Inisialisasi status filter tab

                // TAMBAHAN: Fitur Auto-Save Jurnal
                journalTopic: localStorage.getItem('journal_' + config.sessionId + '_topic') || {!! json_encode($session->topic ?? '') !!},
                journalActivities: localStorage.getItem('journal_' + config.sessionId + '_activities') || {!! json_encode($session->activities ?? '') !!},

                init() {
                    // Watcher untuk menyimpan jurnal ke localStorage setiap kali ada huruf yang diketik
                    this.$watch('journalTopic', value => localStorage.setItem('journal_' + this.sessionId + '_topic', value));
                    this.$watch('journalActivities', value => localStorage.setItem('journal_' + this.sessionId + '_activities', value));
                },

                clearJournalDraft() {
                    // Hapus draft saat form sukses di-submit
                    localStorage.removeItem('journal_' + this.sessionId + '_topic');
                    localStorage.removeItem('journal_' + this.sessionId + '_activities');
                },

                // TAMBAHAN: Fungsi Bulk Action
                async markRestAsAlpha() {
                    const totalStudents = {{ $allStudents->count() }};
                    const totalMarked = this.stats.present + this.stats.sick + this.stats.permission + this.stats.alpha;
                    const unmarkedCount = totalStudents - totalMarked;

                    if (unmarkedCount <= 0) {
                        this.showToast('info', 'Semua siswa sudah diabsen.');
                        return;
                    }

                    Swal.fire({
                        title: 'Tandai ' + unmarkedCount + ' Siswa Alpha?',
                        text: "Siswa yang belum diabsen akan otomatis diubah statusnya menjadi Alpha.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#D13438',
                        cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Tandai Alpha',
                        cancelButtonText: 'Batal',
                        customClass: { popup: 'rounded-[2rem] shadow-2xl border-slate-100', confirmButton: 'rounded-xl px-6 py-3 font-bold', cancelButton: 'rounded-xl px-6 py-3 font-bold' }
                    }).then(async (result) => {
                        if (result.isConfirmed) {
                            try {
                                const response = await fetch('/teaching/bulk-alpha', { 
                                    method: 'POST',
                                    headers: { 
                                        'Content-Type': 'application/json', 
                                        'Accept': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                                    },
                                    body: JSON.stringify({ session_id: this.sessionId })
                                });
                                
                                const data = await response.json();
                                if (data.status === 'success') {
                                    playBeep('error'); // Gunakan nada error/peringatan karena berstatus Alpha
                                    this.showToast('success', data.message);
                                    
                                    // Update UI list siswa secara reaktif (tanpa reload halaman)
                                    data.updated_ids.forEach(id => {
                                        window.dispatchEvent(new CustomEvent('update-status-' + id, { detail: { status: 'alpha' } }));
                                    });
                                    
                                    // Update statistik counter Alpha di UI atas
                                    this.stats.alpha += data.updated_ids.length;
                                } else {
                                    throw new Error(data.message || 'Gagal memproses.');
                                }
                            } catch (e) {
                                this.showToast('error', e.message || 'Terjadi kesalahan sistem.');
                            }
                        }
                    });
                },

                showToast(icon, title) {
                    const Toast = Swal.mixin({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true,
                        didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); },
                        customClass: { popup: 'rounded-2xl font-sans border border-slate-100 shadow-lg' }
                    })
                    Toast.fire({ icon: icon, title: title })
                },

                toggleScanMode() {
                    this.isScanMode = !this.isScanMode;
                    if(this.isScanMode) {
                        this.showToast('info', 'Auto Focus ON');
                        this.$nextTick(() => document.getElementById('rfidInput').focus({ preventScroll: true }));
                    } else {
                        this.showToast('info', 'Auto Focus OFF');
                    }
                },

                keepFocus(event) {
                    if (this.isScanMode && !this.showCamera) {
                        if (event && event.relatedTarget && event.relatedTarget.classList.contains('journal-input')) return;
                        setTimeout(() => {
                            const input = document.getElementById('rfidInput');
                            if(input) input.focus({ preventScroll: true });
                        }, 100);
                    }
                },

                async setManual(studentId, status) {
                    try {
                        const response = await fetch('{{ route("teaching.manual") }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                            },
                            body: JSON.stringify({ session_id: this.sessionId, student_id: studentId, status: status })
                        });

                        if (!response.ok) {
                            const errData = await response.json().catch(() => null);
                            throw new Error(errData?.message || 'Server error ' + response.status);
                        }

                        const data = await response.json();
                        
                        if(data.status === 'success') {
                            const newStatus = data.new_status || null; 
                            window.dispatchEvent(new CustomEvent('update-status-' + studentId, { detail: { status: newStatus } }));
                            
                            playBeep('success');
                            
                            // Map ke bahasa Indonesia agar sama dengan database
                            const statusMap = { 'present': 'HADIR', 'sick': 'SAKIT', 'permission': 'IZIN', 'alpha': 'ALFA', 'Hadir': 'HADIR', 'Sakit': 'SAKIT', 'Izin': 'IZIN', 'Alfa': 'ALFA' };
                            const statusText = newStatus ? (statusMap[newStatus] || newStatus.toUpperCase()) : 'DIRESET';

                            this.showToast('success', 'Status: ' + statusText);
                        } else {
                            throw new Error(data.message || 'Gagal update status.');
                        }
                    } catch (e) { 
                        console.error(e);
                        this.showToast('error', e.message || 'Gagal update status.'); 
                    }
                },

                updateLocalStats(oldStatus, newStatus) {
                    // Memetakan ke bahasa Inggris untuk Local Stats (UI Count)
                    const map = {
                        'present': 'present', 'Hadir': 'present', 'Terlambat': 'present',
                        'sick': 'sick', 'Sakit': 'sick',
                        'permission': 'permission', 'Izin': 'permission',
                        'alpha': 'alpha', 'Alfa': 'alpha'
                    };
                    const mappedOld = map[oldStatus];
                    const mappedNew = map[newStatus];
                    
                    if(mappedOld && this.stats[mappedOld] > 0) this.stats[mappedOld]--;
                    if(mappedNew) this.stats[mappedNew]++;
                },

                async submitScan() {
                    if(this.rfidCode.length < 3) return;
                    this.statusMessage = 'Memproses...';
                    try {
                        const response = await fetch('{{ route("teaching.scan") }}', {
                            method: 'POST',
                            headers: { 
                                'Content-Type': 'application/json', 
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') 
                            },
                            body: JSON.stringify({ rfid: this.rfidCode, session_id: this.sessionId })
                        });
                        const data = await response.json();
                        
                        if(data.status === 'success') {
                            this.statusMessage = 'OK: ' + data.student.name;
                            
                            window.dispatchEvent(new CustomEvent('update-status-' + data.student.id, { detail: { status: 'present' } }));
                            
                            let rowEl = document.getElementById('student-row-' + data.student.id);
                            if(rowEl) {
                                let alpineEl = Alpine.$data(rowEl);
                                if(alpineEl) this.updateLocalStats(alpineEl.status, 'present');
                                rowEl.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }

                            playBeep('success');
                            Swal.fire({
                                icon: 'success', title: 'Hadir!', text: data.student.name,
                                timer: 1000, showConfirmButton: false, backdrop: `rgba(0,0,0,0.4)`,
                                customClass: { popup: 'rounded-[2rem] shadow-2xl font-sans border-0 w-[85%] max-w-sm' }
                            });
                        } else if(data.status === 'warning') {
                             this.statusMessage = 'Sudah absen: ' + data.student.name;
                             playBeep('error'); 
                             this.showToast('warning', data.student.name + ' sudah absen.');
                        } else {
                            this.statusMessage = 'GAGAL: ' + data.message;
                            playBeep('error');
                            this.showToast('error', data.message);
                        }
                    } catch (error) { this.statusMessage = 'Error koneksi'; }
                    this.rfidCode = '';
                    if(this.isScanMode) document.getElementById('rfidInput').focus({ preventScroll: true });
                },

                toggleCamera() {
                    this.showCamera = !this.showCamera;
                    if (this.showCamera) this.$nextTick(() => { this.startScanner(); }); else this.stopScanner();
                },

                startScanner() {
                    this.html5QrcodeScanner = new Html5Qrcode("reader");
                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                    
                    this.html5QrcodeScanner.start({ facingMode: "environment" }, config,
                        (decodedText) => { 
                             if (!this.loading) { 
                                 this.rfidCode = decodedText; 
                                 this.submitScan();
                                 this.loading = true;
                                 setTimeout(() => { this.loading = false; }, 2000);
                             } 
                        },
                        (errorMessage) => { }
                    ).catch(err => {
                        this.statusMessage = "Error Kamera: Izin ditolak.";
                        Swal.fire({
                            title: 'Kamera Error', 
                            text: 'Pastikan Anda menggunakan HTTPS dan memberikan izin kamera.', 
                            icon: 'error',
                            customClass: { popup: 'rounded-[2rem] shadow-2xl' }
                        });
                    });
                },

                stopScanner() {
                    if (this.html5QrcodeScanner) {
                        this.html5QrcodeScanner.stop().then(() => { this.html5QrcodeScanner.clear(); }).catch(err => {});
                    }
                }
            }));
        });
    </script>
    @endpush
</x-app-layout>