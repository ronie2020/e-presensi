<x-app-layout>
    @push('styles')
    <style>
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
        
        /* FIX KAMERA RESPONSIVE (ANTI GEPENG) */
        #reader { 
            width: 100% !important; 
            min-height: 300px !important; 
            border: none !important; 
            border-radius: 0.75rem; 
            overflow: hidden; 
            position: relative; 
            background: #0f172a; 
        }
        #reader__scan_region { width: 100% !important; min-height: 300px !important; background: transparent !important; }
        #reader video, #reader canvas { 
            width: 100% !important; height: 100% !important; min-height: 300px !important;
            object-fit: cover !important; /* Force fit to avoid squished view */
            display: block !important; border-radius: 0.75rem;
            position: absolute !important; top: 0 !important; left: 0 !important;
        }
        #reader__dashboard_section_csr span, #reader__dashboard_section_swaplink { display: none !important; }

        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <div class="py-4 sm:py-10 font-sans text-slate-800 pb-20" 
         x-data="teachingSession({ 
            sessionId: {{ $session->id }}, 
            stats: {{ json_encode($stats) }}
         })">
         
        <div class="max-w-[1600px] mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- 1. HEADER NAVIGASI --}}
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 sm:mb-6">
                <a href="{{ route('teaching.index') }}" class="group inline-flex items-center gap-2 text-sm text-slate-500 hover:text-[#5295FF] transition font-bold">
                    <div class="w-8 h-8 rounded-full bg-white border border-slate-200 flex items-center justify-center shadow-sm group-hover:border-[#D0E7F8] group-hover:bg-[#F3F9FD] transition">
                        <i class="ph-bold ph-arrow-left"></i>
                    </div>
                    Kembali ke Jadwal
                </a>

                @if(session('error'))
                    <div class="bg-[#FDE7E9] text-[#D13438] px-4 py-2 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 border border-[#F4C3C9] animate-pulse shadow-sm">
                        <i class="ph-fill ph-warning-circle"></i> {{ session('error') }}
                    </div>
                @endif
                @if(session('success'))
                    <div class="bg-[#DFF6DD] text-[#107C10] px-4 py-2 rounded-xl text-xs sm:text-sm font-bold flex items-center gap-2 border border-[#B7DFB9] shadow-sm">
                        <i class="ph-fill ph-check-circle"></i> {{ session('success') }}
                    </div>
                @endif
            </div>

            {{-- 2. HEADER SESI KELAS ELEVATE --}}
            <div class="relative bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] rounded-xl p-6 sm:p-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] mb-8 overflow-hidden group border border-white/40">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute top-0 right-0 w-80 h-80 bg-white/30 rounded-full blur-[80px] translate-x-1/2 -translate-y-1/2 pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div class="space-y-3 w-full">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="bg-[#2A3B52] shadow-sm text-white text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-wider border border-transparent">
                                {{ $session->schedule->schoolClass->name }}
                            </span>
                            <span class="bg-white/40 backdrop-blur-md border border-white/50 text-[#2A3B52] text-[10px] font-bold px-3 py-1.5 rounded-lg flex items-center gap-1.5 shadow-sm">
                                <i class="ph-bold ph-clock"></i> {{ \Carbon\Carbon::parse($session->started_at)->format('H:i') }}
                            </span>

                            @if(!$isOpen)
                                <span class="bg-slate-100/90 backdrop-blur text-slate-500 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-slate-200 flex items-center gap-1 shadow-sm">
                                    <i class="ph-fill ph-lock-key"></i> Selesai
                                </span>
                            @else
                                <span class="bg-[#107C10]/90 backdrop-blur text-white text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase border border-[#B7DFB9] animate-pulse flex items-center gap-1 shadow-sm">
                                    <span class="w-1.5 h-1.5 bg-white rounded-full"></span> Live Session
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-5xl font-black tracking-tight leading-tight text-[#2A3B52] break-words">
                            {{ $session->schedule->subject->name }}
                        </h1>
                    </div>
                    
                    @if($isOpen)
                        <form id="close-session-form" action="{{ route('teaching.close', $session->id) }}" method="POST" class="w-full md:w-auto">
                            @csrf
                            <button type="button" onclick="confirmCloseClass()" class="w-full md:w-auto group/btn relative overflow-hidden bg-white hover:bg-[#FDE7E9] text-[#D13438] pl-4 pr-5 py-3 rounded-xl font-bold shadow-md transition-all active:scale-95 flex items-center justify-center md:justify-start gap-3 border border-white/40">
                                <div class="bg-[#FDE7E9] p-2 rounded-lg group-hover/btn:bg-[#F4C3C9] transition-colors border border-[#F4C3C9]">
                                    <i class="ph-bold ph-power text-lg"></i>
                                </div>
                                <div class="text-left">
                                    <div class="text-[9px] uppercase opacity-80 font-black tracking-widest text-[#D13438]">Selesai</div>
                                    <div class="text-sm font-black">Tutup Kelas</div>
                                </div>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('teaching.edit', $session->id) }}" class="w-full md:w-auto group/btn relative overflow-hidden bg-[#D83B01] hover:bg-[#a62d01] text-white pl-4 pr-5 py-3 rounded-xl font-bold shadow-md transition-all active:scale-95 flex items-center justify-center md:justify-start gap-3 border border-transparent">
                            <div class="bg-white/20 p-2 rounded-lg group-hover/btn:bg-white/30 transition-colors">
                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                            </div>
                            <div class="text-left">
                                <div class="text-[9px] uppercase opacity-90 font-black tracking-widest text-white">Ada Kesalahan?</div>
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
                        <div class="bg-white rounded-xl fluent-card p-6 relative overflow-hidden group">
                            <div class="relative z-10">
                                <div class="flex items-center justify-between mb-4">
                                    <h3 class="font-bold text-[#2A3B52] flex items-center gap-2 text-lg">
                                        <i class="ph-fill ph-scan text-[#5295FF]"></i> Scanner
                                    </h3>
                                    
                                    <button @click="toggleScanMode()" 
                                            class="text-[10px] font-bold px-3 py-1.5 rounded-lg border transition-all flex items-center gap-2"
                                            :class="isScanMode ? 'bg-[#5295FF] text-white border-[#5295FF] shadow-sm' : 'bg-slate-100 text-slate-500 border-slate-200'">
                                        <span class="w-1.5 h-1.5 rounded-full" :class="isScanMode ? 'bg-white animate-pulse' : 'bg-slate-400'"></span>
                                        <span x-text="isScanMode ? 'AUTO FOCUS' : 'MANUAL'"></span>
                                    </button>
                                </div>

                                <div class="mb-4 relative group/input">
                                    <input type="text" id="rfidInput" x-model="rfidCode" @keydown.enter.prevent="submitScan()"
                                        @blur="keepFocus($event)" 
                                        :disabled="!isScanMode && !showCamera"
                                        class="relative w-full bg-slate-50 border border-slate-200 focus:border-[#5295FF] text-[#2A3B52] rounded-xl text-center font-mono text-lg tracking-[0.2em] py-4 transition-all focus:ring-[#5295FF] uppercase placeholder:text-slate-400 shadow-inner disabled:opacity-50 disabled:cursor-not-allowed font-bold"
                                        placeholder="TAP KARTU / NIS..." autocomplete="off">
                                </div>

                                <button @click="toggleCamera()" type="button" class="w-full py-3 bg-white hover:bg-[#F3F9FD] text-[#5295FF] font-bold rounded-xl border border-slate-200 hover:border-[#D0E7F8] transition-colors flex items-center justify-center gap-2 text-sm shadow-sm mb-4 active:scale-95">
                                    <i class="ph-bold ph-camera text-lg"></i>
                                    <span x-text="showCamera ? 'Tutup Kamera' : 'Buka Kamera HP'"></span>
                                </button>

                                <div x-show="showCamera" x-transition class="mt-4 bg-slate-900 rounded-xl overflow-hidden border border-slate-200 relative shadow-inner">
                                    <div id="reader" class="w-full h-64 bg-slate-900"></div>
                                </div>

                                <p class="mt-4 text-xs font-mono font-bold text-slate-500 text-center bg-slate-50 p-2 rounded-lg border border-slate-100" x-text="statusMessage">Menunggu input...</p>
                            </div>
                        </div>
                    @else
                        <div class="bg-white border border-slate-200 rounded-xl p-6 text-center shadow-sm fluent-card">
                            <h3 class="font-bold text-[#2A3B52] text-base">Absensi Terkunci</h3>
                            <p class="text-xs text-slate-500 mt-1">Sesi kelas telah berakhir. Gunakan tombol Edit di atas jika ada kesalahan.</p>
                        </div>
                    @endif

                    {{-- 4. FORM JURNAL MENGAJAR --}}
                    <div class="bg-white rounded-xl fluent-card overflow-hidden" x-data="{ photoPreview: null }">
                         <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-[#F3F9FD] text-[#5295FF] flex items-center justify-center text-lg shadow-sm border border-[#D0E7F8]">
                                <i class="ph-fill ph-notebook"></i>
                            </div>
                            <h3 class="font-bold text-[#2A3B52] text-base">Jurnal Mengajar</h3>
                        </div>
                        <div class="p-6">
                            <fieldset {{ !$isOpen ? 'disabled' : '' }}>
                                <form action="{{ route('teaching.update', $session->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Topik / Materi <span class="text-[#D13438]">*</span></label>
                                            <input type="text" name="topic" value="{{ old('topic', $session->topic) }}" 
                                                class="journal-input w-full rounded-xl border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] font-bold text-[#2A3B52] py-3 px-4 text-sm bg-slate-50 transition-all" 
                                                placeholder="Contoh: Aljabar Linear" required>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Catatan</label>
                                            <textarea name="activities" rows="3" 
                                                class="journal-input w-full rounded-xl border-slate-200 focus:border-[#5295FF] focus:ring-[#5295FF] text-sm text-slate-600 font-medium py-3 px-4 bg-slate-50 transition-all" 
                                                placeholder="Deskripsi kegiatan...">{{ old('activities', $session->activities) }}</textarea>
                                        </div>
                                        
                                        <div>
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Foto Dokumentasi</label>
                                            @if($session->photo_proof)
                                                <div class="relative group h-40 rounded-xl overflow-hidden border border-slate-200 mb-4 shadow-sm" x-show="!photoPreview">
                                                    <img src="{{ asset('storage/' . $session->photo_proof) }}" class="w-full h-full object-cover">
                                                    <a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="absolute inset-0 bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 text-white font-bold text-xs gap-2">
                                                        <i class="ph-bold ph-eye text-lg"></i> Lihat Foto
                                                    </a>
                                                </div>
                                            @endif

                                            <div class="relative h-40 rounded-xl overflow-hidden border border-[#D0E7F8] mb-4 shadow-sm bg-[#F3F9FD]" x-show="photoPreview" x-cloak>
                                                <img :src="photoPreview" class="w-full h-full object-cover">
                                                <div class="absolute bottom-0 left-0 right-0 bg-[#5295FF]/80 text-white text-[10px] font-bold py-1.5 text-center backdrop-blur-sm">Foto Baru</div>
                                            </div>

                                            @if($isOpen)
                                                <label class="flex flex-col items-center justify-center w-full h-20 border-2 border-dashed border-slate-200 rounded-xl cursor-pointer hover:bg-[#F3F9FD] hover:border-[#5295FF] transition-all group/upload bg-slate-50">
                                                    <div class="flex flex-col items-center justify-center pt-2">
                                                        <i class="ph-duotone ph-image text-xl text-slate-400 group-hover/upload:text-[#5295FF] mb-1 transition-colors"></i>
                                                        <p class="text-[10px] text-slate-500 group-hover/upload:text-[#5295FF] transition-colors"><span class="font-bold">Upload Foto</span></p>
                                                    </div>
                                                    <input type="file" name="photo_proof" accept="image/*" class="hidden" 
                                                           @change="photoPreview = URL.createObjectURL($event.target.files[0])" />
                                                </label>
                                            @endif
                                        </div>
                                        
                                        @if($isOpen)
                                            <button type="submit" class="w-full bg-[#2A3B52] text-white hover:bg-[#182436] font-bold py-3.5 rounded-xl shadow-md transition-all active:scale-95 flex items-center justify-center gap-2 border border-transparent">
                                                <i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal
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
                    <div class="bg-white rounded-xl fluent-card flex flex-col h-full min-h-[600px] overflow-hidden">
                        
                        {{-- Header List & Search --}}
                        <div class="p-6 border-b border-slate-100 flex flex-col gap-5 bg-slate-50/30">
                            <div>
                                <h3 class="font-black text-[#2A3B52] text-xl">Kehadiran Siswa</h3>
                                <p class="text-xs text-slate-500 font-medium mt-1">Kelola absensi siswa secara manual atau scan.</p>
                            </div>
                            
                            {{-- Statistik Ringkas (Real-time via Alpine) --}}
                            <div class="grid grid-cols-4 gap-3">
                                <div class="px-2 py-3 bg-[#DFF6DD] text-[#107C10] rounded-xl text-center border border-[#B7DFB9] shadow-sm">
                                    <div class="text-[9px] font-black uppercase opacity-70 mb-1">Hadir</div>
                                    <div class="text-xl font-black leading-none" x-text="stats.present">0</div>
                                </div>
                                <div class="px-2 py-3 bg-[#F3F9FD] text-[#5295FF] rounded-xl text-center border border-[#D0E7F8] shadow-sm">
                                    <div class="text-[9px] font-black uppercase opacity-70 mb-1">Sakit</div>
                                    <div class="text-xl font-black leading-none" x-text="stats.sick">0</div>
                                </div>
                                <div class="px-2 py-3 bg-[#FFEFD6] text-[#D83B01] rounded-xl text-center border border-[#FFD8A8] shadow-sm">
                                    <div class="text-[9px] font-black uppercase opacity-70 mb-1">Izin</div>
                                    <div class="text-xl font-black leading-none" x-text="stats.permission">0</div>
                                </div>
                                <div class="px-2 py-3 bg-[#FDE7E9] text-[#D13438] rounded-xl text-center border border-[#F4C3C9] shadow-sm">
                                    <div class="text-[9px] font-black uppercase opacity-70 mb-1">Alpha</div>
                                    <div class="text-xl font-black leading-none" x-text="stats.alpha">0</div>
                                </div>
                            </div>

                            {{-- Search Bar --}}
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="ph-bold ph-magnifying-glass text-slate-400 group-focus-within:text-[#5295FF] transition-colors"></i>
                                </div>
                                <input type="text" x-model="searchQuery" class="journal-input block w-full pl-12 pr-4 py-3.5 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:border-[#5295FF] focus:ring-[#5295FF] placeholder-slate-400 text-sm font-bold shadow-sm transition-colors text-[#2A3B52]" placeholder="Cari nama atau NIS siswa...">
                            </div>
                        </div>

                        {{-- 5. LIST SISWA --}}
                        <div class="flex-1 p-6 bg-slate-50/50 overflow-y-auto max-h-[800px] custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($allStudents as $student)
                                    @php
                                        $att = $attendances[$student->id] ?? null;
                                        $initialStatus = $att ? $att->status : null; 
                                        $initials = Str::upper(Str::substr(trim($student->name), 0, 1));
                                    @endphp

                                    <div class="relative border rounded-xl p-4 flex items-center gap-3 transition-all duration-300 bg-white" 
                                         id="student-row-{{ $student->id }}"
                                         x-data="{ 
                                            name: '{{ strtolower($student->name) }}', 
                                            id: '{{ $student->student_id }}',
                                            status: '{{ $initialStatus }}'
                                         }"
                                         @update-status-{{ $student->id }}.window="status = $event.detail.status"
                                         x-show="name.includes(searchQuery.toLowerCase()) || id.includes(searchQuery.toLowerCase())"
                                         :class="{
                                            'bg-[#DFF6DD]/30 border-[#B7DFB9] shadow-sm': status === 'present',
                                            'bg-[#F3F9FD]/30 border-[#D0E7F8] shadow-sm': status === 'sick',
                                            'bg-[#FFEFD6]/30 border-[#FFD8A8] shadow-sm': status === 'permission',
                                            'bg-[#FDE7E9]/30 border-[#F4C3C9] shadow-sm': status === 'alpha',
                                            'bg-white border-slate-200': !status
                                         }">
                                        
                                        {{-- Avatar Status (Semantic Colors) --}}
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-xs shrink-0 shadow-sm transition-all border"
                                             :class="{ 
                                                 'bg-[#107C10] text-white border-[#107C10]': status === 'present',
                                                 'bg-[#5295FF] text-white border-[#5295FF]': status === 'sick',
                                                 'bg-[#D83B01] text-white border-[#D83B01]': status === 'permission',
                                                 'bg-[#D13438] text-white border-[#D13438]': status === 'alpha',
                                                 'bg-slate-100 text-slate-400 border-slate-200': !status 
                                             }">
                                             <template x-if="status === 'present'"> <i class="ph-bold ph-check text-lg"></i> </template>
                                             <template x-if="status === 'sick'"> <span>S</span> </template>
                                             <template x-if="status === 'permission'"> <span>I</span> </template>
                                             <template x-if="status === 'alpha'"> <span>A</span> </template>
                                             <template x-if="!status"> <span>{{ $initials }}</span> </template>
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="font-bold text-[#2A3B52] text-sm leading-tight break-words">{{ $student->name }}</p>
                                            <p class="text-[10px] text-slate-500 font-mono tracking-wide mt-0.5">{{ $student->student_id }}</p>
                                        </div>

                                        @if($isOpen)
                                            <div class="flex items-center gap-1.5 shrink-0">
                                                {{-- Tombol Hadir Cepat --}}
                                                <button @click="setManual({{ $student->id }}, 'present')" 
                                                        class="w-9 h-9 rounded-lg flex items-center justify-center transition-all shadow-sm active:scale-95 border"
                                                        :class="status === 'present' ? 'bg-[#107C10] text-white border-[#107C10]' : 'bg-white border-slate-200 text-slate-400 hover:border-[#107C10] hover:text-[#107C10] hover:bg-[#DFF6DD]'">
                                                    <i class="ph-bold ph-check text-lg"></i>
                                                </button>
                                                
                                                {{-- Dropdown Pilihan Lain --}}
                                                <div class="relative" x-data="{ open: false }">
                                                    <button @click="open = !open" @click.outside="open = false"
                                                            class="w-9 h-9 rounded-lg flex items-center justify-center transition-all shadow-sm active:scale-95 border"
                                                            :class="['sick', 'permission', 'alpha'].includes(status) ? 'bg-[#2A3B52] text-white border-[#2A3B52]' : 'bg-white border-slate-200 text-slate-400 hover:border-[#2A3B52] hover:text-[#2A3B52] hover:bg-slate-100'">
                                                        <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                                    </button>
                                                    
                                                    <div x-show="open" style="display: none;" x-transition class="absolute right-0 mt-2 w-36 bg-white rounded-xl shadow-lg border border-slate-100 z-50 py-1 overflow-hidden">
                                                        <button @click="setManual({{ $student->id }}, 'sick'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#5295FF] hover:bg-[#F3F9FD] flex items-center gap-2"><div class="w-1.5 h-1.5 bg-[#5295FF] rounded-full"></div> Sakit</button>
                                                        <button @click="setManual({{ $student->id }}, 'permission'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#D83B01] hover:bg-[#FFEFD6] flex items-center gap-2"><div class="w-1.5 h-1.5 bg-[#D83B01] rounded-full"></div> Izin</button>
                                                        <button @click="setManual({{ $student->id }}, 'alpha'); open=false" class="w-full text-left px-4 py-2.5 text-xs font-bold text-[#D13438] hover:bg-[#FDE7E9] flex items-center gap-2"><div class="w-1.5 h-1.5 bg-[#D13438] rounded-full"></div> Alpha</button>
                                                        <div class="border-t border-slate-100 my-1"></div>
                                                        <button @click="setManual({{ $student->id }}, null); open=false" class="w-full text-left px-4 py-2.5 text-xs text-slate-500 hover:bg-slate-50 font-bold">Reset</button>
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
                text: "Tutup kelas sekarang?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#D13438',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Tutup',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-xl fluent-modal border-0 font-sans',
                    confirmButton: 'rounded-lg px-6 py-2.5 font-bold shadow-sm',
                    cancelButton: 'rounded-lg px-6 py-2.5 font-bold shadow-sm'
                }
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

                showToast(icon, title) {
                    const Toast = Swal.mixin({
                        toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true,
                        didOpen: (toast) => { toast.addEventListener('mouseenter', Swal.stopTimer); toast.addEventListener('mouseleave', Swal.resumeTimer); },
                        customClass: { popup: 'rounded-xl font-sans border border-slate-100 shadow-md' }
                    })
                    Toast.fire({ icon: icon, title: title })
                },

                toggleScanMode() {
                    this.isScanMode = !this.isScanMode;
                    if(this.isScanMode) {
                        this.showToast('info', 'Auto Focus ON');
                        this.$nextTick(() => document.getElementById('rfidInput').focus({ preventScroll: true }));
                    } else {
                        this.showToast('info', 'Auto Focus OFF (Mode Ketik)');
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
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                            body: JSON.stringify({ session_id: this.sessionId, student_id: studentId, status: status })
                        });
                        const data = await response.json();
                        
                        if(data.status === 'success') {
                            const newStatus = data.new_status || null; 
                            window.dispatchEvent(new CustomEvent('update-status-' + studentId, { detail: { status: newStatus } }));
                            
                            playBeep('success');
                            const statusMap = { 'present': 'HADIR', 'sick': 'SAKIT', 'permission': 'IZIN', 'alpha': 'ALPHA' };
                            const statusText = newStatus ? (statusMap[newStatus] || newStatus.toUpperCase()) : 'DIRESET';

                            this.showToast('success', 'Status: ' + statusText);
                        }
                    } catch (e) { this.showToast('error', 'Gagal update status.'); }
                },

                updateLocalStats(oldStatus, newStatus) {
                    if(oldStatus && this.stats[oldStatus] > 0) this.stats[oldStatus]--;
                    if(newStatus) this.stats[newStatus]++;
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
                                customClass: { popup: 'rounded-xl fluent-modal font-sans border-0 w-[85%] max-w-sm' }
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
                            customClass: { popup: 'rounded-xl fluent-modal font-sans border-0' }
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