@extends('layouts.public')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
    [x-cloak] { display: none !important; }
</style>

<div class="py-6 md:py-10 font-sans text-slate-800" x-data="bkStudentChatHandler({{ $session->id }})">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb / Back -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <a href="{{ route('student.bk.index') }}" class="group inline-flex items-center text-sm text-slate-500 hover:text-blue-600 font-bold transition-colors">
                <i class="ph-bold ph-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Riwayat
            </a>
            <div class="px-4 py-1.5 rounded-full bg-slate-100 border border-slate-200 text-xs font-mono font-bold text-slate-500 shadow-sm">
                ID TIKET: #{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- ========================================== -->
            <!-- KOLOM KIRI: STATUS & INFO SISWA            -->
            <!-- ========================================== -->
            <div class="space-y-6">
                <!-- Card Status -->
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-xl shadow-slate-200/50 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-500/5 to-transparent rounded-full -mr-16 -mt-16"></div>
                    
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Status Pengajuan</h3>
                    
                    <div class="flex items-center gap-4 mb-6">
                        @php
                            $statusLabel = [
                                'pending' => 'Menunggu',
                                'approved' => 'Dijadwalkan',
                                'ongoing' => 'Chat Berlangsung',
                                'finished' => 'Selesai',
                                'rejected' => 'Ditolak'
                            ];
                            $statusColor = [
                                'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
                                'approved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                'ongoing' => 'bg-indigo-100 text-indigo-700 border-indigo-200',
                                'finished' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                'rejected' => 'bg-rose-100 text-rose-700 border-rose-200'
                            ];
                        @endphp
                        <div class="px-4 py-2 rounded-xl border {{ $statusColor[$session->status] }} text-xs font-black uppercase tracking-wider shadow-sm">
                            {{ $statusLabel[$session->status] }}
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-slate-400 uppercase tracking-tighter">Metode</span>
                            <span class="text-slate-700 uppercase flex items-center gap-1">
                                <i class="ph-fill {{ $session->method == 'online' ? 'ph-globe' : 'ph-users' }}"></i>
                                {{ strtoupper($session->method) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold p-3 bg-slate-50 rounded-xl border border-slate-100">
                            <span class="text-slate-400 uppercase tracking-tighter">Topik Utama</span>
                            <span class="text-slate-700 uppercase">{{ $session->category->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Guru (Jika Sudah Direspon) -->
                @if($session->teacher_id)
                <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-lg">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Guru Pembimbing</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white text-xl shadow-md">
                            <i class="ph-fill ph-user-focus"></i>
                        </div>
                        <div>
                            <p class="font-black text-slate-800 text-sm leading-tight">{{ $session->teacher->name }}</p>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Konselor Sekolah</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- ========================================== -->
            <!-- KOLOM KANAN: RUANG CHAT / DETAIL HASIL     -->
            <!-- ========================================== -->
            <div class="lg:col-span-2 space-y-6">
                
                {{-- 1. RUANG CHAT AKTIF (Jika Status Ongoing) --}}
                @if($session->status == 'ongoing' && $session->method == 'online')
                <div class="bg-white rounded-[2.5rem] shadow-2xl border border-indigo-100 overflow-hidden flex flex-col h-[600px] animate-in zoom-in-95 duration-300">
                    
                    <!-- Chat Header -->
                    <div class="p-5 sm:p-6 bg-gradient-to-r from-indigo-600 to-blue-600 text-white flex items-center gap-4 z-10 shadow-md">
                        <div class="w-12 h-12 rounded-2xl bg-white/20 flex items-center justify-center text-2xl backdrop-blur-md border border-white/20">
                            <i class="ph-fill ph-chats-circle"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-base sm:text-lg leading-tight">Konseling Online</h3>
                            <div class="flex items-center gap-1.5 mt-1">
                                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                                <p class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest opacity-90">Terhubung dengan Guru BK</p>
                            </div>
                        </div>
                    </div>

                    <!-- Area Chat -->
                    <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 bg-slate-50/80 custom-scrollbar" x-ref="chatBox">
                        <!-- Pesan Kosong -->
                        <div class="text-center py-4 opacity-50" x-show="messages.length === 0">
                            <span class="bg-slate-200 text-slate-600 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Mulai Percakapan</span>
                        </div>

                        <template x-for="msg in messages" :key="msg.id">
                            <div :class="msg.sender_type === 'student' ? 'flex justify-end' : 'flex justify-start'">
                                <div :class="msg.sender_type === 'student' ? 'bg-indigo-600 text-white rounded-2xl rounded-tr-sm shadow-indigo-200' : 'bg-white text-slate-800 border border-slate-200 rounded-2xl rounded-tl-sm shadow-slate-100'"
                                     class="max-w-[85%] sm:max-w-[75%] p-3 sm:p-4 shadow-md relative group animate-in slide-in-from-bottom-2 duration-300">
                                    <p class="text-sm font-medium leading-relaxed break-words" x-text="msg.message"></p>
                                    <div class="flex justify-end items-center gap-1 mt-1 opacity-60">
                                        <span class="text-[8px] sm:text-[9px] font-bold" x-text="formatTime(msg.created_at)"></span>
                                        <template x-if="msg.sender_type === 'student'">
                                            <i class="ph-bold ph-checks text-[10px]"></i>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Input Chat (Siswa) -->
                    <div class="p-4 sm:p-5 bg-white border-t border-slate-100 z-10">
                        <div class="flex gap-2 sm:gap-3 relative">
                            <input type="text" x-model="newMessage" @keydown.enter="send()" placeholder="Ketik balasan pesan di sini..." 
                                class="flex-1 rounded-2xl border-slate-200 bg-slate-50 text-sm font-medium focus:bg-white focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 sm:py-4 transition-all">
                            
                            <button @click="send()" :disabled="isSending" class="w-12 h-12 sm:w-14 sm:h-14 bg-indigo-600 text-white rounded-2xl flex items-center justify-center hover:bg-indigo-700 shadow-xl shadow-indigo-500/30 transition-all disabled:opacity-50 active:scale-95 shrink-0">
                                <i class="ph-bold ph-paper-plane-right text-lg sm:text-xl" x-show="!isSending"></i>
                                <i class="ph-bold ph-spinner animate-spin text-lg sm:text-xl" x-show="isSending" x-cloak></i>
                            </button>
                        </div>
                    </div>
                </div>

                @else
                    {{-- 2. TAMPILAN PESAN STANDAR (Jika Masih Pending / Selesai / Offline) --}}
                    <div class="bg-white rounded-[2.5rem] p-6 sm:p-8 border border-slate-100 shadow-xl shadow-slate-200/50 space-y-8">
                        
                        <!-- Pesan Awal Siswa -->
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 block">Pengajuan Masalah Kamu</label>
                            <div class="p-5 sm:p-6 bg-slate-50 rounded-3xl border border-slate-100 text-slate-700 leading-relaxed font-medium italic relative">
                                <i class="ph-fill ph-quotes text-slate-200 text-4xl absolute top-2 left-2 opacity-50"></i>
                                <span class="relative z-10">"{{ $session->initial_message }}"</span>
                            </div>
                        </div>

                        <!-- Jadwal Pertemuan (Jika Tatap Muka) -->
                        @if($session->status == 'approved' && $session->scheduled_at)
                        <div class="p-6 bg-blue-50 rounded-3xl border border-blue-100 relative overflow-hidden">
                            <i class="ph-duotone ph-calendar-check absolute right-4 top-4 text-6xl text-blue-200 opacity-50"></i>
                            <h4 class="text-xs font-black text-blue-600 uppercase tracking-wider mb-3 relative z-10">Informasi Pertemuan</h4>
                            <p class="text-xl sm:text-2xl font-black text-slate-800 mb-1 relative z-10">{{ $session->scheduled_at->translatedFormat('l, d F Y') }}</p>
                            <p class="text-sm font-bold text-slate-500 relative z-10 flex items-center gap-1.5">
                                <i class="ph-fill ph-clock"></i> Pukul {{ $session->scheduled_at->format('H:i') }} WIB <span class="mx-1">•</span> Ruang BK
                            </p>
                        </div>
                        @endif

                        <!-- Balasan Langsung / Info Tambahan dari Guru -->
                        @if($session->response_message)
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 block">Catatan dari Guru BK</label>
                            <div class="p-6 bg-indigo-50 rounded-3xl border border-indigo-100 text-slate-700 leading-relaxed font-bold">
                                {{ $session->response_message }}
                            </div>
                        </div>
                        @endif

                    </div>
                @endif

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Alpine JS Handler untuk Siswa (Dioptimalkan & Tahan Error)
    function bkStudentChatHandler(sessionId) {
        return {
            messages: [],
            newMessage: '',
            isSending: false,
            init() {
                this.fetchMessages();
                // Polling chat server setiap 3 detik
                setInterval(() => this.fetchMessages(), 3000);
            },
            fetchMessages() {
                fetch(`/student/bk/chat/${sessionId}`, {
                    headers: { 'Accept': 'application/json' } // Pastikan Server merespon JSON
                })
                .then(res => res.json())
                .then(data => {
                    let isNew = this.messages.length !== data.length;
                    this.messages = data;
                    if(isNew) this.scrollToBottom();
                })
                .catch(err => console.warn("Fetch polling error (diabaikan):", err));
            },
            send() {
                if (!this.newMessage.trim() || this.isSending) return;
                
                let text = this.newMessage;
                this.newMessage = '';
                this.isSending = true;

                // Optimistic UI (tampil instan di layar siswa)
                this.messages.push({
                    id: 'temp-' + Date.now(),
                    message: text,
                    sender_type: 'student',
                    created_at: new Date().toISOString()
                });
                this.scrollToBottom();
                
                fetch(`/student/bk/chat/${sessionId}`, {
                    method: 'POST',
                    headers: { 
                        'Content-Type': 'application/json', 
                        'Accept': 'application/json', // KRUSIAL agar tidak me-redirect jika error
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' 
                    },
                    body: JSON.stringify({ message: text })
                })
                .then(async res => {
                    if(!res.ok) {
                        // Ambil isi error dari Laravel Backend
                        let errText = await res.text();
                        let errMsg = "Terjadi Kesalahan Server.";
                        try {
                            let errJson = JSON.parse(errText);
                            errMsg = errJson.message || errMsg;
                        } catch(e) {}
                        throw new Error(errMsg);
                    }
                    return res.json();
                })
                .then(data => {
                    this.isSending = false;
                    this.fetchMessages(); // Ambil ID asli dari database
                })
                .catch(err => {
                    this.isSending = false;
                    this.messages.pop(); // Hapus pesan semu karena gagal
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal Terkirim',
                        text: err.message,
                        customClass: { popup: 'rounded-3xl shadow-xl' }
                    });
                });
            },
            scrollToBottom() {
                this.$nextTick(() => {
                    const box = this.$refs.chatBox;
                    if(box) box.scrollTop = box.scrollHeight;
                });
            },
            formatTime(iso) {
                if(!iso) return '';
                return new Date(iso).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            }
        }
    }
</script>
@endsection