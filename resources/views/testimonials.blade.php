@extends('layouts.public')

@section('title', 'Apa Kata Alumni - ' . config('app.name', 'SMP Negeri 3 Lakbok'))

@push('styles')
    <style>
        /* Animasi Custom */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
        
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        .animate-float { animation: float 6s ease-in-out infinite; }
    </style>
@endpush

@section('content')
    {{-- HEADER SECTION (Dark Glassmorphism) --}}
    <div class="relative pt-32 pb-24 overflow-hidden -mt-24 mb-12 border-b border-white/10">
        <!-- Glowing accents -->
        <div class="absolute top-1/4 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-elevate-accent/15 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-10 w-72 h-72 bg-blue-500/10 rounded-full blur-[100px] pointer-events-none"></div>
        
        {{-- KONTEN HEADER --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center animate-enter">
            <span class="px-4 py-1.5 bg-white/10 text-elevate-accent rounded-full text-xs font-bold uppercase tracking-widest border border-white/20 mb-6 inline-flex items-center backdrop-blur-md shadow-sm">
                <i class="ph-fill ph-chats-circle mr-1.5 text-elevate-accent"></i> Kata Alumni
            </span>
            <h1 class="text-4xl md:text-6xl font-black text-white mb-6 tracking-tight drop-shadow-sm leading-tight">
                Jejak Langkah <span class="text-transparent bg-clip-text bg-gradient-to-r from-elevate-accent to-blue-400">Alumni</span>
            </h1>
            <p class="text-slate-300 text-lg max-w-2xl mx-auto font-medium leading-relaxed">
                Kumpulan kisah sukses, kenangan manis, dan inspirasi dari para alumni selama menempuh pendidikan di SMP Negeri 3 Lakbok.
            </p>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        {{-- GRID TESTIMONI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @forelse($testimonials as $index => $testi)
                <div class="animate-enter bg-white/10 backdrop-blur-2xl rounded-[2rem] p-8 shadow-2xl border border-white/15 hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] hover:border-elevate-accent/40 hover:-translate-y-2 transition-all duration-500 flex flex-col h-full relative group overflow-hidden text-white"
                     style="animation-delay: {{ $index * 100 }}ms">
                    
                    {{-- Decor Corner --}}
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-white/5 to-elevate-accent/10 rounded-bl-[100%] -mr-8 -mt-8 transition-colors group-hover:from-elevate-accent/20"></div>
                    <i class="ph-fill ph-quotes text-6xl text-white/5 absolute top-4 right-4 group-hover:text-elevate-accent/15 transition-colors duration-500 transform group-hover:rotate-12"></i>

                    {{-- User Info --}}
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div class="w-14 h-14 rounded-full bg-white/10 border-2 border-elevate-accent/40 shadow-md overflow-hidden shrink-0 group-hover:scale-110 transition-transform duration-500">
                            @if($testi->student && $testi->student->photo_path)
                                <img src="{{ asset('storage/' . $testi->student->photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-white/10 text-elevate-accent font-black text-xl">
                                    {{ substr($testi->student->name ?? 'A', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-white text-base truncate group-hover:text-elevate-accent transition-colors" title="{{ $testi->student->name ?? 'Alumni' }}">
                                {{ $testi->student->name ?? 'Alumni' }}
                            </h4>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                    Lulusan {{ $testi->student->graduation_year ?? '-' }}
                                </span>
                                <span class="text-xs text-elevate-accent font-bold truncate max-w-[150px] bg-elevate-accent/20 border border-elevate-accent/30 px-2 py-0.5 rounded-md mt-1 w-fit">
                                    {{ $testi->activity_status }} 
                                    @if($testi->campus_name || $testi->company_name)
                                        @ {{ $testi->campus_name ?? $testi->company_name }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Text --}}
                    <div class="flex-1 relative z-10">
                        <p class="text-slate-200 text-sm italic leading-relaxed relative">
                            "{{ $testi->testimony }}"
                        </p>
                    </div>

                    {{-- Rating --}}
                    <div class="mt-8 pt-4 border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
                        <span class="flex items-center gap-1 font-medium"><i class="ph-bold ph-calendar"></i> {{ $testi->updated_at->format('d M Y') }}</span>
                        <div class="flex items-center gap-0.5 text-amber-400 text-sm">
                            @for($i=0; $i < ($testi->rating ?? 5); $i++) <i class="ph-fill ph-star"></i> @endfor
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-24 text-center animate-enter bg-white/10 backdrop-blur-2xl rounded-[2.5rem] border-2 border-dashed border-white/20 text-white">
                    <div class="inline-flex items-center justify-center w-24 h-24 rounded-full bg-elevate-accent/10 text-elevate-accent mb-6 ring-8 ring-elevate-accent/10 border border-elevate-accent/20">
                        <i class="ph-duotone ph-chat-teardrop-slash text-5xl"></i>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">Belum Ada Testimoni</h3>
                    <p class="text-slate-300 font-medium max-w-md mx-auto">Jadilah alumni pertama yang membagikan kisah suksesmu di sini.</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mb-16 flex justify-center animate-enter">
            {{ $testimonials->onEachSide(1)->links() }}
        </div>

        {{-- CTA SECTION --}}
        <div class="animate-enter relative bg-white/10 backdrop-blur-2xl rounded-[2.5rem] p-8 md:p-16 text-center shadow-2xl overflow-hidden group border border-white/15">
            <!-- Decorative Background Glows -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-accent/15 rounded-full blur-[60px] translate-x-1/2 -translate-y-1/2 pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/15 rounded-full blur-[60px] -translate-x-1/2 translate-y-1/2 pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl mx-auto">
                <h3 class="text-3xl font-black text-white mb-4 tracking-tight">Kamu Alumni Sekolah Ini?</h3>
                <p class="text-slate-300 mb-8 font-medium leading-relaxed">
                    Mari berbagi pengalaman dan inspirasi untuk adik-adik kelasmu. Partisipasi Anda sangat berarti untuk kemajuan sekolah dan update data tracer study.
                </p>
                
                @auth('student')
                    <a href="{{ route('alumni.tracer') }}" class="inline-flex items-center justify-center px-8 py-4 text-sm font-black text-elevate-dark bg-elevate-accent rounded-full hover:bg-white hover:-translate-y-0.5 transition-all shadow-lg shadow-elevate-accent/20 group">
                        <i class="ph-bold ph-pencil-simple mr-2 text-lg"></i> Tulis Testimoni
                    </a>
                @else
                    <a href="{{ route('student.login') }}" class="inline-flex items-center justify-center px-8 py-4 text-sm font-black text-elevate-dark bg-elevate-accent rounded-full hover:bg-white hover:-translate-y-0.5 transition-all shadow-lg shadow-elevate-accent/20 group">
                        <i class="ph-bold ph-sign-in mr-2 text-lg"></i> Login Alumni
                    </a>
                @endauth
            </div>
        </div>

    </div>
@endsection