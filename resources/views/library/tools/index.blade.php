<x-app-layout>
    {{-- SCRIPT PENDUKUNG --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
    </style>

    {{-- ALERT ERROR JIKA DATA KOSONG --}}
    @if(session('error'))
    <script>
        Swal.fire({ 
            icon: 'error', 
            title: 'Oops...', 
            text: {!! json_encode(session('error')) !!}, 
            background: '#021124', color: '#fff', confirmButtonColor: '#0d52a1',
            customClass: { popup: 'rounded-[2.5rem] border border-white/10 bg-[#021124] text-white' } 
        });
    </script>
    @endif

    <div class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-sky-600/10 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <div class="mb-8 sm:mb-10 relative z-10">
                <x-hero-section
                    badge="ALAT ADMINISTRASI PUSTAKA"
                    badgeIcon="ph-fill ph-printer"
                    showcaseIcon="ph-duotone ph-printer"
                    showcaseTitle="Pusat Dokumen"
                    showcaseSubtitle="Cetak & Barcode">
                    <x-slot:title>
                        <span class="block text-slate-100">Pusat Cetak &</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Laporan Pustaka
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Kelola kebutuhan administrasi fisik perpustakaan, cetak kartu anggota barcode, label barcode buku, dan laporan sirkulasi.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-identification-card text-sky-400"></i> Kartu Anggota
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-barcode text-emerald-400"></i> Label Barcode
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-file-text text-cyan-400"></i> Bebas Pustaka
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <a href="{{ route('library.dashboard') }}"
                           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-300 hover:text-white font-bold text-xs border border-slate-700 transition-all duration-300">
                            <i class="ph-bold ph-arrow-left"></i>
                            <span>Dashboard Pustaka</span>
                        </a>
                    </x-slot:cta>
                </x-hero-section>
            </div>

            {{-- GRID MENU --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                {{-- CARD 1: CETAK KARTU ANGGOTA --}}
                <div class="animate-enter delay-100 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 h-full flex flex-col relative overflow-hidden group hover:border-sky-500/50 transition-all duration-300 text-white">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none transition-colors duration-500"></div>
                    
                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-sky-500/20 text-sky-400 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-sky-500/30 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-identification-card"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-white">Kartu Anggota</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">ID Card Siswa</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-300 font-medium mb-6 leading-relaxed">
                            Cetak kartu perpustakaan siswa. Bisa satuan atau per kelas (Batch Print).
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="{{ route('library.tools.print-card') }}" method="GET" target="_blank" class="space-y-4" x-data="{ mode: 'single' }">
                            
                            {{-- Tab Switcher --}}
                            <div class="flex bg-slate-900/80 p-1 rounded-xl mb-4 border border-white/10">
                                <button type="button" @click="mode = 'single'" :class="mode === 'single' ? 'bg-[#0d52a1] text-white shadow-sm font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-xs rounded-lg transition-all border border-transparent">Per Siswa</button>
                                <button type="button" @click="mode = 'class'" :class="mode === 'class' ? 'bg-[#0d52a1] text-white shadow-sm font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-xs rounded-lg transition-all border border-transparent">Per Kelas</button>
                            </div>

                            <input type="hidden" name="mode" x-model="mode">

                            {{-- Input Single --}}
                            <div x-show="mode === 'single'" x-transition>
                                <label class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2 ml-1">NISN / NIS Siswa</label>
                                <div class="flex items-center px-4 py-3 bg-slate-900/80 border border-white/10 rounded-2xl focus-within:border-[#56bbf1] focus-within:ring-4 focus-within:ring-sky-500/20 transition-all shadow-sm">
                                    <i class="ph-bold ph-user text-slate-500 mr-3"></i>
                                    <input type="text" name="nisn" class="w-full bg-transparent border-none focus:ring-0 text-white font-bold text-sm placeholder-slate-500" placeholder="Contoh: 12345678">
                                </div>
                            </div>

                            {{-- Input Class --}}
                            <div x-show="mode === 'class'" style="display: none;" x-transition>
                                <label class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                                <div class="relative">
                                    <select name="class_id" class="w-full bg-slate-900/80 border border-white/10 rounded-2xl px-4 py-3 font-bold text-white text-sm focus:ring-4 focus:ring-sky-500/20 focus:border-[#56bbf1] transition-all appearance-none cursor-pointer shadow-sm [color-scheme:dark]">
                                        <option value="" disabled selected class="bg-slate-900 text-slate-400">-- Pilih Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" class="bg-slate-900 text-white">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-[#0d52a1] hover:bg-sky-600 text-white font-bold rounded-2xl shadow-lg shadow-sky-950/50 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn border border-sky-400/30">
                                <i class="ph-bold ph-printer text-lg"></i> 
                                <span>Cetak Sekarang</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CARD 2: LABEL BUKU --}}
                <div class="animate-enter delay-200 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 h-full flex flex-col relative overflow-hidden group hover:border-sky-500/50 transition-all duration-300 text-white">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none transition-colors duration-500"></div>
                    
                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-sky-500/20 text-sky-400 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-sky-500/30 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-barcode"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-white">Label Buku</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Stiker Barcode</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-300 font-medium mb-6 leading-relaxed">
                            Cetak label punggung dan barcode untuk koleksi buku baru.
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="{{ route('library.tools.print-book-label') }}" method="GET" target="_blank" class="space-y-4" x-data="{ mode: 'by_book' }">
                            
                            {{-- Tab Switcher --}}
                            <div class="flex bg-slate-900/80 p-1 rounded-xl mb-4 border border-white/10">
                                <button type="button" @click="mode = 'by_book'" :class="mode === 'by_book' ? 'bg-[#0d52a1] text-white shadow-sm font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-[10px] sm:text-xs rounded-lg transition-all border border-transparent">Per Buku</button>
                                <button type="button" @click="mode = 'latest'" :class="mode === 'latest' ? 'bg-[#0d52a1] text-white shadow-sm font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-[10px] sm:text-xs rounded-lg transition-all border border-transparent">Terbaru</button>
                                <button type="button" @click="mode = 'manual'" :class="mode === 'manual' ? 'bg-[#0d52a1] text-white shadow-sm font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-[10px] sm:text-xs rounded-lg transition-all border border-transparent">Manual</button>
                            </div>

                            {{-- OPSI 1: PER BUKU --}}
                            <div x-show="mode === 'by_book'" x-transition>
                                <label class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2 ml-1">Pilih Judul Buku</label>
                                <div class="relative">
                                    <select name="book_id" :disabled="mode !== 'by_book'" class="w-full bg-slate-900/80 border border-white/10 rounded-2xl px-4 py-3 font-bold text-white text-sm focus:ring-4 focus:ring-sky-500/20 focus:border-[#56bbf1] transition-all appearance-none cursor-pointer shadow-sm [color-scheme:dark]">
                                        <option value="" disabled selected class="bg-slate-900 text-slate-400">-- Pilih Buku --</option>
                                        @foreach($books as $book)
                                            <option value="{{ $book->id }}" class="bg-slate-900 text-white">{{ $book->title }} ({{ $book->stock }} Eksemplar)</option>
                                        @endforeach
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                </div>
                                <p class="text-[10px] text-slate-400 mt-1 italic ml-1">*Akan mencetak seluruh eksemplar dari buku ini.</p>
                            </div>

                            {{-- OPSI 2: LATEST --}}
                            <div x-show="mode === 'latest'" style="display: none;" x-transition>
                                <label class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2 ml-1">Jumlah Terakhir Ditambahkan</label>
                                <div class="flex items-center px-4 py-3 bg-slate-900/80 border border-white/10 rounded-2xl focus-within:border-[#56bbf1] focus-within:ring-4 focus-within:ring-sky-500/20 transition-all shadow-sm">
                                    <i class="ph-bold ph-stack text-slate-500 mr-3"></i>
                                    <input type="number" name="limit" :disabled="mode !== 'latest'" value="10" min="1" max="100" class="w-full bg-transparent border-none focus:ring-0 text-white font-bold text-sm">
                                </div>
                            </div>

                            {{-- OPSI 3: MANUAL --}}
                            <div x-show="mode === 'manual'" style="display: none;" x-transition>
                                <label class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2 ml-1">Kode Fisik (Pisahkan koma)</label>
                                <div class="flex items-center px-4 py-3 bg-slate-900/80 border border-white/10 rounded-2xl focus-within:border-[#56bbf1] focus-within:ring-4 focus-within:ring-sky-500/20 transition-all shadow-sm">
                                    <i class="ph-bold ph-keyboard text-slate-500 mr-3"></i>
                                    <input type="text" name="book_codes" :disabled="mode !== 'manual'" class="w-full bg-transparent border-none focus:ring-0 text-white font-bold text-sm placeholder-slate-500" placeholder="BK-01, BK-02">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-4 bg-[#0d52a1] hover:bg-sky-600 text-white font-bold rounded-2xl shadow-lg shadow-sky-950/50 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn border border-sky-400/30">
                                <i class="ph-bold ph-printer text-lg"></i> 
                                <span>Cetak Label</span>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- CARD 3: LAPORAN --}}
                <div class="animate-enter delay-300 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 h-full flex flex-col relative overflow-hidden group hover:border-sky-500/50 transition-all duration-300 text-white">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-sky-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none transition-colors duration-500"></div>

                    <div class="p-8 pb-0 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-14 h-14 bg-sky-500/20 text-sky-400 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm border border-sky-500/30 group-hover:scale-110 transition-transform">
                                <i class="ph-duotone ph-file-pdf"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-black text-white">Export Laporan</h2>
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-1">Data Sirkulasi</p>
                            </div>
                        </div>
                        <p class="text-sm text-slate-300 font-medium mb-6 leading-relaxed">
                            Unduh rekapitulasi data peminjaman dan statistik bulanan.
                        </p>
                    </div>

                    <div class="p-8 pt-0 mt-auto relative z-10">
                        <form action="{{ route('library.tools.report') }}" method="GET" target="_blank" class="space-y-4">
                            <div>
                                <label class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2 ml-1">Jenis Laporan</label>
                                <div class="relative">
                                    <select name="type" class="w-full bg-slate-900/80 border border-white/10 rounded-2xl px-4 py-3 font-bold text-white text-sm focus:ring-4 focus:ring-sky-500/20 focus:border-[#56bbf1] transition-all appearance-none cursor-pointer shadow-sm [color-scheme:dark]">
                                        <option value="monthly" class="bg-slate-900 text-white">Sirkulasi Bulanan</option>
                                        <option value="top_books" class="bg-slate-900 text-white">Buku Terpopuler</option>
                                    </select>
                                    <i class="ph-bold ph-caret-down absolute right-4 top-3.5 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2 ml-1">Bulan</label>
                                    <div class="relative">
                                        <select name="month" class="w-full bg-slate-900/80 border border-white/10 rounded-2xl px-3 py-3 font-bold text-white text-sm focus:ring-4 focus:ring-sky-500/20 focus:border-[#56bbf1] transition-all appearance-none cursor-pointer shadow-sm [color-scheme:dark]">
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ date('m') == $i ? 'selected' : '' }} class="bg-slate-900 text-white">{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                                            @endfor
                                        </select>
                                        <i class="ph-bold ph-caret-down absolute right-3 top-3.5 text-slate-400 pointer-events-none"></i>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-black text-sky-400 uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                    <input type="number" name="year" value="{{ date('Y') }}" class="w-full bg-slate-900/80 border border-white/10 rounded-2xl px-3 py-3 font-bold text-white text-sm focus:ring-4 focus:ring-sky-500/20 focus:border-[#56bbf1] transition-all text-center shadow-sm">
                                </div>
                            </div>
                            <button type="submit" class="w-full py-4 bg-[#0d52a1] hover:bg-sky-600 text-white font-bold rounded-2xl shadow-lg shadow-sky-950/50 transition-all transform active:scale-95 flex items-center justify-center gap-2 group/btn border border-sky-400/30">
                                <i class="ph-bold ph-download-simple text-lg"></i> 
                                <span>Lihat Laporan</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- BARIS KEDUA: BEBAS PUSTAKA --}}
            <div class="mt-8">
                {{-- CARD 4: BEBAS PUSTAKA --}}
                <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 relative overflow-hidden group hover:border-emerald-500/50 transition-all duration-300 text-white">
                    <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-500/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none transition-colors duration-500"></div>

                    <div class="p-8 relative z-10">
                        <div class="flex items-start gap-6">
                            {{-- Icon & Title --}}
                            <div class="shrink-0">
                                <div class="w-16 h-16 bg-emerald-500/20 text-emerald-400 rounded-[1.5rem] flex items-center justify-center text-3xl shadow-sm border border-emerald-500/30 group-hover:scale-110 transition-transform">
                                    <i class="ph-duotone ph-seal-check"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-3 mb-2">
                                    <h2 class="text-2xl font-black text-white">Bebas Pustaka</h2>
                                    <span class="px-3 py-1 bg-emerald-950/80 text-emerald-300 border border-emerald-500/30 rounded-full text-xs font-black uppercase tracking-wide">Surat Keterangan</span>
                                </div>
                                <p class="text-sm text-slate-300 font-medium leading-relaxed max-w-xl">
                                    Cek status bebas pinjam siswa dan cetak Surat Keterangan Bebas Pustaka — digunakan saat perpisahan, PPDB, atau lulus sekolah.
                                </p>
                            </div>
                        </div>

                        {{-- Form Grid --}}
                        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-white/10" x-data="{ mode: 'single' }">
                            
                            {{-- Kiri: Cek Per Siswa --}}
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-6 h-6 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 flex items-center justify-center text-xs font-black">1</div>
                                    <span class="text-xs font-black text-white uppercase tracking-wider">Cek Siswa</span>
                                </div>
                                <form action="{{ route('library.tools.bebas_pustaka') }}" method="GET" target="_blank" class="space-y-3">
                                    <input type="hidden" name="mode" value="single">
                                    <div>
                                        <label class="block text-xs font-black text-emerald-400 uppercase tracking-wider mb-2 ml-1">NISN / NIS Siswa</label>
                                        <div class="flex items-center px-4 py-3.5 bg-slate-900/80 border border-white/10 rounded-2xl focus-within:border-emerald-400 focus-within:ring-4 focus-within:ring-emerald-400/20 transition-all shadow-sm">
                                            <i class="ph-bold ph-user text-emerald-400 mr-3 shrink-0"></i>
                                            <input type="text" name="nisn" class="w-full bg-transparent border-none focus:ring-0 text-white font-bold text-sm placeholder-slate-500" placeholder="Contoh: 1234567890" autocomplete="off">
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-2xl shadow-lg shadow-emerald-950/50 transition-all transform active:scale-95 flex items-center justify-center gap-2 border border-emerald-400/30">
                                        <i class="ph-bold ph-magnifying-glass text-base"></i>
                                        <span>Cek & Cetak</span>
                                    </button>
                                </form>
                            </div>

                            {{-- Kanan: Cetak Per Kelas --}}
                            <div>
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-6 h-6 rounded-full bg-teal-500/20 text-teal-300 border border-teal-500/30 flex items-center justify-center text-xs font-black">2</div>
                                    <span class="text-xs font-black text-white uppercase tracking-wider">Per Kelas (Batch)</span>
                                </div>
                                <form action="{{ route('library.tools.bebas_pustaka') }}" method="GET" target="_blank" class="space-y-3">
                                    <input type="hidden" name="mode" value="class">
                                    <div>
                                        <label class="block text-xs font-black text-teal-400 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                                        <div class="relative">
                                            <select name="class_id" class="w-full bg-slate-900/80 border border-white/10 rounded-2xl px-4 py-3.5 font-bold text-white text-sm focus:ring-4 focus:ring-teal-400/20 focus:border-teal-400 transition-all appearance-none cursor-pointer shadow-sm [color-scheme:dark]">
                                                <option value="" disabled selected class="bg-slate-900 text-slate-400">-- Pilih Kelas --</option>
                                                @foreach($classes as $class)
                                                    <option value="{{ $class->id }}" class="bg-slate-900 text-white">{{ $class->name }}</option>
                                                @endforeach
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-4 text-teal-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                    <button type="submit" class="w-full py-3.5 bg-teal-600 hover:bg-teal-500 text-white font-bold rounded-2xl shadow-lg shadow-teal-950/50 transition-all transform active:scale-95 flex items-center justify-center gap-2 border border-teal-400/30">
                                        <i class="ph-bold ph-printer text-base"></i>
                                        <span>Cetak Batch</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>