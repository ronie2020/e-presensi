<x-app-layout>
    {{-- Scripts & Styles --}}
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen pb-32" 
         x-data="{ 
            loading: false, 
            submitFilter() {
                this.loading = true;
                this.$el.closest('form').submit();
            }
         }">
    
        {{-- LOADING OVERLAY --}}
        <div x-show="loading" style="display: none;" 
             class="fixed inset-0 z-[100] bg-slate-900/60 backdrop-blur-sm flex items-center justify-center">
            <div class="bg-white p-8 rounded-3xl shadow-2xl flex flex-col items-center">
                <div class="w-12 h-12 border-4 border-slate-100 border-t-elevate-primary rounded-full animate-spin mb-4"></div>
                <span class="text-xs font-black text-elevate-dark tracking-widest uppercase animate-pulse">Memuat Data...</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER SECTION (HERO + FILTER) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                {{-- Hero Card Elevate Theme --}}
                <div class="animate-enter lg:col-span-1 bg-elevate-gradient-main rounded-[2.5rem] p-6 lg:p-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 relative overflow-hidden flex flex-col justify-between min-h-[200px] border border-white/60 group">
                    {{-- Decorative Blobs --}}
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-elevate-primary/10 rounded-full blur-2xl group-hover:bg-elevate-primary/20 transition-all duration-700 pointer-events-none"></div>
                    <div class="absolute -left-10 bottom-0 w-32 h-32 bg-elevate-peach/20 rounded-full blur-xl group-hover:bg-elevate-peach/30 transition-all duration-700 pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/50 border border-white/60 text-elevate-dark text-[10px] font-bold uppercase tracking-widest mb-3 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-book-open"></i> E-Library
                        </div>
                        <h1 class="text-2xl lg:text-3xl font-black mb-1 tracking-tight text-elevate-dark flex items-center gap-2 leading-tight">
                            Monitoring <br> Literasi
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium tracking-wide mt-2">Pantau aktivitas membaca siswa secara real-time.</p>
                    </div>
                    
                    <div class="relative z-10 mt-6">
                        <div class="inline-flex items-center gap-2 bg-white/60 backdrop-blur-md border border-white px-4 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-sm text-elevate-dark">
                            <i class="ph-bold ph-calendar-blank text-elevate-primary text-lg"></i>
                            <span>
                                @if(request('date'))
                                    {{ \Carbon\Carbon::parse(request('date'))->translatedFormat('d M Y') }}
                                @else
                                    Semua Waktu
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Filter Card --}}
                <div class="animate-enter lg:col-span-2 bg-white rounded-[2.5rem] p-6 lg:p-8 border border-slate-100 shadow-sm relative overflow-hidden" style="animation-delay: 100ms">
                    <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
                    
                    <div class="relative z-10 h-full flex flex-col justify-center">
                         <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                            <h2 class="text-lg font-black text-elevate-dark flex items-center gap-2 uppercase tracking-wider">
                                <i class="ph-fill ph-funnel text-elevate-primary"></i>
                                Filter Jurnal
                            </h2>
                        </div>

                        <form method="GET" class="flex flex-col md:flex-row gap-4 w-full" @submit.prevent="submitFilter">
                            {{-- Class Select --}}
                            <div class="flex-1 relative">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Kelas</label>
                                <select name="class_id" class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold h-12 text-sm px-4 focus:ring-elevate-primary focus:border-elevate-primary text-elevate-dark shadow-sm transition-all cursor-pointer appearance-none">
                                    <option value="">-- Semua Kelas --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                                <div class="absolute bottom-3 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                            </div>

                            {{-- Date Picker --}}
                            <div class="flex-1">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Tanggal</label>
                                <div class="relative">
                                    <input type="date" name="date" value="{{ request('date') }}" 
                                           class="w-full rounded-2xl border-slate-200 bg-slate-50 font-bold h-12 text-sm px-4 pl-11 focus:ring-elevate-primary focus:border-elevate-primary text-elevate-dark shadow-sm placeholder-slate-400 transition-all">
                                    <i class="ph-bold ph-calendar absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                </div>
                            </div>

                            <div class="flex gap-2 w-full md:w-auto items-end">
                                <button type="submit" class="flex-[3] md:flex-none bg-elevate-dark hover:bg-elevate-primary text-white px-8 rounded-2xl h-12 font-bold text-sm shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2 transition-all active:scale-95 group">
                                    <i class="ph-bold ph-magnifying-glass text-lg group-hover:scale-110 transition-transform"></i> <span class="md:hidden">Terapkan</span>
                                </button>
                                <a href="{{ route('admin.literacy.index') }}" class="flex-1 md:flex-none bg-white border border-slate-200 text-slate-500 hover:text-rose-500 hover:border-rose-200 hover:bg-rose-50 px-5 rounded-2xl h-12 font-bold text-sm flex items-center justify-center transition-colors active:scale-95" title="Reset Filter">
                                    <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DASHBOARD / KPI CARDS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-10">
                
                {{-- 1. Partisipasi Siswa --}}
                <div class="animate-enter bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col justify-between group hover:shadow-lg transition-all relative overflow-hidden" style="animation-delay: 200ms">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition transform group-hover:scale-110 pointer-events-none">
                        <i class="ph-fill ph-chart-pie-slice text-[10rem] text-elevate-primary"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest flex items-center gap-2 mb-4">
                            <i class="ph-bold ph-users-three text-elevate-primary text-base"></i> Partisipasi
                        </h3>
                        
                        <div class="flex items-baseline gap-2 mb-4">
                            <span class="text-5xl lg:text-6xl font-black text-elevate-dark tracking-tight">{{ $participationRate }}<span class="text-2xl text-slate-400 ml-1">%</span></span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-slate-100 rounded-full h-3 mb-6 overflow-hidden flex shadow-inner">
                            <div class="bg-elevate-primary h-full rounded-full transition-all duration-1000" style="width: {{ $participationRate }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="px-3 py-2.5 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-[11px] font-bold flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></div> 
                                <span class="truncate">Sudah: {{ $submittedStudentCount }}</span>
                            </div>
                            <div class="px-3 py-2.5 bg-rose-50 text-rose-700 border border-rose-100 rounded-xl text-[11px] font-bold flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></div> 
                                <span class="truncate">Belum: {{ $notSubmittedCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Buku Terpopuler (List) --}}
                <div class="animate-enter lg:col-span-2 bg-white p-6 md:p-8 rounded-[2.5rem] shadow-sm border border-slate-100 group hover:shadow-lg transition-all relative overflow-hidden flex flex-col" style="animation-delay: 300ms">
                    <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-5 transition transform group-hover:scale-110 pointer-events-none">
                        <i class="ph-fill ph-books text-[12rem] text-elevate-dark"></i>
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-widest flex items-center gap-2">
                                <i class="ph-bold ph-trend-up text-elevate-accent text-base"></i> Buku Populer
                            </h3>
                            <span class="px-3 py-1 bg-elevate-accent/10 text-elevate-primary rounded-lg text-[10px] font-black uppercase tracking-wider border border-elevate-accent/20">Top 5</span>
                        </div>

                        @if($topBooks->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                @foreach($topBooks as $index => $book)
                                    <div class="flex items-center gap-4 p-3 rounded-2xl border transition-colors {{ $index == 0 ? 'bg-elevate-accent/5 border-elevate-accent/20 shadow-sm' : 'bg-slate-50/50 border-slate-100 hover:bg-white hover:border-elevate-accent/30 hover:shadow-sm' }}">
                                        {{-- Rank Badge --}}
                                        <div class="w-10 h-10 rounded-[0.8rem] flex items-center justify-center font-black text-sm shrink-0 {{ $index == 0 ? 'bg-elevate-primary text-white shadow-md shadow-elevate-primary/30' : 'bg-white text-slate-500 border border-slate-200' }}">
                                            #{{ $index + 1 }}
                                        </div>
                                        
                                        <div class="min-w-0 flex-1 py-1">
                                            <h4 class="font-black text-elevate-dark text-sm truncate" title="{{ $book->title }}">
                                                {{ $book->title }}
                                            </h4>
                                            <p class="text-[10px] font-bold text-slate-400 truncate flex items-center gap-1.5 mt-0.5">
                                                <i class="ph-fill ph-pen-nib text-elevate-primary"></i> {{ $book->author ?? 'Tanpa Penulis' }}
                                            </p>
                                        </div>
                                        
                                        <div class="text-right pl-2 shrink-0 pr-2">
                                            <span class="block font-black text-elevate-primary text-lg leading-none">{{ $book->total_read }}</span>
                                            <span class="text-[9px] uppercase tracking-wider text-slate-400 font-bold block mt-1">Pembaca</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center py-8">
                                <div class="w-14 h-14 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-3 border border-slate-100 shadow-inner">
                                    <i class="ph-bold ph-books text-2xl"></i>
                                </div>
                                <p class="text-slate-500 text-sm font-bold">Belum ada data tren buku.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TABEL DATA JURNAL --}}
            <div class="animate-enter bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden min-h-[400px]" style="animation-delay: 400ms">
                {{-- Table Header Custom --}}
                <div class="px-6 py-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <h3 class="font-black text-elevate-dark flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-list-dashes text-elevate-primary text-xl"></i>
                        Daftar Jurnal Masuk
                    </h3>
                    <div class="text-xs font-black uppercase tracking-widest text-slate-500 bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm">
                        Total: {{ $journals->total() }} Data
                    </div>
                </div>

                @if($journals->count() > 0)
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] uppercase tracking-widest text-slate-400 font-black">
                                    <th class="p-5 pl-8 w-64">Siswa</th>
                                    <th class="p-5">Buku & Ringkasan</th>
                                    <th class="p-5 text-center w-28">Bukti</th>
                                    <th class="p-5 pr-8 text-right w-40">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($journals as $item)
                                <tr class="group hover:bg-slate-50/80 transition-colors">
                                    <td class="p-5 pl-8 align-top">
                                        <div class="flex items-center gap-3.5">
                                            {{-- Avatar Initials (Diperbaiki) --}}
                                            <div class="w-10 h-10 rounded-2xl bg-elevate-accent/10 text-elevate-primary flex items-center justify-center font-black text-sm shrink-0 border border-elevate-accent/20 group-hover:bg-elevate-primary group-hover:text-white transition-colors shadow-sm">
                                                {{ substr($item->student?->name ?? '?', 0, 1) }}
                                            </div>
                                            <div>
                                                {{-- Nama Siswa (Diperbaiki) --}}
                                                <p class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors leading-tight line-clamp-2">
                                                    {{ $item->student?->name ?? 'Siswa Dihapus / Tidak Ditemukan' }}
                                                </p>
                                                {{-- Nama Kelas (Diperbaiki) --}}
                                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-1">
                                                    {{ $item->student?->schoolClass?->name ?? '-' }}
                                                </p>
                                                <div class="flex items-center gap-1.5 mt-1 text-[10px] text-slate-400 font-medium">
                                                    <i class="ph-bold ph-calendar-blank"></i>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M, H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5 align-top max-w-sm lg:max-w-md">
                                        <div class="flex flex-wrap gap-2 mb-2">
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-600 text-[10px] font-bold shadow-sm">
                                                <i class="ph-bold ph-book-open text-elevate-primary text-sm"></i> {{ $item->pages_read }} Halaman
                                            </span>
                                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-700 text-[10px] font-bold shadow-sm">
                                                @for($i=1; $i<=5; $i++)
                                                    @if($i <= ($item->rating ?? 0)) ★ @else ☆ @endif
                                                @endfor
                                            </span>
                                        </div>
                                        <h4 class="font-black text-elevate-dark text-sm mb-1 leading-snug line-clamp-2">{{ $item->title }}</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Penulis: {{ $item->author ?? '-' }}</p>
                                        
                                        @if($item->favorite_character || $item->new_vocabulary)
                                            <div class="grid grid-cols-2 gap-2 mb-3">
                                                @if($item->favorite_character)
                                                    <div class="bg-slate-50 p-2 rounded-lg border border-slate-100">
                                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Tokoh Favorit</p>
                                                        <p class="text-[11px] font-medium text-slate-700 truncate" title="{{ $item->favorite_character }}">{{ $item->favorite_character }}</p>
                                                    </div>
                                                @endif
                                                @if($item->new_vocabulary)
                                                    <div class="bg-slate-50 p-2 rounded-lg border border-slate-100">
                                                        <p class="text-[8px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kosakata</p>
                                                        <p class="text-[11px] font-medium text-slate-700 truncate" title="{{ $item->new_vocabulary }}">{{ $item->new_vocabulary }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif

                                        <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-xs text-slate-600 leading-relaxed relative group-hover:bg-white group-hover:border-elevate-accent/30 transition-all font-medium">
                                            <i class="ph-fill ph-quotes text-elevate-accent/20 absolute -top-1 -left-1 text-2xl"></i>
                                            <span class="relative z-10 break-words">{{ Str::limit($item->summary, 150) }}</span>
                                        </div>
                                    </td>
                                    <td class="p-5 align-top text-center">
                                        @if($item->proof_image)
                                            <a href="{{ asset('storage/'.$item->proof_image) }}" target="_blank" class="inline-block relative group/img">
                                                <img src="{{ asset('storage/'.$item->proof_image) }}" class="h-16 w-16 object-cover rounded-xl border border-slate-200 shadow-sm transition transform group-hover/img:scale-105">
                                                <div class="absolute inset-0 bg-elevate-dark/60 opacity-0 group-hover/img:opacity-100 rounded-xl flex items-center justify-center transition-all backdrop-blur-[2px]">
                                                    <i class="ph-bold ph-eye text-white text-xl"></i>
                                                </div>
                                            </a>
                                        @else
                                            <div class="h-16 w-16 mx-auto rounded-xl border border-dashed border-slate-300 flex items-center justify-center text-slate-300 bg-slate-50">
                                                <i class="ph-bold ph-image-broken text-xl"></i>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-bold block mt-2 uppercase tracking-widest">Tanpa Bukti</span>
                                        @endif
                                    </td>
                                    <td class="p-5 pr-8 align-top text-right">
                                        <div class="flex flex-col gap-2 items-end">
                                            @if($item->status === 'rejected')
                                                <div class="w-full px-4 py-2 bg-rose-50 text-rose-600 border border-rose-100 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 cursor-default shadow-sm mb-1" title="{{ $item->rejection_reason }}">
                                                    <i class="ph-fill ph-x-circle text-sm"></i> Ditolak
                                                </div>
                                            @elseif(!$item->verified_at)
                                                <form action="{{ route('admin.literacy.verify', $item->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="submit" class="w-full px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold shadow-lg shadow-emerald-500/20 transition-all flex items-center justify-center gap-2 active:scale-95 mb-1">
                                                        <i class="ph-bold ph-check-circle text-base"></i> Verifikasi
                                                    </button>
                                                </form>
                                                
                                                {{-- Tombol Tolak dengan SweetAlert untuk input alasan --}}
                                                <form id="form-reject-{{ $item->id }}" action="{{ route('admin.literacy.reject', $item->id) }}" method="POST" class="w-full">
                                                    @csrf
                                                    <button type="button" onclick="rejectJournal({{ $item->id }})" class="w-full px-5 py-2 bg-white border border-rose-200 text-rose-500 hover:bg-rose-50 rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-2">
                                                        <i class="ph-bold ph-x text-base"></i> Tolak
                                                    </button>
                                                </form>
                                            @else
                                                <div class="w-full px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 cursor-default shadow-sm mb-1">
                                                    <i class="ph-fill ph-check-circle text-sm"></i> Selesai
                                                </div>
                                            @endif

                                            <form action="{{ route('admin.literacy.destroy', $item->id) }}" method="POST" 
                                                  onsubmit="event.preventDefault(); 
                                                            const form = this;
                                                            Swal.fire({
                                                                title: 'Hapus Jurnal?',
                                                                text: 'Yakin ingin menghapus jurnal literasi ini secara permanen?',
                                                                icon: 'warning',
                                                                showCancelButton: true,
                                                                confirmButtonColor: '#e11d48',
                                                                cancelButtonColor: '#94a3b8',
                                                                confirmButtonText: 'Ya, Hapus!',
                                                                cancelButtonText: 'Batal',
                                                                reverseButtons: true,
                                                                customClass: {
                                                                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                                                                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                                                                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                                                },
                                                                buttonsStyling: false
                                                            }).then((result) => {
                                                                if (result.isConfirmed) form.submit();
                                                            });">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-[10px] font-black text-slate-400 hover:text-rose-500 flex items-center gap-1.5 mt-1 transition-colors uppercase tracking-wider py-1 w-full justify-end">
                                                    <i class="ph-bold ph-trash text-sm"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 border-t border-slate-50 bg-slate-50/30">
                        {{ $journals->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-24">
                        <div class="w-24 h-24 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-6 text-slate-300 shadow-inner border border-slate-100">
                            <i class="ph-duotone ph-books text-5xl"></i>
                        </div>
                        <h3 class="font-black text-elevate-dark text-xl mb-2">Belum ada data jurnal</h3>
                        <p class="text-sm font-medium text-slate-500 max-w-sm mx-auto">Belum ada siswa yang mengisi jurnal literasi yang sesuai dengan filter pencarian ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk menolak jurnal dengan input alasan
        function rejectJournal(id) {
            Swal.fire({
                title: 'Tolak Jurnal Literasi',
                text: 'Berikan alasan mengapa jurnal ini ditolak / perlu direvisi oleh siswa:',
                input: 'textarea',
                inputPlaceholder: 'Contoh: Bukti foto buram, atau buku sudah pernah direview...',
                inputAttributes: {
                    'aria-label': 'Alasan penolakan'
                },
                showCancelButton: true,
                confirmButtonText: 'Tolak Jurnal',
                cancelButtonText: 'Batal',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2',
                    input: 'rounded-xl border-slate-200 focus:ring-elevate-primary focus:border-elevate-primary text-sm'
                },
                buttonsStyling: false,
                preConfirm: (reason) => {
                    if (!reason) {
                        Swal.showValidationMessage('Alasan penolakan wajib diisi!')
                    }
                    return reason;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Buat input hidden untuk mengirim alasan ke controller
                    const form = document.getElementById('form-reject-' + id);
                    const inputReason = document.createElement('input');
                    inputReason.type = 'hidden';
                    inputReason.name = 'rejection_reason';
                    inputReason.value = result.value;
                    form.appendChild(inputReason);
                    form.submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#3b5889',
                    customClass: { popup: 'rounded-[2.5rem] shadow-xl border border-slate-100' }
                });
            @endif
        });
    </script>
</x-app-layout>