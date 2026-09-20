{{-- 
    ========================================================================
    SECTION: JEJAK ALUMNI & TESTIMONI (Glassmorphism Dark)
    ======================================================================== 
--}}
<div id="alumni" class="py-24 relative overflow-hidden transition-colors duration-300">
    
    <!-- Ambient Backgrounds -->
    <div class="absolute top-0 right-0 w-[600px] h-[600px] bg-elevate-accent/10 rounded-full blur-[150px] pointer-events-none -mt-40 -mr-40"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-primary/10 rounded-full blur-[120px] pointer-events-none -mb-40 -ml-40"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest border border-elevate-accent/30 shadow-sm mb-4 transition-colors">
                <i class="ph-fill ph-graduation-cap text-sm"></i> Tracer Study
            </span>
            <h2 class="text-3xl font-black text-white sm:text-4xl leading-tight">Jejak Langkah Alumni</h2>
            <p class="mt-4 text-sm md:text-base text-slate-300 max-w-2xl mx-auto font-medium leading-relaxed transition-colors">
                Melihat sebaran dan kisah sukses para alumni SMPN 3 Lakbok yang telah melanjutkan ke jenjang pendidikan lebih tinggi maupun kegiatan lainnya.
            </p>
        </div>

        <!-- STATISTIK ALUMNI -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-20">
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 text-center hover:bg-white/10 hover:border-elevate-accent/40 transition-all duration-300 group shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)]" data-aos="fade-up" data-aos-delay="0">
                <p class="text-4xl font-black text-white mb-2 group-hover:scale-110 transition-transform">{{ $alumniStats['total'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-elevate-accent uppercase tracking-wider">Total Alumni</p>
            </div>
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 text-center hover:bg-white/10 hover:border-elevate-accent/40 transition-all duration-300 group shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)]" data-aos="fade-up" data-aos-delay="100">
                <p class="text-4xl font-black text-elevate-accent mb-2 group-hover:scale-110 transition-transform">{{ $alumniStats['sma'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-elevate-accent uppercase tracking-wider">Lanjut SMA</p>
            </div>
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 text-center hover:bg-white/10 hover:border-elevate-accent/40 transition-all duration-300 group shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)]" data-aos="fade-up" data-aos-delay="200">
                <p class="text-4xl font-black text-blue-300 mb-2 group-hover:scale-110 transition-transform">{{ $alumniStats['smk'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-blue-300 uppercase tracking-wider">Lanjut SMK</p>
            </div>
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 text-center hover:bg-white/10 hover:border-emerald-400/40 transition-all duration-300 group shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(16,185,129,0.2)]" data-aos="fade-up" data-aos-delay="300">
                <p class="text-4xl font-black text-emerald-400 mb-2 group-hover:scale-110 transition-transform">{{ $alumniStats['pesantren'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-emerald-300 uppercase tracking-wider">Pesantren</p>
            </div>
            <div class="bg-white/5 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 text-center hover:bg-white/10 hover:border-slate-400/40 transition-all duration-300 group shadow-[0_8px_32px_rgba(0,0,0,0.3)]" data-aos="fade-up" data-aos-delay="400">
                <p class="text-4xl font-black text-slate-300 mb-2 group-hover:scale-110 transition-transform">{{ $alumniStats['tidak_lanjut'] ?? 0 }}</p>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tidak Lanjut</p>
            </div>
        </div>

        <!-- SLIDER TESTIMONI ALUMNI -->
        @if(isset($alumniTestimonials) && count($alumniTestimonials) > 0)
            <div class="flex overflow-x-auto gap-6 pb-12 snap-x snap-mandatory hide-scrollbar custom-scrollbar md:grid md:grid-cols-3 md:overflow-visible">
                @foreach($alumniTestimonials as $testi)
                    <div class="snap-center shrink-0 w-[85vw] md:w-auto flex flex-col h-full" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        
                        <!-- Testimonial Card -->
                        <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-8 md:p-10 rounded-[2.5rem] shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:bg-white/10 hover:-translate-y-2 hover:border-elevate-accent/40 transition-all duration-300 relative overflow-hidden group flex-1 flex flex-col">
                            
                            <i class="ph-fill ph-quotes text-5xl text-elevate-accent/20 absolute top-6 right-6 group-hover:text-elevate-accent/40 transition-colors"></i>
                            
                            <div class="relative z-10 h-full flex flex-col">
                                <div class="flex items-center gap-4 mb-6">
                                    <div class="w-14 h-14 rounded-2xl overflow-hidden border-2 border-elevate-accent shadow-[0_0_15px_rgba(86,187,241,0.3)] shrink-0 transition-colors">
                                        @if($testi->student && $testi->student->photo_path)
                                            <img src="{{ asset('storage/' . $testi->student->photo_path) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-elevate-accent text-elevate-dark font-bold text-xl">{{ substr($testi->student->name ?? 'A', 0, 1) }}</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-black text-white text-base leading-tight line-clamp-1">{{ $testi->student->name ?? 'Alumni' }}</h4>
                                        <div class="flex flex-wrap items-center gap-2 mt-1.5">
                                            {{-- Menambahkan Tahun Lulus disini --}}
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded-md bg-elevate-accent/20 text-elevate-accent uppercase tracking-wider border border-elevate-accent/30">
                                                Lulusan {{ $testi->student->graduation_year ?? ($testi->student->graduated_date ? \Carbon\Carbon::parse($testi->student->graduated_date)->year : '-') }}
                                            </span>
                                            
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest transition-colors flex items-center gap-1">
                                                {{ in_array($testi->activity_status, ['Bekerja', 'Mencari Kerja', 'Lainnya']) ? 'Tidak Lanjut' : $testi->activity_status }} 
                                                @if($testi->campus_name || $testi->company_name)
                                                    <span class="text-slate-500 truncate max-w-[100px]">@ {{ Str::limit($testi->campus_name ?? $testi->company_name, 20) }}</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-slate-200 text-sm italic leading-relaxed line-clamp-4 font-medium transition-colors">
                                        "{{ $testi->testimony }}"
                                    </p>
                                </div>

                                <div class="mt-6 pt-4 border-t border-white/10 flex items-center gap-1 text-amber-400 text-sm opacity-90 transition-colors">
                                    @for($i=0; $i < ($testi->rating ?? 5); $i++) <i class="ph-fill ph-star"></i> @endfor
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="flex justify-center gap-3 mt-2 md:hidden">
                <i class="ph-bold ph-arrow-left text-elevate-accent animate-pulse"></i>
                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest transition-colors">Geser untuk melihat</span>
                <i class="ph-bold ph-arrow-right text-elevate-accent animate-pulse"></i>
            </div>

            {{-- TOMBOL LIHAT SEMUA --}}
            <div class="mt-14 text-center" data-aos="fade-up">
                <a href="{{ route('public.testimonials') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-black uppercase tracking-widest text-white bg-gradient-to-r from-elevate-accent to-elevate-primary border border-elevate-accent/30 rounded-full hover:shadow-[0_0_20px_rgba(86,187,241,0.4)] transition-all shadow-lg group">
                    Jelajahi Tracer Study 
                    <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        @else
            <div class="text-center py-16 border-2 border-dashed border-white/10 rounded-[3rem] bg-white/5 backdrop-blur-sm max-w-3xl mx-auto transition-colors">
                <div class="inline-flex p-4 rounded-full bg-elevate-accent/10 text-elevate-accent mb-4 border border-elevate-accent/20"><i class="ph-duotone ph-graduation-cap text-4xl"></i></div>
                <p class="text-slate-400 font-medium text-sm transition-colors">Belum ada testimoni alumni yang ditampilkan saat ini.</p>
            </div>
        @endif
    </div>
</div>