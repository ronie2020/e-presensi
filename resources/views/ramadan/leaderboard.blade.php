<x-app-layout>
    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes bounce-slow { 0%, 100% { transform: translateY(-5%); } 50% { transform: translateY(0); } }
        .animate-podium { animation: bounce-slow 3s ease-in-out infinite; }
    </style>

    <div class="p-6 md:p-10 space-y-8 min-h-screen bg-[#020b18] font-sans text-slate-100 relative overflow-hidden">
        
        {{-- Ambient Glows --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        {{-- HERO SECTION --}}
        <div class="relative z-10">
            <x-hero-section
                badge="FASTABIQUL KHAIRAT"
                badgeIcon="ph-fill ph-star"
                showcaseIcon="ph-duotone ph-trophy"
                showcaseTitle="Papan Peringkat"
                showcaseSubtitle="Inspirasi Ibadah Ramadhan">
                <x-slot:title>
                    <span class="block text-slate-100">Papan Peringkat</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Kebaikan Ramadhan
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Daftar siswa paling aktif yang menginspirasi dalam menjalankan ibadah harian selama bulan suci Ramadhan.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-moon-stars text-amber-400"></i> Amaliyah Ramadhan
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-trophy text-yellow-400"></i> Top Santri
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-900/80 border border-white/10 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-sparkle text-emerald-400"></i> Poin Kebaikan
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-white/10 backdrop-blur-md">
                        <i class="ph-fill ph-users text-sky-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Peserta:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $topStudents->count() }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-amber-500/30 backdrop-blur-md">
                        <i class="ph-fill ph-star text-amber-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Total Poin:</span>
                        <span class="text-sm font-black text-amber-400 font-mono">{{ number_format($topStudents->sum('points'), 0, ',', '.') }}</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>
        </div>

        {{-- TOP 3 PODIUM --}}
        @if($topStudents->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end max-w-5xl mx-auto py-10 min-h-[350px] relative z-10">
            
            {{-- PERINGKAT 2 (Kiri) --}}
            <div class="order-2 md:order-1 flex flex-col items-center {{ !isset($topStudents[1]) ? 'invisible md:visible opacity-0' : '' }}">
                @if(isset($topStudents[1]))
                <div class="relative mb-5">
                    <div class="w-24 h-24 rounded-full border-4 border-sky-400/50 overflow-hidden shadow-lg bg-slate-900 p-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($topStudents[1]->name) }}&background=38bdf8&color=ffffff&bold=true" alt="Runner up" class="w-full h-full object-cover rounded-full">
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-sky-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-black shadow-md border-2 border-slate-900 text-sm">2</div>
                </div>
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] border border-white/10 shadow-2xl backdrop-blur-xl p-6 rounded-[2rem] rounded-b-xl w-full text-center h-44 flex flex-col justify-center">
                    <h4 class="font-bold text-white text-lg line-clamp-1">{{ $topStudents[1]->name }}</h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 mt-1">{{ $topStudents[1]->schoolClass->name ?? 'Kelas' }}</p>
                    <div class="text-sky-400 font-black text-2xl">{{ number_format($topStudents[1]->points, 0, ',', '.') }} <span class="text-xs font-bold text-slate-400">pts</span></div>
                </div>
                @endif
            </div>

            {{-- PERINGKAT 1 (Tengah) --}}
            <div class="order-1 md:order-2 flex flex-col items-center scale-110 md:-translate-y-6 z-10">
                @if(isset($topStudents[0]))
                <div class="relative mb-6">
                    <div class="absolute -top-10 left-1/2 -translate-x-1/2 animate-podium">
                        <i class="ph-fill ph-crown text-amber-400 text-6xl drop-shadow-md"></i>
                    </div>
                    <div class="w-32 h-32 rounded-full border-4 border-amber-400 overflow-hidden shadow-xl bg-slate-900 ring-8 ring-amber-500/20 p-1.5">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($topStudents[0]->name) }}&background=f59e0b&color=ffffff&bold=true" alt="Winner" class="w-full h-full object-cover rounded-full">
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-amber-500 text-white w-10 h-10 rounded-full flex items-center justify-center font-black shadow-lg border-2 border-slate-900 text-lg">1</div>
                </div>
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] border border-amber-500/40 shadow-2xl backdrop-blur-xl p-8 rounded-[2.5rem] rounded-b-xl w-full text-center h-56 flex flex-col justify-center">
                    <h4 class="font-black text-white text-xl line-clamp-1">{{ $topStudents[0]->name }}</h4>
                    <p class="text-[10px] font-bold text-amber-400 uppercase mb-4 mt-1 tracking-widest">Sultan Ibadah</p>
                    <div class="bg-amber-500 text-slate-950 px-5 py-2 rounded-xl inline-block text-2xl font-black shadow-md">
                        {{ number_format($topStudents[0]->points, 0, ',', '.') }} <span class="text-xs font-bold text-slate-900">pts</span>
                    </div>
                </div>
                @endif
            </div>

            {{-- PERINGKAT 3 (Kanan) --}}
            <div class="order-3 flex flex-col items-center {{ !isset($topStudents[2]) ? 'invisible md:visible opacity-0' : '' }}">
                @if(isset($topStudents[2]))
                <div class="relative mb-5">
                    <div class="w-24 h-24 rounded-full border-4 border-emerald-400/50 overflow-hidden shadow-lg bg-slate-900 p-1">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($topStudents[2]->name) }}&background=10b981&color=ffffff&bold=true" alt="3rd place" class="w-full h-full object-cover rounded-full">
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-emerald-500 text-white w-8 h-8 rounded-full flex items-center justify-center font-black shadow-md border-2 border-slate-900 text-sm">3</div>
                </div>
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] border border-white/10 shadow-2xl backdrop-blur-xl p-6 rounded-[2rem] rounded-b-xl w-full text-center h-44 flex flex-col justify-center">
                    <h4 class="font-bold text-white text-lg line-clamp-1">{{ $topStudents[2]->name }}</h4>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3 mt-1">{{ $topStudents[2]->schoolClass->name ?? 'Kelas' }}</p>
                    <div class="text-emerald-400 font-black text-2xl">{{ number_format($topStudents[2]->points, 0, ',', '.') }} <span class="text-xs font-bold text-slate-400">pts</span></div>
                </div>
                @endif
            </div>
        </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="text-center py-24 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] border border-white/10 rounded-[2.5rem] shadow-2xl max-w-4xl mx-auto">
                <div class="w-20 h-20 bg-slate-900/80 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/10 shadow-sm">
                    <i class="ph-duotone ph-users text-4xl text-slate-500"></i>
                </div>
                <h3 class="font-bold text-white text-lg">Belum ada data peringkat</h3>
                <p class="text-slate-400 text-sm mt-1">Data akan muncul setelah siswa mengisi jurnal secara aktif.</p>
            </div>
        @endif

        {{-- LIST RANK 4+ --}}
        <div class="max-w-4xl mx-auto bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-white/10 shadow-2xl backdrop-blur-xl overflow-hidden relative z-10">
            <div class="p-6 border-b border-white/10 flex items-center justify-between bg-slate-900/50">
                <h3 class="font-black text-white uppercase tracking-wider text-xs flex items-center gap-2">
                    <i class="ph-bold ph-list-numbers text-sky-400"></i> Daftar Peringkat Lainnya
                </h3>
            </div>
            
            <div class="divide-y divide-white/5">
                @forelse($topStudents->slice(3) as $index => $student)
                <div class="group flex items-center justify-between p-5 hover:bg-slate-800/40 transition-colors cursor-default">
                    <div class="flex items-center gap-5">
                        <div class="w-12 h-12 rounded-xl bg-slate-900/80 text-slate-300 font-black text-sm flex items-center justify-center border border-white/10 group-hover:bg-sky-500 group-hover:text-white transition-colors shadow-sm">
                            #{{ $index + 4 }}
                        </div>
                        <div class="w-12 h-12 rounded-full overflow-hidden border border-white/10 shadow-sm">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=021124&color=38bdf8&bold=true" alt="avatar" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="text-sm font-bold text-white group-hover:text-sky-400 transition-colors">{{ $student->name }}</div>
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $student->schoolClass->name ?? 'Tanpa Kelas' }}</div>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="text-base font-black text-sky-400">{{ number_format($student->points, 0, ',', '.') }} <span class="text-[10px] text-slate-400 font-bold">pts</span></div>
                        <div class="w-24 bg-slate-900/80 rounded-full h-1.5 mt-2 overflow-hidden shadow-inner border border-white/10">
                            @php $percent = ($student->points / ($topStudents[0]->points ?: 1)) * 100; @endphp
                            <div class="bg-gradient-to-r from-sky-500 to-blue-500 h-full rounded-full transition-all duration-1000" style="width: {{ $percent }}%"></div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-20 text-center text-slate-400">
                    <i class="ph-duotone ph-magnifying-glass text-5xl mb-4 opacity-50"></i>
                    <p class="font-bold text-sm">Belum ada peringkat tambahan.</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- INFO SECTION --}}
        <div class="max-w-4xl mx-auto flex flex-col md:flex-row gap-6 relative z-10">
            <div class="flex-1 bg-slate-900/80 p-6 rounded-[2rem] border border-white/10 shadow-2xl flex items-start gap-4">
                <div class="p-3 bg-slate-800 rounded-xl text-sky-400 shadow-sm border border-white/10"><i class="ph-fill ph-lightning text-xl"></i></div>
                <div>
                    <h5 class="font-bold text-white text-sm mb-1">Cara Mendapat Poin?</h5>
                    <p class="text-xs text-slate-300 font-medium leading-relaxed">
                        Poin dihitung dari setiap jurnal harian yang dinilai oleh guru. Pastikan semua ibadah wajib dan sunnah terisi dengan lengkap dan jujur!
                    </p>
                </div>
            </div>
            <div class="flex-1 bg-slate-900/80 p-6 rounded-[2rem] border border-amber-500/20 shadow-2xl flex items-start gap-4">
                <div class="p-3 bg-slate-800 rounded-xl text-amber-400 shadow-sm border border-amber-500/20"><i class="ph-fill ph-gift text-xl"></i></div>
                <div>
                    <h5 class="font-bold text-white text-sm mb-1">Apresiasi Kebaikan!</h5>
                    <p class="text-xs text-slate-300 font-medium leading-relaxed">
                        Tiga peringkat teratas di akhir Ramadhan akan mendapatkan apresiasi khusus dari sekolah sebagai bentuk penghargaan ketaqwaan.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>