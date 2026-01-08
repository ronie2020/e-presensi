<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Tambahkan x-data 'mode' di sini untuk mengatur tab --}}
    <div class="py-6 sm:py-8 font-sans text-slate-800" x-data="liaisonHandler()">
        
        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-indigo-900 via-purple-900 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-indigo-900/40 overflow-hidden border border-white/10 group transition-all duration-500"
                 :class="mode === 'chat' ? 'h-auto pb-6' : ''">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-purple-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-purple-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content & Tabs --}}
                    <div class="max-w-2xl w-full">
                        <div class="flex items-center gap-4 mb-4">
                            {{-- Tab Switcher --}}
                            <div class="inline-flex bg-white/10 backdrop-blur-md rounded-xl p-1 border border-white/10">
                                <button @click="mode = 'note'" 
                                        :class="mode === 'note' ? 'bg-indigo-500 text-white shadow-lg' : 'text-indigo-200 hover:text-white hover:bg-white/5'"
                                        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2">
                                    <i class="ph-fill ph-book-bookmark"></i> Catatan
                                </button>
                                <button @click="mode = 'chat'; fetchChatContacts()" 
                                        :class="mode === 'chat' ? 'bg-emerald-500 text-white shadow-lg' : 'text-indigo-200 hover:text-white hover:bg-white/5'"
                                        class="px-4 py-2 rounded-lg text-xs font-bold uppercase tracking-wider transition-all flex items-center gap-2 relative">
                                    <i class="ph-fill ph-chats-circle"></i> Pesan Ortu
                                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full animate-ping"></span>
                                    <span class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-rose-500 rounded-full border border-white"></span>
                                </button>
                            </div>
                        </div>

                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2 flex items-center gap-3 text-white leading-tight">
                            <span x-text="mode === 'note' ? 'Buku Penghubung Digital' : 'Pesan & Diskusi'"></span>
                        </h1>
                        <p class="text-indigo-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg" x-text="mode === 'note' ? 'Kirim catatan akademik, peringatan disiplin, atau apresiasi prestasi.' : 'Komunikasi langsung dua arah dengan orang tua siswa secara real-time.'"></p>
                    </div>
                    
                    {{-- Stats Cards (Hanya Tampil di Mode Note) --}}
                    <div class="w-full md:w-auto mt-4 md:mt-0" x-show="mode === 'note'" x-transition>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/10 backdrop-blur-md px-5 py-5 rounded-2xl border border-white/10 text-center md:text-left hover:bg-white/15 transition-colors">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-indigo-300">
                                    <i class="ph-duotone ph-note-pencil text-2xl"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Total Catatan</span>
                                </div>
                                <span class="block text-3xl font-black text-white tracking-tight mt-1">{{ $messages->total() }}</span>
                            </div>
                            <div class="bg-emerald-500/20 backdrop-blur-md px-5 py-5 rounded-2xl border border-emerald-400/20 text-center md:text-left hover:bg-emerald-500/30 transition-colors">
                                <div class="flex flex-col md:flex-row lg:flex-col items-center justify-center md:justify-start gap-2 mb-1 text-emerald-300">
                                    <i class="ph-duotone ph-check-circle text-2xl"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Tersampaikan</span>
                                </div>
                                <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                                    <span class="relative flex h-3 w-3">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                    </span>
                                    <span class="text-sm font-black text-white tracking-tight">Aktif</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Main Content Container --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Flash Messages --}}
            @if(session('success'))
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success', title: 'Berhasil', text: "{{ session('success') }}",
                            timer: 3000, showConfirmButton: false, toast: true, position: 'top-end',
                            background: '#ecfdf5', color: '#064e3b', iconColor: '#10b981'
                        });
                    });
                </script>
            @endif

            {{-- MODE 1: CATATAN (FORM & LIST) --}}
            <div x-show="mode === 'note'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                    
                    {{-- KOLOM KIRI (2/3): FORM INPUT --}}
                    <div class="lg:col-span-2 space-y-8">
                        <!-- FORM CARD -->
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:shadow-indigo-900/10 transition-all duration-300">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-600 to-purple-500"></div>
                            
                            <div class="p-6 sm:p-8 relative z-10">
                                <div class="flex items-center gap-4 mb-6 pb-4 border-b border-slate-50">
                                    <div class="w-12 h-12 sm:w-14 sm:h-14 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl sm:text-3xl shadow-sm border border-indigo-100 shrink-0">
                                        <i class="ph-duotone ph-pencil-simple-line"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-lg sm:text-xl font-black text-slate-800">Tulis Catatan Baru</h3>
                                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Pilih siswa dan sampaikan pesan resmi</p>
                                    </div>
                                </div>

                                <form action="{{ route('liaison.store') }}" method="POST" class="space-y-6">
                                    @csrf
                                    {{-- 1. PILIH TARGET (Kelas & Siswa) --}}
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                                            <div class="relative">
                                                <select x-model="selectedClass" @change="fetchStudents()" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 font-bold text-slate-700 py-3 px-4 appearance-none transition-colors cursor-pointer">
                                                    <option value="">-- Pilih Kelas --</option>
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                            <div class="relative">
                                                <select name="student_id" :disabled="!selectedClass || isLoading" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 font-bold text-slate-700 py-3 px-4 appearance-none transition-colors cursor-pointer disabled:bg-slate-100 disabled:text-slate-400">
                                                    <option value="">-- Pilih Siswa --</option>
                                                    <template x-for="student in students" :key="student.id">
                                                        <option :value="student.id" x-text="student.name"></option>
                                                    </template>
                                                </select>
                                                <i x-show="!isLoading" class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                                <i x-show="isLoading" class="ph-bold ph-spinner animate-spin absolute right-4 top-1/2 -translate-y-1/2 text-indigo-500 pointer-events-none"></i>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 2. JUDUL & JENIS PESAN --}}
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                                        <div class="md:col-span-2">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul / Topik</label>
                                            <input type="text" name="title" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 font-bold text-slate-800 py-3 px-4 placeholder:font-normal" placeholder="Contoh: Keterlambatan Berulang" required>
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Catatan</label>
                                            <div class="relative">
                                                <select name="type" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 font-bold text-slate-700 py-3 px-4 appearance-none cursor-pointer">
                                                    <option value="info">📢 Informasi</option>
                                                    <option value="warning">⚠️ Peringatan</option>
                                                    <option value="achievement">🏆 Prestasi</option>
                                                    <option value="call">📞 Panggilan</option>
                                                </select>
                                                <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- 3. ISI PESAN --}}
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Isi Catatan</label>
                                        <textarea name="message" rows="4" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-indigo-600 focus:ring-indigo-600 text-sm p-4 text-slate-700 font-medium placeholder:text-slate-400" placeholder="Tulis pesan lengkap yang akan disampaikan kepada siswa dan orang tua..." required></textarea>
                                    </div>

                                    {{-- BUTTON KIRIM --}}
                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="w-full sm:w-auto py-3 px-8 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20 text-sm flex items-center justify-center gap-2 transform active:scale-95 group">
                                            <i class="ph-bold ph-paper-plane-right text-lg group-hover:translate-x-1 transition-transform"></i>
                                            Kirim Catatan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN (1/3): FEED RIWAYAT --}}
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col lg:sticky lg:top-24 h-auto lg:h-[calc(100vh-6rem)]">
                            <div class="p-6 border-b border-slate-50 flex items-center justify-between bg-white z-10">
                                <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                    <i class="ph-duotone ph-clock-counter-clockwise text-indigo-600"></i> Riwayat
                                </h3>
                                <span class="bg-indigo-50 text-[10px] font-black px-2.5 py-1 rounded-full text-indigo-600 border border-indigo-100 uppercase tracking-wide">{{ $messages->total() }} Item</span>
                            </div>
                            
                            <div class="p-0 overflow-y-auto flex-1 custom-scrollbar">
                                <div class="divide-y divide-slate-50">
                                    @forelse($messages as $msg)
                                        @php
                                            $style = match($msg->type) {
                                                'warning' => ['icon' => 'ph-warning', 'color' => 'text-amber-600', 'bg' => 'bg-amber-50', 'border' => 'border-amber-100'],
                                                'achievement' => ['icon' => 'ph-trophy', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-100'],
                                                'call' => ['icon' => 'ph-phone-call', 'color' => 'text-rose-600', 'bg' => 'bg-rose-50', 'border' => 'border-rose-100'],
                                                default => ['icon' => 'ph-info', 'color' => 'text-blue-600', 'bg' => 'bg-blue-50', 'border' => 'border-blue-100'],
                                            };
                                        @endphp
                                        <div class="p-5 hover:bg-slate-50/80 transition-all group relative">
                                            <div class="flex items-start justify-between gap-3 mb-2">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-9 h-9 rounded-xl {{ $style['bg'] }} border {{ $style['border'] }} flex items-center justify-center {{ $style['color'] }} shadow-sm shrink-0">
                                                        <i class="ph-fill {{ $style['icon'] }} text-lg"></i>
                                                    </div>
                                                    <div class="min-w-0">
                                                        <p class="text-xs font-bold text-slate-800 truncate max-w-[120px]">{{ $msg->student->name ?? 'Siswa Terhapus' }}</p>
                                                        <p class="text-[10px] font-bold text-slate-400">{{ $msg->student->schoolClass->name ?? '-' }}</p>
                                                    </div>
                                                </div>
                                                <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg border border-slate-100 whitespace-nowrap">
                                                    {{ $msg->created_at->diffForHumans() }}
                                                </span>
                                            </div>
                                            <div class="pl-12">
                                                <h4 class="font-bold text-slate-700 text-sm leading-snug mb-1 group-hover:text-indigo-700 transition-colors">{{ $msg->title }}</h4>
                                                <p class="text-xs text-slate-500 line-clamp-2 leading-relaxed bg-slate-50 p-2 rounded-lg border border-slate-100/50">
                                                    {{ $msg->message }}
                                                </p>
                                                <div class="flex justify-end mt-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    @if($msg->teacher_id == Auth::id() || Auth::user()->role == 'admin')
                                                        <form action="{{ route('liaison.destroy', $msg->id) }}" method="POST" onsubmit="return confirm('Hapus catatan ini?')">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="text-[10px] font-bold text-rose-400 hover:text-rose-600 flex items-center gap-1 bg-white px-2 py-1 rounded border border-rose-100 shadow-sm hover:shadow">
                                                                <i class="ph-bold ph-trash"></i> Hapus
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-16 px-6">
                                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300 border border-slate-100">
                                                <i class="ph-duotone ph-notebook text-3xl"></i>
                                            </div>
                                            <p class="text-sm font-bold text-slate-600">Belum ada catatan.</p>
                                        </div>
                                    @endforelse
                                </div>
                                @if($messages->hasPages())
                                    <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                                        {{ $messages->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- MODE 2: CHAT INTERFACE (DINAMIS & REAL) --}}
            <div x-show="mode === 'chat'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden h-[75vh] flex">
                    
                    {{-- SIDEBAR: DAFTAR KONTAK --}}
                    <div class="w-full md:w-80 border-r border-slate-100 flex flex-col bg-slate-50/50">
                        {{-- Filter Kelas & Search --}}
                        <div class="p-4 border-b border-slate-100 bg-white space-y-3">
                            <select x-model="chatClassFilter" @change="fetchChatContacts()" class="w-full bg-slate-100 border-none text-xs font-bold rounded-lg py-2 focus:ring-emerald-500">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            <div class="relative">
                                <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input x-model="chatSearch" @input.debounce.500ms="fetchChatContacts()" type="text" placeholder="Cari Siswa..." class="w-full pl-10 pr-4 py-2 bg-slate-100 border-transparent focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 rounded-xl text-sm font-bold placeholder:font-medium transition-all">
                            </div>
                        </div>
                        
                        {{-- Contact List --}}
                        <div class="flex-1 overflow-y-auto custom-scrollbar p-2 space-y-1">
                            <template x-for="contact in chatContacts" :key="contact.id">
                                <button @click="selectContact(contact)" 
                                    :class="activeContact && activeContact.id === contact.id ? 'bg-white border-slate-200 shadow-sm ring-1 ring-emerald-500/20' : 'hover:bg-white hover:shadow-sm'"
                                    class="w-full p-3 flex items-start gap-3 rounded-xl border border-transparent transition-all text-left relative group">
                                    
                                    {{-- Avatar Initials --}}
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 shrink-0 font-bold border border-slate-200"
                                         :class="activeContact && activeContact.id === contact.id ? 'bg-emerald-100 text-emerald-600 border-emerald-200' : ''">
                                        <span x-text="getInitials(contact.name)"></span>
                                    </div>
                                    
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-baseline mb-0.5">
                                            <h4 class="font-bold text-slate-700 text-sm truncate" x-text="contact.name"></h4>
                                            <span class="text-[10px] font-bold text-slate-400" x-text="formatTime(contact.last_message_time)"></span>
                                        </div>
                                        <p class="text-xs text-slate-400 truncate" 
                                           :class="contact.unread_count > 0 ? 'font-bold text-slate-700' : ''"
                                           x-text="contact.last_message ? contact.last_message.message : 'Belum ada pesan'">
                                        </p>
                                    </div>
                                    
                                    {{-- Unread Badge --}}
                                    <div x-show="contact.unread_count > 0" 
                                         class="absolute right-3 top-8 px-1.5 py-0.5 bg-emerald-500 text-white text-[9px] font-bold rounded-full min-w-[18px] text-center border-2 border-white"
                                         x-text="contact.unread_count">
                                    </div>
                                </button>
                            </template>
                            
                            {{-- Empty State --}}
                            <div x-show="!isLoadingContacts && chatContacts.length === 0" class="p-8 text-center">
                                <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-2 text-slate-300">
                                    <i class="ph-duotone ph-user-minus text-2xl"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-400">Tidak ada siswa ditemukan.</p>
                            </div>
                            
                            {{-- Loading State --}}
                            <div x-show="isLoadingContacts" class="p-4 text-center text-slate-400 text-xs animate-pulse">
                                Memuat kontak...
                            </div>
                        </div>
                    </div>

                    <!-- KANAN: AREA CHAT / DASHBOARD -->
                    <div class="flex-1 flex flex-col bg-white relative overflow-hidden">
                        
                        <!-- KONDISI 1: BELUM PILIH SISWA (Tampilkan Dashboard Testimoni) -->
                        <section x-show="!activeContact" class="flex-1 bg-slate-50 relative overflow-hidden flex flex-col">
                            <!-- Background Pattern Sederhana -->
                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#3b82f6 1px, transparent 1px); background-size: 32px 32px;"></div>

                            <div class="flex-1 overflow-y-auto p-8 relative z-10 custom-scrollbar">
                                <div class="h-full flex flex-col items-center justify-center text-center">
                                    <div class="w-20 h-20 bg-white rounded-full shadow-lg shadow-indigo-100 flex items-center justify-center mb-6 animate-bounce-slow">
                                        <i class="ph-duotone ph-chats-circle text-4xl text-indigo-500"></i>
                                    </div>
                                    <h3 class="text-2xl font-black text-slate-800 mb-2">Pilih Siswa untuk Memulai</h3>
                                    <p class="text-slate-500 max-w-sm mb-10">
                                        Klik salah satu kontak di sebelah kiri untuk membuka percakapan real-time dengan orang tua siswa.
                                    </p>
                                    
                                    <!-- Divider Dekoratif -->
                                    <div class="w-full max-w-md border-t border-slate-200 mb-10 relative">
                                        <span class="absolute left-1/2 top-0 -translate-x-1/2 -translate-y-1/2 bg-slate-50 px-3 text-xs font-bold text-slate-400 uppercase tracking-widest">Atau Baca Ini</span>
                                    </div>

                                    <!-- Mini Testimoni (Sesuai Request Sebelumnya) -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full max-w-4xl opacity-70 hover:opacity-100 transition-opacity duration-500">
                                         <!-- Kartu 1 -->
                                        <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 text-left">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-xs">H</div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800 text-xs">Bpk. Hendra</h4>
                                                    <p class="text-[10px] text-slate-400">Wali Murid</p>
                                                </div>
                                            </div>
                                            <p class="text-[11px] text-slate-500 italic">"Fitur chat ini sangat membantu saya memantau perkembangan anak."</p>
                                        </div>
                                         <!-- Kartu 2 -->
                                         <div class="bg-white rounded-xl shadow-sm border border-slate-100 p-5 text-left">
                                            <div class="flex items-center gap-3 mb-3">
                                                <div class="w-8 h-8 rounded-full bg-pink-100 text-pink-600 flex items-center justify-center font-bold text-xs">S</div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800 text-xs">Ibu Siti</h4>
                                                    <p class="text-[10px] text-slate-400">Wali Murid</p>
                                                </div>
                                            </div>
                                            <p class="text-[11px] text-slate-500 italic">"Terima kasih bapak/ibu guru atas respon cepatnya."</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                        <!-- KONDISI 2: SUDAH PILIH SISWA (Tampilkan Chat Room) -->
                        <div x-show="activeContact" class="flex-1 flex flex-col h-full absolute inset-0 z-20 bg-white" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0">
                            
                            <!-- A. CHAT HEADER -->
                            <div class="bg-white border-b border-slate-100 p-4 flex items-center justify-between shadow-sm z-10">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-lg border border-indigo-200">
                                        <span x-text="getInitials(activeContact?.name)"></span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800 text-base leading-tight" x-text="activeContact?.name"></h3>
                                        <p class="text-xs text-slate-500 flex items-center gap-1">
                                            <i class="ph-fill ph-student"></i>
                                            <span x-text="activeContact?.school_class?.name || activeContact?.classroom?.name || 'Kelas Siswa'"></span>
                                        </p>
                                    </div>
                                </div>
                                <button @click="activeContact = null" class="md:hidden p-2 text-slate-400 hover:text-slate-600">
                                    <i class="ph-bold ph-x text-xl"></i>
                                </button>
                            </div>

                            <!-- B. CHAT BODY (MESSAGES) -->
                            <div class="flex-1 overflow-y-auto p-6 bg-slate-50 space-y-6 custom-scrollbar scroll-smooth" x-ref="chatBox">
                                {{-- Loading State --}}
                                <div x-show="isLoadingMessages" class="flex justify-center py-8">
                                    <div class="flex items-center gap-2 text-slate-400 text-xs font-bold bg-white px-4 py-2 rounded-full shadow-sm">
                                        <i class="ph-bold ph-spinner animate-spin"></i> Memuat percakapan...
                                    </div>
                                </div>

                                {{-- Empty Messages --}}
                                <div x-show="!isLoadingMessages && chatMessages.length === 0" class="text-center py-10 opacity-50">
                                    <p class="text-xs font-bold text-slate-400">Belum ada percakapan. Mulai dengan menyapa!</p>
                                </div>

                                {{-- Loop Messages --}}
                                <template x-for="msg in chatMessages" :key="msg.id">
                                    <div class="flex w-full" :class="msg.sender_type === 'teacher' ? 'justify-end' : 'justify-start'">
                                        <div class="max-w-[75%] group">
                                            {{-- Bubble --}}
                                            <div class="p-3.5 rounded-2xl text-sm leading-relaxed shadow-sm relative transition-all duration-200"
                                                 :class="msg.sender_type === 'teacher' 
                                                    ? 'bg-indigo-600 text-white rounded-br-sm hover:bg-indigo-700' 
                                                    : 'bg-white text-slate-700 border border-slate-100 rounded-bl-sm hover:shadow-md'">
                                                <p x-text="msg.message" class="whitespace-pre-wrap"></p>
                                            </div>
                                            {{-- Time --}}
                                            <p class="text-[10px] font-bold mt-1 opacity-0 group-hover:opacity-100 transition-opacity"
                                               :class="msg.sender_type === 'teacher' ? 'text-right text-indigo-300' : 'text-left text-slate-400'"
                                               x-text="formatTime(msg.created_at, true)">
                                            </p>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- C. CHAT FOOTER (INPUT) -->
                            <div class="bg-white border-t border-slate-100 p-4 relative z-10">
                                <form @submit.prevent="sendMessage()" class="flex items-end gap-3">
                                    <div class="flex-1 bg-slate-100 rounded-2xl flex items-center px-4 border border-transparent focus-within:bg-white focus-within:border-indigo-300 focus-within:ring-4 focus-within:ring-indigo-100 transition-all">
                                        <input x-model="newMessage" type="text" placeholder="Tulis pesan..." class="w-full bg-transparent border-none focus:ring-0 py-3 text-sm font-medium text-slate-700 placeholder:text-slate-400">
                                    </div>
                                    <button type="submit" 
                                            :disabled="!newMessage.trim()"
                                            class="p-3 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-indigo-200 transition-all transform active:scale-90 flex items-center justify-center">
                                        <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                    </button>
                                </form>
                            </div>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- SCRIPT: LOGIKA GABUNGAN --}}
    <script>
        function liaisonHandler() {
            return {
                // STATE UMUM
                mode: 'note', 
                selectedClass: '',
                students: [], // Untuk Form Note
                isLoading: false,

                // STATE CHAT
                chatClassFilter: '',
                chatSearch: '',
                chatContacts: [], // Daftar kontak chat
                activeContact: null,
                chatMessages: [],
                newMessage: '',
                isLoadingContacts: false,
                isLoadingMessages: false,

                // --- LOGIKA FORM NOTE (LAMA) ---
                fetchStudents() {
                    if (!this.selectedClass) { this.students = []; return; }
                    this.isLoading = true;
                    this.students = [];
                    
                    let url = "{{ route('liaison.get_students', ':id') }}".replace(':id', this.selectedClass);
                    fetch(url)
                        .then(res => res.json())
                        .then(data => { this.students = data; this.isLoading = false; })
                        .catch(err => { console.error(err); this.isLoading = false; });
                },

                // --- LOGIKA CHAT (UPDATED) ---
                
                // 1. Ambil Kontak (Siswa)
                fetchChatContacts() {
                    this.isLoadingContacts = true;
                    let url = "{{ route('liaison.chat.contacts') }}";
                    
                    // UPDATE: Bangun Query Params yang benar untuk filter & search
                    const params = new URLSearchParams();
                    if (this.chatClassFilter) params.append('class_id', this.chatClassFilter);
                    if (this.chatSearch) params.append('search', this.chatSearch); // Kirim search ke server
                    
                    // Tambahkan params ke URL
                    if (Array.from(params).length > 0) {
                        url += '?' + params.toString();
                    }

                    fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest', // Penting untuk deteksi AJAX Laravel
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => {
                        if (!res.ok) throw new Error('Server Error: ' + res.status);
                        return res.json();
                    })
                    .then(data => {
                        // Data dari controller paginate, ambil .data
                        this.chatContacts = data.data ? data.data : data; 
                        this.isLoadingContacts = false;
                    })
                    .catch(err => { 
                        console.error('Fetch error:', err); 
                        this.isLoadingContacts = false;
                        
                        // Tampilkan alert error agar user tahu
                        if(this.mode === 'chat') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal Memuat Kontak',
                                text: 'Terjadi kesalahan saat mengambil data siswa. Coba refresh halaman.',
                                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000
                            });
                        }
                    });
                },

                // 2. Pilih Kontak & Load Pesan
                selectContact(contact) {
                    this.activeContact = contact;
                    this.fetchChatMessages(contact.id);
                    contact.unread_count = 0; // Reset unread visual
                },

                // 3. Ambil Pesan
                fetchChatMessages(studentId) {
                    this.isLoadingMessages = true;
                    let url = "{{ route('liaison.chat.messages', ':id') }}".replace(':id', studentId);
                    
                    fetch(url)
                        .then(res => res.json())
                        .then(data => {
                            this.chatMessages = data;
                            this.isLoadingMessages = false;
                            this.scrollToBottom();
                        });
                },

                // 4. Kirim Pesan
                sendMessage() {
                    if (!this.newMessage.trim() || !this.activeContact) return;

                    let msgText = this.newMessage;
                    this.newMessage = ''; // Reset input segera

                    // Optimistic UI Update
                    let tempId = Date.now();
                    this.chatMessages.push({
                        id: tempId,
                        message: msgText,
                        sender_type: 'teacher',
                        created_at: new Date().toISOString(),
                        is_read: false
                    });
                    this.scrollToBottom();

                    // Kirim ke Server
                    fetch("{{ route('liaison.chat.send') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            student_id: this.activeContact.id,
                            message: msgText
                        })
                    })
                    .then(res => res.json())
                    .then(savedMsg => {
                        // Update pesan terakhir di daftar kontak
                        let contact = this.chatContacts.find(c => c.id === this.activeContact.id);
                        if(contact) {
                            contact.last_message = { message: msgText };
                            contact.last_message_time = new Date().toISOString();
                            // Re-sort contacts (paling baru di atas)
                            this.chatContacts.sort((a,b) => new Date(b.last_message_time) - new Date(a.last_message_time));
                        }
                    })
                    .catch(err => console.error("Gagal kirim pesan", err));
                },

                // Helper: Scroll ke bawah
                scrollToBottom() {
                    setTimeout(() => {
                        let box = this.$refs.chatBox;
                        if(box) box.scrollTop = box.scrollHeight;
                    }, 100);
                },

                // Helper: Format Waktu
                formatTime(isoString, timeOnly = false) {
                    if(!isoString) return '';
                    let d = new Date(isoString);
                    if (timeOnly) return d.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    
                    let today = new Date();
                    if (d.toDateString() === today.toDateString()) {
                        return d.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'});
                    }
                    return d.toLocaleDateString('id-ID', {day: 'numeric', month: 'short'});
                },

                getInitials(name) {
                    if (!name) return '';
                    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
                }
            }
        }
    </script>

    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        /* Background Pattern for Chat */
        .bg-pattern {
            background-color: #f0f2f5;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        [x-cloak] { display: none !important; }
        .animate-bounce-slow { animation: bounce 3s infinite; }
    </style>
</x-app-layout>