<x-app-layout>
    {{-- Scripts & Styles (Diambil dari daily.blade.php) --}}
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }
        /* Hide scrollbar for top books if needed */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="py-6 md:py-8 font-sans text-slate-800 pb-32" 
         x-data="{ 
            loading: false, 
            submitFilter() {
                this.loading = true;
                this.$el.closest('form').submit();
            }
         }">
    
        {{-- LOADING OVERLAY --}}
        <div x-show="loading" style="display: none;" 
             class="fixed inset-0 z-[100] bg-slate-900/40 backdrop-blur-[2px] flex items-center justify-center">
            <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center">
                <div class="w-12 h-12 border-4 border-slate-100 border-t-blue-600 rounded-full animate-spin mb-4"></div>
                <span class="text-xs font-bold text-slate-700 tracking-wider uppercase animate-pulse">Memuat Data...</span>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER SECTION (HERO + FILTER) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                {{-- Hero Card --}}
                <div class="animate-enter bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 rounded-[2rem] p-6 lg:p-8 text-white shadow-xl shadow-blue-900/30 relative overflow-hidden flex flex-col justify-between min-h-[200px] border border-white/10 group">
                    {{-- Decorative Blobs --}}
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/20 rounded-full blur-2xl group-hover:bg-blue-500/30 transition-all duration-700"></div>
                    <div class="absolute -left-10 bottom-0 w-32 h-32 bg-blue-400/10 rounded-full blur-xl group-hover:bg-blue-400/20 transition-all duration-700"></div>
                    <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <h1 class="text-xl lg:text-2xl font-extrabold mb-1 tracking-tight text-white flex items-center gap-2">
                            Monitoring Literasi
                        </h1>
                        <p class="text-blue-300 text-sm font-medium tracking-wide">Pantau aktivitas membaca siswa.</p>
                    </div>
                    
                    <div class="relative z-10 mt-6">
                        <div class="inline-flex items-center gap-2 bg-slate-900/40 backdrop-blur-md border border-white/10 px-4 py-2 rounded-xl text-sm font-bold shadow-sm">
                            <i class="ph-bold ph-calendar-blank text-blue-300"></i>
                            <span>
                                @if(request('date'))
                                    {{ \Carbon\Carbon::parse(request('date'))->translatedFormat('d F Y') }}
                                @else
                                    Semua Waktu
                                @endif
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Filter Card --}}
                <div class="animate-enter lg:col-span-2 bg-white rounded-[2rem] p-6 lg:p-8 border border-slate-100 shadow-sm relative overflow-hidden" style="animation-delay: 100ms">
                    <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:20px_20px]"></div>
                    <div class="relative z-10">
                         <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
                                Filter Data
                            </h2>
                        </div>

                        <form method="GET" class="flex flex-col md:flex-row gap-3 w-full" @submit.prevent="submitFilter">
                            {{-- Class Select --}}
                            <div class="flex-1">
                                <select name="class_id" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900 shadow-sm transition-all cursor-pointer">
                                    <option value="">Semua Kelas</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ request('class_id') == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Date Picker --}}
                            <div class="flex-1">
                                <input type="date" name="date" value="{{ request('date') }}" 
                                       class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900 shadow-sm placeholder-slate-400">
                            </div>

                            <div class="flex gap-2 w-full md:w-auto">
                                <button type="submit" class="flex-1 md:flex-none bg-blue-900 hover:bg-slate-900 text-white px-5 rounded-xl h-11 font-bold text-sm shadow-lg flex items-center justify-center gap-2 transition-all active:scale-95">
                                    <i class="ph-bold ph-magnifying-glass"></i> <span class="md:hidden">Tampilkan</span>
                                </button>
                                <a href="{{ route('admin.literacy.index') }}" class="flex-1 md:flex-none bg-white border border-slate-200 text-slate-600 hover:text-rose-600 hover:border-rose-200 px-5 rounded-xl h-11 font-bold text-sm flex items-center justify-center gap-2 transition-colors active:scale-95" title="Reset Filter">
                                    <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- DASHBOARD / KPI CARDS --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                
                {{-- 1. Partisipasi Siswa --}}
                <div class="animate-enter bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between group hover:shadow-md transition-all relative overflow-hidden" style="animation-delay: 200ms">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                        <i class="ph-fill ph-chart-pie-slice text-9xl text-blue-500"></i>
                    </div>
                    
                    <div class="relative z-10">
                        <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1 mb-4">
                            <i class="ph-bold ph-users-three"></i> Partisipasi
                        </h3>
                        
                        <div class="flex items-baseline gap-2 mb-2">
                            <span class="text-4xl lg:text-5xl font-black text-slate-800 tracking-tight">{{ $participationRate }}<span class="text-2xl text-slate-400">%</span></span>
                        </div>

                        {{-- Progress Bar --}}
                        <div class="w-full bg-slate-100 rounded-full h-3 mb-6 overflow-hidden flex">
                            <div class="bg-blue-600 h-full rounded-full" style="width: {{ $participationRate }}%"></div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="px-3 py-2 bg-emerald-50 text-emerald-700 border border-emerald-100 rounded-xl text-xs font-bold flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-emerald-500 shrink-0"></div> 
                                <span class="truncate">Sudah: {{ $submittedStudentCount }}</span>
                            </div>
                            <div class="px-3 py-2 bg-rose-50 text-rose-700 border border-rose-100 rounded-xl text-xs font-bold flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full bg-rose-500 shrink-0"></div> 
                                <span class="truncate">Belum: {{ $notSubmittedCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Buku Terpopuler (List) --}}
                <div class="animate-enter lg:col-span-2 bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 group hover:shadow-md transition-all relative overflow-hidden flex flex-col" style="animation-delay: 300ms">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition transform group-hover:scale-110">
                        <i class="ph-fill ph-books text-9xl text-indigo-800"></i>
                    </div>

                    <div class="relative z-10 flex flex-col h-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                                <i class="ph-bold ph-trend-up text-indigo-500"></i> Buku Populer
                            </h3>
                            <span class="px-2 py-1 bg-indigo-50 text-indigo-600 rounded-lg text-[10px] font-bold uppercase border border-indigo-100">Top 5</span>
                        </div>

                        @if($topBooks->count() > 0)
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach($topBooks as $index => $book)
                                    <div class="flex items-center gap-3 p-3 rounded-2xl border transition-colors {{ $index == 0 ? 'bg-indigo-50/50 border-indigo-100' : 'bg-slate-50/50 border-slate-100 hover:bg-white hover:shadow-sm' }}">
                                        {{-- Rank Badge --}}
                                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shrink-0 {{ $index == 0 ? 'bg-indigo-600 text-white shadow-md shadow-indigo-200' : 'bg-white text-slate-500 border border-slate-200' }}">
                                            #{{ $index + 1 }}
                                        </div>
                                        
                                        <div class="min-w-0 flex-1">
                                            <h4 class="font-bold text-slate-800 text-sm truncate" title="{{ $book->title }}">
                                                {{ $book->title }}
                                            </h4>
                                            <p class="text-[10px] text-slate-500 truncate flex items-center gap-1">
                                                <i class="ph-fill ph-pen-nib text-slate-300"></i> {{ $book->author ?? 'Tanpa Penulis' }}
                                            </p>
                                        </div>
                                        
                                        <div class="text-right pl-2 shrink-0">
                                            <span class="block font-black text-indigo-600 text-sm">{{ $book->total_read }}</span>
                                            <span class="text-[9px] text-slate-400 font-bold block -mt-1">Pembaca</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center py-8">
                                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-2">
                                    <i class="ph-bold ph-books"></i>
                                </div>
                                <p class="text-slate-500 text-xs font-bold">Belum ada data tren buku.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- TABEL DATA JURNAL --}}
            <div class="animate-enter bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden min-h-[400px]" style="animation-delay: 400ms">
                {{-- Table Header Custom --}}
                <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-blue-600 text-lg"></i>
                        Daftar Jurnal Masuk
                    </h3>
                    <div class="text-xs font-bold text-slate-400">
                        Total: {{ $journals->total() }} Data
                    </div>
                </div>

                @if($journals->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-bold">
                                    <th class="p-5 pl-6">Siswa</th>
                                    <th class="p-5">Buku & Ringkasan</th>
                                    <th class="p-5 text-center">Bukti</th>
                                    <th class="p-5 pr-6 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($journals as $item)
                                <tr class="group hover:bg-slate-50/50 transition-colors">
                                    <td class="p-5 pl-6 align-top">
                                        <div class="flex items-center gap-3">
                                            {{-- Avatar Initials --}}
                                            <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-sm shrink-0 border border-blue-100 group-hover:bg-blue-100 transition-colors">
                                                {{ substr($item->student->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors">{{ $item->student->name }}</p>
                                                <p class="text-[10px] text-slate-400 font-bold uppercase">{{ $item->student->schoolClass->name ?? '-' }}</p>
                                                <div class="flex items-center gap-1 mt-1 text-[10px] text-slate-400">
                                                    <i class="ph-bold ph-calendar"></i>
                                                    {{ \Carbon\Carbon::parse($item->created_at)->format('d M H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-5 align-top max-w-sm lg:max-w-md">
                                        <div class="mb-1.5">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-white border border-slate-200 text-slate-600 text-[10px] font-bold shadow-sm">
                                                <i class="ph-bold ph-book-open text-blue-500"></i> {{ $item->pages_read }} Halaman
                                            </span>
                                        </div>
                                        <h4 class="font-bold text-slate-800 text-sm mb-1 leading-snug">{{ $item->title }}</h4>
                                        <p class="text-xs text-slate-500 italic mb-2">Penulis: {{ $item->author ?? '-' }}</p>
                                        <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 text-xs text-slate-600 leading-relaxed relative group-hover:bg-white group-hover:shadow-sm transition-all">
                                            <i class="ph-fill ph-quotes text-slate-300 absolute -top-2 -left-1 text-xl"></i>
                                            {{ Str::limit($item->summary, 120) }}
                                        </div>
                                    </td>
                                    <td class="p-5 align-top text-center">
                                        @if($item->proof_image)
                                            <a href="{{ asset('storage/'.$item->proof_image) }}" target="_blank" class="inline-block relative group/img">
                                                <img src="{{ asset('storage/'.$item->proof_image) }}" class="h-16 w-16 object-cover rounded-lg border border-slate-200 shadow-sm transition transform group-hover/img:scale-110">
                                                <div class="absolute inset-0 bg-slate-900/60 opacity-0 group-hover/img:opacity-100 rounded-lg flex items-center justify-center transition-all backdrop-blur-[1px]">
                                                    <i class="ph-bold ph-eye text-white"></i>
                                                </div>
                                            </a>
                                        @else
                                            <div class="h-16 w-16 mx-auto rounded-lg border border-dashed border-slate-200 flex items-center justify-center text-slate-300 bg-slate-50">
                                                <i class="ph-bold ph-image-broken text-xl"></i>
                                            </div>
                                            <span class="text-[9px] text-slate-400 font-medium block mt-1">Tanpa Bukti</span>
                                        @endif
                                    </td>
                                    <td class="p-5 pr-6 align-top text-right">
                                        <div class="flex flex-col gap-2 items-end">
                                            @if(!$item->verified_at)
                                                <form action="{{ route('admin.literacy.verify', $item->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="w-full px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-bold shadow-md shadow-emerald-200 transition-all flex items-center justify-center gap-1.5 active:scale-95">
                                                        <i class="ph-bold ph-check"></i> Verifikasi
                                                    </button>
                                                </form>
                                            @else
                                                <div class="w-full px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-100 rounded-xl text-xs font-bold flex items-center justify-center gap-1.5 cursor-default">
                                                    <i class="ph-fill ph-check-circle"></i> Selesai
                                                </div>
                                            @endif

                                            <form action="{{ route('admin.literacy.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus jurnal ini?')">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="text-xs font-bold text-rose-500 hover:text-rose-700 hover:underline flex items-center gap-1 mt-1 transition-colors">
                                                    <i class="ph-bold ph-trash"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-5 border-t border-slate-50 bg-slate-50/30">
                        {{ $journals->withQueryString()->links() }}
                    </div>
                @else
                    <div class="text-center py-20">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-sm border border-slate-100">
                            <i class="ph-duotone ph-books text-4xl"></i>
                        </div>
                        <h3 class="font-bold text-slate-800">Belum ada data jurnal</h3>
                        <p class="text-xs text-slate-400 mt-1">Belum ada siswa yang mengisi jurnal sesuai filter ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>