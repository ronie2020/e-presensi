@extends('layouts.public')

@section('title', 'Pojok Literasi: Artikel & Opini Guru - ' . config('app.name', 'SMP Negeri 3 Lakbok'))

@push('styles')
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    </style>
@endpush

@section('content')
    <!-- HEADER (Hero Section) -->
    <div class="bg-slate-900 pt-32 pb-32 relative overflow-hidden -mt-24">
        <!-- Dekorasi Background -->
        <div class="absolute inset-0 bg-orange-600/10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-orange-500/20 rounded-full mix-blend-screen filter blur-[100px] opacity-50 -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-500/20 rounded-full mix-blend-screen filter blur-[100px] opacity-50 translate-y-1/2 -translate-x-1/2"></div>
        <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-slate-900/95 to-slate-900"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-orange-500/20 border border-orange-500/30 text-orange-300 text-xs font-bold uppercase tracking-wider mb-4">
                <i class="ph-fill ph-pen-nib"></i> Pojok Literasi
            </span>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 tracking-tight">Kumpulan Artikel & Opini</h1>
            <p class="text-slate-400 max-w-2xl mx-auto text-lg leading-relaxed">
                Jelajahi berbagai gagasan, pemikiran, dan karya tulis inspiratif dari para tenaga pendidik kami.
            </p>
        </div>
    </div>

    <!-- FILTER & PENCARIAN -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20 animate-enter" style="animation-delay: 100ms;">
        <div class="bg-white rounded-3xl p-4 shadow-xl shadow-slate-200/50 border border-slate-100">
            <form action="{{ route('articles.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <!-- Search Input -->
                <div class="flex-1 relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <i class="ph-bold ph-magnifying-glass text-slate-400 text-lg"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari judul artikel atau nama penulis..." class="w-full pl-12 pr-4 py-3.5 bg-slate-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 rounded-2xl text-sm font-medium text-slate-700 transition-all placeholder:text-slate-400">
                </div>
                
                <!-- Category Select -->
                <div class="md:w-64 shrink-0">
                    <select name="category" class="w-full px-4 py-3.5 bg-slate-50 border-transparent focus:bg-white focus:border-orange-500 focus:ring-2 focus:ring-orange-200 rounded-2xl text-sm font-medium text-slate-700 transition-all appearance-none cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="pendidikan" {{ request('category') == 'pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                        <option value="opini" {{ request('category') == 'opini' ? 'selected' : '' }}>Opini</option>
                        <option value="teknologi" {{ request('category') == 'teknologi' ? 'selected' : '' }}>Teknologi</option>
                        <option value="umum" {{ request('category') == 'umum' ? 'selected' : '' }}>Umum</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="px-8 py-3.5 bg-orange-500 hover:bg-orange-600 text-white font-bold rounded-2xl transition-colors shadow-lg shadow-orange-500/30 flex items-center justify-center gap-2 shrink-0">
                    <i class="ph-bold ph-funnel"></i> Filter
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN KONTEN: GRID ARTIKEL -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10 animate-enter" style="animation-delay: 200ms;">
        
        @if(request('search') || request('category'))
            <div class="mb-8 flex items-center justify-between">
                <p class="text-slate-500 font-medium text-sm">
                    Menampilkan hasil pencarian untuk: <span class="font-bold text-slate-800">"{{ request('search') ?: request('category') }}"</span>
                </p>
                <a href="{{ route('articles.index') }}" class="text-sm font-bold text-rose-500 hover:text-rose-600 flex items-center gap-1">
                    <i class="ph-bold ph-x-circle"></i> Reset Filter
                </a>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($articles ?? [] as $article)
                <div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl hover:shadow-slate-200/40 overflow-hidden group hover:-translate-y-2 transition-all duration-500 flex flex-col">
                    
                    <!-- Thumbnail Artikel -->
                    <div class="relative h-56 bg-slate-200 overflow-hidden shrink-0">
                        @if($article->image_path)
                            <img src="{{ asset('storage/' . $article->image_path) }}" 
                                 alt="{{ $article->title }}" 
                                 loading="lazy"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-orange-100 to-amber-100">
                                <i class="ph-duotone ph-article text-6xl text-orange-300"></i>
                            </div>
                        @endif
                        <!-- Kategori Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="px-3 py-1 bg-white/90 backdrop-blur-sm text-orange-600 text-xs font-black uppercase tracking-wider rounded-lg shadow-sm">
                                {{ $article->category ?? 'Umum' }}
                            </span>
                        </div>
                    </div>

                    <!-- Konten Artikel -->
                    <div class="p-6 md:p-8 flex flex-col flex-1">
                        <!-- Info Penulis & Tanggal -->
                        <div class="flex items-center justify-between mb-4">
                            <a href="{{ route('teachers.show', $article->user_id) }}" class="flex items-center gap-2 hover:opacity-80 transition-opacity">
                                <div class="w-8 h-8 rounded-full bg-slate-100 overflow-hidden border border-slate-200 shrink-0">
                                    <img src="{{ optional($article->user)->photo_path ? asset('storage/' . $article->user->photo_path) : 'https://ui-avatars.com/api/?name='.urlencode(optional($article->user)->name ?? 'Anonim').'&background=random' }}" 
                                         alt="Penulis" 
                                         loading="lazy"
                                         class="w-full h-full object-cover">
                                </div>
                                <span class="text-xs font-bold text-slate-700 line-clamp-1">{{ optional($article->user)->name ?? 'Penulis Tidak Diketahui' }}</span>
                            </a>
                            <span class="text-xs text-slate-400 font-medium flex items-center gap-1 shrink-0">
                                <i class="ph-bold ph-calendar-blank"></i> 
                                {{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') : '-' }}
                            </span>
                        </div>

                        <!-- Judul & Excerpt -->
                        <a href="{{ $article->url ?? '#' }}" target="{{ $article->url ? '_blank' : '_self' }}" class="block group-hover:text-orange-600 transition-colors">
                            <h3 class="text-xl font-black text-slate-800 mb-3 leading-tight line-clamp-2">{{ $article->title }}</h3>
                        </a>
                        
                        <!-- Perbaikan Excerpt: Hindari tag HTML merusak layout -->
                        <p class="text-sm text-slate-500 line-clamp-3 mb-6 flex-1">
                            {{ Str::limit(strip_tags($article->excerpt), 150) }}
                        </p>

                        <!-- Tombol Baca -->
                        <div class="mt-auto pt-4 border-t border-slate-100">
                            <a href="{{ $article->url ?? route('teachers.show', $article->user_id) }}" target="{{ $article->url ? '_blank' : '_self' }}" class="inline-flex items-center gap-2 text-sm font-bold text-orange-500 hover:text-orange-600 group/link">
                                Baca Selengkapnya 
                                <i class="ph-bold ph-arrow-right group-hover/link:translate-x-1 transition-transform"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 text-center py-20 px-4 bg-white rounded-[3rem] border border-slate-100 shadow-sm">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-slate-50 text-slate-300 mb-4">
                        <i class="ph-duotone ph-magnifying-glass text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Artikel Tidak Ditemukan</h3>
                    <p class="text-slate-500 max-w-md mx-auto">
                        Maaf, kami tidak dapat menemukan artikel yang sesuai dengan kriteria pencarian Anda. Silakan coba kata kunci lain.
                    </p>
                    @if(request('search') || request('category'))
                        <a href="{{ route('articles.index') }}" class="inline-block mt-6 px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold rounded-xl transition-colors">
                            Kembali ke Semua Artikel
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        @if(isset($articles) && $articles->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $articles->appends(request()->query())->links() }}
            </div>
        @endif
        
    </div>
@endsection