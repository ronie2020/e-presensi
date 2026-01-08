@extends('layouts.public')

@section('content')
    {{-- AlpineJS Handler --}}
    <div x-data="studentLiaisonHandler()" class="min-h-screen bg-slate-50 font-sans">
        
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
            
            {{-- HEADER SECTION (Disesuaikan dengan Style Public) --}}
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-r from-indigo-900 via-purple-900 to-slate-900 p-8 md:p-10 mb-8 text-white shadow-2xl shadow-indigo-900/20 overflow-hidden border border-white/10">
                <!-- Dekorasi Background -->
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-purple-500 rounded-full mix-blend-overlay filter blur-[120px] opacity-20"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        {{-- Tombol Kembali --}}
                        <a href="{{ route('portal.index') }}" class="inline-flex items-center gap-2 text-indigo-200 hover:text-white transition-colors mb-4 text-xs font-bold uppercase tracking-widest">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Dashboard
                        </a>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">Buku Penghubung</h1>
                        <p class="text-indigo-100/80 max-w-xl leading-relaxed">
                            Pantau catatan akademik dari guru dan berkomunikasi langsung dengan pihak sekolah melalui fitur pesan.
                        </p>
                    </div>

                    {{-- TAB SWITCHER --}}
                    <div class="bg-white/10 backdrop-blur-md p-1.5 rounded-xl flex border border-white/10 shrink-0 w-full md:w-auto">
                        <button @click="mode = 'note'" 
                            :class="mode === 'note' ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-white/10'"
                            class="flex-1 md:flex-none px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                            <i class="ph-fill ph-notebook text-lg"></i> Catatan Guru
                        </button>
                        <button @click="mode = 'chat'; fetchMessages()" 
                            :class="mode === 'chat' ? 'bg-emerald-400 text-white shadow-lg' : 'text-indigo-100 hover:bg-white/10'"
                            class="flex-1 md:flex-none px-6 py-3 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center justify-center gap-2">
                            <i class="ph-fill ph-chat-circle-text text-lg"></i> Chat Ortu
                        </button>
                    </div>
                </div>
            </div>

            {{-- CONTENT CONTAINER --}}
            <div class="relative z-20">
                
                {{-- TAB 1: BUKU PENGHUBUNG (CATATAN) --}}
                <div x-show="mode === 'note'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                    
                    <div class="grid grid-cols-1 gap-4">
                        @forelse($messages as $msg)
                            @php
                                $style = match($msg->type) {
                                    'warning' => ['icon' => 'ph-warning', 'border' => 'border-l-amber-500', 'bg' => 'bg-white', 'icon_color' => 'text-amber-500', 'shadow' => 'shadow-amber-100'],
                                    'achievement' => ['icon' => 'ph-trophy', 'border' => 'border-l-emerald-500', 'bg' => 'bg-white', 'icon_color' => 'text-emerald-500', 'shadow' => 'shadow-emerald-100'],
                                    'call' => ['icon' => 'ph-phone-call', 'border' => 'border-l-rose-500', 'bg' => 'bg-rose-50', 'icon_color' => 'text-rose-500', 'shadow' => 'shadow-rose-100'],
                                    default => ['icon' => 'ph-info', 'border' => 'border-l-blue-500', 'bg' => 'bg-white', 'icon_color' => 'text-blue-500', 'shadow' => 'shadow-blue-100'],
                                };
                            @endphp
                            <div class="rounded-2xl shadow-sm hover:shadow-md transition-all border border-slate-100 overflow-hidden {{ $style['bg'] }} border-l-4 {{ $style['border'] }}">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center {{ $style['icon_color'] }}">
                                                <i class="ph-fill {{ $style['icon'] }} text-xl"></i>
                                            </div>
                                            <div>
                                                <h3 class="font-bold text-slate-800 text-lg leading-tight">{{ $msg->title }}</h3>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $msg->created_at->format('d M Y, H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="pl-[52px]">
                                        <p class="text-sm text-slate-600 leading-relaxed bg-slate-50/50 p-4 rounded-xl border border-slate-100/50">
                                            {{ $msg->message }}
                                        </p>
                                        <div class="mt-3 flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600 border border-white shadow-sm">
                                                {{ substr($msg->teacher->name ?? 'G', 0, 1) }}
                                            </div>
                                            <span class="text-xs font-bold text-slate-500">Dikirim oleh: <span class="text-slate-700">{{ $msg->teacher->name ?? 'Guru' }}</span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="bg-white rounded-[2rem] p-12 text-center shadow-sm border border-slate-100 flex flex-col items-center">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 text-slate-300 border border-slate-100">
                                    <i class="ph-duotone ph-notebook text-4xl"></i>
                                </div>
                                <h3 class="font-bold text-slate-800 text-lg">Belum Ada Catatan</h3>
                                <p class="text-sm text-slate-500 mt-1 max-w-sm">Catatan akademik, prestasi, atau informasi penting dari guru akan muncul di sini.</p>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $messages->links() }}
                    </div>
                </div>

                {{-- TAB 2: CHAT ORANG TUA --}}
                <div x-show="mode === 'chat'" x-cloak x-transition>
                    <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col h-[70vh]">
                        
                        {{-- Chat Header --}}
                        <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold border-2 border-white shadow-sm">
                                <i class="ph-fill ph-chalkboard-teacher text-2xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-slate-800">Hubungi Sekolah / Wali Kelas</h4>
                                <p class="text-xs text-slate-500 flex items-center gap-1.5 mt-0.5">
                                    <span class="relative flex h-2 w-2">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                    </span>
                                    Layanan Aktif
                                </p>
                            </div>
                        </div>

                        {{-- Chat Messages Area --}}
                        <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50 custom-scrollbar scroll-smooth" x-ref="chatBox">
                            
                            <div x-show="loading" class="flex justify-center py-4">
                                <div class="bg-white px-4 py-2 rounded-full shadow-sm text-xs font-bold text-slate-400 flex items-center gap-2">
                                    <i class="ph-bold ph-spinner animate-spin"></i> Memuat percakapan...
                                </div>
                            </div>

                            <template x-for="msg in messages" :key="msg.id">
                                <div class="flex w-full" :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'justify-end' : 'justify-start'">
                                    <div class="max-w-[85%] group flex flex-col" :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'items-end' : 'items-start'">
                                        
                                        {{-- Sender Name Label --}}
                                        <p x-show="msg.sender_type === 'teacher'" class="text-[10px] font-bold text-slate-400 mb-1 ml-1" x-text="msg.teacher ? msg.teacher.name : 'Guru'"></p>

                                        {{-- Bubble --}}
                                        <div class="p-4 rounded-2xl text-sm leading-relaxed shadow-sm relative transition-all"
                                             :class="msg.sender_type === 'parent' || msg.sender_type === 'student'
                                                ? 'bg-indigo-600 text-white rounded-br-none hover:bg-indigo-700' 
                                                : 'bg-white text-slate-700 border border-slate-100 rounded-bl-none hover:shadow-md'">
                                            <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                        </div>
                                        
                                        {{-- Time --}}
                                        <p class="text-[9px] font-bold mt-1 opacity-60 flex items-center gap-1"
                                           :class="msg.sender_type === 'parent' ? 'text-right text-indigo-400' : 'text-left text-slate-400'">
                                           <i class="ph-bold ph-check" x-show="msg.sender_type === 'parent'"></i>
                                           <span x-text="formatTime(msg.created_at)"></span>
                                        </p>
                                    </div>
                                </div>
                            </template>

                            <div x-show="messages.length === 0 && !loading" class="h-full flex flex-col items-center justify-center text-center opacity-60">
                                <div class="w-16 h-16 bg-slate-200 rounded-full flex items-center justify-center mb-3">
                                    <i class="ph-duotone ph-chats-circle text-3xl text-slate-400"></i>
                                </div>
                                <p class="text-sm font-bold text-slate-500">Belum ada percakapan.</p>
                                <p class="text-xs text-slate-400">Silakan kirim pesan kepada guru.</p>
                            </div>
                        </div>

                        {{-- Chat Input --}}
                        <div class="p-4 bg-white border-t border-slate-100">
                            <form @submit.prevent="sendMessage()" class="flex items-end gap-3">
                                <div class="flex-1 bg-slate-50 border border-slate-200 rounded-2xl flex items-center px-4 py-2 focus-within:bg-white focus-within:border-indigo-300 focus-within:ring-4 focus-within:ring-indigo-100 transition-all">
                                    <input x-model="newMessage" type="text" placeholder="Tulis pesan..." 
                                        class="w-full bg-transparent border-none focus:ring-0 text-sm font-medium text-slate-700 placeholder:text-slate-400"
                                        :disabled="sending">
                                </div>
                                
                                <button type="submit" :disabled="!newMessage.trim() || sending"
                                    class="w-12 h-12 rounded-xl bg-indigo-600 text-white flex items-center justify-center shadow-lg shadow-indigo-600/20 disabled:opacity-50 disabled:shadow-none hover:bg-indigo-700 transition-all transform active:scale-95">
                                    <i x-show="!sending" class="ph-bold ph-paper-plane-right text-xl"></i>
                                    <i x-show="sending" class="ph-bold ph-spinner animate-spin text-xl"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        function studentLiaisonHandler() {
            return {
                mode: 'note', // 'note' or 'chat'
                messages: [],
                newMessage: '',
                loading: false,
                sending: false,

                fetchMessages() {
                    this.loading = true;
                    fetch("{{ route('student.liaison.chat.messages') }}")
                        .then(res => res.json())
                        .then(data => {
                            this.messages = data;
                            this.loading = false;
                            this.scrollToBottom();
                        })
                        .catch(err => {
                            console.error(err);
                            this.loading = false;
                        });
                },

                sendMessage() {
                    if (!this.newMessage.trim()) return;
                    this.sending = true;

                    // Optimistic UI
                    const tempMsg = {
                        id: Date.now(),
                        message: this.newMessage,
                        sender_type: 'parent',
                        created_at: new Date().toISOString(),
                        teacher: null
                    };
                    this.messages.push(tempMsg);
                    this.scrollToBottom();
                    
                    const payload = { message: this.newMessage };
                    this.newMessage = ''; // Reset input immediately

                    fetch("{{ route('student.liaison.chat.send') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.sending = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.sending = false;
                        alert('Gagal mengirim pesan.');
                    });
                },

                scrollToBottom() {
                    setTimeout(() => {
                        const box = this.$refs.chatBox;
                        if (box) box.scrollTop = box.scrollHeight;
                    }, 100);
                },

                formatTime(isoString) {
                    const d = new Date(isoString);
                    return d.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                }
            }
        }
    </script>
    
    <style>
        [x-cloak] { display: none !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
@endsection