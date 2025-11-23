{{-- Halaman ini adalah tampilan untuk resources/views/announcements/index.blade.php --}}
<x-app-layout>
    {{-- Header Page --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
            Pusat Informasi & Notifikasi
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Kelola pengumuman sekolah dan kirim notifikasi WhatsApp ke wali murid.
        </p>
    </div>

    {{-- Pesan Flash --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- KOLOM KIRI (2/3): FORM PENGUMUMAN WEBSITE --}}
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Card Form Pengumuman -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative group">
                <div class="absolute top-0 left-0 w-2 h-full bg-blue-600"></div>
                <div class="p-6 md:p-8">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center text-xl shadow-sm">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Buat Pengumuman Baru</h3>
                            <p class="text-xs text-gray-500">Akan tampil di halaman depan (Landing Page)</p>
                        </div>
                    </div>

                    <form action="{{ route('announcements.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Judul Pengumuman</label>
                            <input type="text" name="title" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-medium py-3" placeholder="Contoh: Libur Awal Ramadhan" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Isi Konten</label>
                            <div class="rounded-xl border border-gray-200 overflow-hidden bg-white focus-within:ring-1 focus-within:ring-blue-500 focus-within:border-blue-500">
                                <!-- Fake Toolbar -->
                                <div class="bg-gray-50 border-b border-gray-200 p-2 flex gap-2">
                                    <button type="button" class="p-1.5 hover:bg-gray-200 rounded text-gray-500 font-bold" title="Bold">B</button>
                                    <button type="button" class="p-1.5 hover:bg-gray-200 rounded text-gray-500 italic" title="Italic">I</button>
                                    <button type="button" class="p-1.5 hover:bg-gray-200 rounded text-gray-500 underline" title="Underline">U</button>
                                    <div class="w-px h-6 bg-gray-300 mx-1"></div>
                                    <button type="button" class="p-1.5 hover:bg-gray-200 rounded text-gray-500 text-xs uppercase font-bold" title="List">List</button>
                                </div>
                                <textarea name="content" rows="6" class="w-full border-0 focus:ring-0 text-gray-700 p-4 resize-none" placeholder="Tulis detail pengumuman di sini..." required></textarea>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="w-full py-3 px-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                Publikasikan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Broadcast WA -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative group">
                <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
                <div class="p-6 md:p-8">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-sm">
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Broadcast WhatsApp</h3>
                                <p class="text-xs text-gray-500">Kirim pesan massal ke orang tua</p>
                            </div>
                        </div>
                        <span class="bg-emerald-100 text-emerald-700 text-xs font-bold px-3 py-1 rounded-full">Gateway Aktif</span>
                    </div>

                    <form action="{{ route('announcements.send') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Target Pengiriman</label>
                                <select name="target_type" id="target_type" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5" onchange="toggleKelas(this.value)">
                                    <option value="class">Satu Kelas Spesifik</option>
                                    <option value="all">Semua Siswa (Broadcast Akbar)</option>
                                </select>
                            </div>
                            <div id="class_select_container">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Kelas</label>
                                <select name="class_id" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5">
                                    @foreach($classes as $class)
                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Template Cepat</label>
                            <select id="template_select" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5" onchange="fillTemplate(this.value)">
                                <option value="">-- Pilih Template Pesan --</option>
                                @foreach($templates as $key => $val)
                                    <option value="{{ $val }}">{{ $key }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Isi Pesan</label>
                            <textarea name="message" id="wa_message" rows="4" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Tulis pesan WhatsApp..." required></textarea>
                        </div>

                        <div class="pt-2 flex items-center justify-between">
                            <p class="text-xs text-gray-400 italic">*Pesan akan dikirim secara antrian (background process).</p>
                            <button type="submit" onclick="return confirm('Yakin ingin mengirim pesan ini?')" class="py-3 px-6 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                Kirim Pesan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- KOLOM KANAN (1/3): FEED PENGUMUMAN --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                    <h3 class="text-lg font-bold text-gray-800">Feed Pengumuman</h3>
                    <p class="text-xs text-gray-500">Riwayat publikasi website</p>
                </div>
                
                <div class="p-4 overflow-y-auto flex-1 space-y-4 max-h-[600px] custom-scrollbar">
                    @forelse($announcements as $announce)
                        <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all relative group">
                            <div class="flex items-start justify-between mb-2">
                                <span class="text-[10px] font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-md">
                                    {{ $announce->created_at->format('d M Y') }}
                                </span>
                                <span class="text-[10px] text-gray-400">
                                    {{ $announce->created_at->format('H:i') }}
                                </span>
                            </div>
                            
                            <h4 class="font-bold text-gray-800 mb-1 leading-tight">{{ $announce->title }}</h4>
                            <p class="text-xs text-gray-500 line-clamp-3 mb-3">
                                {{ $announce->content }}
                            </p>

                            <div class="flex items-center justify-between pt-2 border-t border-gray-50">
                                <div class="flex items-center gap-2">
                                    <div class="w-5 h-5 rounded-full bg-gray-200 flex items-center justify-center text-[9px] font-bold text-gray-600">
                                        {{ substr($announce->author->name ?? 'A', 0, 1) }}
                                    </div>
                                    <span class="text-[10px] text-gray-500">{{ $announce->author->name ?? 'Admin' }}</span>
                                </div>
                                
                                <form action="{{ route('announcements.destroy', $announce->id) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-gray-300 hover:text-red-500 transition-colors" onclick="return confirm('Hapus pengumuman ini?')" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-10">
                            <div class="bg-gray-50 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-3">
                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Belum ada pengumuman.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleKelas(val) {
            const container = document.getElementById('class_select_container');
            if (val === 'all') {
                container.style.display = 'none';
            } else {
                container.style.display = 'block';
            }
        }

        function fillTemplate(val) {
            if (val) {
                document.getElementById('wa_message').value = val;
            }
        }
    </script>
</x-app-layout>