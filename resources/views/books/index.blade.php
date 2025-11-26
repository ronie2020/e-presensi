<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-black text-gray-800">Katalog Buku</h1>
                    <p class="text-gray-500 text-sm">Kelola koleksi buku perpustakaan sekolah.</p>
                </div>
                
                <div class="flex gap-2">
                    {{-- Tombol Import --}}
                    <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Import Excel
                    </button>

                    {{-- Tombol Tambah --}}
                    <a href="{{ route('library.books.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Buku
                    </a>
                </div>
            </div>

            {{-- Filter & Search (SAMA SEPERTI SEBELUMNYA) --}}
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm mb-6">
                <form method="GET" class="flex flex-col md:flex-row gap-3">
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, pengarang, atau kode buku..." class="w-full pl-10 rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <select name="category_id" class="rounded-xl border-gray-200 focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                        <option value="">Semua Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            {{-- Grid Buku (SAMA SEPERTI SEBELUMNYA) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden flex flex-col h-full group">
                        <div class="h-48 bg-gray-100 relative overflow-hidden">
                            @if($book->cover_path)
                                <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $book->title }}">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2">
                                <span class="px-2 py-1 bg-white/90 backdrop-blur text-xs font-bold rounded-lg text-indigo-600 shadow-sm">
                                    {{ $book->category->name ?? 'Umum' }}
                                </span>
                            </div>
                        </div>
                        <div class="p-4 flex-1 flex flex-col">
                            <h3 class="font-bold text-gray-800 line-clamp-2 mb-1">{{ $book->title }}</h3>
                            <p class="text-xs text-gray-500 mb-3">{{ $book->author ?? 'Anonim' }}</p>
                            <div class="mt-auto flex items-center justify-between pt-3 border-t border-gray-50">
                                <div class="text-xs font-mono bg-gray-100 px-2 py-1 rounded text-gray-600">{{ $book->book_code }}</div>
                                <div class="text-xs font-bold {{ $book->stock > 0 ? 'text-emerald-600' : 'text-rose-500' }}">
                                    Stok: {{ $book->stock }}
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 flex justify-between items-center">
                            <a href="{{ route('library.books.edit', $book->id) }}" class="text-xs font-bold text-indigo-600 hover:text-indigo-800">Edit</a>
                            <form action="{{ route('library.books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Hapus buku ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700">Hapus</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-12 text-center text-gray-400">
                        <p>Belum ada data buku.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-6">
                {{ $books->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL IMPORT EXCEL --}}
    <div id="importModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm" aria-hidden="true" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-gray-200">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-emerald-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-bold text-gray-900" id="modal-title">
                                Import Data Buku
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 mb-4">
                                    Silakan upload file Excel (.xlsx / .csv) berisi data buku. Pastikan format header sesuai.
                                </p>
                                
                                <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 text-xs text-gray-600 mb-4 font-mono">
                                    Format Header: <br>
                                    <strong>kode_buku, judul, pengarang, penerbit, tahun, stok, rak, kategori</strong>
                                </div>

                                <form action="{{ route('library.books.import') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="file" required class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-300 rounded-lg mb-4">
                                    
                                    <div class="flex justify-end gap-2 mt-4">
                                        <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-bold text-sm">Batal</button>
                                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 font-bold text-sm">Upload & Proses</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>