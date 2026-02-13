<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500" x-data="{ showForm: false }">
    
    {{-- KOLOM KIRI: PROFIL & GAMIFIKASI --}}
    <div class="lg:col-span-1 space-y-6">
        
        {{-- Card Level Literasi (DINAMIS) --}}
        <div class="bg-gradient-to-br from-teal-500 to-emerald-600 p-6 rounded-[2.5rem] shadow-lg text-white relative overflow-hidden">
            {{-- Background Pattern --}}
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
            <div class="absolute bottom-0 left-0 w-24 h-24 bg-black/10 rounded-full blur-xl -ml-8 -mb-8"></div>

            <div class="relative z-10 text-center">
                <div class="w-20 h-20 bg-white/20 backdrop-blur-sm rounded-full mx-auto flex items-center justify-center mb-3 border-2 border-white/30 shadow-inner">
                    <i class="ph-fill ph-trophy text-4xl text-yellow-300 drop-shadow-md"></i>
                </div>
                
                {{-- Ambil Data Level dari Controller --}}
                <h3 class="font-black text-lg uppercase tracking-wider mb-1">
                    {{ $literacy_stats['level'] ?? 'Pemula' }}
                </h3>
                <p class="text-teal-100 text-xs font-medium mb-4">Level Literasi</p>

                {{-- Progress Bar Dinamis --}}
                <div class="w-full bg-black/20 rounded-full h-3 mb-2 overflow-hidden border border-white/10">
                    <div class="bg-yellow-400 h-full rounded-full shadow-[0_0_10px_rgba(250,204,21,0.5)] transition-all duration-1000" 
                         style="width: {{ $literacy_stats['progress'] ?? 0 }}%"></div>
                </div>
                <div class="flex justify-between text-[10px] font-bold text-teal-100 opacity-80">
                    <span>{{ number_format($literacy_stats['points'] ?? 0) }} Poin</span>
                    <span>Target: {{ number_format($literacy_stats['next_target'] ?? 100) }}</span>
                </div>
            </div>
        </div>

        {{-- Statistik Cepat --}}
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100">
            <h4 class="font-bold text-slate-700 text-sm mb-4 flex items-center gap-2">
                <i class="ph-bold ph-chart-bar text-teal-500"></i> Statistik Total
            </h4>
            <div class="space-y-4">
                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-orange-50 text-orange-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-book-bookmark text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500">Jurnal</p>
                            <p class="text-[10px] text-slate-400">Total Judul</p>
                        </div>
                    </div>
                    <span class="text-xl font-black text-slate-800">{{ $literacy_stats['total_books'] ?? 0 }}</span>
                </div>

                <div class="flex items-center justify-between group">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-500 flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="ph-bold ph-files text-xl"></i>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-500">Literasi</p>
                            <p class="text-[10px] text-slate-400">Halaman Dibaca</p>
                        </div>
                    </div>
                    <span class="text-xl font-black text-slate-800">{{ number_format($literacy_stats['total_pages'] ?? 0) }}</span>
                </div>
            </div>
        </div>

        {{-- Motivasi --}}
        <div class="bg-amber-50 border border-amber-100 p-5 rounded-[2rem]">
            <div class="flex gap-3">
                <i class="ph-fill ph-quotes text-3xl text-amber-300"></i>
                <div>
                    <p class="text-xs font-bold text-slate-700 italic leading-relaxed">
                        "Semakin banyak kamu membaca, semakin banyak hal yang kamu ketahui."
                    </p>
                    <p class="text-[10px] font-black text-amber-600 mt-2 uppercase tracking-wide">- Dr. Seuss</p>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: JURNAL & INPUT --}}
    <div class="lg:col-span-2 space-y-6">

        {{-- Header & Tombol Tambah --}}
        <div class="flex flex-col sm:flex-row justify-between items-end sm:items-center gap-4">
            <div>
                <h2 class="text-2xl font-black text-slate-800">Jurnal Literasiku</h2>
                <p class="text-slate-400 text-sm">Catat apa yang kamu baca hari ini di rumah.</p>
            </div>
            <button @click="showForm = !showForm" 
                class="px-6 py-3 bg-slate-800 hover:bg-slate-900 text-white rounded-2xl font-bold text-sm shadow-lg shadow-slate-200 transition-all transform hover:-translate-y-1 flex items-center gap-2">
                <i class="ph-bold" :class="showForm ? 'ph-x' : 'ph-plus'"></i>
                <span x-text="showForm ? 'Tutup Form' : 'Catat Bacaan'"></span>
            </button>
        </div>

        {{-- Alert Sukses/Error --}}
        @if(session('success'))
            <div class="p-4 bg-teal-50 border border-teal-100 text-teal-700 rounded-2xl text-xs font-bold flex items-center gap-2 animate-in fade-in slide-in-from-top-2">
                <i class="ph-fill ph-check-circle text-lg"></i> {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-xs font-bold animate-in fade-in slide-in-from-top-2">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM INPUT --}}
        <div x-show="showForm" 
             style="display: none;"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-y-4"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-xl border border-indigo-100 ring-4 ring-indigo-50/50 relative overflow-hidden">
            
             <form action="{{ route('portal.literacy.store') }}" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="student_id" value="{{ $student->id }}">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide ml-1">Judul Buku / Artikel</label>
                        <input type="text" name="title" required class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 font-bold text-slate-700 focus:ring-2 focus:ring-teal-200 focus:bg-white transition-all" placeholder="Contoh: Laskar Pelangi...">
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide ml-1">Penulis</label>
                        <input type="text" name="author" class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 font-bold text-slate-700 focus:ring-2 focus:ring-teal-200 focus:bg-white transition-all" placeholder="Nama Pengarang">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-6 mb-6">
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide ml-1">Halaman Dibaca</label>
                        <div class="relative">
                            <input type="number" name="pages" required min="1" class="w-full bg-slate-50 border-none rounded-2xl pl-4 pr-12 py-3 font-bold text-slate-700 focus:ring-2 focus:ring-teal-200 focus:bg-white transition-all" placeholder="0">
                            <span class="absolute right-4 top-3 text-xs font-bold text-slate-400">Hal</span>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide ml-1">Bukti Foto (Opsional)</label>
                        <input type="file" name="proof" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-teal-50 file:text-teal-700 hover:file:bg-teal-100 cursor-pointer bg-slate-50 rounded-2xl border border-transparent focus:border-teal-200">
                    </div>
                </div>

                <div class="space-y-1 mb-8">
                    <label class="text-xs font-bold text-slate-500 uppercase tracking-wide ml-1">Hikmah / Ringkasan Cerita</label>
                    <textarea name="summary" rows="3" required class="w-full bg-slate-50 border-none rounded-2xl px-4 py-3 font-medium text-slate-600 focus:ring-2 focus:ring-teal-200 focus:bg-white transition-all resize-none" placeholder="Tuliskan 1-2 kalimat tentang apa yang kamu pelajari dari bacaan ini..."></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-8 py-3 bg-teal-500 hover:bg-teal-600 text-white rounded-2xl font-black shadow-lg shadow-teal-200 transition-all flex items-center gap-2 transform hover:scale-105">
                        <i class="ph-bold ph-paper-plane-right"></i> Simpan Jurnal
                    </button>
                </div>
            </form>
        </div>

        {{-- TIMELINE JURNAL (DINAMIS DENGAN LOOP) --}}
        <div class="space-y-6 relative">
            {{-- Garis Timeline --}}
            <div class="absolute left-8 top-8 bottom-0 w-0.5 bg-slate-200 z-0 hidden sm:block"></div>

            @forelse($literacy_journals as $journal)
                <div class="relative z-10 flex flex-col sm:flex-row gap-6 group animate-in slide-in-from-bottom-4 duration-700" style="animation-delay: {{ $loop->index * 100 }}ms">
                    {{-- Date Badge --}}
                    <div class="flex-shrink-0 flex sm:flex-col items-center sm:w-16 gap-3 sm:gap-1">
                        <div class="w-16 h-16 rounded-2xl bg-white border-2 border-slate-100 shadow-sm flex flex-col items-center justify-center text-slate-700 flex-shrink-0">
                            <span class="text-[10px] font-bold uppercase text-slate-400">
                                {{ \Carbon\Carbon::parse($journal->created_at)->format('M') }}
                            </span>
                            <span class="text-2xl font-black">
                                {{ \Carbon\Carbon::parse($journal->created_at)->format('d') }}
                            </span>
                        </div>
                        <span class="text-[10px] font-bold text-slate-400 bg-white px-2 py-1 rounded-full border border-slate-100 shadow-sm hidden sm:block">
                            {{ \Carbon\Carbon::parse($journal->created_at)->translatedFormat('l') }}
                        </span>
                    </div>

                    {{-- Content Card --}}
                    <div class="flex-grow bg-white p-6 rounded-[2.5rem] rounded-tl-none sm:rounded-tl-[2.5rem] shadow-sm border border-slate-100 group-hover:shadow-md transition-all">
                        <div class="flex flex-col sm:flex-row justify-between items-start gap-2 mb-3">
                            <div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-teal-50 text-teal-700 rounded-full text-[10px] font-bold uppercase tracking-wider mb-2">
                                    <i class="ph-bold ph-book-open"></i> Jurnal
                                </span>
                                <h3 class="font-black text-slate-800 text-lg leading-snug">{{ $journal->title }}</h3>
                                <p class="text-xs text-slate-400 font-bold mt-0.5">
                                    <i class="ph-bold ph-pen-nib"></i> {{ $journal->author ?? 'Tanpa Penulis' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="inline-flex items-center gap-1 text-slate-400 text-xs font-bold bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-100 whitespace-nowrap">
                                    <i class="ph-bold ph-book-open-text"></i> {{ $journal->pages_read }} Hal
                                </div>
                            </div>
                        </div>

                        {{-- Ringkasan --}}
                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 mb-4 relative">
                            <i class="ph-fill ph-quotes text-2xl text-slate-200 absolute -top-3 -left-2"></i>
                            <p class="text-sm text-slate-600 leading-relaxed italic relative z-10">
                                "{{ $journal->summary }}"
                            </p>
                            
                            {{-- Tampilkan Foto Jika Ada --}}
                            @if($journal->proof_image)
                                <div class="mt-3">
                                    <img src="{{ asset('storage/' . $journal->proof_image) }}" class="h-24 rounded-xl object-cover border border-slate-200" alt="Bukti Baca">
                                </div>
                            @endif
                        </div>

                        <div class="flex items-center justify-between pt-2 border-t border-slate-50">
                            {{-- Status Validasi --}}
                            @if($journal->verified_at)
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                        <i class="ph-bold ph-check text-xs"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-emerald-600">Terverifikasi</span>
                                </div>
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center">
                                        <i class="ph-bold ph-hourglass text-xs"></i>
                                    </div>
                                    <span class="text-[10px] font-bold text-slate-400">Menunggu Guru</span>
                                </div>
                            @endif
                            
                            {{-- Timestamp --}}
                            <span class="text-[10px] font-bold text-slate-300">
                                {{ \Carbon\Carbon::parse($journal->created_at)->format('H:i') }} WIB
                            </span>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State (Jika Data Kosong) --}}
                <div class="text-center py-12 bg-white rounded-[2.5rem] border border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm">
                        <i class="ph-duotone ph-pencil-slash text-4xl"></i>
                    </div>
                    <h3 class="font-bold text-slate-700 text-lg">Belum ada catatan</h3>
                    <p class="text-xs text-slate-400 mt-1 max-w-xs mx-auto">
                        Mulai petualangan membacamu hari ini dan catat di sini untuk mendapatkan poin!
                    </p>
                    <button @click="showForm = true" class="mt-4 px-5 py-2 bg-teal-50 text-teal-600 rounded-xl text-xs font-bold hover:bg-teal-100 transition-colors">
                        Mulai Menulis
                    </button>
                </div>
            @endforelse

        </div>

    </div>
</div>