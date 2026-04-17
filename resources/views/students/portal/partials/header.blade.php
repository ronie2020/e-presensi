<div class="bg-blue-950 rounded-[2rem] shadow-xl overflow-hidden mb-6 border border-white/10 relative group">
    <!-- Background Banner (Kini menutupi seluruh Card dengan absolute inset-0) -->
    <div class="absolute inset-0 z-0 overflow-hidden bg-slate-900">
        {{-- Background Image --}}
        <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
        
        <div class="absolute inset-0 bg-gradient-to-r {{ $isAlumni ? 'from-slate-900 via-amber-900/80 to-slate-900' : 'from-slate-900 via-blue-900/80 to-slate-900' }}"></div>
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        
        <!-- Dekorasi Blur -->
        <div class="absolute top-0 right-0 w-[200px] sm:w-[400px] h-[200px] sm:h-[400px] {{ $isAlumni ? 'bg-amber-500' : 'bg-cyan-500' }} rounded-full mix-blend-overlay filter blur-[60px] sm:blur-[80px] opacity-30 -mr-10 -mt-10 animate-blob"></div>
        <div class="absolute bottom-0 left-10 w-[150px] sm:w-[300px] h-[150px] sm:h-[300px] bg-blue-600 rounded-full mix-blend-overlay filter blur-[60px] opacity-20 animate-blob animation-delay-2000"></div>
    </div>
    
    <!-- Content Container (Padding atas disesuaikan agar proporsional di dalam card penuh) -->
    <div class="relative z-10 px-6 sm:px-10 pt-12 sm:pt-16 pb-8 flex flex-col md:flex-row items-center md:items-end text-center md:text-left gap-4 sm:gap-6">
        <!-- Foto Profil -->
        <div class="relative group shrink-0 mx-auto md:mx-0 -mb-2">
            <div class="w-28 h-28 sm:w-36 sm:h-36 rounded-full bg-white p-1 shadow-2xl relative z-10 transform group-hover:scale-105 transition-transform duration-300 ring-4 ring-cyan-400/30 backdrop-blur-sm">
                <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-white relative">
                    @if($student->photo_path)
                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-4xl sm:text-5xl font-black text-slate-400 select-none">
                            {{ substr(trim($student->name), 0, 1) }}
                        </div>
                    @endif
                </div>
            </div>
            
            {{-- LOGIKA LEVEL BERDASARKAN POIN MERIT --}}
            @if(!$isAlumni)
                @php
                    $points = $total_merit_points ?? 0;
                    $level = floor($points / 50) + 1; // Naik level tiap 50 poin
                    $nextLevelPoints = $level * 50;
                    $currentLevelProgress = $points % 50;
                    $progressPercent = ($currentLevelProgress / 50) * 100;
                @endphp
                <div class="absolute -bottom-3 left-1/2 transform -translate-x-1/2 z-30 whitespace-nowrap">
                    <div class="bg-slate-900 text-amber-400 text-[10px] font-black px-3 py-1 rounded-full border-2 border-white shadow-lg flex items-center gap-1">
                        <i class="ph-fill ph-crown text-amber-400"></i> LVL {{ $level }}
                    </div>
                </div>
            @else
                 <div class="absolute bottom-1 right-1 z-20 bg-amber-500 text-slate-900 text-[10px] font-black px-3 py-1 rounded-full border-2 border-white shadow-sm flex items-center gap-1.5">
                    <i class="ph-fill ph-graduation-cap"></i> ALUMNI
                </div>
            @endif
        </div>
        
        <!-- Detail Siswa -->
        <div class="flex-1 min-w-0 w-full md:pb-3">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div class="flex-1">
                    <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight mb-2 break-words capitalize drop-shadow-lg">
                        {{ strtolower($student->name) }}
                    </h1>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-2 text-xs sm:text-sm font-medium mb-3">
                        @if(!$isAlumni)
                        <span class="flex items-center bg-blue-600 px-3 sm:px-4 py-1.5 rounded-full text-white shadow-lg shadow-blue-900/30 border border-blue-500 transition hover:bg-blue-500">
                            <i class="ph-fill ph-chalkboard-teacher mr-2 text-base sm:text-lg text-blue-200"></i>
                            <span>Kelas <strong class="font-bold text-white">{{ $student->schoolClass->name ?? 'Unassigned' }}</strong></span>
                        </span>
                        @endif
                        
                        <span x-data="{ copied: false }" 
                              @click="navigator.clipboard.writeText('{{ $student->student_id }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                              class="flex items-center bg-blue-600 px-3 sm:px-4 py-1.5 rounded-full text-white shadow-lg shadow-blue-900/30 border border-blue-500 font-mono transition hover:bg-blue-500 cursor-pointer select-none relative" 
                              title="Klik untuk salin">
                            <i class="ph-fill mr-2 text-base sm:text-lg text-blue-200" :class="copied ? 'ph-check' : 'ph-identification-card'"></i>
                            <span x-text="copied ? 'Tersalin!' : '{{ $student->student_id }}'"></span>
                        </span>
                    </div>

                    {{-- GAMIFIED PROGRESS BAR --}}
                    @if(!$isAlumni)
                    <div class="max-w-md mx-auto md:mx-0">
                        <div class="flex items-center justify-between text-[10px] font-bold text-blue-200 mb-1 px-1">
                            <span>{{ $points }} XP</span>
                            <span>{{ $nextLevelPoints }} XP (Next Lvl)</span>
                        </div>
                        <div class="h-2 w-full bg-blue-900/50 rounded-full overflow-hidden backdrop-blur-sm border border-white/10 shadow-inner">
                            <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 rounded-full transition-all duration-1000 ease-out shadow-[0_0_10px_rgba(34,211,238,0.5)]" style="width: {{ $progressPercent }}%"></div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2">
                    @if(!$isAlumni)
                    <a href="{{ route('portal.card', $student->id) }}" target="_blank" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-3 bg-cyan-500/80 backdrop-blur-md border border-cyan-400/30 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-cyan-500 transition-all shadow-lg hover:shadow-cyan-500/30 group">
                        <i class="ph-bold ph-identification-card mr-2 group-hover:animate-bounce"></i> Kartu OSIS
                    </a>
                    @endif

                    <button onclick="window.print()" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-white hover:text-slate-900 transition-all shadow-lg">
                        <i class="ph-bold ph-printer mr-2"></i> Biodata
                    </button>
                    <a href="{{ route('portal.index') }}" class="flex-1 sm:flex-none justify-center inline-flex items-center px-4 py-3 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-xs sm:text-sm font-bold text-white hover:bg-white hover:text-slate-900 transition-all shadow-lg">
                        <i class="ph-bold ph-magnifying-glass mr-2"></i> Cari Lain
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>