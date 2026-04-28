<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Perpustakaan</title>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-elevate-surface font-sans text-elevate-dark">

    {{-- HERO SECTION (ELEVATED THEME) --}}
    <div class="bg-elevate-gradient-main pt-16 pb-24 px-4 sm:px-6 lg:px-8 relative overflow-hidden border-b border-white/60">
        <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
        <div class="absolute top-0 left-1/2 w-96 h-96 bg-white/40 rounded-full blur-[80px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        
        {{-- TOMBOL NAVIGASI --}}
        <div class="absolute top-6 left-6 z-20">
            @if(Auth::guard('student')->check())
                {{-- Jika Login sebagai Siswa: Kembali ke Portal (Tab Perpustakaan) --}}
                <a href="{{ route('portal.show', Auth::guard('student')->id()) }}?tab=perpustakaan" class="text-elevate-primary hover:text-elevate-dark flex items-center gap-2 text-sm font-bold transition-colors bg-white/50 hover:bg-white/80 px-4 py-2 rounded-full backdrop-blur-sm border border-white/60 shadow-sm">
                    <i class="ph-bold ph-arrow-left"></i> Kembali ke Portal
                </a>
            @else
                {{-- Jika Tamu: Kembali ke Beranda --}}
                <a href="{{ url('/') }}" class="text-elevate-primary hover:text-elevate-dark flex items-center gap-2 text-sm font-bold transition-colors bg-white/50 hover:bg-white/80 px-4 py-2 rounded-full backdrop-blur-sm border border-white/60 shadow-sm">
                    <i class="ph-bold ph-house"></i> Beranda
                </a>
            @endif
        </div>

        <div class="max-w-7xl mx-auto relative z-10 text-center mt-8">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-soft/80 border border-elevate-accent/30 text-elevate-primary text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-md shadow-sm">
                <i class="ph-fill ph-student"></i> Perpustakaan Digital
            </div>
            <h1 class="text-4xl md:text-5xl font-black tracking-tight text-elevate-dark mb-4 leading-tight drop-shadow-sm">
                Jelajahi Dunia Lewat <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-primary to-elevate-accent">Buku</span>
            </h1>
            <p class="text-elevate-dark/70 text-lg font-medium max-w-2xl mx-auto">
                Cari buku favoritmu, cek ketersediaan stok, atau baca E-Book langsung dari perangkatmu.
            </p>

            {{-- SEARCH BAR --}}
            <div class="mt-10 max-w-3xl mx-auto">
                <form method="GET" class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-elevate-primary to-elevate-accent rounded-2xl blur opacity-25 group-hover:opacity-40 transition duration-1000 group-hover:duration-200"></div>
                    <div class="relative flex bg-white rounded-2xl shadow-xl overflow-hidden p-1.5 border border-slate-100">
                        <div class="flex-1 flex items-center px-4">
                            <i class="ph-bold ph-magnifying-glass text-slate-400 text-xl group-focus-within:text-elevate-primary transition-colors"></i>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul buku, penulis, atau topik..." 
                                class="w-full border-none focus:ring-0 text-elevate-dark font-bold placeholder-slate-400 h-12 bg-transparent outline-none">
                        </div>
                        <div class="w-px h-8 bg-slate-100 my-auto"></div>
                        <div class="w-1/3 max-w-[200px] hidden sm:block">
                            <select name="category_id" onchange="this.form.submit()" 
                                class="w-full border-none focus:ring-0 text-elevate-dark/70 font-bold text-sm bg-transparent h-12 cursor-pointer outline-none">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="bg-elevate-dark hover:bg-elevate-primary text-white px-8 rounded-xl font-bold transition-colors shadow-lg shadow-elevate-dark/20 active:scale-95">
                            Cari
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CONTENT GRID --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20 pb-20">
        
        @if(request('search') || request('category_id'))
            <div class="mb-6 flex items-center justify-between bg-white p-4 rounded-xl shadow-sm border border-slate-100">
                <h3 class="font-bold text-elevate-dark/70 uppercase text-xs tracking-wider">
                    Hasil Pencarian: "{{ request('search') }}"
                </h3>
                <a href="{{ route('library.catalogue') }}" class="text-rose-500 text-xs font-bold hover:underline flex items-center gap-1">
                    <i class="ph-bold ph-x-circle"></i> Reset Filter
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($books as $book)
                <div class="group bg-elevate-gradient-card rounded-[2rem] border border-slate-200 hover:border-elevate-accent/50 hover:shadow-2xl hover:shadow-elevate-accent/20 transition-all duration-300 flex flex-col h-full overflow-hidden relative transform hover:-translate-y-1">
                    
                    {{-- Cover Image --}}
                    <div class="h-72 bg-elevate-soft relative overflow-hidden">
                        @if($book->cover_path)
                            <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $book->title }}">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-300 bg-white">
                                <i class="ph-duotone ph-book-open text-5xl mb-2 opacity-50"></i>
                                <span class="text-[10px] font-black uppercase tracking-widest opacity-50">No Cover</span>
                            </div>
                        @endif
                        
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-60 group-hover:opacity-80 transition-opacity"></div>

                        {{-- Badge E-Book --}}
                        @if($book->ebook_path)
                            <div class="absolute top-4 left-4 z-10">
                                <span class="px-3 py-1.5 bg-rose-500 text-[10px] font-black text-white uppercase tracking-wider rounded-lg shadow-lg shadow-rose-500/30 flex items-center gap-1.5 animate-pulse">
                                    <i class="ph-fill ph-file-pdf"></i> E-Book
                                </span>
                            </div>
                        @endif

                        {{-- Badge Kategori --}}
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 bg-white/20 backdrop-blur-md text-[10px] font-black uppercase tracking-wider rounded-lg text-white border border-white/20 shadow-sm">
                                {{ $book->category->name ?? 'Umum' }}
                            </span>
                        </div>

                        {{-- Info Stok --}}
                        <div class="absolute bottom-4 left-4 right-4 text-white">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold {{ $book->stock > 0 ? 'bg-emerald-500/80 text-white' : 'bg-rose-500/80 text-white' }} backdrop-blur-md">
                                    {{ $book->stock > 0 ? 'Stok: '.$book->stock : 'Habis' }}
                                </span>
                                <span class="px-2 py-1 rounded-md text-[10px] font-bold bg-white/20 backdrop-blur-md border border-white/10 font-mono">
                                    {{ $book->book_code }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="mb-4">
                            <h3 class="font-black text-elevate-dark text-lg leading-snug line-clamp-2 mb-1 group-hover:text-elevate-primary transition-colors" title="{{ $book->title }}">
                                {{ $book->title }}
                            </h3>
                            <p class="text-xs text-elevate-dark/60 font-bold flex items-center gap-1">
                                <i class="ph-fill ph-pen-nib text-elevate-accent"></i> {{ $book->author ?? 'Tanpa Pengarang' }}
                            </p>
                        </div>
                        
                        <p class="text-xs text-elevate-dark/50 line-clamp-2 mb-4 leading-relaxed">
                            {{ $book->description ?? 'Tidak ada sinopsis tersedia.' }}
                        </p>

                        <div class="mt-auto pt-4 border-t border-slate-100">
                            @if($book->ebook_path)
                                {{-- Link tanpa origin portal, karena ini di halaman umum --}}
                                <a href="{{ route('library.books.read', $book->id) }}" 
                                   class="w-full py-3.5 rounded-xl bg-elevate-dark hover:bg-elevate-primary text-white text-xs font-bold shadow-lg shadow-elevate-dark/20 transition-all flex items-center justify-center gap-2 group/btn transform active:scale-95 border border-transparent">
                                    <i class="ph-bold ph-book-open-text text-lg group-hover/btn:animate-pulse"></i>
                                    <span>Baca E-Book</span>
                                </a>
                            @else
                                <button disabled class="w-full py-3.5 rounded-xl bg-elevate-soft text-elevate-dark/40 text-xs font-bold flex items-center justify-center gap-2 cursor-not-allowed">
                                    <i class="ph-bold ph-prohibit text-lg"></i>
                                    <span>Fisik Only</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white rounded-[2.5rem] border border-slate-200 shadow-sm mt-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-elevate-soft mb-4 shadow-inner">
                        <i class="ph-duotone ph-magnifying-glass text-5xl text-elevate-primary"></i>
                    </div>
                    <h3 class="text-lg font-black text-elevate-dark">Buku tidak ditemukan</h3>
                    <p class="text-elevate-dark/50 text-sm">Coba cari dengan kata kunci lain.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-10">
            {{ $books->links() }}
        </div>
    </div>

</body>
</html>