<div class="bg-elevate-dark rounded-[2.5rem] shadow-xl shadow-elevate-dark/10 overflow-hidden mb-8 border border-elevate-primary/30 relative group">
    
    <!-- TOMBOL SAKLAR DARK MODE -->
    <div class="absolute top-4 right-4 sm:top-6 sm:right-6 z-50">
        <button @click="toggleTheme()" 
                class="w-10 h-10 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-white/20 hover:scale-110 transition-all shadow-lg focus:outline-none"
                :title="isDark ? 'Beralih ke Mode Terang' : 'Beralih ke Mode Gelap'">
            <i class="transition-all duration-300" 
               :class="isDark ? 'ph-fill ph-sun text-elevate-peach text-xl' : 'ph-fill ph-moon text-elevate-soft text-lg'"></i>
        </button>
    </div>

    <!-- Background Banner (Elevate Style) -->
    <div class="absolute inset-0 z-0 overflow-hidden">
        <!-- Abstract Shapes Overlay -->
        <div class="absolute -top-20 -right-10 w-[300px] sm:w-[400px] h-[300px] sm:h-[400px] bg-elevate-primary rounded-[4rem] rotate-12 opacity-40 mix-blend-screen pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[200px] sm:w-[300px] h-[200px] sm:h-[300px] {{ $isAlumni ? 'bg-elevate-peach-dark' : 'bg-elevate-accent' }} rounded-full filter blur-[80px] opacity-20 animate-blob"></div>
        
        <!-- Texture -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] mix-blend-overlay"></div>
    </div>
    
    <!-- Content Container -->
    <div class="relative z-10 px-6 sm:px-10 pt-14 sm:pt-16 pb-8 flex flex-col md:flex-row items-center md:items-end text-center md:text-left gap-4 sm:gap-6">
        <!-- Foto Profil -->
        <div class="relative group shrink-0 mx-auto md:mx-0 -mb-2">
            <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-full bg-white p-1.5 shadow-2xl relative z-10 transform group-hover:scale-105 transition-transform duration-300 ring-4 ring-elevate-accent/20">
                <div class="w-full h-full rounded-full bg-elevate-soft flex items-center justify-center overflow-hidden border-2 border-white relative">
                    @if($student->photo_path)
                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-elevate-gradient-card flex items-center justify-center text-4xl sm:text-5xl font-black text-elevate-primary select-none">
                            {{ substr(trim($student->name), 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- LOGIKA LEVEL BERDASARKAN POIN MERIT --}}
            @if(!$isAlumni)
                @php
                    $points = $total_merit_points ?? 0;
                    $level = floor($points / 50) + 1;
                    $nextLevelPoints = $level * 50;
                    $currentLevelProgress = $points % 50;
                    $progressPercent = ($currentLevelProgress / 50) * 100;
                @endphp
                <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 z-30 whitespace-nowrap">
                    <div class="bg-white text-elevate-dark text-[10px] font-black px-4 py-1.5 rounded-full shadow-lg border border-slate-100 flex items-center gap-1.5">
                        <i class="ph-fill ph-crown text-elevate-peach"></i> LVL {{ $level }}
                    </div>
                </div>
            @else
                 <div class="absolute bottom-1 right-1 z-20 bg-elevate-peach text-white text-[10px] font-black px-3 py-1 rounded-full border-2 border-white shadow-sm flex items-center gap-1.5">
                    <i class="ph-fill ph-graduation-cap"></i> ALUMNI
                </div>
            @endif
        </div>
        
        <!-- Detail Siswa -->
        <div class="flex-1 min-w-0 w-full md:pb-3">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight mb-2 break-words capitalize">
                        {{ strtolower($student->name) }}
                    </h1>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-2 text-xs sm:text-sm font-medium mb-4">
                        @if(!$isAlumni)
                        <span class="flex items-center bg-elevate-primary/50 backdrop-blur-md px-3 sm:px-4 py-1.5 rounded-full text-white border border-elevate-accent/30">
                            <i class="ph-fill ph-chalkboard-teacher mr-2 text-base sm:text-lg text-elevate-accent"></i>
                            <span>Kelas <strong class="font-bold text-white">{{ $student->schoolClass->name ?? 'Unassigned' }}</strong></span>
                        </span>
                        @endif
                        
                        <span x-data="{ copied: false }" 
                              @click="navigator.clipboard.writeText('{{ $student->student_id }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                              class="flex items-center bg-white/10 backdrop-blur-md px-3 sm:px-4 py-1.5 rounded-full text-white border border-white/20 font-mono hover:bg-white/20 cursor-pointer select-none transition-all" 
                              title="Klik untuk salin">
                            <i class="ph-fill mr-2 text-base sm:text-lg text-elevate-soft" :class="copied ? 'ph-check text-green-400' : 'ph-identification-card'"></i>
                            <span x-text="copied ? 'Tersalin!' : '{{ $student->student_id }}'"></span>
                        </span>
                    </div>

                    {{-- GAMIFIED PROGRESS BAR (Elevate Style) --}}
                    @if(!$isAlumni)
                    <div class="max-w-md mx-auto md:mx-0 bg-white/5 backdrop-blur-sm p-3 rounded-2xl border border-white/10">
                        <div class="flex items-center justify-between text-[10px] font-bold text-elevate-soft mb-1.5 px-1">
                            <span>{{ $points }} XP</span>
                            <span>{{ $nextLevelPoints }} XP (Next Lvl)</span>
                        </div>
                        <div class="h-2.5 w-full bg-elevate-dark/50 rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-elevate-accent rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(86,187,241,0.5)]" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2 mt-4 md:mt-0">
                    @if(!$isAlumni)
                    <a href="{{ route('portal.card', $student->id) }}" target="_blank" class="flex-1 sm:flex-none justify-center inline-flex items-center px-5 py-3 bg-white text-elevate-dark rounded-xl text-xs sm:text-sm font-bold hover:bg-elevate-soft transition-all shadow-lg active:scale-95 group">
                        <i class="ph-bold ph-identification-card mr-2 text-elevate-primary group-hover:animate-bounce"></i> Kartu OSIS
                    </a>
                    @endif

                    <button onclick="window.print()" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-3 bg-elevate-primary/30 backdrop-blur-md border border-elevate-accent/30 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-elevate-primary transition-all shadow-lg">
                        <i class="ph-bold ph-printer mr-2"></i> Biodata
                    </button>
                    <a href="{{ route('portal.index') }}" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-3 bg-elevate-primary/30 backdrop-blur-md border border-elevate-accent/30 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-elevate-primary transition-all shadow-lg">
                        <i class="ph-bold ph-magnifying-glass mr-2"></i> Cari Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>