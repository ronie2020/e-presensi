<x-app-layout>
    {{-- Scripts External --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('library.books.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors group">
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

            {{-- Form Card --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-book-bookmark"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10">Tambah Buku Baru</h2>
                    <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Lengkapi detail buku untuk inventaris perpustakaan.</p>
                </div>

                <div class="p-8">
                    <form action="{{ route('library.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI: IDENTITAS UTAMA --}}
                            <div class="space-y-6">
                                <div class="bg-blue-50/50 p-6 rounded-[2rem] border border-blue-100">
                                    <h3 class="text-xs font-black text-blue-900 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-blue-200 text-blue-700 flex items-center justify-center text-[10px]">1</span>
                                        Identitas Buku
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        {{-- Kode Buku --}}
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kode Buku / Barcode <span class="text-rose-500">*</span></label>
                                            <div class="flex gap-2">
                                                <div class="relative flex-1 group">
                                                    <i class="ph-bold ph-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                                    <input type="text" name="book_code" id="book_code" value="{{ $autoCode ?? old('book_code') }}" required placeholder="Scan atau ketik kode..."
                                                        class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-mono font-bold text-slate-700 transition-all shadow-sm @error('book_code') border-rose-500 @enderror">
                                                </div>
                                                <button type="button" onclick="startScanner()" class="shrink-0 p-3 bg-blue-100 hover:bg-blue-600 hover:text-white text-blue-600 rounded-2xl transition-all shadow-sm border border-blue-200" title="Scan Barcode">
                                                    <i class="ph-bold ph-qr-code text-xl"></i>
                                                </button>
                                            </div>
                                            @error('book_code')
                                                <p class="text-rose-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Judul Buku --}}
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Judul Buku <span class="text-rose-500">*</span></label>
                                            <input type="text" name="title" value="{{ old('title') }}" placeholder="Masukkan judul lengkap..." required 
                                                class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm @error('title') border-rose-500 @enderror">
                                            @error('title')
                                                <p class="text-rose-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- Kategori --}}
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Kategori</label>
                                            <div class="flex gap-2">
                                                <div class="relative flex-1">
                                                    <select name="category_id" id="category_id" class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm appearance-none cursor-pointer">
                                                        <option value="">-- Pilih Kategori --</option>
                                                        @foreach($categories as $cat)
                                                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                                </div>
                                                
                                                <button type="button" onclick="addNewCategory()" class="shrink-0 w-12 bg-indigo-50 text-indigo-600 font-black rounded-2xl hover:bg-indigo-600 hover:text-white transition-all border border-indigo-100 shadow-sm" title="Buat Kategori Baru">
                                                    <i class="ph-bold ph-plus text-lg"></i>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Stok & Tahun --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Stok <span class="text-rose-500">*</span></label>
                                                <input type="number" name="stock" value="{{ old('stock', 1) }}" min="0" required 
                                                    class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tahun Terbit</label>
                                                <input type="number" name="year" value="{{ old('year') }}" placeholder="YYYY" min="1900" max="{{ date('Y') + 1 }}"
                                                    class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: DATA PUSTAKA --}}
                            <div class="space-y-6">
                                <div class="bg-slate-50 p-6 rounded-[2rem] border border-slate-200">
                                    <h3 class="text-xs font-black text-slate-500 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-slate-200 text-slate-600 flex items-center justify-center text-[10px]">2</span>
                                        Data Pustaka
                                    </h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pengarang</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-pen-nib absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                                <input type="text" name="author" value="{{ old('author') }}" placeholder="Nama Penulis" 
                                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Penerbit</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                                <input type="text" name="publisher" value="{{ old('publisher') }}" placeholder="Nama Penerbit" 
                                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Lokasi Rak</label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-squares-four absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-500 transition-colors"></i>
                                                <input type="text" name="shelf_location" value="{{ old('shelf_location') }}" placeholder="Contoh: R-01" 
                                                    class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700 transition-all shadow-sm">
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Cover Buku</label>
                                            <div class="relative group">
                                                <input type="file" name="cover" accept="image/*" class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 border border-dashed border-slate-300 rounded-2xl py-3 px-4 hover:border-blue-400 transition-all cursor-pointer bg-white shadow-sm"/>
                                            </div>
                                            @error('cover')
                                                <p class="text-rose-500 text-xs font-bold mt-1 ml-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        {{-- INPUT E-BOOK DENGAN PESAN BARU (50MB) --}}
                                        <div class="pt-4 border-t border-slate-200">
                                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1 flex justify-between">
                                                <span>File E-Book (PDF)</span>
                                                <span class="text-blue-500 bg-blue-50 px-2 py-0.5 rounded text-[10px]">Opsional</span>
                                            </label>
                                            <div class="relative group">
                                                <i class="ph-bold ph-file-pdf absolute left-4 top-1/2 -translate-y-1/2 text-rose-400 z-10"></i>
                                                <input type="file" name="ebook_file" accept="application/pdf" class="block w-full text-xs text-slate-500 pl-11 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-rose-50 file:text-rose-600 hover:file:bg-rose-100 border border-dashed border-slate-300 rounded-2xl py-3 px-4 hover:border-rose-400 transition-all cursor-pointer bg-white shadow-sm @error('ebook_file') border-rose-500 bg-rose-50 @enderror"/>
                                            </div>
                                            
                                            @error('ebook_file')
                                                <div class="mt-2 p-3 bg-rose-100 border border-rose-200 rounded-xl text-rose-700 text-xs font-bold flex items-center gap-2">
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

                        {{-- Sinopsis --}}
                        <div class="border-t border-slate-100 pt-6">
                            <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Sinopsis</label>
                            <textarea name="description" rows="3" placeholder="Ringkasan cerita..." class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 font-medium text-slate-700 transition-all shadow-inner">{{ old('description') }}</textarea>
                        </div>

                        {{-- Button --}}
                        <div class="pt-4 flex justify-end gap-4 border-t border-slate-100">
                            <a href="{{ route('library.books.index') }}" class="px-6 py-3.5 rounded-2xl text-slate-500 font-bold text-sm hover:bg-slate-50 hover:text-slate-700 transition-colors">Batal</a>
                            <button type="submit" class="px-8 py-3.5 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-lg"></i>
                                Simpan Buku
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT JAVASCRIPT --}}
    <script>
        // --- LOGIKA TAMBAH KATEGORI (AJAX REAL) ---
        async function addNewCategory() {
            const { value: newCategory } = await Swal.fire({
                title: 'Tambah Kategori Baru',
                input: 'text',
                inputPlaceholder: 'Contoh: Novel, Biografi, Sains',
                confirmButtonText: 'Simpan',
                confirmButtonColor: '#1e3a8a',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-[2rem]',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold',
                    cancelButton: 'rounded-xl px-6 py-2.5 font-bold',
                    input: 'rounded-xl border-slate-200 focus:ring-blue-900 focus:border-blue-900'
                },
                inputValidator: (value) => {
                    if (!value) return 'Nama kategori tidak boleh kosong!'
                }
            });

            if (newCategory) {
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading(),
                    customClass: { popup: 'rounded-[2rem]' }
                });

                try {
                    const response = await fetch("{{ route('library.books.categories.ajax') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ name: newCategory })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        const select = document.getElementById('category_id');
                        const option = new Option(data.name, data.id, true, true);
                        select.add(option);
                        
                        Swal.fire({
                            icon: 'success', title: 'Berhasil!', text: data.message,
                            timer: 1500, showConfirmButton: false, customClass: { popup: 'rounded-[2rem]' }
                        });
                    } else {
                        Swal.fire({ icon: 'error', title: 'Gagal', text: data.message || 'Terjadi kesalahan.', customClass: { popup: 'rounded-[2rem]' } });
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menghubungi server.', customClass: { popup: 'rounded-[2rem]' } });
                }
            }
        }

        // --- LOGIKA SCANNER ---
        let html5QrcodeScanner = null;

        function startScanner() {
            document.getElementById('scannerModal').classList.remove('hidden');
            if (html5QrcodeScanner === null) {
                html5QrcodeScanner = new Html5Qrcode("reader");
            }
            const config = { fps: 10, qrbox: { width: 250, height: 150 }, aspectRatio: 1.0 };
            html5QrcodeScanner.start({ facingMode: "environment" }, config, (decodedText) => {
                document.getElementById('book_code').value = decodedText;
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