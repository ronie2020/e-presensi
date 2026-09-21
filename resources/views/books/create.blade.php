<x-app-layout>
    {{-- Scripts External --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-sky-600/10 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('library.books.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-sky-400 mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Katalog
            </a>

            {{-- ERROR HANDLER --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-950/50 border border-rose-500/30 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-rose-400 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-300">Terdapat Kesalahan Input</h3>
                        <ul class="list-disc list-inside text-xs text-rose-300/80 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            {{-- HEADER HALAMAN (ELEVATED THEME) --}}
            <div class="bg-gradient-to-r from-[#031d3d] via-[#021124] to-[#020b18] rounded-[2.5rem] p-8 mb-8 text-white shadow-2xl relative overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute top-0 right-0 w-64 h-64 bg-sky-500/10 rounded-full blur-[60px] translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
                <div class="relative z-10">
                    <h1 class="text-3xl font-black tracking-tight mb-2">Tambah Buku Baru</h1>
                    <p class="text-slate-300 text-sm max-w-xl leading-relaxed font-semibold">
                        Masukkan identitas buku induk. Sistem akan otomatis memproduksi barcode untuk masing-masing fisik buku sesuai jumlah yang Anda tentukan.
                    </p>
                </div>
            </div>

            {{-- FORM UTAMA --}}
            <form action="{{ route('library.books.store') }}" method="POST" enctype="multipart/form-data" class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-8 sm:p-10 shadow-2xl border border-white/10">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    {{-- KOLOM KIRI (7 Kolom) --}}
                    <div class="lg:col-span-7 space-y-6">
                        <div class="bg-slate-900/60 p-6 rounded-[2rem] border border-white/10">
                            <h3 class="text-xs font-black text-sky-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-sky-500/20 text-sky-300 flex items-center justify-center text-[10px] shadow-sm border border-sky-500/30">1</span>
                                Identitas Buku Induk
                            </h3>
                            
                            <div class="space-y-5">
                                {{-- Kode Buku / ISBN Induk --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Kode Buku / ISBN (Induk) <span class="text-rose-400">*</span></label>
                                    <div class="flex gap-2">
                                        <div class="relative flex-1 group">
                                            <i class="ph-bold ph-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                            <input type="text" name="book_code" id="book_code" required value="{{ old('book_code') }}"
                                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-mono font-bold text-white placeholder-slate-500 shadow-sm transition-all" placeholder="Misal: 9786022828">
                                        </div>
                                        <button type="button" onclick="startScanner()" class="px-4 bg-slate-900/80 border border-white/10 text-slate-400 hover:text-sky-400 hover:border-sky-500/50 rounded-2xl transition shadow-sm" title="Scan pakai Kamera">
                                            <i class="ph-bold ph-camera text-xl"></i>
                                        </button>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1 font-medium"><i class="ph-bold ph-info text-sky-400"></i> Ketik manual atau scan barcode dari sampul buku. Sistem akan men-generate kode eksemplar tambahan (-01, -02).</p>
                                </div>

                                {{-- Judul Buku --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Judul Buku <span class="text-rose-400">*</span></label>
                                    <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Laskar Pelangi"
                                        class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white placeholder-slate-500 transition-all shadow-sm">
                                </div>

                                {{-- Kategori Buku --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Kategori / DDC <span class="text-rose-400">*</span></label>
                                    <div class="flex gap-2">
                                        <div class="relative flex-1">
                                            <select id="category_id" name="category_id" required class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm cursor-pointer appearance-none">
                                                <option value="" class="bg-slate-900 text-slate-300">-- Pilih Kategori --</option>
                                                @foreach($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }} class="bg-slate-900 text-white">
                                                        {{ $category->code }} - {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                        {{-- Tombol Tambah Kategori Baru (AJAX) --}}
                                        <button type="button" onclick="addNewCategory()" class="shrink-0 w-12 bg-slate-900/80 text-sky-400 font-black rounded-2xl hover:bg-sky-500 hover:text-white transition-all border border-white/10 hover:border-sky-500 shadow-sm" title="Tambah Kategori Baru">
                                            <i class="ph-bold ph-plus text-lg"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Pengarang & Penerbit --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Pengarang</label>
                                        <input type="text" name="author" value="{{ old('author') }}" placeholder="Nama Penulis"
                                            class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white placeholder-slate-500 transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Penerbit</label>
                                        <input type="text" name="publisher" value="{{ old('publisher') }}" placeholder="Nama Penerbit"
                                            class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white placeholder-slate-500 transition-all shadow-sm">
                                    </div>
                                </div>

                                {{-- Tahun & Tanggal Pembelian (Grid 2 Kolom) --}}
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tahun Terbit</label>
                                        <input type="number" name="year" value="{{ old('year') }}" placeholder="YYYY" min="1900" max="{{ date('Y') + 1 }}"
                                            class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white placeholder-slate-500 transition-all shadow-sm">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tanggal Pembelian</label>
                                        <div class="relative group">
                                            <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                            <input type="date" name="purchase_date" value="{{ old('purchase_date') }}"
                                                class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm uppercase [color-scheme:dark]">
                                        </div>
                                    </div>
                                </div>                               

                                {{-- Buku Paket Checkbox --}}
                                <div class="mt-4">
                                    <label class="flex items-center gap-3 p-4 border border-white/10 bg-slate-900/80 rounded-2xl cursor-pointer hover:border-sky-500/50 transition shadow-sm">
                                        <input type="checkbox" name="is_textbook" value="1" {{ old('is_textbook') ? 'checked' : '' }} class="w-5 h-5 text-sky-500 border-white/20 bg-slate-900 rounded focus:ring-sky-500 cursor-pointer">
                                        <div>
                                            <span class="block text-sm font-bold text-white">Ini Buku Paket / Pelajaran</span>
                                            <span class="block text-xs text-slate-400 mt-0.5">Buku paket bisa dipinjam massal selama 1 tahun.</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN (5 Kolom) --}}
                    <div class="lg:col-span-5 space-y-6">
                        
                        {{-- Blok Eksemplar Fisik --}}
                        <div class="bg-slate-900/60 p-6 rounded-[2rem] border border-white/10">
                            <h3 class="text-xs font-black text-sky-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-sky-500/20 text-sky-300 flex items-center justify-center text-[10px] shadow-sm border border-sky-500/30">2</span>
                                Fisik & Eksemplar
                            </h3>
                            
                            <div class="space-y-5">
                                {{-- JUMLAH BUKU (Otomatis Generate Barcode Fisik) --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Jumlah Fisik Buku / Eksemplar <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-stack absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                        <input type="number" name="jumlah_buku" required min="1" max="500" value="{{ old('jumlah_buku', 1) }}"
                                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white placeholder-slate-500 shadow-sm transition-all" placeholder="Misal: 32">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1"><i class="ph-bold ph-info"></i> Sistem otomatis memproduksi barcode tambahan sebanyak ini untuk stiker.</p>
                                </div>

                                {{-- Lokasi Rak --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Lokasi Rak <span class="text-rose-400">*</span></label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-bookshelf absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                        <input type="text" name="shelf_location" required value="{{ old('shelf_location') }}" placeholder="Misal: Rak A1 / Fiksi 2"
                                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white placeholder-slate-500 transition-all shadow-sm">
                                    </div>
                                </div>
                                
                                {{-- Sinopsis / Deskripsi --}}
                                <div>
                                    <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Sinopsis / Ringkasan</label>
                                    <textarea name="description" rows="3" placeholder="Tuliskan deskripsi singkat tentang isi buku..."
                                        class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-medium text-white placeholder-slate-500 transition-all shadow-sm custom-scrollbar text-sm resize-none">{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Blok Media (Cover & E-book) --}}
                        <div class="bg-slate-900/60 p-6 rounded-[2rem] border border-white/10">
                            <h3 class="text-xs font-black text-amber-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                <span class="w-6 h-6 rounded-full bg-amber-500/20 text-amber-300 flex items-center justify-center text-[10px] shadow-sm border border-amber-500/30">3</span>
                                Media & Digital
                            </h3>
                            
                            <div class="space-y-5">
                                {{-- Upload Cover --}}
                                <div>
                                    <label class="block text-xs font-bold text-amber-400 uppercase mb-2 ml-1">Foto Sampul (Maks 5MB)</label>
                                    <div class="relative border-2 border-dashed border-white/15 rounded-2xl bg-slate-900/80 hover:bg-slate-900 transition-colors group">
                                        <input type="file" name="cover" id="cover" accept="image/*" onchange="previewCover(event)" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div class="p-6 text-center" id="coverUploadArea">
                                            <i class="ph-duotone ph-image text-3xl text-amber-400 mb-2 group-hover:scale-110 transition-transform"></i>
                                            <p class="text-xs font-bold text-amber-300">Klik atau Drag foto kesini</p>
                                        </div>
                                        <div id="coverPreviewArea" class="hidden relative p-2">
                                            <img id="coverImg" src="" class="w-full h-32 object-contain rounded-xl">
                                            <div class="absolute inset-0 flex items-center justify-center bg-slate-950/70 rounded-xl opacity-0 hover:opacity-100 transition-opacity">
                                                <span class="text-white text-xs font-bold bg-slate-900/90 px-3 py-1 rounded-full"><i class="ph-bold ph-arrows-clockwise"></i> Ganti</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Upload E-Book --}}
                                <div>
                                    <label class="block text-xs font-bold text-amber-400 uppercase mb-2 ml-1">File E-Book PDF (Opsional)</label>
                                    <div class="relative group">
                                        <i class="ph-bold ph-file-pdf absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-amber-400 transition-colors z-10"></i>
                                        <input type="file" name="ebook_file" accept=".pdf"
                                            class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-amber-400 focus:ring-4 focus:ring-amber-400/20 font-medium text-white transition-all shadow-sm file:mr-4 file:py-1.5 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-amber-500/20 file:text-amber-300 hover:file:bg-amber-500/30 cursor-pointer relative z-20">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="mt-10 pt-6 border-t border-white/10 flex justify-end gap-3">
                    <a href="{{ route('library.books.index') }}" class="px-6 py-3.5 bg-slate-800 text-slate-300 font-bold rounded-2xl hover:bg-slate-700 hover:text-white transition-colors">Batal</a>
                    <button type="submit" class="px-8 py-3.5 bg-[#0d52a1] text-white font-bold rounded-2xl hover:bg-sky-600 shadow-lg shadow-sky-950/50 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 border border-sky-400/30 active:scale-95">
                        <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan ke Katalog
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL SCANNER BARCODE --}}
    <div id="scannerModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="stopScanner()"></div>
        <div class="bg-[#021124] rounded-[2.5rem] shadow-2xl p-6 w-full max-w-sm relative z-10 border border-white/10 text-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-black text-white text-lg">Scan Barcode Buku</h3>
                <button onclick="stopScanner()" class="w-8 h-8 rounded-full bg-slate-800 text-slate-400 hover:bg-rose-500/20 hover:text-rose-400 flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <div id="reader" class="rounded-2xl overflow-hidden border-4 border-slate-800 bg-slate-900"></div>
            <p class="text-xs text-center text-slate-400 mt-4 font-medium"><i class="ph-bold ph-info text-sky-400"></i> Arahkan kamera ke barcode (ISBN) pada sampul belakang buku.</p>
        </div>
    </div>

    {{-- MODAL TAMBAH KATEGORI BARU --}}
    <div id="addCategoryModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center">
        <div class="absolute inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="document.getElementById('addCategoryModal').classList.add('hidden')"></div>
        <div class="bg-[#021124] rounded-[2.5rem] shadow-2xl p-8 w-full max-w-sm relative z-10 border border-white/10 text-white">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-sky-500/20 rounded-xl flex items-center justify-center text-sky-400 border border-sky-500/30">
                    <i class="ph-bold ph-tag text-xl"></i>
                </div>
                <h3 class="font-black text-white text-lg">Kategori Baru</h3>
            </div>
            <div class="mb-6">
                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Nama Kategori</label>
                <input type="text" id="newCategoryName" placeholder="Misal: Sains, Fiksi, Sejarah..."
                    class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white placeholder-slate-500 transition-all shadow-sm">
            </div>
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')" class="flex-1 py-3.5 bg-slate-800 text-slate-300 font-bold rounded-2xl hover:bg-slate-700 hover:text-white transition-colors">Batal</button>
                <button type="button" onclick="saveCategoryAjax()" id="btnSaveCategory" class="flex-1 py-3.5 bg-[#0d52a1] text-white font-bold rounded-2xl hover:bg-sky-600 shadow-lg shadow-sky-950/50 transition-all active:scale-95 flex items-center justify-center gap-2 border border-sky-400/30">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan
                </button>
            </div>
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
                document.getElementById('book_code').classList.add('ring-2', 'ring-sky-400');
                setTimeout(() => document.getElementById('book_code').classList.remove('ring-2', 'ring-sky-400'), 1000);
                
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

        // --- LOGIKA TAMBAH KATEGORI BARU (AJAX) ---
        function addNewCategory() {
            document.getElementById('newCategoryName').value = '';
            document.getElementById('addCategoryModal').classList.remove('hidden');
            setTimeout(() => document.getElementById('newCategoryName').focus(), 200);
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('newCategoryName').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') { e.preventDefault(); saveCategoryAjax(); }
            });
        });

        async function saveCategoryAjax() {
            const name = document.getElementById('newCategoryName').value.trim();
            if (!name) {
                document.getElementById('newCategoryName').focus();
                return;
            }

            const btn = document.getElementById('btnSaveCategory');
            btn.disabled = true;
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i> Menyimpan...';

            try {
                const response = await fetch("{{ route('library.books.categories.ajax') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: name })
                });

                const data = await response.json();

                if (data.success) {
                    // Tambahkan option baru ke dropdown dan langsung pilih
                    const select = document.getElementById('category_id');
                    const newOption = new Option(data.name, data.id, true, true);
                    select.add(newOption);
                    select.value = data.id;

                    document.getElementById('addCategoryModal').classList.add('hidden');

                    Swal.fire({
                        toast: true, position: 'top-end', icon: 'success',
                        title: `Kategori "${data.name}" berhasil ditambahkan!`,
                        showConfirmButton: false, timer: 2500,
                        background: '#021124', color: '#fff',
                        customClass: { popup: 'rounded-2xl border border-white/10 bg-[#021124] text-white' }
                    });
                } else {
                    Swal.fire({
                        icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.',
                        background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1',
                        customClass: { popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white' }
                    });
                }
            } catch (error) {
                Swal.fire({
                    icon: 'error', title: 'Error Koneksi', text: 'Tidak dapat menghubungi server.',
                    background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1',
                    customClass: { popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white' }
                });
            } finally {
                btn.disabled = false;
                btn.innerHTML = '<i class="ph-bold ph-floppy-disk"></i> Simpan';
            }
        }
    </script>
</x-app-layout>