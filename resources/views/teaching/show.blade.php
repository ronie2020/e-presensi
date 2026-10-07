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
        
            {{-- BANNER: INFORMASI MATERI PERTEMUAN SEBELUMNYA --}}
            @if($previousSession)
                @php
                    $prevStatus = $previousSession->material_status;
                    $prevTopic = $previousSession->topic;
                    $prevCoverage = $previousSession->material_coverage;
                    $prevDate = $previousSession->date ? \Carbon\Carbon::parse($previousSession->date)->translatedFormat('l, d F Y') : '-';
                @endphp

                @if($prevStatus === 'belum_selesai' && $prevTopic)
                    {{-- Banner KUNING - materi belum selesai, guru perlu melanjutkan --}}
                    <div class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 p-4 flex items-start gap-3 shadow-sm animate-fade-in">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="ph-bold ph-warning text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-amber-800 text-sm mb-0.5">⚠️ Materi Belum Selesai dari Pertemuan Sebelumnya</p>
                            <p class="text-xs font-semibold text-amber-700 mb-1">Pertemuan: {{ $prevDate }}</p>
                            <div class="bg-amber-100 rounded-xl px-4 py-2.5 border border-amber-200">
                                <p class="text-xs font-bold text-amber-900 mb-1">📚 Materi: <span class="font-black">{{ $prevTopic }}</span></p>
                                @if($prevCoverage)
                                    <p class="text-xs text-amber-800 font-medium"><span class="font-bold">Sudah disampaikan:</span> {{ $prevCoverage }}</p>
                                @endif
                            </div>
                            <p class="text-[11px] text-amber-600 font-medium mt-1.5">Lanjutkan materi di atas pada pertemuan ini sebelum memulai materi baru.</p>
                        </div>
                    </div>
                @elseif($prevTopic)
                    {{-- Banner BIRU/HIJAU - materi sudah selesai, info saja --}}
                    <div class="mb-4 rounded-2xl border border-sky-100 bg-sky-50/60 p-4 flex items-start gap-3 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="ph-bold ph-book-open text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-black text-sky-800 text-sm mb-0.5">📖 Materi Pertemuan Sebelumnya</p>
                            <p class="text-xs font-semibold text-sky-600 mb-1">{{ $prevDate }}</p>
                            <div class="bg-sky-100/60 rounded-xl px-4 py-2 border border-sky-200">
                                <p class="text-xs font-bold text-sky-900">{{ $prevTopic }}</p>
                                @if($prevStatus === 'selesai')
                                    <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-black text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-full border border-emerald-200"><i class="ph-bold ph-check-circle"></i> Sudah Selesai</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            @endif

            {{-- BANNER: REMINDER TUGAS YANG BELUM DEADLINE --}}
            @if($pendingHomework)
                @php
                    $hwDeadline = \Carbon\Carbon::parse($pendingHomework->homework_deadline);
                    $hwDaysLeft = (int) now('Asia/Jakarta')->diffInDays($hwDeadline, false);
                    $hwHoursLeft = (int) now('Asia/Jakarta')->diffInHours($hwDeadline, false);
                @endphp
                <div class="mb-4 rounded-2xl border border-violet-200 bg-violet-50 p-4 flex items-start gap-3 shadow-sm">
                    <div class="w-10 h-10 rounded-xl bg-violet-100 text-violet-600 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="ph-bold ph-clipboard-text text-xl"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-black text-violet-800 text-sm mb-0.5">📝 Ada Tugas Belum Deadline dari Pertemuan Sebelumnya</p>
                        <div class="bg-violet-100 rounded-xl px-4 py-2.5 border border-violet-200 mb-1.5">
                            <p class="text-xs font-black text-violet-900 mb-0.5">{{ $pendingHomework->homework_title }}</p>
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="inline-flex items-center gap-1 text-[10px] font-black px-2 py-0.5 rounded-full
                                    {{ $pendingHomework->homework_type === 'offline' ? 'bg-slate-100 text-slate-700 border border-slate-200' : 
                                       ($pendingHomework->homework_type === 'file_upload' ? 'bg-blue-100 text-blue-700 border border-blue-200' : 'bg-green-100 text-green-700 border border-green-200') }}">
                                    <i class="ph-bold {{ $pendingHomework->homework_type === 'offline' ? 'ph-pencil-line' : ($pendingHomework->homework_type === 'file_upload' ? 'ph-upload' : 'ph-link') }}"></i>
                                    {{ $pendingHomework->homework_type === 'offline' ? 'Tatap Muka' : ($pendingHomework->homework_type === 'file_upload' ? 'Upload File' : 'Link Tugas') }}
                                </span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-violet-700">
                                    <i class="ph-bold ph-clock"></i>
                                    Deadline: {{ $hwDeadline->translatedFormat('d M Y, H:i') }}
                                    @if($hwDaysLeft > 0)
                                        <span class="font-black text-violet-800">({{ $hwDaysLeft }} hari lagi)</span>
                                    @elseif($hwHoursLeft > 0)
                                        <span class="font-black text-rose-600">({{ $hwHoursLeft }} jam lagi!)</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        @if($pendingHomework->lms_assignment_id)
                            <a href="{{ route('lms.assignments.submissions', $pendingHomework->lms_assignment_id) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 text-[11px] font-black text-violet-700 hover:text-violet-900 underline underline-offset-2">
                                <i class="ph-bold ph-arrow-square-out"></i> Lihat pengumpulan di LMS
                            </a>
                        @endif
                    </div>
                </div>
            @endif


            <x-hero-section
                badge="{{ $session->timetable->studentClass->name ?? 'Kelas' }}"
                badgeIcon="ph-chalkboard-teacher"
                title="{{ $session->timetable->subject->name ?? 'Mata Pelajaran' }}"
                titleHighlight="Jurnal Pembelajaran"
                description="Sesi pembelajaran tatap muka dan monitoring absensi siswa di kelas."
                :chips="[
                    ['icon' => 'ph-calendar-check', 'label' => ($session->timetable->timeslot->name ?? 'Sesi Pelajaran')],
                    ['icon' => 'ph-clock', 'label' => \Carbon\Carbon::parse($session->timetable->timeslot->start_time)->format('H:i') . ' - ' . \Carbon\Carbon::parse($session->timetable->timeslot->end_time)->format('H:i')],
                    ['icon' => ($isOpen ? 'ph-broadcast' : 'ph-lock-key'), 'label' => ($isOpen ? 'Live Session' : 'Sesi Selesai')]
                ]"
                heroIcon="ph-chalkboard-teacher"
                statusOrb="{{ $isOpen ? 'Aktif' : 'Selesai' }}"
                statusColor="{{ $isOpen ? 'emerald' : 'slate' }}"
            >
                <x-slot name="cta">
                    <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                        <a href="{{ route('teaching.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/15 border border-white/15 text-slate-200 text-xs font-bold transition-all backdrop-blur-md shadow-sm active:scale-95">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </a>
                        @if($isOpen)
                            <form id="close-session-form" action="{{ route('teaching.close', $session->id) }}" method="POST" class="w-full sm:w-auto">
                                @csrf
                                <button type="button" onclick="confirmCloseClass()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2.5 px-6 py-3 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold transition-all shadow-lg shadow-rose-600/30 active:scale-95 border border-rose-400/30">
                                    <i class="ph-bold ph-power text-base"></i> Tutup Kelas
                                </button>
                            </form>
                        @else
                            <a href="{{ route('teaching.edit', $session->id) }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-gradient-to-r from-[#56bbf1] to-[#0d52a1] text-white text-xs font-bold transition-all shadow-lg shadow-cyan-500/25 active:scale-95 border border-cyan-300/30">
                                <i class="ph-bold ph-pencil-simple text-base"></i> Edit Data
                            </a>
                        @endif
                    </div>
                </x-slot>
            </x-hero-section>

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

                                        {{-- SECTION: STATUS PENYELESAIAN MATERI --}}
                                        <div class="rounded-2xl border-2 p-5 transition-all"
                                             :class="materialStatus === 'belum_selesai' ? 'border-amber-200 bg-amber-50/50' : (materialStatus === 'selesai' ? 'border-emerald-200 bg-emerald-50/50' : 'border-slate-100 bg-slate-50/50')">
                                            <label class="block text-xs font-bold uppercase tracking-wider mb-3 transition-colors"
                                                   :class="materialStatus === 'belum_selesai' ? 'text-amber-700' : (materialStatus === 'selesai' ? 'text-emerald-700' : 'text-elevate-primary')"
                                            >
                                                <i class="ph-bold ph-flag-checkered mr-1"></i>
                                                Status Penyelesaian Materi
                                            </label>
                                            
                                            <input type="hidden" name="material_status" :value="materialStatus">

                                            {{-- Toggle Tombol --}}
                                            <div class="flex gap-2 mb-3">
                                                <button type="button"
                                                        @click="materialStatus = 'selesai'"
                                                        class="flex-1 py-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 border-2 transition-all active:scale-95"
                                                        :class="materialStatus === 'selesai' 
                                                            ? 'bg-emerald-600 text-white border-emerald-600 shadow-lg shadow-emerald-600/20' 
                                                            : 'bg-white text-slate-500 border-slate-200 hover:border-emerald-300 hover:text-emerald-700'">
                                                    <i class="ph-bold ph-check-circle text-lg"></i>
                                                    Materi Selesai
                                                </button>
                                                <button type="button"
                                                        @click="materialStatus = 'belum_selesai'"
                                                        class="flex-1 py-3 rounded-xl text-xs font-black flex items-center justify-center gap-2 border-2 transition-all active:scale-95"
                                                        :class="materialStatus === 'belum_selesai' 
                                                            ? 'bg-amber-500 text-white border-amber-500 shadow-lg shadow-amber-500/20' 
                                                            : 'bg-white text-slate-500 border-slate-200 hover:border-amber-300 hover:text-amber-700'">
                                                    <i class="ph-bold ph-clock-countdown text-lg"></i>
                                                    Belum Selesai
                                                </button>
                                            </div>

                                            {{-- Textarea keterangan (muncul jika belum selesai) --}}
                                            <div x-show="materialStatus === 'belum_selesai'" x-transition x-cloak>
                                                <label class="block text-xs font-bold text-amber-700 mb-1.5 ml-1">Sudah sampai mana? <span class="text-amber-500">(agar pertemuan berikutnya bisa melanjutkan)</span></label>
                                                <textarea x-model="materialCoverage" rows="2"
                                                    class="journal-input w-full rounded-2xl border-amber-200 focus:bg-white focus:border-amber-400 focus:ring-amber-400/30 text-sm text-elevate-dark font-medium py-3 px-4 bg-white transition-all"
                                                    placeholder="Contoh: Sudah sampai sub-bab 3.2 tentang persamaan linear satu variabel..."></textarea>
                                            </div>

                                            {{-- Hidden inputs agar selalu tersubmit ke server --}}
                                            <input type="hidden" name="material_coverage" :value="materialStatus === 'belum_selesai' ? materialCoverage : ''">

                                            <p x-show="!materialStatus" x-cloak class="text-[10px] text-slate-400 font-medium text-center pt-1">Pilih status agar guru berikutnya tahu kondisi materi.</p>
                                        </div>
                                        
                                        {{-- SECTION: TUGAS / PR --}}
                                        <div class="rounded-2xl border-2 p-5 transition-all"
                                             :class="hasHomework ? 'border-violet-200 bg-violet-50/50' : 'border-slate-100 bg-slate-50/50'">
                                            <div class="flex items-center justify-between mb-3">
                                                <label class="text-xs font-bold uppercase tracking-wider transition-colors"
                                                       :class="hasHomework ? 'text-violet-700' : 'text-elevate-primary'">
                                                    <i class="ph-bold ph-clipboard-text mr-1"></i>
                                                    Tugas / PR
                                                </label>
                                                {{-- Toggle Ada/Tidak Ada Tugas --}}
                                                <button type="button" @click="hasHomework = !hasHomework; if(!hasHomework){ homeworkTitle=''; homeworkDeadline=''; homeworkType='offline'; homeworkDesc=''; homeworkLinkUrl=''; }"
                                                        class="flex items-center gap-2 px-3 py-1.5 rounded-xl text-xs font-black border-2 transition-all active:scale-95"
                                                        :class="hasHomework ? 'bg-violet-600 text-white border-violet-600' : 'bg-slate-100 text-slate-500 border-slate-200 hover:border-violet-300'">
                                                    <i class="ph-bold" :class="hasHomework ? 'ph-check' : 'ph-plus'"></i>
                                                    <span x-text="hasHomework ? 'Ada Tugas' : 'Tidak Ada'"></span>
                                                </button>
                                            </div>

                                            <div x-show="hasHomework" x-transition x-cloak class="space-y-3">
                                                {{-- Judul Tugas --}}
                                                <div>
                                                    <label class="block text-[10px] font-bold text-violet-700 uppercase tracking-wider mb-1.5 ml-1">Judul Tugas <span class="text-rose-500">*</span></label>
                                                    <input type="text" x-model="homeworkTitle" 
                                                        class="journal-input w-full rounded-xl border-violet-200 focus:bg-white focus:border-violet-400 focus:ring-violet-400/30 font-bold text-elevate-dark py-3 px-4 text-sm bg-white transition-all"
                                                        placeholder="Contoh: Latihan Soal Halaman 45-50">
                                                    <input type="hidden" name="homework_title" :value="homeworkTitle">
                                                </div>

                                                {{-- Deskripsi Tugas --}}
                                                <div>
                                                    <label class="block text-[10px] font-bold text-violet-700 uppercase tracking-wider mb-1.5 ml-1">Instruksi / Deskripsi</label>
                                                    <textarea x-model="homeworkDesc" rows="2"
                                                        class="journal-input w-full rounded-xl border-violet-200 focus:bg-white focus:border-violet-400 focus:ring-violet-400/30 text-sm text-elevate-dark py-3 px-4 bg-white transition-all"
                                                        placeholder="Kerjakan soal no 1-10, kumpulkan dalam bentuk foto..."></textarea>
                                                    <input type="hidden" name="homework_description" :value="homeworkDesc">
                                                </div>

                                                {{-- Tipe Tugas --}}
                                                <div>
                                                    <label class="block text-[10px] font-bold text-violet-700 uppercase tracking-wider mb-1.5 ml-1">Tipe Pengumpulan</label>
                                                    <input type="hidden" name="homework_type" :value="homeworkType">
                                                    <div class="flex gap-2">
                                                        <button type="button" @click="homeworkType = 'offline'"
                                                                class="flex-1 py-2.5 rounded-xl text-[10px] font-black flex items-center justify-center gap-1.5 border-2 transition-all"
                                                                :class="homeworkType === 'offline' ? 'bg-slate-700 text-white border-slate-700' : 'bg-white text-slate-500 border-slate-200 hover:border-slate-400'">
                                                            <i class="ph-bold ph-pencil-line"></i> Tatap Muka
                                                        </button>
                                                        <button type="button" @click="homeworkType = 'file_upload'"
                                                                class="flex-1 py-2.5 rounded-xl text-[10px] font-black flex items-center justify-center gap-1.5 border-2 transition-all"
                                                                :class="homeworkType === 'file_upload' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-slate-500 border-slate-200 hover:border-blue-300'">
                                                            <i class="ph-bold ph-upload"></i> Upload File
                                                        </button>
                                                        <button type="button" @click="homeworkType = 'link'"
                                                                class="flex-1 py-2.5 rounded-xl text-[10px] font-black flex items-center justify-center gap-1.5 border-2 transition-all"
                                                                :class="homeworkType === 'link' ? 'bg-emerald-600 text-white border-emerald-600' : 'bg-white text-slate-500 border-slate-200 hover:border-emerald-300'">
                                                            <i class="ph-bold ph-link"></i> Link
                                                        </button>
                                                    </div>
                                                </div>

                                                {{-- URL Link (muncul jika tipe = link) --}}
                                                <div x-show="homeworkType === 'link'" x-transition x-cloak>
                                                    <label class="block text-[10px] font-bold text-emerald-700 uppercase tracking-wider mb-1.5 ml-1">URL Tugas</label>
                                                    <input type="url" x-model="homeworkLinkUrl"
                                                        class="journal-input w-full rounded-xl border-emerald-200 focus:bg-white focus:border-emerald-400 focus:ring-emerald-400/30 text-sm py-3 px-4 bg-white transition-all"
                                                        placeholder="https://...">
                                                    <input type="hidden" name="homework_link_url" :value="homeworkLinkUrl">
                                                </div>

                                                {{-- Deadline --}}
                                                <div>
                                                    <label class="block text-[10px] font-bold text-violet-700 uppercase tracking-wider mb-1.5 ml-1">Deadline Pengumpulan</label>
                                                    <input type="datetime-local" x-model="homeworkDeadline"
                                                        class="journal-input w-full rounded-xl border-violet-200 focus:bg-white focus:border-violet-400 focus:ring-violet-400/30 text-sm py-3 px-4 bg-white transition-all font-bold text-elevate-dark">
                                                    <input type="hidden" name="homework_deadline" :value="homeworkDeadline">
                                                </div>

                                                {{-- Info LMS jika tugas sudah tersinkronisasi --}}
                                                @if($session->lms_assignment_id)
                                                    <div class="flex items-center gap-2 bg-violet-100 rounded-xl px-3 py-2 border border-violet-200">
                                                        <i class="ph-fill ph-check-circle text-violet-600"></i>
                                                        <span class="text-[11px] font-bold text-violet-700">Tersinkronisasi ke LMS</span>
                                                        <a href="{{ route('lms.assignments.submissions', $session->lms_assignment_id) }}" target="_blank"
                                                           class="ml-auto text-[11px] font-black text-violet-600 hover:text-violet-800 underline">
                                                            Lihat di LMS →
                                                        </a>
                                                    </div>
                                                @else
                                                    <p class="text-[10px] text-violet-500 font-medium text-center">
                                                        <i class="ph-bold ph-info"></i> Simpan jurnal untuk otomatis membuat tugas di LMS
                                                    </p>
                                                @endif
                                            </div>

                                            <p x-show="!hasHomework" x-cloak class="text-[10px] text-slate-400 font-medium text-center">Klik tombol di atas jika ada tugas untuk pertemuan ini.</p>
                                        </div>

                                        <div x-data="{ 
                                            photoPreviews: [],
                                            allFiles: [],
                                            handleFileSelect(e) {
                                                if(e.target.files.length === 0) return;
                                                
                                                const newFiles = Array.from(e.target.files);
                                                if (this.allFiles.length + newFiles.length > 5) {
                                                    Swal.fire({
                                                        icon: 'warning',
                                                        title: 'Batas Upload',
                                                        text: 'Maksimal 5 foto dokumentasi.',
                                                        confirmButtonColor: '#3b5889'
                                                    });
                                                    const slotLeft = 5 - this.allFiles.length;
                                                    this.allFiles = [...this.allFiles, ...newFiles.slice(0, slotLeft)];
                                                } else {
                                                    this.allFiles = [...this.allFiles, ...newFiles];
                                                }
                                                
                                                this.updatePreviewsAndInput();
                                            },
                                            removeFile(index) {
                                                this.allFiles.splice(index, 1);
                                                this.updatePreviewsAndInput();
                                            },
                                            updatePreviewsAndInput() {
                                                this.photoPreviews = this.allFiles.map(file => URL.createObjectURL(file));
                                                const dt = new DataTransfer();
                                                this.allFiles.forEach(file => dt.items.add(file));
                                                this.$refs.photoInput.files = dt.files;
                                            }
                                        }">
                                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Foto Dokumentasi (Maksimal 5 Foto)</label>
                                            
                                            {{-- Tampilkan foto-foto lama jika ada --}}
                                            @php
                                                $existingPhotos = [];
                                                if ($session->photo_proof) {
                                                    $decoded = json_decode($session->photo_proof, true);
                                                    if (is_array($decoded)) {
                                                        $existingPhotos = $decoded;
                                                    } else {
                                                        $existingPhotos = [$session->photo_proof];
                                                    }
                                                }
                                            @endphp

                                            @if(count($existingPhotos) > 0)
                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4" x-show="photoPreviews.length === 0">
                                                    @foreach($existingPhotos as $photo)
                                                        <div class="relative group h-32 rounded-2xl overflow-hidden border border-slate-200 shadow-sm">
                                                            <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover">
                                                            <a href="{{ asset('storage/' . $photo) }}" target="_blank" class="absolute inset-0 bg-elevate-dark/60 backdrop-blur-sm flex items-center justify-center opacity-0 group-hover:opacity-100 transition duration-300 text-white font-bold text-sm gap-2">
                                                                <i class="ph-bold ph-eye text-xl"></i> Lihat
                                                            </a>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif

                                            {{-- Tampilkan pratinjau foto baru --}}
                                            <div class="grid grid-cols-2 md:grid-cols-3 gap-3 mb-4" x-show="photoPreviews.length > 0" x-cloak>
                                                <template x-for="(preview, index) in photoPreviews" :key="index">
                                                    <div class="relative h-32 rounded-2xl overflow-hidden border border-elevate-accent/50 shadow-sm bg-elevate-primary/10">
                                                        <img :src="preview" class="w-full h-full object-cover">
                                                        <button type="button" @click.prevent="removeFile(index)" class="absolute top-1 right-1 bg-rose-500/90 backdrop-blur-sm text-white w-7 h-7 rounded-full flex items-center justify-center hover:bg-rose-600 transition-colors shadow-md border border-rose-400 z-10">
                                                            <i class="ph-bold ph-x text-sm"></i>
                                                        </button>
                                                        <div class="absolute bottom-0 left-0 right-0 bg-elevate-primary/90 text-white text-[10px] font-bold py-1.5 text-center backdrop-blur-sm">Foto <span x-text="index + 1"></span></div>
                                                    </div>
                                                </template>
                                            </div>

                                            @if($isOpen)
                                                <label x-show="allFiles.length < 5" class="flex flex-col items-center justify-center w-full h-24 border-2 border-dashed border-slate-300 rounded-2xl cursor-pointer hover:bg-elevate-peach-light/20 hover:border-elevate-peach transition-all group/upload bg-elevate-soft/50 mb-2">
                                                    <div class="flex flex-col items-center justify-center pt-2">
                                                        <div class="flex gap-2">
                                                            <i class="ph-duotone ph-camera text-3xl text-slate-400 group-hover/upload:text-elevate-peach-dark mb-1 transition-colors"></i>
                                                            <i class="ph-duotone ph-images text-3xl text-slate-400 group-hover/upload:text-elevate-peach-dark mb-1 transition-colors"></i>
                                                        </div>
                                                        <p class="text-[10px] font-bold uppercase tracking-wider text-slate-500 group-hover/upload:text-elevate-peach-dark transition-colors mt-1">Jepret Kamera / Pilih Foto</p>
                                                    </div>
                                                    <input type="file" x-ref="photoInput" name="photo_proof[]" accept="image/*" multiple capture="environment" class="hidden" 
                                                           @change="handleFileSelect($event)" />
                                                </label>
                                                <p x-show="allFiles.length >= 5" x-cloak class="text-[10px] text-center text-amber-600 font-bold bg-amber-100 p-2 rounded-lg border border-amber-200">
                                                    Batas maksimal 5 foto telah tercapai.
                                                </p>
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
                                {{-- Perubahan: Gunakan flex-wrap agar tombol otomatis turun ke bawah jika sidebar lebar --}}
                                <div class="flex flex-col xl:flex-row items-start xl:items-center justify-between gap-4 w-full">
                                    
                                    {{-- Container filter --}}
                                    <div class="flex flex-wrap items-center gap-2 py-1 w-full">
                                        <button @click="filterTab = 'all'" 
                                                :class="filterTab === 'all' ? 'bg-gradient-to-r from-[#0d52a1] to-sky-600 text-white border-sky-400/40 shadow-lg' : 'bg-slate-900/80 text-slate-400 border-white/10 hover:bg-slate-800 hover:text-white'" 
                                                class="shrink-0 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border">
                                            Semua Siswa
                                        </button>
                                        
                                        <button @click="filterTab = 'unmarked'" 
                                                :class="filterTab === 'unmarked' ? 'bg-slate-700 text-white border-white/20 shadow-lg' : 'bg-slate-900/80 text-slate-400 border-white/10 hover:bg-slate-800 hover:text-white'" 
                                                class="shrink-0 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border flex items-center gap-2">
                                            <span class="w-2 h-2 rounded-full" :class="filterTab === 'unmarked' ? 'bg-white' : 'bg-slate-500'"></span> Belum Absen
                                        </button>
                                        
                                        <button @click="filterTab = 'present'" 
                                                :class="filterTab === 'present' ? 'bg-emerald-600 text-white border-emerald-400/40 shadow-lg' : 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30 hover:bg-emerald-500/20'" 
                                                class="shrink-0 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border flex items-center gap-2">
                                            <i class="ph-bold ph-check"></i> Hadir
                                        </button>
                                        
                                        <button @click="filterTab = 'alpha'" 
                                                :class="filterTab === 'alpha' ? 'bg-rose-600 text-white border-rose-400/40 shadow-lg' : 'bg-rose-500/10 text-rose-400 border-rose-500/30 hover:bg-rose-500/20'" 
                                                class="shrink-0 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-wider whitespace-nowrap transition-all border flex items-center gap-2">
                                            <i class="ph-bold ph-x"></i> Alpha
                                        </button>
                                    </div>
                                    
                                    @if($isOpen)
                                        {{-- Tombol Bulk Alpha diletakkan di samping/bawah filter --}}
                                        <button @click="markRestAsAlpha()" type="button" 
                                                class="shrink-0 w-full xl:w-auto px-4 py-2 bg-rose-500/10 text-rose-300 hover:bg-rose-500/20 font-bold rounded-xl text-xs flex items-center justify-center gap-2 border border-rose-500/30 transition-colors shadow-sm active:scale-95 whitespace-nowrap">
                                            <i class="ph-bold ph-users-three"></i> Tandai Sisanya Alpha
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- 5. LIST SISWA --}}
                        <div class="flex-1 p-5 md:p-8 pb-32 md:pb-40 bg-white overflow-y-auto max-h-[800px] custom-scrollbar">
                            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-1 2xl:grid-cols-2 gap-3 sm:gap-4">
                                @foreach($allStudents as $student)
                                    @php
                                        $att = $attendances[$student->id] ?? null;
                                        $initialStatus = $att ? $att->status : null; 
                                        $initials = Str::upper(Str::substr(trim($student->name), 0, 1));
                                    @endphp

                                    <div class="border-2 rounded-2xl p-3 sm:p-4 flex items-center gap-3 sm:gap-4 transition-all duration-300" 
                                         id="student-row-{{ $student->id }}"
                                         x-data="{ name: '{{ strtolower($student->name) }}', id: '{{ $student->student_id }}', status: '{{ $initialStatus }}', open: false }"
                                         @update-status-{{ $student->id }}.window="status = $event.detail.status"
                                         {{-- TAMBAHAN: Logika filter dikombinasikan dengan pencarian --}}
                                         x-show="(name.includes(searchQuery.toLowerCase()) || id.includes(searchQuery.toLowerCase())) &&
                                                 (filterTab === 'all' ||
                                                 (filterTab === 'unmarked' && !status) ||
                                                 (filterTab === 'present' && ['Hadir', 'present', 'Terlambat', 'late', 'terlambat'].includes(status)) ||
                                                 (filterTab === 'sick' && ['Sakit', 'sick', 'sakit'].includes(status)) ||
                                                 (filterTab === 'permission' && ['Izin', 'permission', 'izin'].includes(status)) ||
                                                 (filterTab === 'alpha' && ['Alfa', 'alpha', 'Alpa', 'alfa'].includes(status)))"
                                         :class="{
                                            'z-40 relative': open,
                                            'z-0 relative': !open,
                                            'bg-[#DFF6DD]/20 border-[#B7DFB9]': ['Hadir', 'present', 'Terlambat', 'late', 'terlambat'].includes(status),
                                            'bg-elevate-soft/40 border-slate-200': ['Sakit', 'sick', 'sakit'].includes(status),
                                            'bg-[#FFEFD6]/20 border-[#FFD8A8]': ['Izin', 'permission', 'izin'].includes(status),
                                            'bg-[#FDE7E9]/20 border-[#F4C3C9]': ['Alfa', 'alpha', 'Alpa', 'alfa'].includes(status),
                                            'bg-white border-slate-100 hover:border-slate-300 shadow-sm': !status
                                         }">
                                        
                                        {{-- Avatar Status --}}
                                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl flex items-center justify-center font-black text-sm shrink-0 shadow-sm transition-all border"
                                             :class="{ 
                                                 'bg-[#107C10] text-white border-[#107C10]': ['Hadir', 'present', 'Terlambat', 'late', 'terlambat'].includes(status),
                                                 'bg-elevate-primary text-white border-elevate-primary': ['Sakit', 'sick', 'sakit'].includes(status),
                                                 'bg-[#D83B01] text-white border-[#D83B01]': ['Izin', 'permission', 'izin'].includes(status),
                                                 'bg-[#D13438] text-white border-[#D13438]': ['Alfa', 'alpha', 'Alpa', 'alfa'].includes(status),
                                                 'bg-slate-100 text-slate-400 border-slate-200': !status 
                                             }">
                                             <template x-if="['Hadir', 'present', 'Terlambat', 'late', 'terlambat'].includes(status)"> <i class="ph-bold ph-check text-lg sm:text-xl"></i> </template>
                                             <template x-if="['Sakit', 'sick', 'sakit'].includes(status)"> <span>S</span> </template>
                                             <template x-if="['Izin', 'permission', 'izin'].includes(status)"> <span>I</span> </template>
                                             <template x-if="['Alfa', 'alpha', 'Alpa', 'alfa'].includes(status)"> <span>A</span> </template>
                                             <template x-if="!status"> <span>{{ $initials }}</span> </template>
                                        </div>

                                        <div class="flex-1 min-w-0 pr-1">
                                            <p class="font-black text-elevate-dark text-sm sm:text-base leading-snug line-clamp-2" title="{{ $student->name }}">{{ $student->name }}</p>
                                            <p class="text-[10px] sm:text-xs text-slate-500 font-bold tracking-wide mt-1">{{ $student->student_id }}</p>
                                        </div>

                                        @if($isOpen)
                                            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
                                                {{-- Tombol Hadir Cepat --}}
                                                <button @click="setManual({{ $student->id }}, ['Hadir', 'present', 'Terlambat', 'late', 'terlambat'].includes(status) ? null : 'present')" 
                                                        class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-95 border border-transparent"
                                                        :class="['Hadir', 'present', 'Terlambat', 'late', 'terlambat'].includes(status) ? 'bg-[#107C10] text-white' : 'bg-slate-100 text-slate-400 hover:bg-[#DFF6DD] hover:text-[#107C10]'">
                                                    <i class="ph-bold ph-check text-base sm:text-lg"></i>
                                                </button>
                                                
                                                {{-- Dropdown Pilihan --}}
                                                <div class="relative">
                                                    <button @click="open = !open" @click.outside="open = false"
                                                            class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl flex items-center justify-center transition-all shadow-sm active:scale-95 border border-transparent"
                                                            :class="['sick', 'permission', 'alpha', 'Sakit', 'Izin', 'Alfa', 'sakit', 'izin', 'alfa', 'alpa'].includes(status) ? 'bg-elevate-dark text-white' : 'bg-slate-100 text-slate-400 hover:bg-elevate-dark hover:text-white'">
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
                
                // Status Penyelesaian Materi
                materialStatus: {!! json_encode($session->material_status ?? '') !!} || null,
                materialCoverage: {!! json_encode($session->material_coverage ?? '') !!},

                // Tugas / PR
                hasHomework: {{ $session->homework_title ? 'true' : 'false' }},
                homeworkTitle: {!! json_encode($session->homework_title ?? '') !!},
                homeworkDesc: {!! json_encode($session->homework_description ?? '') !!},
                homeworkType: {!! json_encode($session->homework_type ?? 'offline') !!},
                homeworkLinkUrl: {!! json_encode($session->homework_link_url ?? '') !!},
                homeworkDeadline: '{{ $session->homework_deadline ? \Carbon\Carbon::parse($session->homework_deadline)->format("Y-m-d\TH:i") : '' }}',

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
                        let rowEl = document.getElementById('student-row-' + studentId);
                        let oldStatus = null;
                        if(rowEl) {
                            let alpineEl = Alpine.$data(rowEl);
                            if(alpineEl) oldStatus = alpineEl.status;
                        }

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
                            
                            this.updateLocalStats(oldStatus, newStatus);

                            playBeep('success');
                            
                            // Map ke bahasa Indonesia agar sama dengan database
                            const statusMap = { 'present': 'HADIR', 'sick': 'SAKIT', 'permission': 'IZIN', 'alpha': 'ALFA', 'Hadir': 'HADIR', 'Sakit': 'SAKIT', 'Izin': 'IZIN', 'Alfa': 'ALFA', 'Terlambat': 'TERLAMBAT', 'late': 'TERLAMBAT' };
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
                    if (this.html5QrcodeScanner) {
                        try {
                            this.html5QrcodeScanner.stop().catch(() => {});
                        } catch(e) {}
                    }
                    
                    this.html5QrcodeScanner = new Html5Qrcode("reader");
                    const config = { 
                        fps: 5,
                        qrbox: { width: 200, height: 200 },
                        experimentalFeatures: {
                            useBarCodeDetectorIfSupported: true
                        }
                    };

                    // Aktifkan continuous autofocus agar kamera menyesuaikan jarak secara dinamis
                    const lockCameraFocus = () => {
                        setTimeout(() => {
                            const videoEl = document.querySelector('#reader video');
                            if (!videoEl) return;
                            const stream = videoEl.srcObject;
                            if (!stream) return;
                            const track = stream.getVideoTracks()[0];
                            if (!track) return;
                            
                            const capabilities = track.getCapabilities ? track.getCapabilities() : {};

                            if (capabilities.focusMode && capabilities.focusMode.includes('continuous')) {
                                track.applyConstraints({ advanced: [{ focusMode: 'continuous' }] }).catch(() => {});
                            }
                        }, 1500);
                    };
                    
                    const onScanSuccess = (decodedText) => { 
                        if (!this.loading) { 
                            this.rfidCode = decodedText; 
                            this.submitScan();
                            this.loading = true;
                            setTimeout(() => { this.loading = false; }, 2000);
                        } 
                    };

                    // Coba kamera belakang (Mobile / HP)
                    this.html5QrcodeScanner.start(
                        { facingMode: "environment" }, 
                        config,
                        onScanSuccess,
                        (errorMessage) => { }
                    ).then(() => {
                        lockCameraFocus();
                    }).catch(err => {
                        // Fallback ke kamera depan / Webcam PC
                        this.html5QrcodeScanner.start(
                            { facingMode: "user" }, 
                            config,
                            onScanSuccess,
                            (errorMessage) => { }
                        ).then(() => {
                            lockCameraFocus();
                        }).catch(e => {
                            this.statusMessage = "Error Kamera: Izin ditolak atau kamera tidak ditemukan.";
                            Swal.fire({
                                title: 'Kamera Error', 
                                text: 'Pastikan Anda menggunakan HTTPS dan memberikan izin kamera.', 
                                icon: 'error',
                                customClass: { popup: 'rounded-[2rem] shadow-2xl' }
                            });
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