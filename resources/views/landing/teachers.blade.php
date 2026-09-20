<!-- GURU SECTION -->
<div id="guru" class="py-24 relative overflow-hidden transition-colors duration-300">
    
    <!-- Ambient Backgrounds -->
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-elevate-accent/10 rounded-full blur-[120px] pointer-events-none -translate-y-1/4 translate-x-1/4"></div>
    <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-elevate-primary/10 rounded-full blur-[120px] pointer-events-none translate-y-1/4 -translate-x-1/4"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header Section -->
        <div class="text-center mb-16" data-aos="fade-up">
            <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-accent/20 text-elevate-accent text-[10px] font-black uppercase tracking-widest mb-4 border border-elevate-accent/30 shadow-sm transition-colors">
                <i class="ph-fill ph-users-three text-sm"></i> SDM Unggul
            </span>
            <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-4 transition-colors">Tenaga Pendidik</h2>
            <p class="text-slate-300 max-w-2xl mx-auto text-sm md:text-lg font-medium transition-colors">Dibimbing oleh guru-guru profesional yang berdedikasi tinggi dalam mencetak generasi emas.</p>
        </div>

        <!-- Grid Guru -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($teachers as $teacher)
                @php
                    // LOGIKA ROLE ASLI DIPERTAHANKAN
                    $displayRole = $teacher->position;
                    if (empty($displayRole)) {
                        $decodedRoles = is_string($teacher->role) ? json_decode($teacher->role, true) : $teacher->role;
                        $displayRole = is_array($decodedRoles) ? implode(', ', $decodedRoles) : $teacher->role;
                    }
                @endphp

                <!-- Teacher Card Elevate -->
                <div class="group bg-white/5 backdrop-blur-xl rounded-[2.5rem] p-2 border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.3)] hover:shadow-[0_8px_32px_rgba(86,187,241,0.2)] hover:-translate-y-2 transition-all duration-500 h-full flex flex-col hover:border-elevate-accent/40 hover:bg-white/10 overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    
                    {{-- Photo Container --}}
                    <div class="aspect-[3/4] w-full relative rounded-[2rem] overflow-hidden bg-white/5 border border-white/5 transition-colors">
                        @if($teacher->photo_path)
                            <img src="{{ asset('storage/' . $teacher->photo_path) }}" loading="lazy" alt="{{ $teacher->name }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                            <div class="w-full h-full hidden flex-col items-center justify-center bg-white/5 text-elevate-accent/40">
                                <span class="text-6xl font-black opacity-30 select-none uppercase">{{ substr($teacher->name, 0, 2) }}</span>
                            </div>
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center bg-white/5 text-elevate-accent/40 transition-colors">
                                <span class="text-7xl font-black opacity-20 select-none uppercase group-hover:scale-110 transition-transform">{{ substr($teacher->name, 0, 2) }}</span>
                            </div>
                        @endif
                        
                        {{-- Hover Gradient Overlay --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-[#021124]/90 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                        {{-- Role Badge (Floating on Image) --}}
                        <div class="absolute bottom-4 left-0 right-0 px-4 transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition-all duration-300">
                             <div class="bg-[#021124]/80 backdrop-blur-md px-3 py-1.5 rounded-xl border border-white/10 text-center shadow-lg">
                                <span class="text-[9px] font-black uppercase text-elevate-accent tracking-widest truncate block">
                                    {{ $displayRole }}
                                </span>
                             </div>
                        </div>
                    </div>

                    {{-- Content Area --}}
                    <div class="p-5 text-center relative flex-1 flex flex-col items-center justify-center">
                        {{-- Badge Posisi (Visible always) --}}
                        <div class="mb-3 group-hover:opacity-0 transition-opacity duration-200">
                            <span class="bg-elevate-accent/10 text-elevate-accent text-[9px] font-black uppercase tracking-widest py-1 px-3 rounded-lg border border-elevate-accent/20 truncate max-w-[150px] inline-block shadow-sm">
                                {{ $displayRole }}
                            </span>
                        </div>
                        
                        <h3 class="text-lg font-black text-white group-hover:text-elevate-accent transition-colors line-clamp-1 leading-tight">{{ $teacher->name }}</h3>
                        
                        @if(!empty($teacher->nip))
                            <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-wider">NIP. {{ $teacher->nip }}</p>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center animate-enter bg-white/5 backdrop-blur-xl rounded-[3rem] border-2 border-dashed border-white/10 transition-colors">
                    <div class="inline-flex p-5 bg-elevate-accent/10 rounded-full mb-4 text-elevate-accent shadow-sm border border-elevate-accent/20 transition-colors">
                        <i class="ph-duotone ph-chalkboard-teacher text-4xl"></i>
                    </div>
                    <p class="text-slate-400 font-medium">Belum ada data tenaga pendidik.</p>
                </div>
            @endforelse
        </div>

        <!-- Footer CTA -->
        <div class="text-center mt-16" data-aos="fade-up">
            <a href="{{ route('teachers.index') }}" class="inline-flex items-center justify-center px-8 py-3.5 text-xs font-bold text-white bg-gradient-to-r from-elevate-accent to-elevate-primary rounded-full hover:shadow-[0_0_25px_rgba(86,187,241,0.4)] border border-elevate-accent/30 transition-all shadow-lg group">
                Lihat Seluruh Staff 
                <i class="ph-bold ph-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
    </div>
</div>