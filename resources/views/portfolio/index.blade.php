<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="{ activeTab: 'pengalaman' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-3xl font-black text-gray-800 leading-tight flex items-center gap-3">
                        <i class="ph-duotone ph-medal text-blue-600"></i> 
                        Kelola Portofolio {{ isset($targetUser) && $targetUser->id !== auth()->id() ? '- ' . $targetUser->name : '' }}
                    </h2>
                    <p class="text-slate-500 text-sm mt-1">Tambahkan karya, materi, dan pengalaman untuk ditampilkan di direktori publik.</p>
                </div>
                <a href="{{ route('teachers.show', request('user_id') ?? auth()->id()) }}" target="_blank" class="px-5 py-2.5 bg-blue-100 text-blue-700 font-bold rounded-2xl hover:bg-blue-200 transition-colors flex items-center gap-2 shadow-sm">
                    <i class="ph-bold ph-eye"></i> Lihat Profil Publik
                </a>
            </div>

            {{-- Alerts --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600"><i class="ph-bold ph-check-circle text-xl"></i></div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden flex flex-col md:flex-row min-h-[600px]">
                
                {{-- SIDEBAR TABS --}}
                <div class="md:w-64 bg-slate-50 border-r border-slate-100 p-6 shrink-0">
                    <nav class="flex md:flex-col gap-2 overflow-x-auto custom-scrollbar pb-2 md:pb-0">
                        <button @click="activeTab = 'pendidikan'" :class="activeTab === 'pendidikan' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-graduation-cap text-lg"></i> Pendidikan
                        </button>
                        <button @click="activeTab = 'pengalaman'" :class="activeTab === 'pengalaman' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-student text-lg"></i> Pengalaman
                        </button>
                        <button @click="activeTab = 'materi'" :class="activeTab === 'materi' ? 'bg-purple-600 text-white shadow-lg shadow-purple-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-presentation-chart text-lg"></i> Materi & Media
                        </button>
                        <button @click="activeTab = 'portofolio'" :class="activeTab === 'portofolio' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-trophy text-lg"></i> Prestasi / Galeri
                        </button>
                        <button @click="activeTab = 'artikel'" :class="activeTab === 'artikel' ? 'bg-orange-600 text-white shadow-lg shadow-orange-600/20' : 'text-slate-500 hover:bg-slate-200 hover:text-slate-700'" class="w-full text-left px-4 py-3.5 rounded-2xl font-bold text-sm transition-all flex items-center gap-3 whitespace-nowrap">
                            <i class="ph-bold ph-article text-lg"></i> Artikel Tulisan
                        </button>
                    </nav>
                </div>

                {{-- KONTEN UTAMA --}}
                <div class="p-6 md:p-8 flex-1">

                    {{-- 1. TAB PENGALAMAN --}}
                    <div x-show="activeTab === 'pengalaman'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-student"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Riwayat Pelatihan & Sertifikasi</h3>
                        </div>
                        
                        {{-- Form Tambah Pengalaman --}}
                        <form action="{{ route('portfolio.exp.store') }}" method="POST" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                <input type="number" name="year" placeholder="2023" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Pelatihan / Sertifikasi</label>
                                <input type="text" name="title" placeholder="Cth: Diklat Guru Penggerak..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700" required>
                            </div>
                            <div class="md:col-span-3">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Penyelenggara</label>
                                <input type="text" name="organizer" placeholder="Cth: Kemdikbud..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 font-bold text-slate-700">
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-500/20 transition-all flex justify-center items-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Tambah
                                </button>
                            </div>
                        </form>

                        {{-- List Pengalaman --}}
                        <div class="space-y-3">
                            @forelse($experiences ?? [] as $exp)
                                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-2xl hover:bg-blue-50/50 transition-colors group">
                                    <div class="flex items-start gap-4">
                                        <div class="px-3 py-1 bg-blue-100 text-blue-700 rounded-lg text-xs font-black mt-1">{{ $exp->year }}</div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $exp->title }}</h4>
                                            <p class="text-sm text-slate-500 mt-0.5">{{ $exp->organizer }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('portfolio.exp.destroy', ['id' => $exp->id, 'user_id' => request('user_id')]) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all"><i class="ph-bold ph-trash"></i></button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <i class="ph-duotone ph-folder-open text-4xl text-slate-300 mb-2"></i>
                                    <p class="text-slate-500 text-sm font-medium">Belum ada data pengalaman ditambahkan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 2. TAB MATERI & MEDIA --}}
                    <div x-show="activeTab === 'materi'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center"><i class="ph-bold ph-presentation-chart"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Materi & Media Pembelajaran</h3>
                        </div>
                        
                        <form action="{{ route('portfolio.mat.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Materi</label>
                                <input type="text" name="title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tipe (Misal: Modul PDF, Slide PPT)</label>
                                <input type="text" name="type" class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link URL (Jika File di GDrive/Youtube)</label>
                                <input type="url" name="file_url" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-purple-500 focus:ring-purple-500 font-bold text-slate-700">
                            </div>
                            <div class="md:col-span-2 p-4 bg-white border-2 border-dashed border-slate-200 rounded-2xl">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Atau Upload File Langsung (Opsional)</label>
                                <input type="file" name="file" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                            </div>
                            <div class="md:col-span-2 flex justify-end">
                                <button type="submit" class="px-8 py-3 bg-purple-600 text-white font-bold rounded-2xl hover:bg-purple-700 shadow-lg shadow-purple-500/20 transition-all flex items-center gap-2">
                                    <i class="ph-bold ph-upload-simple"></i> Simpan Materi
                                </button>
                            </div>
                        </form>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            @forelse($materials ?? [] as $mat)
                                <div class="flex items-start gap-4 p-5 border border-slate-200 rounded-3xl bg-white shadow-sm hover:shadow-md transition-shadow relative group">
                                    <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center shrink-0">
                                        <i class="{{ $mat->icon ?? 'ph-file-text text-slate-500' }} text-3xl"></i>
                                    </div>
                                    <div class="flex-1 pr-8">
                                        <h4 class="font-bold text-sm text-slate-800 line-clamp-2 leading-tight">{{ $mat->title }}</h4>
                                        <p class="text-xs font-medium text-slate-500 mt-1 mb-2">{{ $mat->type }}</p>
                                        @if($mat->file_url) 
                                            <a href="{{ $mat->file_url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-purple-600 bg-purple-50 px-2 py-1 rounded-lg hover:bg-purple-100"><i class="ph-bold ph-link"></i> Buka Link</a>
                                        @elseif($mat->file_path)
                                            <a href="{{ asset('storage/'.$mat->file_path) }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-blue-600 bg-blue-50 px-2 py-1 rounded-lg hover:bg-blue-100"><i class="ph-bold ph-download-simple"></i> Download</a>
                                        @endif
                                    </div>
                                    <form action="{{ route('portfolio.mat.destroy', ['id' => $mat->id, 'user_id' => request('user_id')]) }}" method="POST" class="absolute top-4 right-4" onsubmit="return confirm('Hapus materi?');">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                                    </form>
                                </div>
                            @empty
                                <div class="sm:col-span-2 text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada materi/media dibagikan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 3. TAB PRESTASI / GALERI --}}
                    <div x-show="activeTab === 'portofolio'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-trophy"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Galeri Portofolio & Pencapaian</h3>
                        </div>
                        
                        <form action="{{ route('portfolio.port.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Kegiatan / Prestasi</label>
                                    <input type="text" name="title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700" required>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun</label>
                                    <input type="text" name="year" class="w-full rounded-2xl border-slate-200 bg-white focus:border-emerald-500 focus:ring-emerald-500 font-bold text-slate-700">
                                </div>
                                <div class="md:col-span-3 p-4 bg-white border-2 border-dashed border-slate-200 rounded-2xl flex flex-col md:flex-row gap-4 items-center justify-between">
                                    <div class="flex-1 w-full">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Upload Foto Dokumentasi</label>
                                        <input type="file" name="image" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-emerald-50 file:text-emerald-700" required>
                                    </div>
                                    <button type="submit" class="px-8 py-3 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 shadow-lg shadow-emerald-500/20 transition-all shrink-0 w-full md:w-auto">
                                        <i class="ph-bold ph-upload-simple"></i> Upload Foto
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @forelse($portfolios ?? [] as $port)
                                <div class="relative group rounded-3xl overflow-hidden border border-slate-200 shadow-sm">
                                    <div class="aspect-square bg-slate-100">
                                        <img src="{{ asset('storage/' . $port->image_path) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                    </div>
                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-4">
                                        <form action="{{ route('portfolio.port.destroy', ['id' => $port->id, 'user_id' => request('user_id')]) }}" method="POST" class="self-end" onsubmit="return confirm('Hapus foto ini?');">
                                            @csrf @method('DELETE')
                                            <button class="w-8 h-8 bg-white/20 backdrop-blur text-white rounded-xl hover:bg-rose-500 transition-colors flex items-center justify-center"><i class="ph-bold ph-trash"></i></button>
                                        </form>
                                        <div>
                                            <span class="inline-block px-2 py-1 bg-emerald-500 text-white text-[10px] font-black rounded-lg mb-1">{{ $port->year }}</span>
                                            <h4 class="text-white font-bold text-sm leading-tight line-clamp-2">{{ $port->title }}</h4>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 md:col-span-3 text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada foto galeri.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 4. TAB ARTIKEL --}}
                    <div x-show="activeTab === 'artikel'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center"><i class="ph-bold ph-article"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Artikel & Tulisan Terpublikasi</h3>
                        </div>
                        
                        <form action="{{ route('portfolio.art.store') }}" method="POST" enctype="multipart/form-data" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-2 gap-5">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Judul Artikel / Opini</label>
                                <input type="text" name="title" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kategori Topik</label>
                                <input type="text" name="category" placeholder="Pendidikan, Opini..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tanggal Publikasi</label>
                                <input type="date" name="published_at" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700 text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Ringkasan / Excerpt</label>
                                <textarea name="excerpt" rows="2" class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-medium text-slate-700"></textarea>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Link URL Artikel Asli (Opsional)</label>
                                <input type="url" name="url" placeholder="https://..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-orange-500 focus:ring-orange-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Thumbnail Cover (Opsional)</label>
                                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-3 file:px-4 file:rounded-xl file:border-0 file:font-bold file:bg-orange-50 file:text-orange-600 bg-white rounded-2xl border border-slate-200 overflow-hidden">
                            </div>
                            <div class="md:col-span-2 flex justify-end mt-2">
                                <button type="submit" class="px-8 py-3 bg-orange-500 text-white font-bold rounded-2xl hover:bg-orange-600 shadow-lg shadow-orange-500/20 transition-all flex items-center gap-2">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Artikel
                                </button>
                            </div>
                        </form>

                        <div class="space-y-4">
                            @forelse($articles ?? [] as $art)
                                <div class="flex items-stretch gap-4 p-4 border border-slate-200 rounded-3xl bg-white relative group hover:shadow-md transition-all">
                                    @if($art->image_path)
                                        <div class="w-24 h-24 rounded-2xl overflow-hidden shrink-0 bg-slate-100">
                                            <img src="{{ asset('storage/' . $art->image_path) }}" class="w-full h-full object-cover">
                                        </div>
                                    @endif
                                    <div class="flex-1 py-1 pr-10">
                                        <span class="inline-block px-2 py-1 bg-orange-100 text-orange-700 text-[10px] font-black rounded-lg uppercase tracking-wider mb-1">{{ $art->category ?? 'Umum' }}</span>
                                        <h4 class="font-bold text-slate-800 text-base leading-tight">{{ $art->title }}</h4>
                                        <p class="text-xs font-medium text-slate-500 mt-1 line-clamp-2">{{ $art->excerpt }}</p>
                                        @if($art->url) 
                                            <a href="{{ $art->url }}" target="_blank" class="inline-flex items-center gap-1 text-[10px] uppercase tracking-wider font-black text-blue-600 hover:text-blue-800 mt-2"><i class="ph-bold ph-link"></i> Baca di Web Asli</a> 
                                        @endif
                                    </div>
                                    <form action="{{ route('portfolio.art.destroy', ['id' => $art->id, 'user_id' => request('user_id')]) }}" method="POST" class="absolute top-4 right-4" onsubmit="return confirm('Hapus artikel?');">
                                        @csrf @method('DELETE')
                                        <button class="w-8 h-8 flex items-center justify-center rounded-xl text-slate-400 hover:bg-rose-50 hover:text-rose-500 transition-colors"><i class="ph-bold ph-trash"></i></button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada tulisan artikel.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                    {{-- 5. TAB PENDIDIKAN --}}
                    <div x-show="activeTab === 'pendidikan'" x-cloak x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="flex items-center gap-3 mb-6 pb-2 border-b border-slate-100">
                            <div class="w-8 h-8 rounded-lg bg-cyan-100 text-cyan-600 flex items-center justify-center"><i class="ph-bold ph-graduation-cap"></i></div>
                            <h3 class="text-lg font-black text-slate-800">Riwayat Pendidikan Formal</h3>
                        </div>
                        
                        <form action="{{ route('portfolio.edu.store') }}" method="POST" class="bg-slate-50/50 p-6 rounded-3xl border border-slate-100 mb-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                            @csrf
                            @if(request('user_id')) <input type="hidden" name="user_id" value="{{ request('user_id') }}"> @endif
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Institusi / Universitas</label>
                                <input type="text" name="institution" placeholder="Cth: Universitas Pendidikan..." class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700" required>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Gelar / Jurusan</label>
                                <input type="text" name="degree" placeholder="Cth: S1 Pendidikan Matematika" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Masuk</label>
                                <input type="number" name="start_year" placeholder="Cth: 2010" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Tahun Lulus</label>
                                <input type="number" name="end_year" placeholder="Cth: 2014" class="w-full rounded-2xl border-slate-200 bg-white focus:border-cyan-500 focus:ring-cyan-500 font-bold text-slate-700">
                            </div>
                            <div class="md:col-span-2 flex items-end justify-end">
                                <button type="submit" class="px-8 py-3 bg-cyan-600 text-white font-bold rounded-2xl hover:bg-cyan-700 shadow-lg shadow-cyan-500/20 transition-all flex items-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Tambah Riwayat
                                </button>
                            </div>
                        </form>

                        <div class="space-y-3">
                            @forelse($educations ?? [] as $edu)
                                <div class="flex items-center justify-between p-4 border border-slate-200 rounded-2xl hover:bg-cyan-50/50 transition-colors group">
                                    <div class="flex items-start gap-4">
                                        <div class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-lg text-xs font-black mt-1">{{ $edu->start_year ?? '-' }} - {{ $edu->end_year ?? 'Skrg' }}</div>
                                        <div>
                                            <h4 class="font-bold text-slate-800">{{ $edu->institution }}</h4>
                                            <p class="text-sm text-slate-500 mt-0.5">{{ $edu->degree }}</p>
                                        </div>
                                    </div>
                                    <form action="{{ route('portfolio.edu.destroy', ['id' => $edu->id, 'user_id' => request('user_id')]) }}" method="POST" onsubmit="return confirm('Hapus riwayat ini?');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-xl transition-all"><i class="ph-bold ph-trash"></i></button>
                                    </form>
                                </div>
                            @empty
                                <div class="text-center py-10 bg-slate-50 rounded-3xl border border-dashed border-slate-200">
                                    <p class="text-slate-500 text-sm font-medium">Belum ada riwayat pendidikan ditambahkan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>