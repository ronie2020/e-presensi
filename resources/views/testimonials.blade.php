@extends('layouts.public')

@section('content')
<div class="bg-slate-50 min-h-screen py-12">
    
    {{-- HEADER WITH SCHOOL BACKGROUND --}}
    <div class="relative bg-slate-900 pt-24 pb-20 rounded-b-[3rem] shadow-xl overflow-hidden -mt-20 mb-12">
        
        {{-- 1. BACKGROUND IMAGE --}}
        <div class="absolute inset-0 z-0">
            {{-- 
                TIPS: Ganti src di bawah ini dengan foto sekolah Anda sendiri.
                Contoh: src="{{ asset('img/foto-sekolah.jpg') }}"
            --}}
            <img src="{{ asset('images/netila.jpg') }}" 
                 alt="Background Sekolah" 
                 class="w-full h-full object-cover">
            
            {{-- 2. OVERLAY GELAP (Agar teks terbaca) --}}
            <div class="absolute inset-0 bg-gradient-to-b from-slate-900/90 via-slate-900/70 to-slate-900/90"></div>
            
            {{-- 3. Pattern Halus (Opsional, untuk tekstur) --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20 mix-blend-overlay"></div>
        </div>

        {{-- Dekorasi Blur (Opsional) --}}
        <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500/30 rounded-full blur-3xl pointer-events-none translate-x-1/2 -translate-y-1/2 mix-blend-overlay"></div>
        
        {{-- KONTEN HEADER --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
            <span class="px-3 py-1 bg-white/10 text-indigo-100 rounded-full text-xs font-bold uppercase tracking-widest border border-white/20 mb-4 inline-block backdrop-blur-sm">
                Kata Alumni
            </span>
            <h1 class="text-3xl md:text-5xl font-black text-white mb-4 tracking-tight drop-shadow-lg">Apa Kata Mereka?</h1>
            <p class="text-slate-200 text-lg max-w-2xl mx-auto drop-shadow-md font-medium">
                Kumpulan kisah sukses dan kenangan manis para alumni selama menempuh pendidikan di SMPN 3 Lakbok.
            </p>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- GRID TESTIMONI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
            @forelse($testimonials as $testi)
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full relative group overflow-hidden">
                    
                    {{-- Decor --}}
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-br from-slate-50 to-indigo-50 rounded-bl-[4rem] -mr-4 -mt-4 transition-colors group-hover:from-indigo-50 group-hover:to-purple-50"></div>
                    <i class="ph-fill ph-quotes text-4xl text-slate-200 absolute top-6 right-6 group-hover:text-indigo-200 transition-colors"></i>

                    {{-- User Info --}}
                    <div class="flex items-center gap-4 mb-6 relative z-10">
                        <div class="w-14 h-14 rounded-full bg-slate-100 border-2 border-white shadow-md overflow-hidden shrink-0">
                            @if($testi->student && $testi->student->photo_path)
                                <img src="{{ asset('storage/' . $testi->student->photo_path) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-indigo-500 text-white font-bold text-xl">
                                    {{ substr($testi->student->name ?? 'A', 0, 1) }}
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0">
                            <h4 class="font-bold text-slate-900 text-base truncate" title="{{ $testi->student->name ?? 'Alumni' }}">
                                {{ $testi->student->name ?? 'Alumni' }}
                            </h4>
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                    Lulusan {{ $testi->student->graduation_year ?? '-' }}
                                </span>
                                <span class="text-xs text-indigo-600 font-bold truncate max-w-[150px]">
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
                        <p class="text-slate-600 text-sm italic leading-relaxed">
                            "{{ $testi->testimony }}"
                        </p>
                    </div>

                    {{-- Rating --}}
                    <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between text-xs text-slate-400">
                        <span>{{ $testi->updated_at->format('d M Y') }}</span>
                        <div class="flex items-center gap-1 text-amber-400 text-sm">
                            @for($i=0; $i < ($testi->rating ?? 5); $i++) <i class="ph-fill ph-star"></i> @endfor
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-slate-100 text-slate-300 mb-4">
                        <i class="ph-duotone ph-chat-teardrop-slash text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-700">Belum Ada Testimoni</h3>
                    <p class="text-slate-500">Jadilah alumni pertama yang membagikan kisah suksesmu.</p>
                </div>
            @endforelse
        </div>

        {{-- PAGINATION --}}
        <div class="mt-8 flex justify-center">
            {{ $testimonials->onEachSide(1)->links() }}
        </div>

        {{-- CTA --}}
        <div class="mt-16 bg-white rounded-3xl p-8 md:p-12 text-center shadow-lg border border-slate-100 relative overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-600/5 to-indigo-600/5"></div>
            <div class="relative z-10">
                <h3 class="text-2xl font-bold text-slate-900 mb-3">Kamu Alumni Sekolah Ini?</h3>
                <p class="text-slate-500 mb-8 max-w-xl mx-auto">Mari berbagi pengalaman dan inspirasi untuk adik-adik kelasmu. Update data tracer study dan tuliskan testimonimu sekarang.</p>
                
                @auth('student')
                    <a href="{{ route('alumni.tracer') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-600/20">
                        <i class="ph-bold ph-pencil-simple mr-2"></i> Tulis Testimoni
                    </a>
                @else
                    <a href="{{ route('student.login') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-sm font-bold text-white bg-slate-900 rounded-full hover:bg-slate-800 transition-all shadow-lg shadow-slate-900/20">
                        <i class="ph-bold ph-sign-in mr-2"></i> Login Alumni
                    </a>
                @endauth
            </div>
        </div>

    </div>
</div>
@endsection