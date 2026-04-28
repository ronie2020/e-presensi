<div x-data="studentLiaisonTabHandler()" x-init="init()" class="space-y-6">

    
    <div class="relative rounded-[2.5rem] bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 p-8 md:p-10 text-white shadow-2xl shadow-indigo-900/20 overflow-hidden border border-white/10 group">
        
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-purple-500 rounded-full mix-blend-overlay filter blur-[100px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-indigo-500 rounded-full mix-blend-overlay filter blur-[80px] opacity-10"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
            <div>
                <h2 class="text-2xl md:text-3xl font-black tracking-tight mb-2 leading-tight">Buku <span class="text-transparent bg-clip-text bg-gradient-to-r from-purple-300 to-indigo-200">Penghubung</span></h2>
                <p class="text-indigo-100/70 text-sm max-w-lg leading-relaxed font-medium">
                    Ruang komunikasi resmi antara Wali Kelas dan Orang Tua untuk memantau perkembangan siswa.
                </p>
            </div>

            
            <div class="bg-white/10 backdrop-blur-xl p-1.5 rounded-2xl flex border border-white/10 w-full md:w-auto shadow-inner">
                <button @click="mode = 'note'" 
                    :class="mode === 'note' ? 'bg-white text-indigo-900 shadow-lg' : 'text-indigo-100 hover:bg-white/5'"
                    class="flex-1 md:flex-none px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                    <i class="ph-fill ph-notebook text-lg"></i> Catatan
                </button>
                <button @click="mode = 'chat'; fetchMessages()" 
                    :class="mode === 'chat' ? 'bg-emerald-400 text-white shadow-lg' : 'text-indigo-100 hover:bg-white/5'"
                    class="flex-1 md:flex-none px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                    <i class="ph-fill ph-chat-circle-text text-lg"></i> Chat
                </button>
            </div>
        </div>
    </div>

    
    <div class="relative min-h-[500px]">
        
        
        <div x-show="mode === 'note'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <?php if(isset($liaison_messages) && $liaison_messages->count() > 0): ?>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <?php $__currentLoopData = $liaison_messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $config = match($msg->type ?? 'info') {
                                'warning' => ['icon' => 'ph-warning', 'accent' => 'bg-amber-500', 'bg' => 'bg-white', 'text' => 'text-amber-600'],
                                'achievement' => ['icon' => 'ph-trophy', 'accent' => 'bg-emerald-500', 'bg' => 'bg-white', 'text' => 'text-emerald-600'],
                                'call' => ['icon' => 'ph-phone-call', 'accent' => 'bg-rose-500', 'bg' => 'bg-rose-50/30', 'text' => 'text-rose-600'],
                                default => ['icon' => 'ph-info', 'accent' => 'bg-blue-600', 'bg' => 'bg-white', 'text' => 'text-blue-600'],
                            };
                        ?>
                        <div class="group bg-white rounded-[2.5rem] shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all border border-slate-100 overflow-hidden <?php echo e($config['bg']); ?> flex flex-col">
                            <div class="h-1.5 w-full <?php echo e($config['accent']); ?>"></div>
                            <div class="p-8 flex-1">
                                <div class="flex items-start justify-between mb-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center <?php echo e($config['text']); ?> shadow-inner border border-slate-100">
                                            <i class="ph-fill <?php echo e($config['icon']); ?> text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-black text-slate-800 text-lg leading-tight uppercase tracking-tight"><?php echo e($msg->title); ?></h3>
                                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1 block"><?php echo e($msg->created_at->format('d M Y | H:i')); ?></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-slate-50/50 p-6 rounded-[1.8rem] border border-slate-100">
                                    <p class="text-sm text-slate-600 leading-relaxed italic font-medium">"<?php echo e($msg->message); ?>"</p>
                                </div>
                            </div>
                            <div class="px-8 py-4 bg-slate-50/50 border-t border-slate-50 flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-[10px] font-black shadow-lg shadow-indigo-600/20">
                                        G
                                    </div>
                                    <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Guru Wali Kelas</span>
                                </div>
                                <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest">Resmi</span>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php else: ?>
                <div class="bg-white rounded-[3rem] p-20 text-center border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <i class="ph-duotone ph-notebook text-4xl"></i>
                    </div>
                    <h3 class="font-black text-slate-700 text-xl mb-2">Belum Ada Catatan</h3>
                    <p class="text-sm text-slate-400 font-medium max-w-xs mx-auto">Catatan perkembangan siswa dari guru akan muncul di halaman ini.</p>
                </div>
            <?php endif; ?>
        </div>

        
        <div x-show="mode === 'chat'" x-cloak x-transition>
            <div class="bg-white rounded-[2.5rem] shadow-xl border border-slate-100 overflow-hidden flex flex-col h-[600px]">
                
                
                <div class="p-5 border-b border-slate-50 bg-slate-50/50 flex items-center justify-between z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center text-2xl shadow-lg shadow-indigo-600/20">
                            <i class="ph-fill ph-chat-teardrop-dots"></i>
                        </div>
                        <div>
                            <h4 class="font-black text-slate-800 uppercase tracking-tight text-sm">Diskusi Wali Kelas</h4>
                            <p class="text-[9px] text-emerald-500 font-black uppercase tracking-widest flex items-center gap-1.5 mt-0.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Terhubung
                            </p>
                        </div>
                    </div>
                    <div class="hidden sm:block text-right">
                        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Respons Guru</p>
                        <p class="text-[10px] font-bold text-slate-600 uppercase">07:00 - 15:00 WIB</p>
                    </div>
                </div>

                
                <div class="flex-1 overflow-y-auto p-6 space-y-6 bg-slate-50/30 custom-scrollbar scroll-smooth relative" x-ref="chatBox">
                    
                    
                    <div x-show="loading" class="absolute inset-0 flex items-center justify-center bg-white/50 z-20 backdrop-blur-sm">
                        <div class="bg-white px-6 py-3 rounded-full shadow-lg border border-slate-100 text-[10px] font-black text-slate-400 flex items-center gap-3 uppercase tracking-widest">
                            <i class="ph-bold ph-spinner animate-spin text-indigo-500 text-lg"></i> Memuat Pesan...
                        </div>
                    </div>

                    <template x-for="msg in messages" :key="msg.id">
                        <div class="flex w-full animate-enter" :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'justify-end' : 'justify-start'">
                            <div class="max-w-[85%] md:max-w-[70%] group flex flex-col" :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'items-end' : 'items-start'">
                                
                                
                                <p x-show="msg.sender_type === 'teacher'" class="text-[9px] font-black text-indigo-400 mb-1 uppercase tracking-widest ml-1">Wali Kelas</p>

                                
                                <div class="p-4 rounded-[2rem] text-sm font-medium leading-relaxed shadow-sm transition-all relative"
                                     :class="msg.sender_type === 'parent' || msg.sender_type === 'student'
                                        ? 'bg-indigo-600 text-white rounded-br-none shadow-indigo-600/10' 
                                        : 'bg-white text-slate-700 border border-slate-100 rounded-bl-none shadow-slate-200/50'">
                                    <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                </div>
                                
                                
                                <p class="text-[9px] font-bold mt-1.5 uppercase tracking-widest opacity-40 px-2"
                                   :class="msg.sender_type === 'parent' || msg.sender_type === 'student' ? 'text-right' : 'text-left'">
                                   <span x-text="formatTime(msg.created_at)"></span>
                                </p>
                            </div>
                        </div>
                    </template>
                    
                    
                    <div x-show="messages.length === 0 && !loading" class="flex flex-col items-center justify-center h-full opacity-50">
                        <i class="ph-duotone ph-chat-centered-text text-6xl text-slate-300 mb-2"></i>
                        <p class="text-xs font-bold text-slate-400">Belum ada percakapan</p>
                    </div>
                </div>

                
                <div class="p-5 bg-white border-t border-slate-50">
                    <form @submit.prevent="sendMessage()" class="flex items-center gap-3">
                        <div class="flex-1 bg-slate-100 rounded-2xl flex items-center px-5 border-2 border-transparent focus-within:bg-white focus-within:border-indigo-100 focus-within:ring-4 focus-within:ring-indigo-50 transition-all">
                            <input x-model="newMessage" type="text" placeholder="Tulis pesan untuk guru..." 
                                class="w-full bg-transparent border-none focus:ring-0 py-4 text-sm font-bold text-slate-700 placeholder:text-slate-400" :disabled="sending">
                        </div>
                        <button type="submit" :disabled="!newMessage.trim() || sending"
                            class="w-14 h-14 rounded-2xl bg-indigo-600 text-white flex items-center justify-center shadow-xl shadow-indigo-600/20 disabled:opacity-40 hover:bg-indigo-700 transition-all transform active:scale-95 disabled:scale-100">
                            <i x-show="!sending" class="ph-bold ph-paper-plane-right text-xl"></i>
                            <i x-show="sending" class="ph-bold ph-spinner animate-spin text-xl"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        function studentLiaisonTabHandler() {
            return {
                mode: 'note', 
                messages: [],
                newMessage: '',
                loading: false,
                sending: false,
                pollInterval: null,

                init() {
                    this.startPolling();
                    this.$watch('mode', value => {
                        if (value === 'chat') {
                            this.fetchMessages();
                            this.scrollToBottom();
                        }
                    });
                },

                startPolling() {
                    // Refresh chat setiap 5 detik jika tab chat aktif
                    if(this.pollInterval) clearInterval(this.pollInterval);
                    this.pollInterval = setInterval(() => {
                        if (this.mode === 'chat') {
                            this.fetchMessages(true);
                        }
                    }, 5000);
                },

                fetchMessages(silent = false) {
                    if (!silent) this.loading = true;
                    // Pastikan route ini sesuai dengan route di web.php Anda
                    const url = "<?php echo e(route('student.liaison.chat.messages')); ?>?student_id=<?php echo e($student->id); ?>";
                    
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            this.messages = data;
                            if (!silent) {
                                this.loading = false;
                                this.scrollToBottom();
                            }
                        })
                        .catch(err => {
                            console.error("Gagal memuat pesan:", err);
                            if(!silent) this.loading = false;
                        });
                },

                sendMessage() {
                    if (!this.newMessage.trim()) return;
                    this.sending = true;
                    let msgText = this.newMessage;
                    this.newMessage = ''; 
                    
                    // Optimistic UI Update
                    const tempId = Date.now();
                    this.messages.push({ 
                        id: tempId, 
                        message: msgText, 
                        sender_type: 'student', // Asumsi pengirim di portal ini adalah siswa/ortu
                        created_at: new Date().toISOString() 
                    });
                    this.scrollToBottom();
                    
                    const payload = { 
                        message: msgText, 
                        student_id: "<?php echo e($student->id); ?>" // Penting: sertakan ID siswa target
                    };

                    fetch("<?php echo e(route('student.liaison.chat.send')); ?>", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" },
                        body: JSON.stringify(payload)
                    }).then(res => {
                        if(!res.ok) throw new Error('Network response was not ok');
                        return res.json();
                    }).then(() => { 
                        this.sending = false;
                        this.fetchMessages(true); 
                    }).catch((error) => { 
                        console.error('Error:', error);
                        this.sending = false; 
                        // Opsi: Tampilkan alert error jika gagal
                    });
                },

                scrollToBottom() { 
                    setTimeout(() => { 
                        const box = this.$refs.chatBox; 
                        if (box) box.scrollTop = box.scrollHeight; 
                    }, 100); 
                },
                
                formatTime(iso) { 
                    return new Date(iso).toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}); 
                }
            }
        }
    </script>
</div><?php /**PATH C:\Users\ronie\Documents\aplikasi terpadu\sistem_absensi_sekolah\resources\views/students/portal/partials/tab-penghubung.blade.php ENDPATH**/ ?>