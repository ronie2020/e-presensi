{{-- 
    KOMPONEN WIDGET BUTUH BANTUAN SISWA (SIMADU CARE)
    Dapat disematkan di layout publik, portal siswa, login CBT, dan login LMS.
--}}
<div x-data="studentHelpWidget()" x-cloak class="relative z-[9999]" id="student-help-widget-root">

    {{-- 1. FLOATING BUTTON & SPEECH BUBBLE (PERSIS REFERENSI USER) --}}
    <div class="fixed bottom-6 right-4 sm:bottom-8 sm:right-6 z-[9999] flex items-center select-none group"
         :class="{ 'opacity-90 hover:opacity-100': !isOpen }">

        {{-- BALON KATA "Butuh bantuan?" --}}
        <div x-show="bubbleVisible && !isOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4 scale-95"
             x-transition:enter-end="opacity-100 translate-x-0 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-x-0 scale-100"
             x-transition:leave-end="opacity-0 translate-x-4 scale-95"
             @click="openHelpModal('nisn')"
             class="mr-2 sm:mr-3 relative cursor-pointer filter drop-shadow-xl animate-float-gentle">
            
            <div class="bg-white text-slate-900 rounded-2xl px-4 py-2.5 sm:px-5 sm:py-3 border-2 border-sky-400/30 flex items-center gap-2.5 shadow-2xl relative">
                
                {{-- Tombol Toggle Oranye (Panah Chevron <) --}}
                <button type="button" 
                        @click.stop="bubbleVisible = false" 
                        title="Sembunyikan pesan"
                        class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-400 hover:to-orange-400 text-white flex items-center justify-center text-[10px] sm:text-xs font-black shadow-md transition-transform hover:scale-110 cursor-pointer">
                    <i class="ph-bold ph-caret-right text-xs"></i>
                </button>

                {{-- Teks Balon --}}
                <div class="leading-tight">
                    <span class="block text-[11px] sm:text-xs font-black text-[#032b5b] tracking-tight">Butuh bantuan?</span>
                    <span class="text-[9px] sm:text-[10px] font-semibold text-slate-500 block">Klik di sini &bull; Cek NISN / Bantuan</span>
                </div>

                {{-- Segitiga Balon (Speech Bubble Arrow) Mengarah ke Avatar --}}
                <div class="absolute -right-2 top-1/2 -translate-y-1/2 w-0 h-0 border-y-8 border-y-transparent border-l-8 border-l-white"></div>
            </div>
        </div>

        {{-- AVATAR OPERATOR DOCK (TOMBOL BULAT MELAYANG) --}}
        <div class="relative cursor-pointer" @click="toggleHelpModal()">
            
            {{-- Glowing Pulse Ring --}}
            <div class="absolute inset-0 rounded-full bg-sky-400/30 blur-md animate-ping pointer-events-none"></div>
            
            {{-- Tombol Avatar Utama --}}
            <button type="button"
                    class="relative w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-gradient-to-tr from-[#032b5b] via-[#0d52a1] to-[#38bdf8] p-0.5 shadow-2xl transition-all duration-300 hover:scale-105 active:scale-95 border-2 border-white/60 flex items-center justify-center overflow-hidden focus:outline-none">
                
                {{-- Foto Avatar Helpdesk --}}
                <img src="{{ asset('img/helpdesk-operator.png') }}" 
                     alt="Bantuan Siswa" 
                     class="w-full h-full object-cover rounded-full"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                
                {{-- Fallback Icon jika gambar belum terload --}}
                <div class="w-full h-full rounded-full bg-[#032b5b] hidden items-center justify-center text-white">
                    <i class="ph-bold ph-headset text-2xl text-sky-300"></i>
                </div>
            </button>

            {{-- Tombol Kecil Biru Double Arrow (>>) di Sudut Kanan Bawah --}}
            <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-blue-600 border-2 border-white text-white flex items-center justify-center shadow-md">
                <i class="ph-bold ph-chats text-xs" :class="isOpen ? 'ph-x' : 'ph-chats'"></i>
            </div>

            {{-- Status Online Indicator (Green Dot) --}}
            <span class="absolute top-0 right-0 flex h-3.5 w-3.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3.5 w-3.5 bg-emerald-500 border-2 border-white"></span>
            </span>
        </div>

        {{-- Tombol Mini untuk Munculkan kembali Balon jika ditutup --}}
        <button type="button" 
                x-show="!bubbleVisible && !isOpen" 
                @click="bubbleVisible = true"
                class="ml-1 w-5 h-5 rounded-full bg-white/20 hover:bg-white/40 text-white flex items-center justify-center text-[10px] transition cursor-pointer"
                title="Buka pesan bantuan">
            <i class="ph-bold ph-caret-left"></i>
        </button>
    </div>

    {{-- 2. MODAL DRAWER SLIDE-OVER (ELEVATE DARK GLASSMORPHISM) --}}
    <div x-show="isOpen" 
         style="display: none;"
         class="fixed inset-0 z-[10000] overflow-hidden" 
         aria-labelledby="slide-over-title" 
         role="dialog" 
         aria-modal="true">
        
        {{-- Backdrop Blur Overlay --}}
        <div class="fixed inset-0 bg-[#021124]/80 backdrop-blur-md transition-opacity"
             x-show="isOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="isOpen = false"></div>

        <div class="fixed inset-y-0 right-0 max-w-full flex pl-6 sm:pl-10">
            <div class="w-screen max-w-lg"
                 x-show="isOpen"
                 x-transition:enter="transform transition ease-out duration-300 sm:duration-400"
                 x-transition:enter-start="translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transform transition ease-in duration-250"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="translate-x-full">
                
                {{-- Panel Konten Bantuan --}}
                <div class="h-full flex flex-col bg-[#031d3d] border-l border-white/15 text-white shadow-2xl relative overflow-hidden">
                    
                    {{-- Latar Dekorasi Gradient --}}
                    <div class="absolute top-0 right-0 w-72 h-72 bg-sky-500/10 rounded-full blur-[100px] pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-72 h-72 bg-indigo-500/10 rounded-full blur-[100px] pointer-events-none"></div>

                    {{-- HEADER DRAWER --}}
                    <div class="p-5 sm:p-6 bg-[#021124]/90 border-b border-white/10 relative z-10">
                        <div class="flex items-start justify-between gap-3">
                            <div class="flex items-center gap-3.5">
                                <div class="relative w-12 h-12 rounded-2xl bg-gradient-to-tr from-sky-400 to-blue-600 p-0.5 shrink-0 shadow-lg shadow-sky-500/20">
                                    <img src="{{ asset('img/helpdesk-operator.png') }}" alt="Operator" class="w-full h-full object-cover rounded-[14px]">
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3.5 h-3.5 rounded-full bg-emerald-500 border-2 border-[#021124]"></span>
                                </div>
                                <div>
                                    <h3 class="font-black text-lg text-white leading-tight flex items-center gap-2">
                                        Pusat Bantuan Siswa
                                    </h3>
                                    <p class="text-xs text-sky-300 font-semibold mt-0.5">SIMADU Care &bull; SMPN 3 Lakbok</p>
                                </div>
                            </div>

                            <button type="button" 
                                    @click="isOpen = false"
                                    class="p-2 rounded-xl bg-white/5 text-slate-400 hover:text-white hover:bg-white/10 transition cursor-pointer">
                                <i class="ph-bold ph-x text-xl"></i>
                            </button>
                        </div>

                        {{-- NAVIGATION TABS --}}
                        <div class="flex items-center gap-1.5 mt-5 bg-black/40 p-1.5 rounded-xl border border-white/10 overflow-x-auto custom-scrollbar">
                            <button type="button" 
                                    @click="activeTab = 'nisn'"
                                    :class="activeTab === 'nisn' ? 'bg-sky-500 text-white font-black shadow-md' : 'text-slate-300 hover:text-white font-bold'"
                                    class="flex-1 py-2 px-3 rounded-lg text-xs transition-all whitespace-nowrap flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-magnifying-glass"></i>
                                <span>Cari NISN</span>
                            </button>
                            <button type="button" 
                                    @click="activeTab = 'cbt'"
                                    :class="activeTab === 'cbt' ? 'bg-sky-500 text-white font-black shadow-md' : 'text-slate-300 hover:text-white font-bold'"
                                    class="flex-1 py-2 px-3 rounded-lg text-xs transition-all whitespace-nowrap flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-monitor-play"></i>
                                <span>Kendala CBT</span>
                            </button>
                            <button type="button" 
                                    @click="activeTab = 'kontak'"
                                    :class="activeTab === 'kontak' ? 'bg-sky-500 text-white font-black shadow-md' : 'text-slate-300 hover:text-white font-bold'"
                                    class="flex-1 py-2 px-3 rounded-lg text-xs transition-all whitespace-nowrap flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-whatsapp-logo"></i>
                                <span>WhatsApp</span>
                            </button>
                            <button type="button" 
                                    @click="activeTab = 'faq'"
                                    :class="activeTab === 'faq' ? 'bg-sky-500 text-white font-black shadow-md' : 'text-slate-300 hover:text-white font-bold'"
                                    class="flex-1 py-2 px-3 rounded-lg text-xs transition-all whitespace-nowrap flex items-center justify-center gap-1.5 cursor-pointer">
                                <i class="ph-bold ph-question"></i>
                                <span>FAQ</span>
                            </button>
                        </div>
                    </div>

                    {{-- BODY CONTENT DRAWER --}}
                    <div class="flex-1 overflow-y-auto p-5 sm:p-6 space-y-6 custom-scrollbar relative z-10 text-sm">

                        {{-- ======================================================== --}}
                        {{-- TAB 1: PENCARIAN NISN MANDIRI                            --}}
                        {{-- ======================================================== --}}
                        <div x-show="activeTab === 'nisn'" class="space-y-4">
                            <div class="p-4 rounded-2xl bg-sky-500/10 border border-sky-400/30 flex items-start gap-3">
                                <i class="ph-bold ph-info text-xl text-sky-400 shrink-0 mt-0.5"></i>
                                <div class="text-xs text-slate-200 leading-relaxed">
                                    <span class="font-bold text-white block mb-0.5">Lupa nomor NISN untuk masuk?</span>
                                    Ketik nama lengkap atau nama panggilan Anda di bawah untuk menemukan NISN Anda dengan cepat.
                                </div>
                            </div>

                            {{-- Input Search & Filter Kelas --}}
                            <div class="space-y-3">
                                <div class="relative">
                                    <i class="ph-bold ph-user absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                                    <input type="text" 
                                           x-model="searchQuery" 
                                           @input.debounce.300ms="searchStudents()"
                                           placeholder="Ketik nama Anda (min. 2 huruf)..."
                                           class="w-full pl-10 pr-10 py-3 rounded-xl bg-[#021124] border border-white/20 text-white font-bold text-xs focus:border-sky-400 focus:ring-1 focus:ring-sky-400 outline-none placeholder:text-slate-500">
                                    <button type="button" 
                                            x-show="searchQuery.length > 0" 
                                            @click="searchQuery = ''; searchResults = []"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-white">
                                        <i class="ph-bold ph-x-circle text-base"></i>
                                    </button>
                                </div>

                                <div class="flex items-center gap-2">
                                    <label class="text-[11px] font-bold text-slate-400 whitespace-nowrap">Filter Kelas:</label>
                                    <select x-model="selectedClassId" 
                                            @change="searchStudents()"
                                            class="w-full px-3 py-2 rounded-xl bg-[#021124] border border-white/20 text-white font-semibold text-xs focus:border-sky-400 outline-none">
                                        <option value="">Semua Kelas</option>
                                        <template x-for="c in classList" :key="c.id">
                                            <option :value="c.id" x-text="c.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            {{-- Loading Indicator --}}
                            <div x-show="isSearching" class="text-center py-6 text-slate-400 text-xs flex items-center justify-center gap-2">
                                <i class="ph-bold ph-spinner animate-spin text-lg text-sky-400"></i>
                                <span>Mencari data siswa...</span>
                            </div>

                            {{-- Hasil Pencarian Siswa --}}
                            <div x-show="!isSearching && searchResults.length > 0" class="space-y-2.5">
                                <div class="flex items-center justify-between text-[11px] font-bold text-slate-400 px-1">
                                    <span>Hasil Ditemukan (<span x-text="searchResults.length"></span> siswa):</span>
                                    <span class="text-sky-400">Klik 'Gunakan' untuk login</span>
                                </div>

                                <div class="space-y-2 max-h-72 overflow-y-auto pr-1 custom-scrollbar">
                                    <template x-for="student in searchResults" :key="student.id">
                                        <div class="p-3.5 rounded-xl bg-[#021124]/90 border border-white/10 hover:border-sky-400/40 transition-all flex items-center justify-between gap-3 group">
                                            <div class="min-w-0">
                                                <div class="font-bold text-white text-xs sm:text-sm truncate group-hover:text-sky-300 transition-colors" x-text="student.name"></div>
                                                <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-400">
                                                    <span class="px-2 py-0.5 rounded-md bg-white/10 font-semibold" x-text="student.class_name"></span>
                                                    <span>NISN: <strong class="text-amber-400 font-mono tracking-wider" x-text="student.nisn"></strong></span>
                                                </div>
                                            </div>

                                            <button type="button" 
                                                    @click="useNisn(student.nisn)"
                                                    class="px-3 py-1.5 rounded-lg bg-sky-500/20 hover:bg-sky-500 text-sky-300 hover:text-white border border-sky-400/30 text-xs font-bold transition-all shrink-0 flex items-center gap-1 active:scale-95 cursor-pointer"
                                                    title="Gunakan NISN ini">
                                                <i class="ph-bold ph-copy"></i>
                                                <span>Gunakan</span>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- State Belum Mencari / Tidak Ditemukan --}}
                            <div x-show="!isSearching && searchQuery.length >= 2 && searchResults.length === 0" 
                                 class="text-center py-8 p-4 rounded-2xl bg-white/[0.02] border border-white/10 text-slate-400">
                                <i class="ph-bold ph-magnifying-glass text-3xl text-slate-500 mb-2 block"></i>
                                <p class="text-xs font-bold text-white">Nama tidak ditemukan</p>
                                <p class="text-[11px] text-slate-400 mt-1">Coba gunakan nama panggilan atau hubungi wali kelas Anda melalui WhatsApp.</p>
                            </div>

                            <div x-show="!isSearching && searchQuery.length < 2 && searchResults.length === 0"
                                 class="text-center py-6 text-slate-500 text-xs">
                                Ketik nama Anda di kolom pencarian di atas.
                            </div>
                        </div>

                        {{-- ======================================================== --}}
                        {{-- TAB 2: KENDALA UJIAN CBT & LOGIN                         --}}
                        {{-- ======================================================== --}}
                        <div x-show="activeTab === 'cbt'" class="space-y-4">
                            <h4 class="text-xs font-black uppercase tracking-wider text-amber-400 flex items-center gap-1.5">
                                <i class="ph-bold ph-warning-circle text-base"></i> Solusi Cepat Masalah Ujian CBT
                            </h4>

                            {{-- Accordion Kendala 1: Token Ujian --}}
                            <div class="p-4 rounded-2xl bg-[#021124]/90 border border-white/10 space-y-2">
                                <div class="font-bold text-white text-xs flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-md bg-amber-500/20 text-amber-400 flex items-center justify-center text-[10px]">1</span>
                                    <span>Token Ujian Dikatakan Salah / Tidak Valid</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed pl-7">
                                    Token ujian bersifat dinamis dan diperbarui oleh Proktor setiap sesi ujian. 
                                    Pastikan waktu ujian Anda sudah dimulai, atau minta token terkini langsung ke pengawas/proktor lab.
                                </p>
                            </div>

                            {{-- Accordion Kendala 2: Sesi Terkunci / Force Close --}}
                            <div class="p-4 rounded-2xl bg-[#021124]/90 border border-white/10 space-y-2">
                                <div class="font-bold text-white text-xs flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-md bg-rose-500/20 text-rose-400 flex items-center justify-center text-[10px]">2</span>
                                    <span>Sesi Ujian Terkunci / Browser Tertutup</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed pl-7">
                                    Jika browser Anda tidak sengaja tertutup atau perangkat mati mendadak, Anda perlu meminta Proktor untuk melakukan <strong>Reset Sesi Ujian</strong> agar dapat login kembali.
                                </p>
                                <div class="pl-7 pt-1">
                                    <a :href="'https://wa.me/6285135961994?text=' + encodeURIComponent('Halo Proktor CBT SMPN 3 Lakbok, saya membutuhkan reset sesi ujian CBT karena kendala teknis.')" 
                                       target="_blank"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500/20 hover:bg-emerald-500 text-emerald-300 hover:text-white border border-emerald-400/30 text-xs font-bold transition active:scale-95">
                                        <i class="ph-bold ph-whatsapp-logo text-sm"></i> Minta Reset Sesi via WhatsApp
                                    </a>
                                </div>
                            </div>

                            {{-- Accordion Kendala 3: Login Siswa --}}
                            <div class="p-4 rounded-2xl bg-[#021124]/90 border border-white/10 space-y-2">
                                <div class="font-bold text-white text-xs flex items-center gap-2">
                                    <span class="w-5 h-5 rounded-md bg-sky-500/20 text-sky-400 flex items-center justify-center text-[10px]">3</span>
                                    <span>Apakah Siswa Butuh Kata Sandi?</span>
                                </div>
                                <p class="text-xs text-slate-300 leading-relaxed pl-7">
                                    <strong>Tidak</strong>. Siswa cukup menggunakan nomor <strong>NISN</strong> (10 digit) atau nomor NIS untuk masuk ke Portal, Ujian CBT, maupun Ruang Belajar LMS.
                                </p>
                            </div>

                            {{-- Pintasan Halaman Ujian --}}
                            <div class="pt-2 flex gap-2">
                                <a href="{{ route('student.login.cbt') }}" class="flex-1 py-2.5 px-3 rounded-xl bg-gradient-to-r from-rose-500 to-red-600 text-white font-bold text-xs text-center shadow-lg transition active:scale-95 flex items-center justify-center gap-1.5">
                                    <i class="ph-bold ph-monitor-play"></i> Buka Halaman CBT
                                </a>
                                <a href="{{ route('portal.index') }}" class="flex-1 py-2.5 px-3 rounded-xl bg-white/10 hover:bg-white/20 text-white font-bold text-xs text-center border border-white/15 transition active:scale-95 flex items-center justify-center gap-1.5">
                                    <i class="ph-bold ph-student"></i> Portal Siswa
                                </a>
                            </div>
                        </div>

                        {{-- ======================================================== --}}
                        {{-- TAB 3: KONTAK BANTUAN RESMI (WHATSAPP)                   --}}
                        {{-- ======================================================== --}}
                        <div x-show="activeTab === 'kontak'" class="space-y-4">
                            <p class="text-xs text-slate-300 leading-relaxed">
                                Butuh bantuan darurat atau konfirmasi langsung? Hubungi saluran resmi sekolah di bawah ini:
                            </p>

                            {{-- Kontak 1: Operator Utama Sekolah --}}
                            <div class="p-4 rounded-2xl bg-[#021124]/90 border border-white/15 space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-400/30 flex items-center justify-center text-xl shrink-0">
                                        <i class="ph-bold ph-whatsapp-logo"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-white text-xs sm:text-sm">Helpdesk Operator Sekolah</h5>
                                        <p class="text-[11px] text-slate-400">Kendala Akun, Presensi, Data Siswa</p>
                                    </div>
                                </div>
                                <a :href="'https://wa.me/6285135961994?text=' + encodeURIComponent('Halo Admin/Operator SMPN 3 Lakbok, saya siswa membutuhkan bantuan terkait...')" 
                                   target="_blank"
                                   class="w-full py-2.5 px-4 rounded-xl bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-400 hover:to-green-500 text-white font-black text-xs flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20 transition active:scale-98">
                                    <i class="ph-bold ph-chat-circle-dots text-base"></i>
                                    <span>Chat WhatsApp Operator (+62 851-3596-1994)</span>
                                </a>
                            </div>

                            {{-- Kontak 2: Bimbingan Konseling (BK) --}}
                            <div class="p-4 rounded-2xl bg-[#021124]/90 border border-white/15 space-y-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 text-purple-400 border border-purple-400/30 flex items-center justify-center text-xl shrink-0">
                                        <i class="ph-bold ph-heart-handshake"></i>
                                    </div>
                                    <div>
                                        <h5 class="font-bold text-white text-xs sm:text-sm">Layanan Guru BK (Bimbingan Konseling)</h5>
                                        <p class="text-[11px] text-slate-400">Konsultasi Privat, Belajar, & Pengaduan Aman</p>
                                    </div>
                                </div>
                                <a :href="'https://wa.me/6285135961994?text=' + encodeURIComponent('Halo Guru BK SMPN 3 Lakbok, saya ingin berkonsultasi mengenai...')" 
                                   target="_blank"
                                   class="w-full py-2.5 px-4 rounded-xl bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 border border-purple-400/40 font-bold text-xs flex items-center justify-center gap-2 transition active:scale-98">
                                    <i class="ph-bold ph-lock-simple text-base"></i>
                                    <span>Konsultasi Privat Guru BK</span>
                                </a>
                            </div>

                            {{-- Jam Operasional --}}
                            <div class="p-3 rounded-xl bg-white/5 border border-white/10 text-[11px] text-slate-400 flex items-center gap-2">
                                <i class="ph-bold ph-clock text-amber-400 text-sm"></i>
                                <span>Layanan aktif: Senin - Sabtu, Pukul 07.00 - 15.30 WIB</span>
                            </div>
                        </div>

                        {{-- ======================================================== --}}
                        {{-- TAB 4: FAQ TANYA JAWAB UMUM                              --}}
                        {{-- ======================================================== --}}
                        <div x-show="activeTab === 'faq'" class="space-y-3">
                            <template x-for="(item, idx) in faqList" :key="idx">
                                <div class="p-3.5 rounded-xl bg-[#021124]/90 border border-white/10 space-y-2 cursor-pointer transition-all"
                                     @click="item.open = !item.open">
                                    <div class="flex items-center justify-between gap-2">
                                        <span class="font-bold text-white text-xs" x-text="item.q"></span>
                                        <i class="ph-bold text-slate-400 text-xs transition-transform" 
                                           :class="item.open ? 'ph-caret-up text-sky-400' : 'ph-caret-down'"></i>
                                    </div>
                                    <div x-show="item.open" x-collapse class="text-xs text-slate-300 leading-relaxed pt-1 border-t border-white/5">
                                        <p x-text="item.a"></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                    </div>

                    {{-- FOOTER DRAWER --}}
                    <div class="p-4 bg-[#021124]/90 border-t border-white/10 text-center text-[11px] text-slate-400 relative z-10 flex items-center justify-between">
                        <span>&copy; {{ date('Y') }} SMP Negeri 3 Lakbok</span>
                        <span class="inline-flex items-center gap-1.5 text-emerald-400 font-bold">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Layanan Aktif
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div x-show="toastMessage" 
         style="display: none;"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 translate-y-4"
         class="fixed bottom-24 right-6 z-[10001] px-4 py-2.5 rounded-xl bg-emerald-500 text-white font-bold text-xs shadow-2xl flex items-center gap-2">
        <i class="ph-bold ph-check-circle text-base"></i>
        <span x-text="toastMessage"></span>
    </div>

</div>

{{-- JAVASCRIPT LOGIC WIDGET (ALPINE.JS COMPATIBLE) --}}
<script>
    function studentHelpWidget() {
        return {
            isOpen: false,
            bubbleVisible: true,
            activeTab: 'nisn',
            searchQuery: '',
            selectedClassId: '',
            isSearching: false,
            searchResults: [],
            classList: [],
            toastMessage: '',

            faqList: [
                {
                    q: 'Bagaimana cara masuk jika saya lupa NISN?',
                    a: 'Gunakan fitur "Cari NISN" di tab pertama widget ini. Cukup masukkan nama Anda, dan sistem akan menampilkan NISN Anda untuk langsung disalin.',
                    open: true
                },
                {
                    q: 'Apakah siswa membutuhkan kata sandi?',
                    a: 'Tidak. Akun siswa terotentikasi langsung menggunakan nomor NISN resmi yang terdaftar di Dapodik sekolah.',
                    open: false
                },
                {
                    q: 'Bagaimana jika kamera tidak terdeteksi saat scan wajah?',
                    a: 'Pastikan Anda memberikan izin akses kamera pada browser (klik ikon gembok di sebelah URL bar, lalu pilih Allow Camera). Gunakan pencahayaan yang cukup.',
                    open: false
                },
                {
                    q: 'Bagaimana cara mengunduh Kartu Pelajar Digital?',
                    a: 'Masuk ke Portal Siswa, kemudian buka tab "Biodata", lalu klik tombol "Cetak Kartu Pelajar".',
                    open: false
                }
            ],

            init() {
                // Ambil daftar kelas untuk filter
                fetch('{{ route("student.help.data") }}')
                    .then(res => res.json())
                    .then(res => {
                        if (res.classes) {
                            this.classList = res.classes;
                        }
                    })
                    .catch(() => {});
            },

            toggleHelpModal() {
                this.isOpen = !this.isOpen;
            },

            openHelpModal(tab = 'nisn') {
                this.activeTab = tab;
                this.isOpen = true;
            },

            searchStudents() {
                const q = this.searchQuery.trim();
                if (q.length < 2 && !this.selectedClassId) {
                    this.searchResults = [];
                    return;
                }

                this.isSearching = true;
                const params = new URLSearchParams({
                    query: q,
                    class_id: this.selectedClassId
                });

                fetch('{{ route("student.help.search-nisn") }}?' + params.toString())
                    .then(res => res.json())
                    .then(res => {
                        this.isSearching = false;
                        if (res.success) {
                            this.searchResults = res.data;
                        } else {
                            this.searchResults = [];
                        }
                    })
                    .catch(() => {
                        this.isSearching = false;
                        this.searchResults = [];
                    });
            },

            useNisn(nisn) {
                // 1. Salin ke clipboard
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(nisn);
                }

                // 2. Jika ada input NISN di halaman saat ini (misal di halaman login / portal)
                const inputEl = document.querySelector('input[name="student_id"]') || 
                                document.querySelector('#student_id') ||
                                document.querySelector('input[name="nisn"]');

                if (inputEl) {
                    inputEl.value = nisn;
                    // Trigger input event agar v-model/x-model tersinkron
                    inputEl.dispatchEvent(new Event('input', { bubbles: true }));
                    inputEl.focus();
                }

                // 3. Tampilkan toast
                this.showToast('NISN disalin: ' + nisn);

                // 4. Tutup drawer setelah 1 detik
                setTimeout(() => {
                    this.isOpen = false;
                }, 800);
            },

            showToast(msg) {
                this.toastMessage = msg;
                setTimeout(() => {
                    this.toastMessage = '';
                }, 3000);
            }
        };
    }
</script>

<style>
    @keyframes floatGentle {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-5px); }
    }
    .animate-float-gentle {
        animation: floatGentle 3.5s ease-in-out infinite;
    }
</style>
