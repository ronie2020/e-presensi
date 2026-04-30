<div class="space-y-8 animate-in fade-in duration-500 font-sans">
    
    {{-- 1. HEADER ROYAL (Konsep Kejuaraan Elevate) --}}
    <div class="bg-gradient-to-br from-elevate-dark via-[#1e2f4f] to-elevate-dark rounded-[2.5rem] p-8 md:p-10 text-white shadow-2xl shadow-elevate-dark/20 relative overflow-hidden flex flex-col items-center text-center border border-elevate-primary/30">
        <!-- Dekorasi Background -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full overflow-hidden pointer-events-none">
            <div class="absolute top-[-50%] left-[-20%] w-[140%] h-[100%] bg-gradient-to-b from-elevate-primary/30 to-transparent rounded-[100%] blur-3xl"></div>
            <i class="ph-fill ph-trophy text-[250px] text-white/5 absolute top-10 left-1/2 -translate-x-1/2"></i>
        </div>

        <!-- Konten -->
        <div class="relative z-10 max-w-lg mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-elevate-peach-light/20 border border-elevate-peach/30 backdrop-blur-md mb-4 shadow-sm">
                <i class="ph-fill ph-sparkle text-elevate-peach animate-pulse"></i>
                <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-white">Fastabiqul Khairat</span>
            </div>
            <h3 class="text-3xl md:text-4xl font-black tracking-tight mb-2 text-transparent bg-clip-text bg-gradient-to-r from-elevate-peach-light via-white to-elevate-peach-light drop-shadow-sm">
                Papan Juara Kebaikan
            </h3>
            <p class="text-white/70 text-sm leading-relaxed">
                "Dan bagi tiap-tiap umat ada kiblatnya sendiri yang ia menghadap kepadanya. Maka berlomba-lombalah kamu dalam kebaikan." (QS. Al-Baqarah: 148)
            </p>
        </div>
    </div>

    {{-- 2. PODIUM JUARA UTAMA (Top 3) --}}
    @if($topRamadanStudents->isNotEmpty())
    <div class="relative pt-10 pb-6 px-4">
        <div class="grid grid-cols-3 gap-3 items-end max-w-xl mx-auto">
            
            {{-- JUARA 2 (Kiri) --}}
            <div class="flex flex-col items-center {{ !isset($topRamadanStudents[1]) ? 'invisible' : '' }}">
                @if(isset($topRamadanStudents[1]))
                <div class="relative group">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-slate-300 p-1 bg-white shadow-xl relative z-10 group-hover:scale-105 transition-transform duration-300">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($topRamadanStudents[1]->name) }}&background=e5eff5&color=2c3f61&bold=true" class="w-full h-full object-cover rounded-full">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-slate-400 text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-md border border-slate-300">
                            #2
                        </div>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm p-3 rounded-2xl shadow-sm border border-slate-200 mt-4 w-full text-center relative hover:border-elevate-accent/30 transition-colors">
                    <div class="w-full h-8 bg-gradient-to-t from-slate-100/80 to-transparent absolute -top-8 left-0 rounded-t-xl -z-10"></div>
                    <p class="text-[10px] font-black text-elevate-dark line-clamp-1 capitalize">{{ strtolower(strtok($topRamadanStudents[1]->name, ' ')) }}</p>
                    <p class="text-[9px] font-bold text-slate-500">{{ number_format($topRamadanStudents[1]->ramadan_points) }} Pts</p>
                </div>
                @endif
            </div>

            {{-- JUARA 1 (Tengah - Terbesar) --}}
            <div class="flex flex-col items-center -mt-8 relative z-20">
                @if(isset($topRamadanStudents[0]))
                <div class="relative group">
                    <i class="ph-fill ph-crown text-elevate-peach text-4xl absolute -top-8 left-1/2 -translate-x-1/2 drop-shadow-md animate-bounce-subtle"></i>
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-full border-4 border-elevate-peach p-1 bg-gradient-to-b from-elevate-peach-light/50 to-white shadow-2xl relative z-10 ring-4 ring-elevate-peach/20 group-hover:scale-105 transition-transform duration-300">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($topRamadanStudents[0]->name) }}&background=f9a282&color=ffffff&bold=true" class="w-full h-full object-cover rounded-full">
                        <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-elevate-peach text-white text-xs font-black px-3 py-0.5 rounded-lg shadow-lg border border-elevate-peach-dark flex items-center gap-1">
                            <i class="ph-fill ph-star"></i> #1
                        </div>
                    </div>
                </div>
                <div class="bg-gradient-to-b from-white to-elevate-peach-light/20 p-4 rounded-3xl shadow-lg border border-elevate-peach/30 mt-5 w-full text-center relative min-w-[120px]">
                    <p class="text-xs font-black text-elevate-dark line-clamp-1 capitalize mb-0.5">{{ strtolower(strtok($topRamadanStudents[0]->name, ' ')) }}</p>
                    <div class="inline-block bg-elevate-primary text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm">
                        {{ number_format($topRamadanStudents[0]->ramadan_points) }} Pts
                    </div>
                </div>
                @endif
            </div>

            {{-- JUARA 3 (Kanan) --}}
            <div class="flex flex-col items-center {{ !isset($topRamadanStudents[2]) ? 'invisible' : '' }}">
                @if(isset($topRamadanStudents[2]))
                <div class="relative group">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full border-4 border-orange-700/40 p-1 bg-white shadow-xl relative z-10 group-hover:scale-105 transition-transform duration-300">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($topRamadanStudents[2]->name) }}&background=c86845&color=ffffff&bold=true" class="w-full h-full object-cover rounded-full">
                        <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 bg-elevate-peach-dark text-white text-[10px] font-black px-2 py-0.5 rounded-md shadow-md border border-orange-800">
                            #3
                        </div>
                    </div>
                </div>
                <div class="bg-white/80 backdrop-blur-sm p-3 rounded-2xl shadow-sm border border-slate-200 mt-4 w-full text-center relative hover:border-elevate-accent/30 transition-colors">
                    <div class="w-full h-6 bg-gradient-to-t from-slate-100/80 to-transparent absolute -top-6 left-0 rounded-t-xl -z-10"></div>
                    <p class="text-[10px] font-black text-elevate-dark line-clamp-1 capitalize">{{ strtolower(strtok($topRamadanStudents[2]->name, ' ')) }}</p>
                    <p class="text-[9px] font-bold text-slate-500">{{ number_format($topRamadanStudents[2]->ramadan_points) }} Pts</p>
                </div>
                @endif
            </div>                   
        </div>
    </div>
    @else
        {{-- EMPTY STATE --}}
        <div class="text-center py-16 bg-white rounded-[3rem] border border-dashed border-slate-200 mx-4">
            <div class="w-20 h-20 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-4 animate-bounce-subtle">
                <i class="ph-duotone ph-trophy text-4xl text-elevate-primary"></i>
            </div>
            <h4 class="text-lg font-black text-elevate-dark">Peringkat Belum Tersedia</h4>
            <p class="text-sm text-slate-500 mt-1 max-w-xs mx-auto">Jadilah yang pertama mengisi jurnal hari ini!</p>
        </div>
    @endif

    {{-- 3. LIST PERINGKAT SELANJUTNYA (Card Style) --}}
    @if($topRamadanStudents->count() > 3)
    <div class="px-2">
        <div class="flex items-center gap-3 mb-4 px-2">
            <div class="h-8 w-1 bg-elevate-primary rounded-full"></div>
            <h3 class="font-bold text-elevate-dark text-lg">Top 10 Pejuang</h3>
            {{-- Tombol Link --}}
            <a href="{{ route('ramadan.leaderboard') }}" class="px-4 py-1.5 rounded-xl bg-elevate-dark hover:bg-elevate-primary text-white text-[10px] font-black uppercase tracking-wider transition-colors shadow-md flex items-center gap-1">
                Lihat Semua <i class="ph-bold ph-arrow-right"></i>
            </a>
        </div>

        <div class="space-y-3">
            @foreach($topRamadanStudents->slice(3) as $index => $s)
            @php $isMe = Auth::guard('student')->id() == $s->id; @endphp
            <div class="flex items-center gap-4 p-4 rounded-2xl border transition-all duration-300
                {{ $isMe 
                    ? 'bg-elevate-primary text-white shadow-lg shadow-elevate-primary/20 scale-[1.02] border-elevate-accent' 
                    : 'bg-white text-elevate-dark hover:bg-elevate-soft/50 hover:shadow-md border-slate-100 hover:border-elevate-accent/30' }}">
                
                {{-- Ranking Number --}}
                <div class="flex-shrink-0 w-8 text-center">
                    <span class="text-sm font-black {{ $isMe ? 'text-elevate-accent' : 'text-slate-400' }}">#{{ $index + 4 }}</span>
                </div>

                {{-- Avatar --}}
                <div class="relative">
                    <div class="w-12 h-12 rounded-full overflow-hidden border-2 {{ $isMe ? 'border-white' : 'border-elevate-soft' }}">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($s->name) }}&size=100&background={{ $isMe ? 'ffffff' : 'e5eff5' }}&color={{ $isMe ? '0d52a1' : '2c3f61' }}" class="w-full h-full object-cover">
                    </div>
                </div>

                {{-- Info --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center gap-2">
                        <p class="text-sm font-bold truncate capitalize">{{ strtolower($s->name) }}</p>
                        @if($isMe) 
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-black bg-white text-elevate-primary uppercase tracking-wider">You</span> 
                        @endif
                    </div>
                    <p class="text-[10px] font-bold uppercase tracking-wide {{ $isMe ? 'text-white/80' : 'text-slate-400' }}">
                        {{ $s->schoolClass->name ?? 'Siswa' }}
                    </p>
                </div>

                {{-- Points --}}
                <div class="text-right">
                    <div class="text-base font-black {{ $isMe ? 'text-white' : 'text-elevate-primary' }}">
                        {{ number_format($s->ramadan_points, 0, ',', '.') }}
                    </div>
                    <div class="text-[9px] font-bold uppercase {{ $isMe ? 'text-elevate-accent' : 'text-slate-400' }}">Poin</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>