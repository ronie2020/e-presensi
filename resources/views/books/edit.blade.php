<x-app-layout>
    {{-- Scripts External --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-sky-600/10 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <a href="{{ route('library.books.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-sky-400 mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Katalog
            </a>

            {{-- ERROR HANDLER --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-rose-950/50 border border-rose-500/30 rounded-2xl flex items-start gap-3 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-rose-400 text-xl mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-bold text-rose-300">Gagal Menyimpan Perubahan</h3>
                        <ul class="list-disc list-inside text-xs text-rose-300/80 mt-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                
                <div class="bg-gradient-to-r from-[#031d3d] via-[#021124] to-[#020b18] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none mix-blend-overlay">
                        <i class="ph-fill ph-pencil-circle"></i>
                    </div>
                    <h2 class="text-3xl font-black relative z-10">Edit Data Buku</h2>
                    <p class="text-slate-300 text-sm font-semibold relative z-10 mt-2">Perbarui informasi detail buku dan inventaris.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('library.books.update', $book->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            <div class="space-y-6">
                                <div class="bg-slate-900/60 p-6 rounded-[2rem] border border-white/10">
                                    <h3 class="text-xs font-black text-sky-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-sky-500/20 text-sky-300 flex items-center justify-center text-[10px] shadow-sm border border-sky-500/30">1</span>
                                        Identitas Buku
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        {{-- Kode Buku --}}
                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Kode Buku / Barcode Induk</label>
                                            <div class="flex gap-2">
                                                <div class="relative flex-1 group">
                                                    <i class="ph-bold ph-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                                    <input type="text" name="book_code" id="book_code" value="{{ old('book_code', $book->book_code) }}" readonly
                                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-950/80 cursor-not-allowed font-mono font-bold text-slate-400 shadow-sm" title="Kode buku tidak dapat diubah setelah dibuat">
                                                </div>
                                            </div>
                                            <p class="text-[10px] text-slate-400 mt-2 ml-1"><i class="ph-bold ph-info text-sky-400"></i> Kode induk tidak dapat diubah untuk menjaga sinkronisasi eksemplar.</p>
                                        </div>

                                        {{-- Judul Buku --}}
                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Judul Buku</label>
                                            <input type="text" name="title" value="{{ old('title', $book->title) }}" required 
                                                class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm">
                                        </div>

                                      {{-- Kategori --}}
                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Kategori</label>
                                            <div class="flex gap-2">
                                                <div class="relative flex-1">
                                                    <select name="category_id" id="category_id" class="w-full pl-4 pr-10 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm appearance-none cursor-pointer">
                                                        <option value="" class="bg-slate-900 text-slate-300">-- Pilih Kategori --</option>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat->id }}" {{ (old('category_id', $book->category_id) == $cat->id) ? 'selected' : '' }} class="bg-slate-900 text-white">
                                                                {{ $cat->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                                </div>
                                                <button type="button" onclick="addNewCategory()" class="shrink-0 w-12 bg-slate-900/80 text-sky-400 font-black rounded-2xl hover:bg-sky-500 hover:text-white transition-all border border-white/10 hover:border-sky-500 shadow-sm">
                                                    <i class="ph-bold ph-plus text-lg"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Stok & Penambahan Eksemplar --}}
                                       <div class="grid grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Stok Saat Ini</label>
                                                <div class="relative">
                                                    <i class="ph-bold ph-stack absolute left-4 top-1/2 -translate-y-1/2 text-slate-500"></i>
                                                    <input type="number" value="{{ $book->stock }}" readonly 
                                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-950/80 cursor-not-allowed font-bold text-slate-400 shadow-sm" title="Total stok tidak bisa diubah manual">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">+ Tambah Eksemplar</label>
                                                <div class="relative group">
                                                    <i class="ph-bold ph-plus-circle absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                    <input type="number" name="tambah_eksemplar" value="0" min="0" max="500"
                                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-sky-400 transition-all shadow-sm" placeholder="Misal: 5">
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-slate-400 mt-1 ml-1 leading-relaxed"><i class="ph-bold ph-info text-sky-400"></i> Isi angka pada kolom <b>Tambah Eksemplar</b> untuk men-generate barcode buku lama atau penambahan fisik baru.</p>

                                        {{-- Tahun Terbit & Tanggal Pembelian (Grid 2 Kolom) --}}
                                        <div class="grid grid-cols-2 gap-4 mt-2">
                                            <div>
                                                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tahun Terbit</label>
                                                <input type="number" name="year" value="{{ old('year', $book->year) }}" placeholder="YYYY" min="1900" max="{{ date('Y') + 1 }}"
                                                    class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Tanggal Pembelian</label>
                                                <div class="relative group">
                                                    <i class="ph-bold ph-calendar-blank absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                    <input type="date" name="purchase_date" value="{{ old('purchase_date', $book->purchase_date) }}"
                                                        class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm uppercase [color-scheme:dark]">
                                                </div>
                                            </div>
                                        </div>
                                        
                                         {{-- Buku Paket Checkbox --}}
                                        <div>
                                            <label class="flex items-center gap-3 p-4 border border-white/10 bg-slate-900/80 rounded-2xl cursor-pointer hover:border-sky-500/50 transition shadow-sm mt-4">
                                                <input type="checkbox" name="is_textbook" value="1" {{ old('is_textbook', $book->is_textbook) ? 'checked' : '' }} class="w-5 h-5 text-sky-500 border-white/20 bg-slate-900 rounded focus:ring-sky-500">
                                                <div>
                                                    <span class="block text-sm font-bold text-white">Ini Buku Paket / Pelajaran</span>
                                                    <span class="block text-xs text-slate-400 mt-0.5">Buku paket bisa dipinjam massal selama 1 tahun.</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <div class="bg-slate-900/60 p-6 rounded-[2rem] border border-white/10">
                                    <h3 class="text-xs font-black text-sky-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-sky-500/20 text-sky-300 flex items-center justify-center text-[10px] shadow-sm border border-sky-500/30">2</span>
                                        Data Pustaka & Media
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Pengarang</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-pen-nib absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                <input type="text" name="author" value="{{ old('author', $book->author) }}" placeholder="Nama Penulis" 
                                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Penerbit</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                <input type="text" name="publisher" value="{{ old('publisher', $book->publisher) }}" placeholder="Nama Penerbit" 
                                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Lokasi Rak</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-squares-four absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                                                <input type="text" name="shelf_location" value="{{ old('shelf_location', $book->shelf_location) }}" placeholder="Contoh: R-01" 
                                                    class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-bold text-white transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Cover Buku</label>
                                            @if($book->cover_path)
                                                <div class="flex items-center gap-4 mb-3 p-3 bg-slate-900/80 rounded-xl border border-white/10 shadow-sm" id="currentCoverContainer">
                                                    <div class="w-12 h-16 rounded overflow-hidden bg-slate-800 flex-shrink-0">
                                                        <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover">
                                                    </div>
                                                    <div>
                                                        <p class="text-xs font-bold text-white">Cover Saat Ini</p>
                                                        <p class="text-[10px] text-slate-400">Biarkan kosong jika tidak ingin mengubah.</p>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="relative group">
                                                <input type="file" name="cover" id="coverInput" onchange="previewCover(event)" accept="image/*" class="block w-full text-xs text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-500/20 file:text-sky-300 hover:file:bg-sky-500/30 border border-dashed border-white/15 rounded-2xl py-3 px-4 hover:border-sky-500 transition-all cursor-pointer bg-slate-900/80 shadow-sm"/>
                                            </div>
                                            <img id="coverPreview" class="hidden mt-3 rounded-xl border border-white/10 object-cover" style="max-height: 160px;" alt="Cover Preview" />
                                        </div>

                                        {{-- INPUT E-BOOK (VALIDASI) --}}
                                        <div class="pt-4 border-t border-white/10">
                                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Update File E-Book</label>
                                            
                                            @if($book->ebook_path)
                                                <div class="flex items-center gap-2 mb-3 px-3 py-2 bg-emerald-950/50 text-emerald-400 rounded-lg border border-emerald-500/30">
                                                    <i class="ph-fill ph-check-circle text-lg"></i>
                                                    <div>
                                                        <p class="text-xs font-bold">E-Book sudah tersedia</p>
                                                        <p class="text-[10px] opacity-80">Upload baru untuk mengganti.</p>
                                                    </div>
                                                </div>
                                            @endif

                                            <div class="relative group">
                                                <i class="ph-bold ph-file-pdf absolute left-4 top-1/2 -translate-y-1/2 text-rose-400 z-10"></i>
                                                <input type="file" name="ebook_file" accept="application/pdf" class="block w-full text-xs text-slate-400 pl-11 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-500/20 file:text-rose-300 hover:file:bg-rose-500/30 border border-dashed border-white/15 rounded-2xl py-3 px-4 hover:border-rose-400 transition-all cursor-pointer bg-slate-900/80 shadow-sm @error('ebook_file') border-rose-500 bg-rose-950/40 @enderror"/>
                                            </div>
                                            @error('ebook_file')
                                                <div class="mt-2 p-3 bg-rose-950/60 border border-rose-500/30 rounded-xl text-rose-300 text-xs font-bold flex items-center gap-2">
                                                    <i class="ph-bold ph-warning-circle text-lg"></i>
                                                    {{ $message }}
                                                </div>
                                            @else
                                                <p class="text-[10px] text-slate-400 mt-1 ml-1 font-medium">*Hanya file PDF, Maksimal 50MB.</p>
                                            @enderror
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SINOPSIS --}}
                        <div class="border-t border-white/10 pt-6">
                            <label class="block text-xs font-bold text-sky-400 uppercase mb-2 ml-1">Sinopsis</label>
                            <textarea name="description" rows="3" placeholder="Ringkasan cerita..." class="w-full px-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-4 focus:ring-sky-500/20 font-medium text-white placeholder-slate-500 transition-all shadow-sm">{{ old('description', $book->description) }}</textarea>
                        </div>

                        {{-- BUTTON --}}
                       <div class="pt-4 flex justify-end gap-4 border-t border-white/10">
                            <a href="{{ route('library.books.index') }}" class="px-6 py-3.5 rounded-2xl text-slate-300 font-bold text-sm hover:bg-slate-800 hover:text-white transition-colors border border-transparent">Batal</a>
                            <button type="submit" class="px-8 py-3.5 bg-[#0d52a1] text-white font-bold rounded-2xl hover:bg-sky-600 shadow-lg shadow-sky-950/50 transition-all transform hover:-translate-y-0.5 flex items-center gap-2 active:scale-95 border border-sky-400/30">
                                <i class="ph-bold ph-check-circle text-lg"></i>
                                Simpan Perubahan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

     {{-- MODAL SCANNER BARCODE --}}
    <div id="scannerModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-sm" onclick="stopScanner()"></div>
        <div class="bg-[#021124] rounded-[2.5rem] shadow-2xl w-full max-w-md overflow-hidden relative z-10 p-6 border border-white/10 text-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-black text-white">Scan Barcode Buku</h3>
                <button onclick="stopScanner()" class="text-slate-400 hover:text-rose-400 transition-colors w-8 h-8 rounded-full hover:bg-rose-500/20 flex items-center justify-center">
                    <i class="ph-bold ph-x text-xl"></i>
                </button>
            </div>
            <div id="reader" class="w-full rounded-2xl overflow-hidden bg-slate-900 border-4 border-slate-800"></div>
            <p class="text-xs text-slate-400 text-center mt-4 font-medium"><i class="ph-bold ph-info text-sky-400"></i> Arahkan kamera ke barcode buku.</p>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        function previewCover(event) {
            const input = event.target;
            const preview = document.getElementById('coverPreview');
            const currentCover = document.getElementById('currentCoverContainer');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if(currentCover) currentCover.classList.add('hidden'); 
                }
                reader.readAsDataURL(input.files[0]);
            } else {
                preview.src = '';
                preview.classList.add('hidden');
                if(currentCover) currentCover.classList.remove('hidden'); 
            }
        }
        
        async function addNewCategory() {
             const { value: newCategory } = await Swal.fire({
                title: 'Tambah Kategori Baru',
                input: 'text',
                inputPlaceholder: 'Contoh: Novel, Biografi, Sains',
                confirmButtonText: 'Simpan',
                confirmButtonColor: '#0d52a1',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                background: '#021124',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold',
                    input: 'rounded-xl border-white/10 bg-slate-900 text-white focus:ring-sky-500 focus:border-[#56bbf1]'
                },
                inputValidator: (value) => { if (!value) return 'Nama kategori tidak boleh kosong!' }
            });

            if (newCategory) {
                try {
                    const response = await fetch("{{ route('library.books.categories.ajax') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
                        body: JSON.stringify({ name: newCategory })
                    });
                    const data = await response.json();
                    if (data.success) {
                        const select = document.getElementById('category_id');
                        const option = new Option(data.name, data.id, true, true);
                        select.add(option);
                        Swal.fire({
                            icon: 'success', title: 'Berhasil!', text: data.message, timer: 1500, showConfirmButton: false,
                            background: '#021124', color: '#fff',
                            customClass: { popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white' }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error', title: 'Gagal', text: data.message,
                            background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1',
                            customClass: { popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white' }
                        });
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error', title: 'Error', text: 'Gagal menghubungi server.',
                        background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1',
                        customClass: { popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white' }
                    });
                }
            }
        }

        let html5QrcodeScanner = null;
        function startScanner() {
            document.getElementById('scannerModal').classList.remove('hidden');
            if (html5QrcodeScanner === null) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }
            html5QrcodeScanner.start({ facingMode: "environment" }, { fps: 10, qrbox: { width: 250, height: 150 }, aspectRatio: 1.0 }, (decodedText) => {
                document.getElementById('book_code').value = decodedText;
                stopScanner();
            }).catch(err => console.error(err));
        }
        function stopScanner() {
            if (html5QrcodeScanner) {
                html5QrcodeScanner.stop().then(() => { document.getElementById('scannerModal').classList.add('hidden'); });
            } else {
                document.getElementById('scannerModal').classList.add('hidden');
            }
        }
    </script>
</x-app-layout>