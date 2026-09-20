@extends('layouts.public')

@section('title', 'Arsip Prestasi - ' . config('app.name', 'SMP Negeri 3 Lakbok'))

@push('styles')
    <style>
        /* Mencegah elemen berkedip saat AlpineJS belum siap */
        [x-cloak] { display: none !important; }

        /* Animasi Custom Khusus Halaman Ini */
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
    <!-- HEADER SECTION (Dark Glassmorphism) -->
    <div class="pt-32 pb-24 relative overflow-hidden -mt-24 text-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 animate-enter">
            <span class="inline-flex items-center gap-2 py-1.5 px-4 rounded-full bg-white/10 border border-white/20 text-elevate-accent text-xs font-bold uppercase tracking-wider mb-6 backdrop-blur-md shadow-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-accent opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-400"></span>
                </span>
                <i class="ph-fill ph-trophy"></i> Hall of Fame
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight drop-shadow-md">Arsip Prestasi Sekolah</h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto mb-10 leading-relaxed font-medium">
                Kumpulan jejak juara dan penghargaan yang diraih oleh siswa, guru, dan institusi SMP Negeri 3 Lakbok di berbagai tingkatan.
            </p>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-20 pb-20">
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            @forelse($achievements ?? [] as $index => $prestasi)
                <div class="bg-white/10 backdrop-blur-xl rounded-[2rem] overflow-hidden shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-elevate-accent/20 hover:border-elevate-accent/50 hover:-translate-y-2 transition-all duration-500 border border-white/15 flex flex-col h-full relative animate-enter group hover:bg-white/15" style="animation-delay: {{ ($index % 6) * 100 }}ms">

                    <!-- Foto -->
                    <div class="aspect-video bg-black/30 relative overflow-hidden group-hover:shadow-inner">
                        @if(!empty($prestasi->photo_path))
                            <img src="{{ asset('storage/' . $prestasi->photo_path) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="{{ $prestasi->title }}">
                        @else
                            <div class="w-full h-full flex items-center justify-center bg-white/5 text-elevate-accent">
                                <i class="ph-bold ph-trophy text-6xl opacity-30 group-hover:scale-110 transition-transform duration-500"></i>
                            </div>
                        @endif
                        
                        <!-- Badge Tingkat -->
                        <div class="absolute top-4 right-4">
                            <span class="px-3 py-1.5 rounded-full bg-black/60 backdrop-blur-md text-[10px] font-black uppercase text-elevate-accent tracking-wider shadow-sm border border-white/20">
                                {{ $prestasi->level ?? 'Sekolah' }}
                            </span>
                        </div>
                    </div>

                    <!-- Info Konten -->
                    <div class="p-6 flex-1 flex flex-col relative bg-transparent text-white">
                        <div class="text-xs text-slate-400 font-bold mb-3 flex items-center gap-1.5">
                            <i class="ph-fill ph-calendar-blank text-elevate-accent"></i>
                            {{ isset($prestasi->date) ? \Carbon\Carbon::parse($prestasi->date)->translatedFormat('d F Y') : '-' }}
                        </div>

                        <h3 class="text-xl font-black text-white leading-tight mb-4 group-hover:text-elevate-accent transition-colors">
                            {{ $prestasi->title }}
                        </h3>

                        <!-- Info Juara -->
                        <div class="mt-auto pt-4 border-t border-white/10 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-white/10 border border-white/20 shadow-sm flex items-center justify-center shrink-0
                                    {{ $prestasi->type === 'Siswa' ? 'text-elevate-accent' : ($prestasi->type === 'Guru' ? 'text-cyan-300' : 'text-amber-300') }}">
                                    @if($prestasi->type === 'Siswa')
                                        <i class="ph-bold ph-student text-xl"></i>
                                    @elseif($prestasi->type === 'Guru')
                                        <i class="ph-bold ph-chalkboard-teacher text-xl"></i>
                                    @else
                                        <i class="ph-bold ph-buildings text-xl"></i>
                                    @endif
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white line-clamp-1">{{ $prestasi->achiever_name }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-black tracking-wider">{{ $prestasi->type }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- TOMBOL TAMBAHAN: Foto, Sertifikat & Video -->
                        @if(!empty($prestasi->photo_path) || !empty($prestasi->certificate_path) || !empty($prestasi->video_link))
                            <div class="mt-5 flex flex-wrap gap-2">
                                {{-- Tombol Lihat Foto Dokumentasi Penuh --}}
                                @if(!empty($prestasi->photo_path))
                                    <a href="{{ asset('storage/' . $prestasi->photo_path) }}" target="_blank" class="flex-1 min-w-[30%] flex items-center justify-center gap-1.5 px-3 py-2.5 bg-white/10 text-slate-200 hover:bg-elevate-accent hover:text-elevate-dark border border-white/15 rounded-xl text-xs font-bold transition-all shadow-sm">
                                        <i class="ph-bold ph-image text-sm"></i> Foto
                                    </a>
                                @endif

                                {{-- Tombol Lihat Sertifikat --}}
                                @if(!empty($prestasi->certificate_path))
                                    <a href="{{ asset('storage/' . $prestasi->certificate_path) }}" target="_blank" class="flex-1 min-w-[30%] flex items-center justify-center gap-1.5 px-3 py-2.5 bg-elevate-accent/20 text-elevate-accent hover:bg-elevate-accent hover:text-elevate-dark border border-elevate-accent/30 rounded-xl text-xs font-bold transition-all shadow-sm">
                                        <i class="ph-bold ph-certificate text-sm"></i> Sertifikat
                                    </a>
                                @endif
                                
                                {{-- Tombol Lihat Video --}}
                                @if(!empty($prestasi->video_link))
                                    <a href="{{ $prestasi->video_link }}" target="_blank" class="flex-1 min-w-[30%] flex items-center justify-center gap-1.5 px-3 py-2.5 bg-rose-500/20 text-rose-300 hover:bg-rose-500 hover:text-white border border-rose-500/30 rounded-xl text-xs font-bold transition-all shadow-sm">
                                        <i class="ph-bold ph-youtube-logo text-sm"></i> Video
                                    </a>
                                @endif
                            </div>
                        @endif

                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="col-span-full py-24 text-center animate-enter bg-white/5 backdrop-blur-xl rounded-[2.5rem] border border-white/15">
                    <div class="inline-flex bg-white/10 p-6 rounded-full mb-6 text-elevate-accent ring-8 ring-white/5">
                        <i class="ph-duotone ph-trophy text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Belum Ada Arsip Prestasi</h3>
                    <p class="text-slate-400 text-sm max-w-md mx-auto">Saat ini belum ada data prestasi yang tercatat dalam sistem arsip sekolah.</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="mt-16 px-4 animate-enter">
            @if(isset($achievements) && method_exists($achievements, 'links'))
                {{ $achievements->links() }}
            @endif
        </div>
        
    </div>
@endsection