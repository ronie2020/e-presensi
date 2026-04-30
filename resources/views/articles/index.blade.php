@extends('layouts.public')

@section('title', 'Pojok Literasi: Artikel & Opini Guru - ' . config('app.name', 'SMP Negeri 3 Lakbok'))

@push('styles')
    <style>
        [x-cloak] { display: none !important; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        .animate-blob { animation: blob 7s infinite; }
        @keyframes blob { 
            0% { transform: translate(0px, 0px) scale(1); } 
            33% { transform: translate(30px, -50px) scale(1.1); } 
            66% { transform: translate(-20px, 20px) scale(0.9); } 
            100% { transform: translate(0px, 0px) scale(1); } 
        }
    </style>
@endpush

@section('content')
    <!-- HEADER (Hero Section - Tema Elevate Light) -->
    <div class="pt-32 pb-32 relative overflow-hidden -mt-24 bg-elevate-gradient-main border-b border-white/60 shadow-sm">
        <!-- Dekorasi Background -->
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] mix-blend-overlay"></div>
        
        <!-- Animated Blobs Elevate Colors -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-elevate-accent/20 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2 animate-blob"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-elevate-peach/20 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/2 animate-blob" style="animation-delay: 2s;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/60 border border-white text-elevate-primary text-xs font-bold uppercase tracking-widest mb-6 backdrop-blur-md shadow-sm">
                <i class="ph-fill ph-pen-nib text-elevate-accent"></i> Pojok Literasi
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-elevate-dark mb-6 tracking-tight">Kumpulan Artikel & Opini</h1>
            <p class="text-elevate-dark/70 max-w-2xl mx-auto text-lg leading-relaxed font-medium">
                Jelajahi berbagai gagasan, pemikiran, dan karya tulis inspiratif dari para tenaga pendidik kami.
            </p>
        </div>
    </div>

    <!-- FILTER & PENCARIAN -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20 animate-enter" style="animation-delay: 100ms;">
        <div class="bg-white/80 backdrop-blur-xl rounded-[2.5rem] p-4 shadow-xl shadow-elevate-primary/5 border border-white">
            <form action="{{ route('articles.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <i class="ph-bold ph-magnifying-glass text-elevate-dark/30 text-lg"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul artikel atau nama penulis..." 
                        class="w-full pl-12 pr-4 py-4 bg-white border-slate-100 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/10 rounded-2xl text-sm font-bold text-elevate-dark transition-all placeholder:text-elevate-dark/30 shadow-sm">
                </div>
                
                <!-- Category Select -->
                <div class="md:w-64 shrink-0 relative">
                    <select name="category" class="w-full px-5 py-4 bg-white border-slate-100 focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/10 rounded-2xl text-sm font-bold text-elevate-dark transition-all appearance-none cursor-pointer shadow-sm">
                        <option value="">Semua Kategori</option>
                        <option value="pendidikan" {{ request('category') == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                        <option value="opini" {{ request('category') == 'opini' ? 'selected' : '' }}>Opini</option>
                        <option value="teknologi" {{ request('category') == 'teknologi' ? 'selected' : '' }}>Teknologi</option>
                        <option value="umum" {{ request('category') == 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-elevate-dark/30">
                        <i class="ph-bold ph-caret-down"></i>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="px-8 py-4 bg-elevate-dark hover:bg-elevate-primary text-white font-black rounded-2xl transition-all shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2 shrink-0 active:scale-95">
                    <i class="ph-bold ph-funnel"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN KONTEN: GRID ARTIKEL -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10 animate-enter" style="animation-delay: 200ms;">
        
        @if(request('search') || request('category'))
            <div class="mb-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <p class="text-elevate-dark/50 font-bold text-sm bg-elevate-soft px-4 py-2 rounded-full w-fit">
                    Hasil pencarian: <span class="text-elevate-primary">"{{ request('search') ?: request('category') }}"</span>
                </p>
                <a href="{{ route('articles.index') }}" class="text-sm font-black text-rose-500 hover:text-rose-600 flex items-center gap-1.5 transition-colors">
                    <i class="ph-bold ph-arrow-counter-clockwise"></i> Reset Pencarian
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-10">
            @forelse($articles ?? [] as $index => $article)
                <article class="bg-elevate-surface rounded-[2.5rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-elevate-accent/10 hover:border-elevate-accent/30 overflow-hidden group hover:-translate-y-2 transition-all duration-500 flex flex-col h-full animate-enter" style="animation-delay: {{ ($index % 6) * 100 }}ms">
                    
                    <!-- Thumbnail Artikel -->
                    <div class="relative h-60 bg-elevate-soft overflow-hidden shrink-0">
                        @if($article->image_path)
                            <img src="{{ asset('storage/' . $article->image_path) }}" 
                                 onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($article->title) }}&background=e5eff5&color=0d52a1&size=500';"
                                 alt="{{ $article->title }}" 
                                 loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-elevate-soft text-elevate-primary">
                                <i class="ph-duotone ph-article text-7xl opacity-30 group-hover:scale-110 transition-transform duration-500"></i>
                            </div>
                        @endif
                        <!-- Kategori Badge -->
                        <div class="absolute top-5 left-5">
                            <span class="px-3 py-1.5 bg-white/90 backdrop-blur-md text-elevate-primary text-[10px] font-black uppercase tracking-widest rounded-xl shadow-sm border border-white">
                                {{ $article->category ?? 'Umum' }}
                            </span>
                        </div>
                    </div>

                    <!-- Konten Artikel -->
                    <div class="p-8 flex flex-col flex-1">
                        <!-- Info Penulis & Tanggal -->
                        <div class="flex items-center justify-between mb-6">
                            <a href="{{ route('teachers.show', $article->user_id) }}" class="flex items-center gap-2.5 group/author">
                                <div class="w-9 h-9 rounded-full bg-elevate-soft overflow-hidden border-2 border-white shadow-sm shrink-0">
                                    <img src="{{ optional($article->user)->photo_path ? asset('storage/' . $article->user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(optional($article->user)->name ?? 'Anonim').'&background=e5eff5&color=0d52a1' }}" 
                                         alt="Penulis" 
                                         class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-black text-elevate-dark group-hover/author:text-elevate-primary transition-colors line-clamp-1">{{ optional($article->user)->name ?? 'Penulis' }}</span>
                            </a>
                            
                            <div class="flex items-center gap-3 shrink-0 text-elevate-dark/40 font-bold text-[10px] uppercase tracking-tighter">
                                <span class="flex items-center gap-1">
                                    <i class="ph-bold ph-calendar-blank text-xs"></i> 
                                    {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') : '-' }}
                                </span>
                            </div>
                        </div>

                        <!-- Judul & Excerpt -->
                        <a href="{{ $article->url ?? '#' }}" target="{{ $article->url ? '_blank' : '_self' }}" class="block mb-4">
                            <h3 class="text-xl font-black text-elevate-dark leading-tight line-clamp-2 group-hover:text-elevate-primary transition-colors">{{ $article->title }}</h3>
                        </a>
                        
                        <p class="text-sm text-elevate-dark/60 line-clamp-3 mb-8 flex-1 font-medium leading-relaxed">
                            {{ Str::limit(strip_tags($article->excerpt), 150) }}
                        </p>

                        <!-- Tombol Baca -->
                        <div class="mt-auto pt-6 border-t border-slate-50">
                            <a href="{{ $article->url ?? route('teachers.show', $article->user_id) }}" 
                               target="{{ $article->url ? '_blank' : '_self' }}" 
                               class="inline-flex items-center gap-2 text-sm font-black text-elevate-primary hover:text-elevate-dark group/link transition-all">
                                Baca Selengkapnya 
                                @if($article->url)
                                    <i class="ph-bold ph-arrow-up-right group-hover/link:translate-x-1 group-hover/link:-translate-y-1 transition-transform"></i>
                                @else
                                    <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                                @endif
                            </a>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full py-24 text-center animate-enter bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-elevate-soft text-elevate-primary mb-6 ring-8 ring-elevate-soft/50">
                        <i class="ph-duotone ph-magnifying-glass text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-elevate-dark mb-2">Artikel Tidak Ditemukan</h3>
                    <p class="text-elevate-dark/60 font-medium max-w-md mx-auto mb-8">
                        Maaf, kami tidak dapat menemukan artikel yang sesuai dengan kriteria pencarian Anda. Silakan coba kata kunci lain.
                    </p>
                    @if(request('search') || request('category'))
                        <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20">
                            <i class="ph-bold ph-arrow-left"></i> Lihat Semua Artikel
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if(isset($articles) && $articles->hasPages())
            <div class="mt-20 flex justify-center animate-enter">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        @endif
        
    </div>
@endsection