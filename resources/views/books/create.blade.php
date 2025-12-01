<x-app-layout>
    {{-- Scripts External --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        #reader video {
            object-fit: cover;
            width: 100% !important;
            height: 100% !important;
            border-radius: 0.75rem;
        }
    </style>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Navigasi --}}
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-black text-gray-800 tracking-tight">Tambah Buku Baru</h1>
                    <p class="text-sm text-gray-500 mt-1">Masukkan detail buku secara lengkap untuk inventaris perpustakaan.</p>
                </div>
                <a href="{{ route('library.books.index') }}" class="text-sm font-bold text-gray-500 hover:text-indigo-600 flex items-center gap-1 transition-colors">
                    Kembali
                </a>
            </div>

            {{-- Form Card --}}
            <div class="bg-white rounded-3xl shadow-xl shadow-indigo-50/50 border border-indigo-50 overflow-hidden">
                <div class="p-8">
                    
                    <form action="{{ route('library.books.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI: IDENTITAS UTAMA --}}
                            <div class="space-y-6">
                                <div class="bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100">
                                    <h3 class="text-xs font-bold text-indigo-500 uppercase tracking-wider mb-4">Identitas Buku</h3>
                                    
                                    <div class="space-y-5">
                                        {{-- Kode Buku --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Kode Buku / Barcode *</label>
                                            <div class="flex gap-2">
                                                <div class="relative flex-1">
                                                    <input type="text" name="book_code" id="book_code" value="{{ $autoCode ?? '' }}" required 
                                                        class="w-full rounded-xl border-indigo-200 bg-white focus:border-indigo-500 focus:ring-indigo-500 font-mono font-bold text-indigo-700 pl-4 pr-10">
                                                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                        <svg class="w-5 h-5 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                                    </div>
                                                </div>
                                                {{-- Tombol Scanner --}}
                                                <button type="button" onclick="startScanner()" class="shrink-0 p-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl transition-colors shadow-md shadow-indigo-200" title="Scan Barcode">
                                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Judul Buku --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Judul Buku *</label>
                                            <input type="text" name="title" placeholder="Masukkan judul lengkap..." required 
                                                class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        {{-- Kategori (DENGAN TOMBOL TAMBAH) --}}
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Kategori</label>
                                            <div class="flex gap-2">
                                                <select name="category_id" id="category_id" class="w-full rounded-xl border-gray-200 bg-white focus:border-indigo-500 focus:ring-indigo-500 text-gray-700">
                                                    <option value="">-- Pilih Kategori --</option>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                                    @endforeach
                                                </select>
                                                
                                                {{-- Tombol Plus untuk Tambah Kategori --}}
                                                <button type="button" onclick="addNewCategory()" class="shrink-0 px-4 py-2 bg-indigo-50 text-indigo-600 font-bold rounded-xl hover:bg-indigo-100 transition-colors border border-indigo-100" title="Buat Kategori Baru">
                                                    +
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Stok & Tahun --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Stok *</label>
                                                <input type="number" name="stock" value="1" min="0" required 
                                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Tahun Terbit</label>
                                                <input type="number" name="year" placeholder="YYYY" min="1900" max="{{ date('Y') + 1 }}"
                                                    class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN: DATA PUSTAKA --}}
                            <div class="space-y-6">
                                <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                                    <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Data Pustaka</h3>
                                    
                                    <div class="space-y-5">
                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Pengarang</label>
                                            <input type="text" name="author" placeholder="Nama Penulis" 
                                                class="w-full rounded-xl border-gray-200 bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Penerbit</label>
                                            <input type="text" name="publisher" placeholder="Nama Penerbit" 
                                                class="w-full rounded-xl border-gray-200 bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Lokasi Rak</label>
                                            <input type="text" name="shelf_location" placeholder="Contoh: R-01" 
                                                class="w-full rounded-xl border-gray-200 bg-white focus:border-indigo-500 focus:ring-indigo-500">
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Cover Buku</label>
                                            <input type="file" name="cover" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 cursor-pointer border border-gray-200 rounded-xl bg-white"/>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SINOPSIS --}}
                        <div class="border-t border-gray-100 pt-6">
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1.5">Sinopsis</label>
                            <textarea name="description" rows="3" placeholder="Ringkasan cerita..." class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                        </div>

                        {{-- BUTTON --}}
                        <div class="pt-4 flex justify-end">
                            <button type="submit" class="px-8 py-3.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 shadow-lg shadow-indigo-200 transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Simpan Buku
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL SCANNER --}}
    <div id="scannerModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="stopScanner()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-bold text-gray-900 mb-2 text-center">Scan Barcode</h3>
                    <div class="relative bg-black rounded-xl overflow-hidden aspect-square">
                        <div id="reader" class="w-full h-full"></div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-transparent shadow-sm px-4 py-2 bg-white text-base font-bold text-gray-700 hover:bg-gray-100 focus:outline-none border-gray-300 sm:ml-3 sm:w-auto sm:text-sm" onclick="stopScanner()">Batal</button>
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
                inputLabel: 'Masukkan nama kategori',
                inputPlaceholder: 'Contoh: Novel, Biografi, Sains',
                showCancelButton: true,
                confirmButtonText: 'Simpan',
                confirmButtonColor: '#4f46e5',
                cancelButtonText: 'Batal',
                inputValidator: (value) => {
                    if (!value) return 'Nama kategori tidak boleh kosong!'
                }
            });

            if (newCategory) {
                // Tampilkan Loading
                Swal.fire({
                    title: 'Menyimpan...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });

                try {
                    // REQUEST AJAX KE SERVER
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
                        // Tambahkan ke dropdown & pilih otomatis
                        const select = document.getElementById('category_id');
                        const option = new Option(data.name, data.id, true, true);
                        select.add(option);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            timer: 1500,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire('Gagal', data.message || 'Terjadi kesalahan saat menyimpan.', 'error');
                    }
                } catch (error) {
                    console.error(error);
                    Swal.fire('Error', 'Gagal menghubungi server. Cek koneksi internet.', 'error');
                }
            }
        }

        // --- LOGIKA SCANNER ---
        let html5QrcodeScanner = null;
        let isScanning = false;

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