<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .glass-card { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.5); }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        [x-cloak] { display: none !important; }
        .animate-bounce-slow { animation: bounce 3s infinite; }
        @keyframes bounce { 0%, 100% { transform: translateY(-5%); } 50% { transform: translateY(0); } }
    </style>

    
    <div class="font-jakarta p-4 md:p-8 space-y-8 min-h-screen bg-slate-50" x-data="liaisonHandler()" x-init="init()">
        
        
        <div class="animate-enter relative rounded-[3rem] bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 md:p-12 text-white shadow-2xl shadow-blue-900/30 overflow-hidden group border border-white/10">
            <div class="absolute top-0 right-0 -mt-20 -mr-20 w-[500px] h-[500px] bg-blue-500/20 rounded-full blur-[100px] group-hover:opacity-40 transition-opacity duration-1000"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-10">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="inline-flex bg-white/10 backdrop-blur-md rounded-2xl p-1.5 border border-white/10 shadow-inner">
                            <button @click="mode = 'note'" 
                                    :class="mode === 'note' ? 'bg-blue-600 text-white shadow-lg' : 'text-blue-200 hover:text-white'"
                                    class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all flex items-center gap-2">
                                <i class="ph-fill ph-book-open"></i> Buku Penghubung
                            </button>
                            <button @click="mode = 'chat'; fetchChatContacts()" 
                                    :class="mode === 'chat' ? 'bg-emerald-600 text-white shadow-lg' : 'text-blue-200 hover:text-white'"
                                    class="px-6 py-3 rounded-xl text-[10px] font-black uppercase tracking-[0.15em] transition-all flex items-center gap-2 relative">
                                <i class="ph-fill ph-chats-circle"></i> Diskusi Ortu
                                <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border border-white"></span>
                            </button>
                        </div>
                    </div>

                    <h1 class="text-4xl md:text-6xl font-black tracking-tighter mb-4 leading-none">
                        <span x-text="mode === 'note' ? 'Pusat Catatan' : 'Pesan Masuk'"></span>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-200">Sekolah</span>
                    </h1>
                    <p class="text-blue-100/60 text-sm md:text-lg font-medium leading-relaxed max-w-lg">
                        Pantau kedisiplinan dan jalin komunikasi dua arah yang efektif dengan orang tua siswa setiap saat.
                    </p>
                </div>
            </div>
        </div>

        
        <div class="animate-enter" style="animation-delay: 100ms">
            
            
            <div x-show="mode === 'note'" x-transition class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                
                <div class="lg:col-span-7">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="p-8 md:p-10">
                            <div class="flex items-center gap-4 mb-10">
                                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-[1.5rem] flex items-center justify-center text-3xl shadow-inner">
                                    <i class="ph-duotone ph-pencil-line"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-black text-slate-800 tracking-tight">Kirim Catatan Baru</h3>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Sampaikan informasi resmi kepada wali murid</p>
                                </div>
                            </div>

                            <form action="<?php echo e(route('liaison.store')); ?>" method="POST" class="space-y-8">
                                <?php echo csrf_field(); ?>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pilih Target Kelas</label>
                                        <select x-model="selectedClass" @change="fetchStudents()" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 py-4 px-5 transition-all appearance-none">
                                            <option value="">-- Pilih Kelas --</option>
                                            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($class->id); ?>"><?php echo e($class->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Pilih Nama Siswa</label>
                                        <select name="student_id" :disabled="!selectedClass || isLoading" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 py-4 px-5 transition-all disabled:opacity-40">
                                            <option value="">-- Pilih Siswa --</option>
                                            <template x-for="student in students" :key="student.id">
                                                <option :value="student.id" x-text="student.name"></option>
                                            </template>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="md:col-span-2 space-y-3">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Subjek / Judul</label>
                                        <input type="text" name="title" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-50 font-bold text-slate-800 py-4 px-5 placeholder:font-medium" placeholder="Misal: Apresiasi Prestasi Siswa" required>
                                    </div>
                                    <div class="space-y-3">
                                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Kategori</label>
                                        <select name="type" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-50 font-bold text-slate-700 py-4 px-5">
                                            <option value="info">📢 Informasi</option>
                                            <option value="warning">⚠️ Peringatan</option>
                                            <option value="achievement">🏆 Prestasi</option>
                                            <option value="call">📞 Panggilan</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1">Isi Detail Pesan</label>
                                    <textarea name="message" rows="5" class="w-full rounded-[2rem] border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-4 focus:ring-blue-50 text-sm p-6 text-slate-700 font-medium placeholder:text-slate-400 leading-relaxed" placeholder="Tulis rincian catatan untuk disampaikan ke orang tua..." required></textarea>
                                </div>
                                <div class="flex justify-end pt-4">
                                    <button type="submit" class="w-full md:w-auto py-5 px-12 bg-blue-600 text-white font-black rounded-2xl hover:bg-blue-700 transition-all shadow-xl shadow-blue-600/30 text-[10px] uppercase tracking-[0.2em] flex items-center justify-center gap-3 active:scale-95 group">
                                        <i class="ph-bold ph-paper-plane-right text-xl transition-transform group-hover:translate-x-1"></i>
                                        Publikasikan Catatan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div x-show="mode === 'note'" class="lg:col-span-5 h-full">
                    
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden flex flex-col h-[650px]">
                        <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-3">
                                <i class="ph-bold ph-clock-counter-clockwise text-blue-600"></i> Riwayat
                            </h3>
                            <span class="bg-blue-50 text-[10px] font-black px-4 py-1.5 rounded-full text-blue-600 uppercase tracking-widest"><?php echo e($messages->total()); ?> Data</span>
                        </div>
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-4 bg-slate-50/30">
                            <?php $__empty_1 = true; $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $msg): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="p-6 bg-white rounded-[2rem] border border-slate-100 hover:border-blue-200 transition-all group shadow-sm">
                                    <div class="flex items-center justify-between mb-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-[10px] uppercase">
                                                <?php echo e(substr($msg->student->name ?? 'S', 0, 1)); ?>

                                            </div>
                                            <div>
                                                <p class="text-xs font-black text-slate-800"><?php echo e($msg->student->name ?? 'Siswa'); ?></p>
                                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo e($msg->student->schoolClass->name ?? '-'); ?></p>
                                            </div>
                                        </div>
                                        <span class="text-[9px] font-bold text-slate-400"><?php echo e($msg->created_at->diffForHumans()); ?></span>
                                    </div>
                                    <h4 class="font-black text-slate-700 text-sm mb-2 group-hover:text-blue-600 transition-colors uppercase tracking-tight"><?php echo e($msg->title); ?></h4>
                                    <p class="text-[11px] text-slate-500 line-clamp-2 italic leading-relaxed">"<?php echo e($msg->message); ?>"</p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="text-center py-20 opacity-30">
                                    <i class="ph-duotone ph-notebook text-5xl mb-4"></i>
                                    <p class="text-xs font-black uppercase tracking-widest">Kosong</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div x-show="mode === 'chat'" x-cloak x-transition>
                <div class="bg-white rounded-[3rem] shadow-2xl border border-slate-100 overflow-hidden h-[75vh] flex relative">
                    
                    
                    <div class="w-full md:w-80 border-r border-slate-50 flex flex-col bg-slate-50/50" :class="activeContact ? 'hidden md:flex' : 'flex'">
                        <div class="p-8 border-b border-slate-50 bg-white">
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                                <i class="ph-fill ph-users-three text-blue-600"></i> Daftar Ortu
                            </h3>
                            <div class="relative group">
                                <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                <input x-model="chatSearch" @input.debounce.500ms="fetchChatContacts()" type="text" placeholder="Cari nama siswa..." class="w-full pl-11 pr-4 py-3.5 bg-slate-100 border-transparent focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 rounded-2xl text-xs font-bold transition-all">
                            </div>
                        </div>
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-2">
                            <template x-for="contact in chatContacts" :key="contact.id">
                                <button @click="selectContact(contact)" 
                                    :class="activeContact && activeContact.id === contact.id ? 'bg-white border-blue-100 shadow-md ring-1 ring-blue-500/10' : 'hover:bg-white/60'"
                                    class="w-full p-5 flex items-start gap-4 rounded-[1.8rem] border border-transparent transition-all text-left group">
                                    <div class="w-12 h-12 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-slate-600 shrink-0 font-black shadow-sm group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-500 transition-all">
                                        <span x-text="getInitials(contact.name)"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-center mb-1">
                                            <h4 class="font-black text-slate-700 text-xs truncate uppercase tracking-tight" x-text="contact.name"></h4>
                                            <span class="text-[8px] font-black text-slate-300" x-text="formatTime(contact.last_message_time)"></span>
                                        </div>
                                        
                                        <p class="text-[9px] font-black text-blue-500 uppercase tracking-widest mb-1" x-text="getClassName(contact)"></p>
                                        
                                        <p class="text-[11px] text-slate-400 truncate font-medium" 
                                           :class="contact.unread_count > 0 ? 'font-black text-slate-800' : ''"
                                           x-text="contact.last_message ? contact.last_message : 'Mulai obrolan baru'"></p>
                                    </div>
                                </button>
                            </template>
                        </div>
                    </div>

                    
                    <div class="flex-1 flex flex-col bg-white relative" :class="activeContact ? 'flex' : 'hidden md:flex'">
                        <div x-show="!activeContact" class="flex-1 flex flex-col items-center justify-center text-center p-12 bg-slate-50/30">
                            <div class="w-24 h-24 bg-white rounded-[2.5rem] shadow-xl flex items-center justify-center mb-10 animate-bounce-slow border border-slate-50">
                                <i class="ph-duotone ph-chats-circle text-5xl text-blue-500"></i>
                            </div>
                            <h3 class="text-3xl font-black text-slate-800 tracking-tight">Pilih Kontak</h3>
                            <p class="text-slate-400 max-w-xs font-medium text-sm mt-2">Silakan pilih salah satu orang tua siswa untuk memulai diskusi dua arah.</p>
                        </div>

                        <div x-show="activeContact" class="flex-1 flex flex-col h-full" x-transition>
                            <div class="bg-white border-b border-slate-50 p-6 flex items-center justify-between shadow-sm z-10">
                                <div class="flex items-center gap-4">
                                    <button @click="activeContact = null" class="md:hidden p-2 text-slate-400 hover:text-blue-600"><i class="ph-bold ph-arrow-left text-2xl"></i></button>
                                    <div class="w-12 h-12 rounded-2xl bg-blue-600 text-white flex items-center justify-center font-black text-xl shadow-lg shadow-blue-600/20">
                                        <span x-text="getInitials(activeContact?.name)"></span>
                                    </div>
                                    <div>
                                        <h3 class="font-black text-slate-800 text-base uppercase tracking-tight" x-text="activeContact?.name"></h3>
                                        
                                        
                                        <div class="flex items-center gap-2 mt-0.5">
                                            <p class="text-[10px] font-black text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md uppercase tracking-wide" 
                                               x-text="getClassName(activeContact)"></p>
                                            
                                            <p class="text-[10px] text-emerald-500 font-black uppercase tracking-widest flex items-center gap-1.5">
                                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Online
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-1 overflow-y-auto p-8 bg-slate-50/50 space-y-6 custom-scrollbar scroll-smooth" x-ref="chatBox">
                                <template x-for="msg in chatMessages" :key="msg.id">
                                    <div class="flex w-full" :class="msg.sender_type === 'teacher' ? 'justify-end' : 'justify-start'">
                                        <div class="max-w-[80%] md:max-w-[65%]">
                                            <div class="p-5 rounded-[2rem] text-[13px] font-medium leading-relaxed shadow-sm transition-all"
                                                 :class="msg.sender_type === 'teacher' 
                                                    ? 'bg-blue-600 text-white rounded-br-none shadow-blue-600/10' 
                                                    : 'bg-white text-slate-700 border border-slate-100 rounded-bl-none shadow-slate-100/50'">
                                                <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                            </div>
                                            <p class="text-[9px] font-black text-slate-400 mt-2 uppercase tracking-widest px-2"
                                               :class="msg.sender_type === 'teacher' ? 'text-right' : 'text-left'"
                                               x-text="formatTime(msg.created_at, true)"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <div class="bg-white p-6 border-t border-slate-50">
                                <form @submit.prevent="sendMessage()" class="flex items-center gap-4">
                                    <div class="flex-1 bg-slate-100 rounded-2xl flex items-center px-6 border-2 border-transparent focus-within:bg-white focus-within:border-blue-100 focus-within:ring-4 focus-within:ring-blue-50 transition-all">
                                        <input x-model="newMessage" type="text" placeholder="Tulis pesan anda di sini..." class="w-full bg-transparent border-none focus:ring-0 py-4 text-sm font-bold text-slate-700">
                                    </div>
                                    <button type="submit" :disabled="!newMessage.trim()" class="w-14 h-14 rounded-2xl bg-blue-600 text-white hover:bg-blue-700 shadow-xl flex items-center justify-center shrink-0 transition-all active:scale-90">
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
                chatSearch: '',
                chatContacts: [],
                activeContact: null,
                chatMessages: [],
                newMessage: '',
                pollInterval: null, // ID Interval

                // Init dijalankan otomatis
                init() {
                    this.startPolling();
                },

                startPolling() {
                    this.pollInterval = setInterval(() => {
                        // Hanya refresh jika di tab chat
                        if (this.mode === 'chat') {
                            // 1. Refresh kontak (untuk cek notif baru)
                            this.fetchChatContacts(true); // true = silent

                            // 2. Jika sedang membuka chat seseorang, refresh isinya
                            if (this.activeContact) {
                                this.fetchChatMessages(this.activeContact.id, true);
                            }
                        }
                    }, 3000); // 3 Detik
                },

                fetchStudents() {
                    if (!this.selectedClass) { this.students = []; return; }
                    this.isLoading = true;
                    fetch("<?php echo e(route('liaison.get_students', ':id')); ?>".replace(':id', this.selectedClass))
                    .then(res => res.json()).then(data => { this.students = data; this.isLoading = false; });
                },

                // Added silent param
                fetchChatContacts(silent = false) {
                    let url = "<?php echo e(route('liaison.chat.contacts')); ?>";
                    if (this.chatSearch) url += "?search=" + this.chatSearch;
                    
                    fetch(url, { headers: { 'Accept': 'application/json' }})
                    .then(res => res.json()).then(data => { 
                        // Update data kontak tanpa loading spinner
                        this.chatContacts = data.data || data; 
                    });
                },

                selectContact(contact) {
                    this.activeContact = contact;
                    this.chatMessages = []; // Kosongkan saat switch user
                    this.fetchChatMessages(contact.id);
                },

                // Added silent param
                fetchChatMessages(studentId, silent = false) {
                    fetch("<?php echo e(route('liaison.chat.messages', ':id')); ?>".replace(':id', studentId))
                    .then(res => res.json()).then(data => {
                        this.chatMessages = data;
                        if (!silent) this.scrollToBottom(); // Scroll hanya jika bukan silent update
                    });
                },

                sendMessage() {
                    if (!this.newMessage.trim() || !this.activeContact) return;
                    let msgText = this.newMessage;
                    let currentContactId = this.activeContact.id; 
                    this.newMessage = '';
                    
                    // Optimistic Update
                    this.chatMessages.push({ id: Date.now(), message: msgText, sender_type: 'teacher', created_at: new Date().toISOString() });
                    this.scrollToBottom();

                    fetch("<?php echo e(route('liaison.chat.send')); ?>", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>" },
                        body: JSON.stringify({ student_id: currentContactId, message: msgText })
                    }).then(() => {
                        // Refresh segera setelah kirim untuk sinkronisasi
                        this.fetchChatMessages(currentContactId, true);
                        this.fetchChatContacts(true);
                    });
                },

                scrollToBottom() { setTimeout(() => { let b = this.$refs.chatBox; if(b) b.scrollTop = b.scrollHeight; }, 100); },
                formatTime(iso, onlyTime = false) {
                    if(!iso) return '';
                    let d = new Date(iso);
                    return onlyTime ? d.toLocaleTimeString('id-ID', {hour:'2-digit', minute:'2-digit'}) : d.toLocaleDateString('id-ID', {day:'numeric', month:'short'});
                },
                getInitials(n) { return n ? n.split(' ').map(x => x[0]).join('').substring(0, 2).toUpperCase() : ''; },
                
                // Helper untuk mengambil nama kelas dengan berbagai kemungkinan
                getClassName(contact) {
                    if (!contact) return '-';
                    // Cek relasi standar Laravel (biasanya snake_case di JSON)
                    if (contact.school_class && contact.school_class.name) return contact.school_class.name;
                    // Cek jika relasi bernama classroom
                    if (contact.classroom && contact.classroom.name) return contact.classroom.name;
                    // Cek jika data ada langsung di atribut (flat)
                    if (contact.class_name) return contact.class_name;
                    if (contact.kelas) return contact.kelas;
                    return '-';
                }
            }
        }
    </script>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH E:\aplikasi terpadu\sistem_absensi_sekolah\resources\views\liaison\index.blade.php ENDPATH**/ ?>