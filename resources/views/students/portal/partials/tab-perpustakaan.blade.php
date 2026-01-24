<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    
    {{-- SIDEBAR KIRI: STATISTIK --}}
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-3xl shadow-sm border border-indigo-100 sticky top-24 h-fit">
            <h3 class="text-lg font-bold text-slate-800 mb-1">Perpustakaan</h3>
            <p class="text-slate-400 text-xs mb-4">Statistik aktivitas membaca.</p>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                            <i class="ph-bold ph-book-open-text"></i>
                        </div>
                        <span class="text-xs font-bold text-slate-500 uppercase">Total Bacaan</span>
                    </div>
                    <span class="text-xl font-black text-slate-800">{{ $library_visits ?? 0 }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- KONTEN UTAMA: E-BOOK & RIWAYAT --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- BAGIAN 1: KOLEKSI E-BOOK --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-books text-blue-500"></i> Perpustakaan Digital
                </h3>
                <span class="text-xs font-bold text-slate-400 bg-slate-100 px-2 py-1 rounded-lg">
                    {{ isset($ebooks) ? $ebooks->count() : 0 }} Buku
                </span>
            </div>

            @if(isset($ebooks) && $ebooks->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
                    @foreach($ebooks as $book)
                        <div class="group relative bg-slate-50 rounded-2xl p-2 border border-slate-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
                            {{-- Cover --}}
                            <div class="aspect-[2/3] bg-slate-200 rounded-xl overflow-hidden mb-2 relative shadow-inner">
                                @if($book->cover_path)
                                    <img src="{{ asset('storage/' . $book->cover_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" alt="{{ $book->title }}">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-400">
                                        <i class="ph-duotone ph-book-open text-3xl"></i>
                                    </div>
                                @endif
                                
                                {{-- Tombol Baca Overlay --}}
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[1px]">
                                    {{-- PERBAIKAN: Menambahkan parameter origin=portal --}}
                                    <a href="{{ route('library.books.read', ['book' => $book->id, 'origin' => 'portal']) }}" class="px-3 py-1.5 bg-white text-blue-900 rounded-full text-[10px] font-bold shadow-xl transform scale-90 group-hover:scale-100 transition-transform flex items-center gap-1 hover:bg-blue-50">
                                        <i class="ph-bold ph-read-cv-logo"></i> BACA
                                    </a>
                                </div>
                            </div>
                            
                            {{-- Info Buku --}}
                            <div class="px-1">
                                <h4 class="font-bold text-slate-800 text-xs line-clamp-2 leading-snug mb-1 group-hover:text-blue-600 transition-colors" title="{{ $book->title }}">
                                    {{ $book->title }}
                                </h4>
                                <p class="text-[10px] text-slate-400 line-clamp-1">{{ $book->author ?? 'Tanpa Pengarang' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
                    <i class="ph-duotone ph-books text-4xl text-slate-300 mb-2 block"></i>
                    <span class="text-slate-400 font-bold text-xs">Belum ada koleksi E-Book.</span>
                </div>
            @endif
        </div>

        {{-- BAGIAN 2: RIWAYAT PEMINJAMAN FISIK --}}
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 pb-2">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-clock-counter-clockwise text-orange-500"></i> Riwayat Peminjaman
                </h3>
            </div>
            
            <div class="divide-y divide-gray-50">
                @if(isset($library_history) && count($library_history) > 0)
                    @foreach($library_history as $loan)
                    <div class="p-5 hover:bg-indigo-50/30 transition-colors flex items-center gap-4">
                        <div class="w-12 h-16 bg-slate-100 rounded flex-shrink-0 flex items-center justify-center text-slate-400 shadow-sm border border-slate-200">
                            <i class="ph-fill ph-book-bookmark text-2xl"></i>
                        </div>
                        <div class="flex-grow min-w-0">
                            <h4 class="font-bold text-slate-800 truncate text-sm" title="{{ $loan->book->title ?? 'Judul Tidak Diketahui' }}">
                                {{ $loan->book->title ?? 'Buku Dihapus' }}
                            </h4>
                            <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-slate-500 font-medium">
                                <span class="flex items-center gap-1">
                                    <i class="ph-bold ph-calendar-blank"></i>
                                    Pinjam: {{ \Carbon\Carbon::parse($loan->borrow_date)->translatedFormat('d M Y') }}
                                </span>
                                
                                @if($loan->status == 'returned')
                                    <span class="text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-100">Dikembalikan</span>
                                @elseif($loan->status == 'overdue')
                                    <span class="text-rose-600 bg-rose-50 px-2 py-0.5 rounded text-[10px] font-bold border border-rose-100">Terlambat</span>
                                @else
                                    <span class="text-blue-600 bg-blue-50 px-2 py-0.5 rounded text-[10px] font-bold border border-blue-100">Dipinjam</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="p-12 text-center">
                        <div class="inline-flex p-4 bg-orange-50 rounded-full text-orange-400 mb-3">
                            <i class="ph-duotone ph-smiley-sad text-3xl"></i>
                        </div>
                        <h3 class="font-bold text-slate-800 text-sm">Belum ada riwayat peminjaman</h3>
                        <p class="text-xs text-slate-400 mt-1">Pinjam buku fisik di perpustakaan untuk melihat riwayat.</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>