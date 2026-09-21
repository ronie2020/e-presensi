<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-sky-600/10 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10 relative z-10">                
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-[#031d3d] to-[#021124] p-8 sm:p-10 text-white shadow-2xl overflow-hidden border border-white/10 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute top-0 right-0 w-80 h-80 bg-sky-500/10 rounded-full blur-[80px] translate-x-1/2 -translate-y-1/2 pointer-events-none transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Text Content --}}
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-3 mb-2">
                            <a href="{{ route('library.dashboard') }}" class="px-3 py-1 bg-slate-800/80 hover:bg-slate-700 rounded-full text-xs font-bold text-slate-300 transition flex items-center gap-2 border border-white/10 backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-slate-600 text-xs">•</span>
                            <span class="text-sky-400 bg-sky-500/10 border border-sky-500/20 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider backdrop-blur-sm shadow-sm">Koleksi</span>
                        </div>                      
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-800/80 border border-slate-700/60 text-sky-400 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-books"></i> Katalog Perpustakaan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Koleksi Buku
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base font-semibold leading-relaxed max-w-lg">
                            Kelola inventaris buku perpustakaan, pantau ketersediaan stok, dan baca koleksi E-Book digital.
                        </p>
                    </div>
                    
                    {{-- Stats Cards --}}
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <div class="bg-slate-900/80 backdrop-blur-md px-6 py-5 rounded-2xl border border-white/10 shadow-sm flex-1 md:flex-none min-w-[140px] text-center md:text-left hover:bg-slate-900 transition-colors">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-sky-400">
                                <i class="ph-duotone ph-book-open-text text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Judul</span>
                            </div>
                            <span class="block text-3xl font-black text-white tracking-tight">{{ $books->total() }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Toolbar: Filter & Actions --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 rounded-[2rem] shadow-2xl border border-white/10 mb-8 flex flex-col lg:flex-row gap-6 justify-between items-center relative overflow-hidden">
                
                {{-- Form Pencarian --}}
                <form method="GET" class="w-full lg:w-2/3 flex flex-col sm:flex-row gap-4 relative z-10">
                    <div class="relative flex-1 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul, pengarang, atau kode buku..." 
                            class="w-full pl-11 pr-4 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-2 focus:ring-[#56bbf1]/30 font-bold text-white placeholder-slate-500 transition-all shadow-sm">
                    </div>
                    <div class="relative sm:w-64 group">
                        <i class="ph-bold ph-tag absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-sky-400 transition-colors"></i>
                        <select name="category_id" onchange="this.form.submit()" 
                            class="w-full pl-11 pr-10 py-3.5 rounded-2xl border-white/10 bg-slate-900/80 focus:bg-slate-900 focus:border-[#56bbf1] focus:ring-2 focus:ring-[#56bbf1]/30 font-bold text-white transition-all shadow-sm appearance-none cursor-pointer">
                            <option value="" class="bg-slate-900 text-white">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </form>

                {{-- Action Buttons --}}
                <div class="flex gap-3 w-full lg:w-auto relative z-10">
                    <button onclick="document.getElementById('importModal').classList.remove('hidden')" class="flex-1 lg:flex-none px-6 py-3.5 bg-slate-800/80 text-sky-400 hover:bg-sky-500/20 border border-white/10 rounded-2xl font-bold text-sm transition-all shadow-sm flex items-center justify-center gap-2 group">
                        <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                        <span>Import</span>
                    </button>
                    <a href="{{ route('library.books.create') }}" class="flex-1 lg:flex-none px-6 py-3.5 bg-sky-600 text-white hover:bg-sky-500 rounded-2xl font-bold text-sm transition-all shadow-lg shadow-sky-600/30 flex items-center justify-center gap-2 transform active:scale-95 border border-transparent">
                        <i class="ph-bold ph-plus-circle text-lg"></i>
                        <span>Tambah Buku</span>
                    </a>
                </div>
            </div>

            {{-- Grid Buku --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($books as $book)
                    <div class="group bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-white/10 hover:border-[#56bbf1]/50 hover:shadow-2xl transition-all duration-300 flex flex-col h-full overflow-hidden relative">
                        
                        {{-- Cover Image --}}
                        <div class="h-64 bg-slate-950 relative overflow-hidden">
                            @if($book->cover_path)
                                <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $book->title }}">
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-500 bg-slate-900/50">
                                    <i class="ph-duotone ph-book-open text-5xl mb-2 opacity-50"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest opacity-50">No Cover</span>
                                </div>
                            @endif
                            
                            {{-- Overlay Gradient --}}
                            <div class="absolute inset-0 bg-gradient-to-t from-[#020b18] via-transparent to-transparent opacity-80 group-hover:opacity-90 transition-opacity"></div>

                            {{-- BADGE E-BOOK --}}
                            @if($book->ebook_path)
                                <div class="absolute top-4 left-4 z-10">
                                    <span class="px-2 py-1 bg-rose-500/90 backdrop-blur-md text-[10px] font-bold text-white rounded-lg shadow-sm border border-white/20 flex items-center gap-1">
                                        <i class="ph-bold ph-file-pdf"></i> E-Book
                                    </span>
                                </div>
                            @endif

                            {{-- Badge Kategori --}}
                            <div class="absolute top-4 right-4">
                                <span class="px-3 py-1.5 bg-slate-900/90 backdrop-blur-md text-[10px] font-black uppercase tracking-wider rounded-xl text-slate-200 shadow-sm border border-white/10">
                                    {{ $book->category->name ?? 'Umum' }}
                                </span>
                            </div>

                            {{-- Stok Badge --}}
                            <div class="absolute bottom-4 left-4 flex items-center gap-2">
                                <span class="px-3 py-1.5 rounded-lg text-xs font-bold text-white flex items-center gap-1.5 backdrop-blur-md border border-white/10 shadow-lg {{ $book->stock > 0 ? 'bg-emerald-500/80' : 'bg-rose-500/80' }}">
                                    <i class="{{ $book->stock > 0 ? 'ph-bold ph-check-circle' : 'ph-bold ph-x-circle' }}"></i>
                                    Stok: {{ $book->stock }}
                                </span>
                            </div>
                        </div>

                        {{-- Content --}}
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="mb-4">
                                <h3 class="font-black text-white text-lg leading-snug line-clamp-2 mb-1 group-hover:text-sky-400 transition-colors" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </h3>
                                <p class="text-xs text-slate-400 font-bold flex items-center gap-1">
                                    <i class="ph-fill ph-pen-nib text-sky-400"></i> {{ $book->author ?? 'Tanpa Pengarang' }}
                                </p>
                            </div>

                            <div class="mt-auto pt-4 border-t border-white/10 flex items-center justify-between">
                                {{-- TOMBOL BACA / KODE BUKU --}}
                                @if($book->ebook_path)
                                    <a href="{{ route('library.books.read', $book->id) }}" 
                                       class="px-4 py-2.5 rounded-xl bg-sky-600 text-white text-xs font-bold shadow-lg shadow-sky-600/30 hover:bg-sky-500 hover:scale-105 transition-all flex items-center gap-2 group/btn border border-transparent">
                                        <i class="ph-bold ph-read-cv-logo text-lg group-hover/btn:animate-pulse"></i>
                                        <span>Baca</span>
                                    </a>
                                @else
                                    <div class="bg-slate-900/80 px-3 py-1.5 rounded-lg border border-white/10 shadow-sm">
                                        <span class="text-[10px] font-mono font-bold text-slate-400">{{ $book->book_code }}</span>
                                    </div>
                                @endif

                                <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity transform translate-y-2 group-hover:translate-y-0 duration-300">
                                    <a href="{{ route('library.books.edit', $book->id) }}" class="w-8 h-8 rounded-lg bg-sky-500/20 text-sky-400 flex items-center justify-center hover:bg-sky-600 hover:text-white transition-colors border border-sky-500/30" title="Edit">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                    </a>
                                    <form action="{{ route('library.books.destroy', $book->id) }}" method="POST" class="delete-form">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="confirmDelete(this)" class="w-8 h-8 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center hover:bg-rose-600 hover:text-white transition-colors border border-rose-500/30" title="Hapus">
                                            <i class="ph-bold ph-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-20 text-center bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl">
                        <div class="w-24 h-24 bg-sky-500/20 rounded-full flex items-center justify-center mx-auto mb-6 text-sky-400 shadow-inner border border-sky-500/30">
                            <i class="ph-duotone ph-books text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-white mb-2">Koleksi Masih Kosong</h3>
                        <p class="text-slate-400 text-sm max-w-xs mx-auto mb-6">Belum ada data buku yang ditemukan. Mulai dengan menambahkan buku baru.</p>
                        <a href="{{ route('library.books.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-sky-600 text-white font-bold rounded-xl hover:bg-sky-500 transition shadow-lg shadow-sky-600/30 border border-transparent active:scale-95">
                            <i class="ph-bold ph-plus"></i> Tambah Buku Pertama
                        </a>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $books->links() }}
            </div>
        </div>
    </div>

    {{-- MODAL IMPORT EXCEL --}}
    <div id="importModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" onclick="document.getElementById('importModal').classList.add('hidden')"></div>
        <div class="flex min-h-screen items-center justify-center p-4">
            <div class="bg-[#021124] rounded-[2.5rem] shadow-2xl w-full max-w-lg overflow-hidden transform transition-all border border-white/10 relative z-10">
                
                {{-- Header Modal --}}
                <div class="bg-slate-900 p-6 flex justify-between items-center border-b border-white/10">
                    <h3 class="text-lg font-black text-white flex items-center gap-2">
                        <i class="ph-bold ph-microsoft-excel-logo text-sky-400"></i> Import Data Buku
                    </h3>
                    <button onclick="document.getElementById('importModal').classList.add('hidden')" class="text-slate-400 hover:text-white transition-colors bg-slate-800 w-8 h-8 rounded-full flex items-center justify-center border border-white/10">
                        <i class="ph-bold ph-x text-xl"></i>
                    </button>
                </div>

                <div class="p-8">
                    <div class="text-center mb-6">
                        <p class="text-sm text-slate-300 font-medium leading-relaxed">
                            Upload file Excel (.xlsx / .csv) untuk menambahkan banyak buku sekaligus.
                        </p>
                    </div>
                    
                    <div class="bg-slate-900/80 p-4 rounded-2xl border border-white/10 mb-6">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Format Header Wajib:</p>
                        <div class="font-mono text-xs text-slate-200 bg-slate-950 p-3 rounded-xl border border-white/10 overflow-x-auto whitespace-nowrap shadow-sm">
                            kode_buku, judul, pengarang, penerbit, tahun, stok, rak, kategori
                        </div>
                    </div>

                    <form action="{{ route('library.books.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="relative group mb-6">
                            <input type="file" name="file" required class="block w-full text-xs text-slate-400 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-slate-800 file:text-sky-400 hover:file:bg-sky-500/20 border border-dashed border-white/20 rounded-2xl py-3 px-4 hover:border-sky-400 transition-all cursor-pointer bg-slate-900/80">
                            
                            {{-- ERROR MESSAGE INLINE --}}
                            @error('file')
                                <div class="mt-2 p-3 bg-rose-500/20 border border-rose-500/30 rounded-xl flex items-center gap-2 text-rose-300 text-xs font-bold animate-pulse">
                                    <i class="ph-bold ph-warning-circle text-lg"></i>
                                    <span>{{ $message }}</span>
                                </div>
                            @enderror
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" onclick="document.getElementById('importModal').classList.add('hidden')" class="flex-1 py-3.5 rounded-xl bg-slate-800 text-slate-300 font-bold text-sm hover:bg-slate-700 transition-colors border border-white/10">Batal</button>
                            <button type="submit" class="flex-1 py-3.5 rounded-xl bg-sky-600 text-white font-bold text-sm hover:bg-sky-500 shadow-lg shadow-sky-600/30 transition-all transform active:scale-95 flex items-center justify-center gap-2 border border-transparent">
                                <i class="ph-bold ph-upload-simple"></i> Upload
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

     {{-- SCRIPT SWEETALERT UNTUK DELETE --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(button) {
            Swal.fire({
                title: 'Hapus Buku Ini?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                background: '#021124',
                color: '#fff',
                confirmButtonColor: '#0d52a1',
                cancelButtonColor: '#94a3b8',  
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' }
            }).then((result) => {
                if (result.isConfirmed) {
                    button.closest('form').submit();
                }
            })
        }
    </script>
    
    {{-- SCRIPT AUTO OPEN MODAL JIKA ERROR --}}
    @if($errors->has('file'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('importModal').classList.remove('hidden');
            Swal.fire({
                icon: 'error',
                title: 'Gagal Upload',
                text: {!! json_encode($errors->first('file')) !!},
                background: '#021124',
                color: '#fff',
                confirmButtonColor: '#0d52a1',
                customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124] text-white' }
            });
        });
    </script>
    @endif
</x-app-layout>