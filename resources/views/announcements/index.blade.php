{{-- Halaman ini adalah tampilan untuk resources/views/announcements/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                Pusat Informasi
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola papan pengumuman sekolah dan kirim notifikasi WhatsApp ke wali murid.
            </p>
        </div>

        {{-- Pesan Flash --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-400 hover:text-rose-600">&times;</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- KOLOM KIRI (2/3): FORM INPUT --}}
            <div class="lg:col-span-2 space-y-8">
                
                <!-- CARD 1: PENGUMUMAN WEBSITE (BLUE THEME) -->
                <div class="bg-white rounded-3xl shadow-sm border border-blue-100 overflow-hidden relative group hover:shadow-lg hover:shadow-blue-100/50 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-600"></div>
                    <div class="p-6 md:p-8 relative z-10">
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-blue-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-800">Pengumuman Website</h3>
                                <p class="text-sm text-gray-500">Tampilkan informasi di halaman depan (Landing Page)</p>
                            </div>
                        </div>

                        <form action="{{ route('announcements.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Judul Pengumuman</label>
                                <input type="text" name="title" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-gray-800 py-3 transition-colors" placeholder="Contoh: Jadwal Libur Awal Ramadhan" required>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Isi Konten</label>
                                <div class="rounded-xl border border-gray-200 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-transparent transition-all">
                                    <!-- Simple Toolbar (Visual) -->
                                    <div class="bg-gray-50 border-b border-gray-200 p-2 flex gap-1">
                                        <div class="flex gap-1">
                                            <span class="w-3 h-3 rounded-full bg-red-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-yellow-400"></span>
                                            <span class="w-3 h-3 rounded-full bg-green-400"></span>
                                        </div>
                                        <div class="ml-auto text-[10px] font-bold text-gray-400 uppercase tracking-widest">Editor</div>
                                    </div>
                                    <textarea name="content" rows="5" class="w-full border-0 focus:ring-0 text-gray-600 p-4 resize-none text-sm leading-relaxed" placeholder="Tulis detail pengumuman di sini..." required></textarea>
                                </div>
                            </div>

                            <div class="pt-2 flex justify-end">
                                <button type="submit" class="py-3 px-6 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-200 flex items-center gap-2 group-hover:translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                    Publikasikan Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- Decor Blob --}}
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-blue-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
                </div>

                <!-- CARD 2: BROADCAST WHATSAPP (EMERALD THEME) -->
                <div class="bg-white rounded-3xl shadow-sm border border-emerald-100 overflow-hidden relative group hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-emerald-500"></div>
                    <div class="p-6 md:p-8 relative z-10">
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-emerald-100">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-gray-800">Broadcast WhatsApp</h3>
                                    <p class="text-sm text-gray-500">Kirim pesan massal ke orang tua</p>
                                </div>
                            </div>
                            <div class="hidden sm:flex items-center gap-2 bg-emerald-50 px-3 py-1 rounded-full border border-emerald-100">
                                <span class="relative flex h-2 w-2">
                                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                                </span>
                                <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wide">Gateway Ready</span>
                            </div>
                        </div>

                        <form action="{{ route('announcements.send') }}" method="POST" class="space-y-5">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Target Penerima</label>
                                    <select name="target_type" id="target_type" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 font-medium transition-colors" onchange="toggleTarget(this.value)">
                                        <option value="class">Satu Kelas Spesifik</option>
                                        <option value="student">Siswa Spesifik (Personal)</option> {{-- Opsi Baru --}}
                                        <option value="all">Semua Siswa (Broadcast Akbar)</option>
                                    </select>
                                </div>
                                
                                {{-- Container Pilih Kelas --}}
                                <div id="class_select_container">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Kelas</label>
                                    <select name="class_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 font-medium transition-colors">
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Container Pilih Siswa (Hidden by Default) --}}
                                <div id="student_select_container" style="display: none;">
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Siswa</label>
                                    <select name="student_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 font-medium transition-colors">
                                        <option value="">-- Cari Nama Siswa --</option>
                                        {{-- Pastikan variable $students dikirim dari Controller --}}
                                        @if(isset($students))
                                            @foreach($students as $student)
                                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->schoolClass->name ?? '-' }})</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Template Cepat</label>
                                <select id="template_select" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 font-medium transition-colors" onchange="fillTemplate(this.value)">
                                    <option value="">-- Pilih Template Pesan --</option>
                                    @foreach($templates as $key => $val)
                                        <option value="{{ $val }}">{{ $key }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Isi Pesan</label>
                                <textarea name="message" id="wa_message" rows="4" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm p-4" placeholder="Tulis pesan WhatsApp..." required></textarea>
                            </div>

                            <div class="pt-2 flex items-center justify-between border-t border-emerald-50 mt-4">
                                <p class="text-xs text-gray-400 italic max-w-xs">*Pesan akan dikirim secara antrian di background.</p>
                                <button type="submit" onclick="return confirm('Yakin ingin mengirim pesan ini?')" class="py-3 px-6 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center gap-2 group-hover:translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    Kirim Pesan
                                </button>
                            </div>
                        </form>
                    </div>
                    {{-- Decor Blob --}}
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-emerald-50 rounded-full blur-3xl opacity-60 pointer-events-none"></div>
                </div>

            </div>

            {{-- KOLOM KANAN (1/3): FEED PENGUMUMAN --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col sticky top-6">
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex items-center justify-between">
                        <h3 class="text-lg font-black text-gray-800">Feed Publik</h3>
                        <span class="bg-white text-xs font-bold px-2 py-1 rounded border border-gray-200 text-gray-500">{{ $announcements->count() }} Item</span>
                    </div>
                    
                    <div class="p-4 overflow-y-auto flex-1 space-y-4 max-h-[calc(100vh-200px)] custom-scrollbar">
                        @forelse($announcements as $announce)
                            <div class="bg-white p-5 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-100 transition-all relative group">
                                
                                {{-- Badge Tanggal --}}
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg border border-blue-100">
                                        {{ $announce->created_at->format('d M Y') }}
                                    </span>
                                    <span class="text-[10px] font-medium text-gray-400 flex items-center gap-1">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $announce->created_at->format('H:i') }}
                                    </span>
                                </div>
                                
                                <h4 class="font-bold text-gray-800 mb-2 leading-tight text-base group-hover:text-blue-600 transition-colors">{{ $announce->title }}</h4>
                                <p class="text-xs text-gray-500 line-clamp-3 leading-relaxed">
                                    {{ Str::limit(strip_tags($announce->content), 120) }}
                                </p>

                                <div class="flex items-center justify-between pt-4 mt-2 border-t border-gray-50">
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-[9px] font-bold text-white shadow-sm">
                                            {{ substr($announce->author->name ?? 'A', 0, 1) }}
                                        </div>
                                        <span class="text-[10px] font-bold text-gray-500">{{ $announce->author->name ?? 'Admin' }}</span>
                                    </div>
                                    
                                    <form action="{{ route('announcements.destroy', $announce->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-gray-300 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-all opacity-0 group-hover:opacity-100" onclick="return confirm('Hapus pengumuman ini?')" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                                </div>
                                <p class="text-sm text-gray-500 font-medium">Belum ada pengumuman publik.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Script Helper --}}
    <script>
        // Logika Tampilan Input Berdasarkan Target
        function toggleTarget(val) {
            const classContainer = document.getElementById('class_select_container');
            const studentContainer = document.getElementById('student_select_container');

            // Reset (Sembunyikan semua dulu)
            if(classContainer) classContainer.style.display = 'none';
            if(studentContainer) studentContainer.style.display = 'none';

            if (val === 'class') {
                if(classContainer) classContainer.style.display = 'block';
            } else if (val === 'student') {
                if(studentContainer) studentContainer.style.display = 'block';
            }
            // Jika 'all', keduanya tetap hidden
        }

        function fillTemplate(val) {
            if (val) {
                document.getElementById('wa_message').value = val;
            }
        }
    </script>
</x-app-layout>