<x-app-layout>
    {{-- Scripts External --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('library.books.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-cyan-600 mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Katalog
            </a>

            {{-- ERROR HANDLER --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-rose-500 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-700">Terdapat Kesalahan Input</h3>
                        <ul class="list-disc list-inside text-xs text-rose-600 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- HEADER HALAMAN (ELEVATED THEME) --}}
            <div class="bg-gradient-to-br from-cyan-500 via-blue-600 to-blue-900 rounded-[2.5rem] p-8 mb-8 text-white shadow-xl shadow-cyan-900/30 relative overflow-hidden">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-300/30 rounded-full blur-[60px] translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
                <div class="relative z-10">
                    <h1 class="text-3xl font-black tracking-tight mb-2">Tambah Buku Baru</h1>
                    <p class="text-cyan-50 text-sm max-w-xl leading-relaxed">
                        Masukkan identitas buku induk. Sistem akan otomatis memproduksi barcode untuk masing-masing fisik buku sesuai jumlah yang Anda tentukan.
                    </p>
                </div>
            </div>

            {{-- FORM UTAMA --}}
            <form action="{{ route('library.books.store') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-xl shadow-slate-200/50 border border-slate-100">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {{-- KOLOM KIRI (7 Kolom) --}}
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200">
                            <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-cyan-100 text-cyan-600 flex items-center justify-center text-[10px]">1</span>
                                Identitas Buku Induk
                            </h3>
                            
                            <div class="space-y-5">
                                {{-- Kode Buku / ISBN Induk --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kode Buku / ISBN (Induk) <span class="text-rose-500">*</span></label>
                                    <div class="flex gap-2">
                                        <div class="relative flex-1 group">
                                            <i class="ph-bold ph-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-cyan-500 transition-colors"></i>
                                            <input type="text" name="book_code" id="book_code" required value="{{ old('book_code') }}"
                                                class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-mono font-bold text-slate-700 shadow-sm" placeholder="Misal: 9786022828">
                                        </div>
                                        <button type="button" onclick="startScanner()" class="px-4 bg-white border border-slate-200 text-slate-600 rounded-xl hover:bg-slate-50 transition tooltip" title="Scan pakai Kamera">
                                            <i class="ph-bold ph-camera text-xl"></i>
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-slate-500 mt-2 ml-1 font-medium"><i class="ph-bold ph-info text-cyan-500"></i> Ketik manual atau scan barcode dari sampul buku. Sistem akan men-generate kode eksemplar tambahan (-01, -02).</p>
                                </div>

                                {{-- Judul Buku --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Buku <span class="text-rose-500">*</span></label>
                                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Laskar Pelangi"
                                        class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700 transition-all shadow-sm">
                                </div>

                                {{-- Kategori Buku --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kategori / DDC <span class="text-rose-500">*</span></label>
                                    <select name="category_id" required class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700 transition-all shadow-sm cursor-pointer">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->code }} - {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Pengarang & Penerbit --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pengarang</label>
                                        <input type="text" name="author" value="{{ old('author') }}" placeholder="Nama Penulis"
                                            class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700 transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Penerbit</label>
                                        <input type="text" name="publisher" value="{{ old('publisher') }}" placeholder="Nama Penerbit"
                                            class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700 transition-all shadow-sm">
                                    </div>
                                </div>

                                {{-- Tahun --}}
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tahun Terbit</label>
                                    <input type="number" name="year" value="{{ old('year') }}" placeholder="YYYY" min="1900" max="{{ date('Y') + 1 }}"
                                        class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700 transition-all shadow-sm">
                                </div>

                                {{-- Buku Paket Checkbox --}}
                                <div class="mt-4">
                                    <label class="flex items-center gap-3 p-4 border border-cyan-200 bg-white rounded-xl cursor-pointer hover:bg-cyan-50 transition">
                                        <input type="checkbox" name="is_textbook" value="1" {{ old('is_textbook') ? 'checked' : '' }} class="w-5 h-5 text-cyan-600 border-cyan-300 rounded focus:ring-cyan-500 cursor-pointer">
                                        <div>
                                            <span class="block text-sm font-bold text-cyan-800">Ini Buku Paket / Pelajaran</span>
                                            <span class="block text-xs text-cyan-600 mt-0.5">Buku paket bisa dipinjam massal selama 1 tahun.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN (5 Kolom) --}}
                    <div class="lg:col-span-5 space-y-6">
                        
                        {{-- Blok Eksemplar Fisik --}}
                        <div class="bg-cyan-50/50 p-6 rounded-[2rem] border border-cyan-100">
                            <h3 class="text-xs font-black text-cyan-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-cyan-200 text-cyan-700 flex items-center justify-center text-[10px]">2</span>
                                Fisik & Eksemplar
                            </h3>
                            
                            <div class="space-y-5">
                                {{-- JUMLAH BUKU (Otomatis Generate Barcode Fisik) --}}
                                <div>
                                    <label class="block text-xs font-bold text-cyan-700 uppercase mb-2 ml-1">Jumlah Fisik Buku / Eksemplar <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-stack absolute left-4 top-1/2 -translate-y-1/2 text-cyan-500"></i>
                                        <input type="number" name="jumlah_buku" required min="1" max="500" value="{{ old('jumlah_buku', 1) }}"
                                            class="w-full pl-11 pr-4 py-3 rounded-2xl border-cyan-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-cyan-700 shadow-sm transition-all" placeholder="Misal: 32">
                                    </div>
                                    <p class="text-[10px] text-cyan-600 mt-2 ml-1"><i class="ph-bold ph-info"></i> Sistem akan otomatis memproduksi barcode tambahan sebanyak ini untuk dicetak sebagai stiker label buku.</p>
                                </div>

                                {{-- Lokasi Rak --}}
                                <div>
                                    <label class="block text-xs font-bold text-cyan-700 uppercase mb-2 ml-1">Lokasi Rak <span class="text-rose-500">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-bookshelf absolute left-4 top-1/2 -translate-y-1/2 text-cyan-500 group-focus-within:text-cyan-600 transition-colors"></i>
                                        <input type="text" name="shelf_location" required value="{{ old('shelf_location') }}" placeholder="Misal: Rak A1 / Fiksi 2"
                                            class="w-full pl-11 pr-4 py-3 rounded-2xl border-cyan-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700 transition-all shadow-sm">
                                    </div>
                                </div>
                                
                                {{-- Sinopsis / Deskripsi --}}
                                <div>
                                    <label class="block text-xs font-bold text-cyan-700 uppercase mb-2 ml-1">Sinopsis / Ringkasan</label>
                                    <textarea name="description" rows="3" placeholder="Tuliskan deskripsi singkat tentang isi buku..."
                                        class="w-full px-4 py-3 rounded-2xl border-cyan-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-medium text-slate-600 transition-all shadow-sm custom-scrollbar text-sm resize-none">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Blok Media (Cover & E-book) --}}
                        <div class="bg-emerald-50/50 p-6 rounded-[2rem] border border-emerald-100">
                            <h3 class="text-xs font-black text-emerald-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-emerald-200 text-emerald-700 flex items-center justify-center text-[10px]">3</span>
                                Media & Digital
                            </h3>
                            
                            <div class="space-y-5">
                                {{-- Upload Cover --}}
                                <div>
                                    <label class="block text-xs font-bold text-emerald-700 uppercase mb-2 ml-1">Foto Sampul (Maks 5MB)</label>
                                    <div class="relative border-2 border-dashed border-emerald-200 rounded-2xl bg-white hover:bg-emerald-50/50 transition-colors group">
                                        <input type="file" name="cover" id="cover" accept="image/*" onchange="previewCover(event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="p-6 text-center" id="coverUploadArea">
                                            <i class="ph-duotone ph-image text-3xl text-emerald-400 mb-2 group-hover:scale-110 transition-transform"></i>
                                            <p class="text-xs font-bold text-emerald-600">Klik atau Drag foto kesini</p>
                                        </div>
                                        <div id="coverPreviewArea" class="hidden relative p-2">
                                            <img id="coverImg" src="" class="w-full h-32 object-contain rounded-xl">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/40 rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                                                <span class="text-white text-xs font-bold bg-black/50 px-3 py-1 rounded-full"><i class="ph-bold ph-arrows-clockwise"></i> Ganti</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Upload E-Book --}}
                                <div>
                                    <label class="block text-xs font-bold text-emerald-700 uppercase mb-2 ml-1">File E-Book PDF (Opsional)</label>
                                    <div class="relative">
                                        <i class="ph-bold ph-file-pdf absolute left-4 top-1/2 -translate-y-1/2 text-emerald-400"></i>
                                        <input type="file" name="ebook_file" accept=".pdf"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-emerald-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-medium text-slate-600 transition-all shadow-sm file:mr-4 file:py-1 file:px-3 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-slate-100 flex justify-end gap-3">
                    <a href="{{ route('library.books.index') }}" class="px-6 py-3 bg-slate-100 text-slate-600 font-bold rounded-2xl hover:bg-slate-200 transition-colors">Batal</a>
                    <button type="submit" class="px-8 py-3 bg-cyan-600 text-white font-bold rounded-2xl hover:bg-cyan-700 shadow-xl shadow-cyan-500/30 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan ke Katalog
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL SCANNER BARCODE --}}
    <div id="scannerModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="stopScanner()"></div>
        <div class="bg-white rounded-[2rem] shadow-2xl p-6 w-full max-w-sm relative z-10 animate-fade-in-down border border-slate-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-black text-slate-800 text-lg">Scan Barcode Buku</h3>
                <button onclick="stopScanner()" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:bg-rose-100 hover:text-rose-600 flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <div id="reader" class="rounded-2xl overflow-hidden border-2 border-cyan-100"></div>
            <p class="text-xs text-center text-slate-500 mt-4 font-medium"><i class="ph-bold ph-info"></i> Arahkan kamera ke barcode (ISBN) pada sampul belakang buku.</p>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT --}}
    <script>
        // --- LOGIKA PREVIEW GAMBAR COVER ---
        function previewCover(event) {
            const input = event.target;
            const previewArea = document.getElementById('coverPreviewArea');
            const uploadArea = document.getElementById('coverUploadArea');
            const img = document.getElementById('coverImg');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                    previewArea.classList.remove('hidden');
                    uploadArea.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        // --- LOGIKA SCANNER KAMERA ---
        let html5QrcodeScanner = null;

        function startScanner() {
            document.getElementById('scannerModal').classList.remove('hidden');
            if (html5QrcodeScanner === null) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }
            const config = { fps: 10, qrbox: { width: 250, height: 150 }, aspectRatio: 1.0 };
            html5QrcodeScanner.start({ facingMode: "environment" }, config, (decodedText) => {
                document.getElementById('book_code').value = decodedText;
                // Highlight input untuk indikasi sukses
                document.getElementById('book_code').classList.add('ring-2', 'ring-cyan-500');
                setTimeout(() => document.getElementById('book_code').classList.remove('ring-2', 'ring-cyan-500'), 1000);
                
                stopScanner();
            }).catch(err => console.error(err));
        }

        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => {
                    document.getElementById('scannerModal').classList.add('hidden');
                });
            } else {
                document.getElementById('scannerModal').classList.add('hidden');
            }
        }
    </script>
</x-app-layout>