<x-app-layout>
    {{-- LOAD ASSETS --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');

        .font-jakarta {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }

        [x-cloak] { display: none !important; }
    </style>

    <div class="font-jakarta p-4 md:p-8 space-y-8 min-h-screen bg-slate-50" x-data="liaisonHandler()">
        
        {{-- HERO SECTION --}}
        <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-blue-900 via-slate-800 to-slate-900 p-8 md:p-12 text-white shadow-2xl shadow-blue-900/20 overflow-hidden group border border-white/10">
            {{-- Elemen Dekoratif --}}
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20"></div>
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-4 mb-6">
                        {{-- Tab Switcher --}}
                        <div class="inline-flex bg-white/10 backdrop-blur-md rounded-2xl p-1.5 border border-white/10 shadow-inner">
                            <button @click="mode = 'note'" 
                                    :class="mode === 'note' ? 'bg-blue-600 text-white shadow-lg' : 'text-blue-200 hover:text-white hover:bg-white/5'"
                                    class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2">
                                <i class="ph-fill ph-book-bookmark"></i> Catatan
                            </button>
                            <button @click="mode = 'chat'; fetchChatContacts()" 
                                    :class="mode === 'chat' ? 'bg-emerald-600 text-white shadow-lg' : 'text-blue-200 hover:text-white hover:bg-white/5'"
                                    class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-2 relative">
                                <i class="ph-fill ph-chats-circle"></i> Pesan Ortu
                                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full animate-ping"></span>
                                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border border-white"></span>
                            </button>
                        </div>
                    </div>

                    <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 leading-tight">
                        <span x-text="mode === 'note' ? 'Buku Penghubung Digital' : 'Pesan & Diskusi Ortu'"></span>
                    </h1>
                    <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                        Jalin komunikasi yang harmonis antara sekolah dan orang tua siswa demi perkembangan karakter anak yang maksimal.
                    </p>
                </div>
                
                {{-- Stats Mini --}}
                <div class="w-full lg:w-auto" x-show="mode === 'note'" x-transition>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="glass-card bg-white/10 p-6 rounded-3xl border-white/10 text-center">
                            <p class="text-[10px] font-black text-blue-300 uppercase tracking-widest mb-1">Total Catatan</p>
                            <p class="text-4xl font-black text-white tracking-tight">{{ $messages->total() }}</p>
                        </div>
                        <div class="glass-card bg-emerald-500/20 p-6 rounded-3xl border-emerald-400/20 text-center">
                            <p class="text-[10px] font-black text-emerald-300 uppercase tracking-widest mb-1">Tersampaikan</p>
                            <div class="flex items-center justify-center gap-2 mt-1">
                                <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse"></div>
                                <span class="text-xl font-black text-white">Aktif</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="animate-enter" style="animation-delay: 100ms">
            
            {{-- FLASH MESSAGES --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success', title: 'Berhasil', text: "{{ session('success') }}",
                            timer: 3000, showConfirmButton: false, toast: true, position: 'top-end',
                            background: '#eff6ff', color: '#1e40af', iconColor: '#3b82f6'
                        });
                    });
                </script>
            @endif

            {{-- MODE 1: CATATAN (NOTE) --}}
            <div x-show="mode === 'note'" x-transition>
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    {{-- FORM INPUT --}}
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden">
                            <div class="p-8 sm:p-10">
                                <div class="flex items-center gap-4 mb-8">
                                    <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-blue-100">
                                        <i class="ph-duotone ph-pencil-simple-line"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-black text-slate-800 tracking-tight">Buat Catatan Baru</h3>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Sampaikan pesan resmi kepada orang tua</p>
                                    </div>
                                </div>

                                <form action="{{ route('liaison.store') }}" method="POST" class="space-y-6">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Kelas</label>
                                            <select x-model="selectedClass" @change="fetchStudents()" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3.5 px-4 transition-all">
                                                <option value="">-- Pilih Kelas --</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Pilih Siswa</label>
                                            <select name="student_id" :disabled="!selectedClass || isLoading" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3.5 px-4 transition-all disabled:opacity-50">
                                                <option value="">-- Pilih Siswa --</option>
                                                <template x-for="student in students" :key="student.id">
                                                    <option :value="student.id" x-text="student.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <div class="md:col-span-2 space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Judul Catatan</label>
                                            <input type="text" name="title" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-800 py-3.5 px-4 placeholder:font-medium" placeholder="Contoh: Apresiasi Kedisiplinan Siswa" required>
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis</label>
                                            <select name="type" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 font-bold text-slate-700 py-3.5 px-4">
                                                <option value="info">📢 Informasi</option>
                                                <option value="warning">⚠️ Peringatan</option>
                                                <option value="achievement">🏆 Prestasi</option>
                                                <option value="call">📞 Panggilan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Isi Pesan</label>
                                        <textarea name="message" rows="4" class="w-full rounded-3xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm p-5 text-slate-700 font-medium placeholder:text-slate-400" placeholder="Tulis rincian pesan yang ingin disampaikan..." required></textarea>
                                    </div>

                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="w-full sm:w-auto py-4 px-10 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-600/20 text-[10px] uppercase tracking-[0.2em] flex items-center justify-center gap-3 transform active:scale-95 group">
                                            <i class="ph-bold ph-paper-plane-right text-lg group-hover:translate-x-1 transition-transform"></i>
                                            Kirim Catatan Resmi
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- RIWAYAT KANAN --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-200 overflow-hidden flex flex-col h-auto lg:h-[calc(100vh-8rem)] lg:sticky lg:top-8">
                            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <i class="ph-duotone ph-clock-counter-clockwise text-blue-600"></i> Riwayat
                                </h3>
                                <span class="bg-blue-50 text-[10px] font-black px-3 py-1 rounded-full text-blue-600 border border-blue-100 uppercase tracking-widest">{{ $messages->total() }}</span>
                            </div>
                            
                            <div class="flex-1 overflow-y-auto custom-scrollbar p-0">
                                <div class="divide-y divide-slate-50">
                                    @forelse($messages as $msg)
                                        @php
                                            $style = match($msg->type) {
                                                'warning' => ['icon' => 'ph-warning-circle', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50', 'border' => 'border-amber-100'],
                                                'achievement' => ['icon' => 'ph-trophy', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-100'],
                                                'call' => ['icon' => 'ph-phone-call', 'color' => 'text-rose-600', 'bg' => 'bg-rose-50', 'border' => 'border-rose-100'],
                                                default => ['icon' => 'ph-info', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-100'],
                                            };
                                        @endphp
                                        <div class="p-5 hover:bg-slate-50 transition-all group">
                                            <div class="flex items-start justify-between gap-3 mb-2">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-2xl {{ $style['bg'] }} border {{ $style['border'] }} flex items-center justify-center {{ $style['color'] }} shadow-sm shrink-0">
                                                        <i class="ph-fill {{ $style['icon'] }} text-lg"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-black text-slate-800 truncate">{{ $msg->student->name ?? 'Siswa' }}</p>
                                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">{{ $msg->student->schoolClass->name ?? '-' }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="pl-13">
                                                <h4 class="font-black text-slate-700 text-sm leading-snug mb-1 group-hover:text-blue-600 transition-colors uppercase tracking-tight">{{ $msg->title }}</h4>
                                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed bg-slate-50/50 p-2.5 rounded-xl border border-slate-100 italic">
                                                    "{{ $msg->message }}"
                                                </p>
                                                <div class="flex items-center justify-between mt-3">
                                                    <span class="text-[10px] font-bold text-slate-400">{{ $msg->created_at->diffForHumans() }}</span>
                                                    @if($msg->teacher_id == Auth::id() || Auth::user()->role == 'admin')
                                                        <form action="{{ route('liaison.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-[10px] font-black text-rose-400 hover:text-rose-600 uppercase tracking-widest flex items-center gap-1">
                                                                <i class="ph-bold ph-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-20 px-6 opacity-40">
                                            <i class="ph-duotone ph-notebook text-5xl mb-3"></i>
                                            <p class="text-xs font-black uppercase tracking-widest">Belum ada riwayat</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODE 2: CHAT INTERFACE (FIXED MOBILE VISIBILITY) --}}
            <div x-show="mode === 'chat'" x-cloak x-transition>
                <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-200 overflow-hidden h-[75vh] flex relative">
                    
                    {{-- SIDEBAR KONTAK (Daftar Kontak) --}}
                    {{-- PERBAIKAN: Menambahkan logic hidden pada mobile jika kontak aktif dipilih --}}
                    <div class="w-full md:w-80 border-r border-slate-100 flex flex-col bg-slate-50/50 transition-all duration-300"
                         :class="activeContact ? 'hidden md:flex' : 'flex'">
                        
                        <div class="p-6 border-b border-slate-100 bg-white space-y-4">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                                <i class="ph-fill ph-users text-blue-600"></i> Kontak Ortu
                            </h3>
                            <div class="relative">
                                <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input x-model="chatSearch" @input.debounce.500ms="fetchChatContacts()" type="text" placeholder="Cari Siswa..." class="w-full pl-10 pr-4 py-3 bg-slate-100 border-transparent focus:bg-white focus:border-blue-500 focus:ring-blue-500 rounded-2xl text-xs font-bold transition-all">
                            </div>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-3 space-y-2">
                            <template x-for="contact in chatContacts" :key="contact.id">
                                <button @click="selectContact(contact)" 
                                    :class="activeContact && activeContact.id === contact.id ? 'bg-white border-blue-200 shadow-md ring-1 ring-blue-500/10' : 'hover:bg-white/80'"
                                    class="w-full p-4 flex items-start gap-4 rounded-[1.5rem] border border-transparent transition-all text-left group">
                                    
                                    <div class="w-11 h-11 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-600 shrink-0 font-black border border-slate-200 group-hover:bg-blue-50 group-hover:text-blue-600 group-hover:border-blue-100 transition-colors">
                                        <span x-text="getInitials(contact.name)"></span>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-center mb-0.5">
                                            <h4 class="font-black text-slate-700 text-xs truncate group-hover:text-blue-600 transition-colors uppercase tracking-tight" x-text="contact.name"></h4>
                                            <span class="text-[9px] font-bold text-slate-400" x-text="formatTime(contact.last_message_time)"></span>
                                        </div>
                                        <p class="text-[11px] text-slate-400 truncate font-medium" 
                                           :class="contact.unread_count > 0 ? 'font-black text-slate-800' : ''"
                                           x-text="contact.last_message ? contact.last_message.message : 'Belum ada pesan'">
                                        </p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    {{-- AREA PESAN (Chat Area) --}}
                    {{-- PERBAIKAN: Mengatur visibilitas pada mobile --}}
                    <div class="flex-1 flex flex-col bg-white relative overflow-hidden transition-all duration-300"
                         :class="activeContact ? 'flex' : 'hidden md:flex'">
                        
                        {{-- TAMPILAN KOSONG (Jika belum pilih kontak) --}}
                        <div x-show="!activeContact" class="flex-1 bg-slate-50 flex flex-col items-center justify-center text-center p-8">
                            <div class="w-24 h-24 bg-white rounded-[2rem] shadow-xl shadow-blue-900/5 flex items-center justify-center mb-8 animate-bounce-slow">
                                <i class="ph-duotone ph-chats-circle text-5xl text-blue-500"></i>
                            </div>
                            <h3 class="text-2xl font-black text-slate-800 mb-2 tracking-tight">Pilih Obrolan</h3>
                            <p class="text-slate-500 max-w-xs font-medium text-sm leading-relaxed">Klik salah satu kontak siswa di sebelah kiri untuk memulai diskusi dua arah dengan orang tua.</p>
                        </div>

                        {{-- TAMPILAN CHAT AKTIF --}}
                        <div x-show="activeContact" class="flex-1 flex flex-col h-full bg-white relative" x-transition>
                            {{-- Header Chat --}}
                            <div class="bg-white border-b border-slate-100 p-5 flex items-center justify-between shadow-sm z-10">
                                <div class="flex items-center gap-4">
                                    {{-- Tombol Kembali untuk Mobile --}}
                                    <button @click="activeContact = null" class="md:hidden p-2 -ml-2 text-slate-400 hover:text-blue-600 transition-colors">
                                        <i class="ph-bold ph-arrow-left text-2xl"></i>
                                    </button>
                                    
                                    <div class="w-11 h-11 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-blue-600/20">
                                        <span x-text="getInitials(activeContact?.name)"></span>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-slate-800 text-base uppercase tracking-tight" x-text="activeContact?.name"></h3>
                                        <p class="text-[10px] text-emerald-500 font-black uppercase tracking-widest flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Terhubung
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Isi Chat (Bubbles) --}}
                            <div class="flex-1 overflow-y-auto p-6 bg-slate-50/50 space-y-6 custom-scrollbar" x-ref="chatBox">
                                <template x-for="msg in chatMessages" :key="msg.id">
                                    <div class="flex w-full" :class="msg.sender_type === 'teacher' ? 'justify-end' : 'justify-start'">
                                        <div class="max-w-[85%] md:max-w-[70%] group">
                                            <div class="p-4 rounded-3xl text-sm font-medium leading-relaxed shadow-sm transition-all"
                                                 :class="msg.sender_type === 'teacher' 
                                                    ? 'bg-blue-600 text-white rounded-br-none shadow-blue-600/10' 
                                                    : 'bg-white text-slate-700 border border-slate-100 rounded-bl-none'">
                                                <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                            </div>
                                            <p class="text-[9px] font-black text-slate-400 mt-2 uppercase tracking-widest px-1"
                                               :class="msg.sender_type === 'teacher' ? 'text-right' : 'text-left'"
                                               x-text="formatTime(msg.created_at, true)">
                                            </p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            {{-- Input Pesan --}}
                            <div class="bg-white border-t border-slate-100 p-6">
                                <form @submit.prevent="sendMessage()" class="flex items-center gap-4">
                                    <div class="flex-1 bg-slate-100 rounded-2xl flex items-center px-5 border-2 border-transparent focus-within:bg-white focus-within:border-blue-100 focus-within:ring-4 focus-within:ring-blue-50 transition-all">
                                        <input x-model="newMessage" type="text" placeholder="Tulis pesan untuk orang tua..." class="w-full bg-transparent border-none focus:ring-0 py-4 text-sm font-bold text-slate-700 placeholder:text-slate-400">
                                    </div>
                                    <button type="submit" :disabled="!newMessage.trim()" class="w-14 h-14 rounded-2xl bg-blue-600 text-white hover:bg-blue-700 disabled:opacity-50 shadow-xl shadow-blue-600/30 transition-all transform active:scale-90 flex items-center justify-center shrink-0">
                                        <i class="ph-bold ph-paper-plane-right text-2xl"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        function liaisonHandler() {
            return {
                mode: 'note', 
                selectedClass: '',
                students: [],
                isLoading: false,
                chatClassFilter: '',
                chatSearch: '',
                chatContacts: [],
                activeContact: null,
                chatMessages: [],
                newMessage: '',
                isLoadingContacts: false,
                isLoadingMessages: false,

                fetchStudents() {
                    if (!this.selectedClass) { this.students = []; return; }
                    this.isLoading = true;
                    let url = "{{ route('liaison.get_students', ':id') }}".replace(':id', this.selectedClass);
                    fetch(url).then(res => res.json()).then(data => { this.students = data; this.isLoading = false; });
                },

                fetchChatContacts() {
                    this.isLoadingContacts = true;
                    let url = "{{ route('liaison.chat.contacts') }}";
                    const params = new URLSearchParams();
                    if (this.chatSearch) params.append('search', this.chatSearch);
                    if (params.toString()) url += '?' + params.toString();

                    fetch(url, { headers: { 'Accept': 'application/json' }})
                    .then(res => res.json())
                    .then(data => { this.chatContacts = data.data || data; this.isLoadingContacts = false; });
                },

                selectContact(contact) {
                    this.activeContact = contact;
                    this.fetchChatMessages(contact.id);
                },

                fetchChatMessages(studentId) {
                    this.isLoadingMessages = true;
                    let url = "{{ route('liaison.chat.messages', ':id') }}".replace(':id', studentId);
                    fetch(url).then(res => res.json()).then(data => {
                        this.chatMessages = data;
                        this.isLoadingMessages = false;
                        this.scrollToBottom();
                    });
                },

                sendMessage() {
                    if (!this.newMessage.trim() || !this.activeContact) return;
                    let msgText = this.newMessage;
                    this.newMessage = '';
                    this.chatMessages.push({ id: Date.now(), message: msgText, sender_type: 'teacher', created_at: new Date().toISOString() });
                    this.scrollToBottom();

                    fetch("{{ route('liaison.chat.send') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify({ student_id: this.activeContact.id, message: msgText })
                    });
                },

                scrollToBottom() { setTimeout(() => { let b = this.$refs.chatBox; if(b) b.scrollTop = b.scrollHeight; }, 100); },
                formatTime(iso, onlyTime = false) {
                    if(!iso) return '';
                    let d = new Date(iso);
                    if(onlyTime) return d.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'});
                    return d.toLocaleDateString('id-ID', {day:'numeric', month:'short'});
                },
                getInitials(n) { return n ? n.split(' ').map(x => x[0]).join('').substring(0, 2).toUpperCase() : ''; }
            }
        }
    </script>
</x-app-layout>