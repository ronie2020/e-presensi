@extends('layouts.public')

@section('content')
    @php \Carbon\Carbon::setLocale('id'); @endphp

    {{-- TAMBAHAN: x-init="init()" untuk menjalankan auto-refresh saat load --}}
    <div x-data="studentLiaisonHandler()" x-init="init()" class="min-h-screen bg-[#e5eff5]/30 font-jakarta pt-24 pb-20">
        
        <div class="max-w-6xl mx-auto px-4 sm:px-6">
            
            {{-- HEADER MICROSOFT ELEVATE THEME --}}
            <div class="animate-enter relative rounded-[3rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 md:p-12 mb-10 text-[#2c3f61] shadow-2xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div>
                        <a href="{{ route('portal.index') }}" class="inline-flex items-center gap-2 text-[#0d52a1] hover:text-[#2c3f61] transition-colors mb-6 text-[10px] font-black uppercase tracking-widest bg-white/60 px-3 py-1.5 rounded-full border border-white shadow-sm backdrop-blur-sm">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h1 class="text-3xl md:text-5xl font-black tracking-tighter mb-3 leading-tight text-[#2c3f61]">Buku <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#0d52a1] to-[#56bbf1]">Penghubung</span></h1>
                        <p class="text-[#2c3f61]/80 text-sm md:text-base max-w-xl leading-relaxed font-medium">
                            Media informasi akademik dan ruang diskusi dua arah antara orang tua siswa dan sekolah.
                        </p>
                    </div>

                    {{-- TAB SWITCHER --}}
                    <div class="bg-white/60 backdrop-blur-xl p-1.5 rounded-2xl flex border border-white w-full md:w-auto shadow-sm">
                        <button @click="mode = 'note'" 
                            :class="mode === 'note' ? 'bg-white text-[#0d52a1] shadow-md border border-white' : 'text-[#2c3f61]/70 hover:bg-white/40'"
                            class="flex-1 md:flex-none px-8 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                            <i class="ph-fill ph-notebook text-lg"></i> Catatan Guru
                        </button>
                        <button @click="mode = 'chat'; fetchMessages()" 
                            :class="mode === 'chat' ? 'bg-white text-[#0d52a1] shadow-md border border-white' : 'text-[#2c3f61]/70 hover:bg-white/40'"
                            class="flex-1 md:flex-none px-8 py-4 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                            <i class="ph-fill ph-chat-circle-text text-lg"></i> Chat Wali Kelas
                        </button>
                    </div>
                </div>
            </div>

            {{-- CONTENT AREA --}}
            <div class="relative">
                
                {{-- TAB 1: CATATAN GURU --}}
                <div x-show="mode === 'note'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($messages as $msg)
                            @php
                                $config = match($msg->type) {
                                    'warning' => ['icon' => 'ph-warning', 'accent' => 'bg-[#f9a282]', 'bg' => 'bg-white', 'text' => 'text-[#c86845]'],
                                    'achievement' => ['icon' => 'ph-trophy', 'accent' => 'bg-emerald-400', 'bg' => 'bg-white', 'text' => 'text-emerald-600'],
                                    'call' => ['icon' => 'ph-phone-call', 'accent' => 'bg-rose-400', 'bg' => 'bg-rose-50/30', 'text' => 'text-rose-600'],
                                    default => ['icon' => 'ph-info', 'accent' => 'bg-[#56bbf1]', 'bg' => 'bg-white', 'text' => 'text-[#0d52a1]'],
                                };
                            @endphp
                            <div class="group bg-white rounded-[2.5rem] shadow-sm hover:shadow-[#56bbf1]/10 hover:-translate-y-1 transition-all border border-slate-100 overflow-hidden {{ $config['bg'] }} flex flex-col">
                                <div class="h-2 w-full {{ $config['accent'] }}"></div>
                                <div class="p-8 flex-1">
                                    <div class="flex items-start justify-between mb-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center {{ $config['text'] }} shadow-inner border border-slate-100">
                                                <i class="ph-fill {{ $config['icon'] }} text-2xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-black text-[#2c3f61] text-lg leading-tight uppercase tracking-tight">{{ $msg->title }}</h3>
                                                <span class="text-[9px] font-black text-[#2c3f61]/40 uppercase tracking-[0.2em] mt-1 block">{{ $msg->created_at->format('d M Y | H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-[#e5eff5]/30 p-6 rounded-[1.8rem] border border-slate-100">
                                        <p class="text-sm text-[#2c3f61]/80 leading-relaxed italic font-medium">"{{ $msg->message }}"</p>
                                    </div>
                                </div>
                                <div class="px-8 py-5 bg-slate-50/50 border-t border-slate-50 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-[#2c3f61] text-white flex items-center justify-center text-xs font-black shadow-lg shadow-[#2c3f61]/20">
                                            {{ substr($msg->teacher->name ?? 'G', 0, 1) }}
                                        </div>
                                        <span class="text-[10px] font-black text-[#2c3f61]/60 uppercase tracking-widest">{{ $msg->teacher->name ?? 'Guru' }}</span>
                                    </div>
                                    <span class="text-[9px] font-black text-[#56bbf1] uppercase tracking-widest">Terkirim Resmi</span>
                                </div>
                            </div>
                        @empty
                            <div class="md:col-span-2 bg-white rounded-[3rem] p-20 text-center shadow-sm border border-slate-100">
                                <div class="w-24 h-24 bg-[#e5eff5] rounded-full flex items-center justify-center mx-auto mb-6 text-[#0d52a1]/40">
                                    <i class="ph-duotone ph-notebook text-5xl"></i>
                                </div>
                                <h3 class="font-black text-[#2c3f61] text-xl tracking-tight">Belum Ada Catatan</h3>
                                <p class="text-sm text-[#2c3f61]/60 mt-2 max-w-sm mx-auto font-medium">Catatan prestasi atau informasi khusus dari guru akan muncul di bagian ini.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-10">{{ $messages->links() }}</div>
                </div>

                {{-- TAB 2: CHAT INTERFACE --}}
                <div x-show="mode === 'chat'" x-cloak x-transition>
                    <div class="bg-white rounded-[3rem] shadow-2xl shadow-[#56bbf1]/5 border border-slate-100 overflow-hidden flex flex-col h-[75vh]">
                        
                        <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-[#0d52a1] text-white flex items-center justify-center text-2xl shadow-lg shadow-[#0d52a1]/20">
                                    <i class="ph-fill ph-chat-teardrop-dots"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-[#2c3f61] uppercase tracking-tight">Diskusi Wali Kelas</h4>
                                    <p class="text-[10px] text-emerald-500 font-black uppercase tracking-widest flex items-center gap-1.5 mt-0.5">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Terhubung
                                    </p>
                                </div>
                            </div>
                            <div class="hidden sm:block text-right">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Waktu Aktif</p>
                                <p class="text-[10px] font-bold text-[#2c3f61] uppercase">07:00 - 15:00 WIB</p>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto p-8 space-y-6 bg-[#e5eff5]/20 custom-scrollbar scroll-smooth" x-ref="chatBox">
                            <div x-show="loading" class="flex justify-center py-10">
                                <div class="bg-white px-6 py-3 rounded-full shadow-sm text-[10px] font-black text-[#0d52a1] flex items-center gap-3 uppercase tracking-widest">
                                    <i class="ph-bold ph-spinner animate-spin text-[#56bbf1]"></i> Sinkronisasi Percakapan...
                                </div>
                            </div>

                            <template x-for="msg in messages" :key="msg.id">
                                <div class="flex w-full" :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'justify-end' : 'justify-start'">
                                    <div class="max-w-[85%] md:max-w-[70%] group flex flex-col" :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'items-end' : 'items-start'">
                                        <p x-show="msg.sender_type === 'teacher'" class="text-[9px] font-black text-[#0d52a1] mb-1.5 uppercase tracking-widest ml-1" x-text="msg.teacher ? msg.teacher.name : 'Guru'"></p>

                                        <div class="p-5 rounded-[2rem] text-[13px] font-medium leading-relaxed shadow-sm transition-all"
                                             :class="msg.sender_type === 'parent' || msg.sender_type === 'student'
                                                ? 'bg-[#2c3f61] text-white rounded-br-none shadow-[#2c3f61]/10 hover:bg-[#1c2940]' 
                                                : 'bg-white text-[#2c3f61] border border-slate-100 rounded-bl-none shadow-slate-100/50 hover:shadow-md'">
                                            <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                        </div>
                                        
                                        <p class="text-[9px] font-black mt-2 uppercase tracking-widest opacity-40 px-2 text-[#2c3f61]"
                                           :class="msg.sender_type === 'parent' ? 'text-right' : 'text-left'">
                                           <span x-text="formatTime(msg.created_at)"></span>
                                        </p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <div class="p-6 bg-white border-t border-slate-50">
                            <form @submit.prevent="sendMessage()" class="flex items-center gap-4">
                                <div class="flex-1 bg-[#e5eff5]/50 rounded-2xl flex items-center px-6 border-2 border-transparent focus-within:bg-white focus-within:border-[#56bbf1] focus-within:ring-4 focus-within:ring-[#56bbf1]/20 transition-all">
                                    <input x-model="newMessage" type="text" placeholder="Tulis pertanyaan atau informasi..." 
                                        class="w-full bg-transparent border-none focus:ring-0 py-4 text-sm font-bold text-[#2c3f61] placeholder:text-[#2c3f61]/40" :disabled="sending">
                                </div>
                                <button type="submit" :disabled="!newMessage.trim() || sending"
                                    class="w-14 h-14 rounded-2xl bg-[#2c3f61] text-white flex items-center justify-center shadow-xl shadow-[#2c3f61]/20 disabled:opacity-40 hover:bg-[#1c2940] transition-all transform active:scale-90">
                                    <i x-show="!sending" class="ph-bold ph-paper-plane-right text-2xl"></i>
                                    <i x-show="sending" class="ph-bold ph-spinner animate-spin text-2xl"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function studentLiaisonHandler() {
            return {
                mode: 'note', 
                messages: [],
                newMessage: '',
                loading: false,
                sending: false,
                pollInterval: null, // Variable untuk menyimpan ID interval

                // Init: Dijalankan otomatis berkat x-init="init()"
                init() {
                    // Mulai polling setiap 3 detik
                    this.startPolling();
                    
                    // Jika user berpindah tab ke chat, segera refresh dan scroll
                    this.$watch('mode', value => {
                        if (value === 'chat') {
                            this.fetchMessages();
                            this.scrollToBottom();
                        }
                    });
                },

                startPolling() {
                    this.pollInterval = setInterval(() => {
                        // Hanya refresh jika sedang di tab chat
                        if (this.mode === 'chat') {
                            this.fetchMessages(true); // true = silent mode (tanpa loading spinner)
                        }
                    }, 3000); // 3000ms = 3 detik
                },

                // Parameter silent=true berarti tidak memunculkan indikator loading
                fetchMessages(silent = false) {
                    if (!silent) this.loading = true;

                    fetch("{{ route('student.liaison.chat.messages') }}")
                        .then(res => res.json()).then(data => {
                            this.messages = data;
                            
                            // Matikan loading jika bukan silent
                            if (!silent) {
                                this.loading = false;
                                this.scrollToBottom();
                            }
                            // Opsional: Anda bisa menambahkan logika untuk scroll jika ada pesan baru
                            // Tapi biasanya dibiarkan agar tidak mengganggu user yang sedang membaca
                        });
                },

                sendMessage() {
                    if (!this.newMessage.trim()) return;
                    this.sending = true;
                    let msgText = this.newMessage;
                    this.newMessage = ''; 
                    
                    // Optimistic UI update
                    this.messages.push({ id: Date.now(), message: msgText, sender_type: 'parent', created_at: new Date().toISOString() });
                    this.scrollToBottom();
                    
                    fetch("{{ route('student.liaison.chat.send') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify({ message: msgText })
                    }).then(() => { 
                        this.sending = false;
                        this.fetchMessages(true); // Pastikan sinkron dengan server
                    }).catch(() => { this.sending = false; });
                },

                scrollToBottom() { setTimeout(() => { const box = this.$refs.chatBox; if (box) box.scrollTop = box.scrollHeight; }, 100); },
                formatTime(iso) { return new Date(iso).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}); }
            }
        }
    </script>
    <style>
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
@endsection