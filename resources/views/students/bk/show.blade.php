@extends('layouts.public')

@section('content')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
    [x-cloak] { display: none !important; }
</style>

<div class="min-h-screen bg-[#020b18] font-sans text-slate-100 py-6 md:py-10 pt-24" x-data="bkStudentChatHandler({{ $session->id }})">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb / Back -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
            <a href="{{ route('student.bk.index') }}" class="group inline-flex items-center text-sm text-[#56bbf1] hover:text-white font-bold transition-colors">
                <i class="ph-bold ph-arrow-left mr-2 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Riwayat
            </a>
            <div class="px-4 py-1.5 rounded-full bg-white/10 border border-white/10 text-xs font-mono font-bold text-[#56bbf1] shadow-sm">
                ID TIKET: #{{ str_pad($session->id, 5, '0', STR_PAD_LEFT) }}
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- ========================================== -->
            <!-- KOLOM KIRI: STATUS & INFO SISWA            -->
            <!-- ========================================== -->
            <div class="space-y-6">
                <!-- Card Status -->
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-[2rem] p-6 border border-white/10 shadow-2xl relative overflow-hidden text-white">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-[#56bbf1]/5 rounded-full -mr-16 -mt-16 pointer-events-none"></div>
                    
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
                                'pending' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',
                                'approved' => 'bg-[#56bbf1]/10 text-[#56bbf1] border-[#56bbf1]/20',
                                'ongoing' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/20',
                                'finished' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20',
                                'rejected' => 'bg-rose-500/10 text-rose-400 border-rose-500/20'
                            ];
                        @endphp
                        <div class="px-4 py-2 rounded-xl border {{ $statusColor[$session->status] }} text-xs font-black uppercase tracking-wider shadow-sm">
                            {{ $statusLabel[$session->status] }}
                        </div>
                    </div>

                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-xs font-bold p-3 bg-white/5 rounded-xl border border-white/10">
                            <span class="text-slate-400 uppercase tracking-tighter">Metode</span>
                            <span class="text-white uppercase flex items-center gap-1">
                                <i class="ph-fill {{ $session->method == 'online' ? 'ph-globe text-[#56bbf1]' : 'ph-users text-[#56bbf1]' }}"></i>
                                {{ strtoupper($session->method) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-xs font-bold p-3 bg-white/5 rounded-xl border border-white/10">
                            <span class="text-slate-400 uppercase tracking-tighter">Topik Utama</span>
                            <span class="text-white uppercase">{{ $session->category->name ?? '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Guru (Jika Sudah Direspon) -->
                @if($session->teacher_id)
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-[2rem] p-6 border border-white/10 shadow-2xl text-white">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Guru Pembimbing</h3>
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] flex items-center justify-center text-white text-xl shadow-md">
                            <i class="ph-fill ph-user-focus"></i>
                        </div>
                        <div>
                            <p class="font-black text-white text-sm leading-tight">{{ $session->teacher->name }}</p>
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
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden flex flex-col h-[600px]">
                    
                    <!-- Chat Header -->
                    <div class="p-5 sm:p-6 bg-white/5 text-white flex items-center gap-4 z-10 border-b border-white/10 shadow-md">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] flex items-center justify-center text-2xl shadow-md">
                            <i class="ph-fill ph-chats-circle"></i>
                        </div>
                        <div>
                            <h3 class="font-black text-base sm:text-lg leading-tight text-white">Konseling Online</h3>
                            <div class="flex items-center gap-1.5 mt-1">
                                <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                                <p class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-emerald-400">Terhubung dengan Guru BK</p>
                            </div>
                        </div>
                    </div>

                    <!-- Area Chat -->
                    <div class="flex-1 overflow-y-auto p-4 sm:p-6 space-y-4 bg-[#020b18]/70 custom-scrollbar" x-ref="chatBox">
                        <!-- Pesan Kosong -->
                        <div class="text-center py-4 opacity-60" x-show="messages.length === 0">
                            <span class="bg-white/10 text-slate-300 border border-white/10 px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider">Mulai Percakapan</span>
                        </div>

                        <template x-for="msg in messages" :key="msg.id">
                            <div :class="msg.sender_type === 'student' ? 'flex justify-end' : 'flex justify-start'">
                                <div :class="msg.sender_type === 'student' ? 'bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white rounded-2xl rounded-tr-sm shadow-md' : 'bg-[#021124] text-white border border-white/10 rounded-2xl rounded-tl-sm shadow-sm'"
                                     class="max-w-[85%] sm:max-w-[75%] p-3 sm:p-4 shadow-md relative group">
                                    <p class="text-sm font-medium leading-relaxed break-words" x-text="msg.message"></p>
                                    <div class="flex justify-end items-center gap-1 mt-1 opacity-70">
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
                    <div class="p-4 sm:p-5 bg-white/5 border-t border-white/10 z-10">
                        <div class="flex gap-2 sm:gap-3 relative">
                            <input type="text" x-model="newMessage" @keydown.enter="send()" placeholder="Ketik balasan pesan di sini..." 
                                class="flex-1 rounded-2xl border-white/15 bg-[#021124]/90 text-sm font-medium focus:bg-[#021124] focus:border-[#56bbf1] focus:ring-[#56bbf1] px-4 py-3 sm:py-4 transition-all text-white placeholder:text-slate-500">
                            
                            <button @click="send()" :disabled="isSending" class="w-12 h-12 sm:w-14 sm:h-14 bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white rounded-2xl flex items-center justify-center hover:brightness-110 shadow-xl shadow-[#56bbf1]/20 transition-all disabled:opacity-50 active:scale-95 shrink-0">
                                <i class="ph-bold ph-paper-plane-right text-lg sm:text-xl" x-show="!isSending"></i>
                                <i class="ph-bold ph-spinner animate-spin text-lg sm:text-xl" x-show="isSending" x-cloak></i>
                            </button>
                        </div>
                    </div>
                </div>

                @else
                    {{-- 2. TAMPILAN PESAN STANDAR (Jika Masih Pending / Selesai / Offline) --}}
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-[2.5rem] p-6 sm:p-8 border border-white/10 shadow-2xl space-y-8 text-white">
                        
                        <!-- Pesan Awal Siswa -->
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 block">Pengajuan Masalah Kamu</label>
                            <div class="p-5 sm:p-6 bg-white/5 rounded-3xl border border-white/10 text-slate-200 leading-relaxed font-medium italic relative">
                                <i class="ph-fill ph-quotes text-white/10 text-4xl absolute top-2 left-2 pointer-events-none"></i>
                                <span class="relative z-10">"{{ $session->initial_message }}"</span>
                            </div>
                        </div>

                        <!-- Jadwal Pertemuan (Jika Tatap Muka) -->
                        @if($session->status == 'approved' && $session->scheduled_at)
                        <div class="p-6 bg-[#56bbf1]/10 rounded-3xl border border-[#56bbf1]/20 relative overflow-hidden">
                            <i class="ph-duotone ph-calendar-check absolute right-4 top-4 text-6xl text-[#56bbf1] opacity-20 pointer-events-none"></i>
                            <h4 class="text-xs font-black text-[#56bbf1] uppercase tracking-wider mb-3 relative z-10">Informasi Pertemuan</h4>
                            <p class="text-xl sm:text-2xl font-black text-white mb-1 relative z-10">{{ $session->scheduled_at->translatedFormat('l, d F Y') }}</p>
                            <p class="text-sm font-bold text-slate-300 relative z-10 flex items-center gap-1.5">
                                <i class="ph-fill ph-clock text-[#56bbf1]"></i> Pukul {{ $session->scheduled_at->format('H:i') }} WIB <span class="mx-1">•</span> Ruang BK
                            </p>
                        </div>
                        @endif

                        <!-- Balasan Langsung / Info Tambahan dari Guru -->
                        @if($session->response_message)
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4 block">Catatan dari Guru BK</label>
                            <div class="p-6 bg-white/5 rounded-3xl border border-white/10 text-slate-200 leading-relaxed font-bold">
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