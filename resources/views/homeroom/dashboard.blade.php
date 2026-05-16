<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <div class="py-8 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Panel --}}
            <div class="relative rounded-[2.5rem] bg-elevate-dark p-8 md:p-10 text-white shadow-xl shadow-elevate-dark/10 overflow-hidden border border-elevate-primary/30 mb-8">
                <div class="absolute top-0 right-0 -mr-20 -mt-20 h-80 w-80 rounded-full bg-elevate-primary blur-[100px] opacity-40 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-elevate-accent blur-[100px] opacity-20 pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md text-xs font-bold uppercase tracking-widest text-elevate-accent border border-white/20 mb-4 shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Dashboard Wali Kelas
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2">Kelas {{ $class->name }}</h1>
                        <p class="text-elevate-soft font-medium text-sm md:text-base max-w-2xl">Pantau statistik kedisiplinan, kehadiran, dan prestasi anak didik Anda secara real-time.</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        
                        {{-- TAMBAHAN: DROPDOWN PILIH KELAS KHUSUS ADMIN & KEPSEK --}}
                        @if(isset($isAdminOrKepsek) && $isAdminOrKepsek && isset($allClasses))
                            <form action="{{ route('homeroom.dashboard') }}" method="GET" class="relative w-full sm:w-auto">
                                <select name="class_id" onchange="this.form.submit()" class="w-full pl-5 pr-12 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-md text-white border border-white/30 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-white/50 cursor-pointer font-bold shadow-sm transition-all">
                                    @foreach($allClasses as $c)
                                        <option value="{{ $c->id }}" {{ $class->id == $c->id ? 'selected' : '' }} class="text-slate-800 font-semibold">
                                            Pantau Kelas {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-white">
                                    <i class="ph-bold ph-caret-down text-lg"></i>
                                </div>
                            </form>
                        @endif

                        <a href="{{ Route::has('reports.classReport') ? route('reports.classReport', ['class_id' => $class->id]) : '#' }}" class="w-full sm:w-auto px-5 py-3 bg-white text-elevate-dark font-bold rounded-xl hover:bg-elevate-soft transition-all shadow-lg flex items-center justify-center gap-2 group active:scale-95">
                            <i class="ph-bold ph-printer group-hover:scale-110 transition-transform"></i> Rekap PDF
                        </a>
                    </div>
                </div>
            </div>

            {{-- Statistik Cepat --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-blue-200 transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl shrink-0"><i class="ph-fill ph-users-three"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                        <p class="text-2xl font-black text-slate-800">{{ $stats['total_students'] }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-emerald-200 transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl shrink-0"><i class="ph-fill ph-star"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Poin Karakter</p>
                        <p class="text-2xl font-black text-emerald-600">+{{ $stats['total_merits'] }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-rose-200 transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-2xl shrink-0"><i class="ph-fill ph-warning-circle"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelanggaran</p>
                        <p class="text-2xl font-black text-rose-600">-{{ $stats['total_violations'] }}</p>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-4 hover:border-amber-200 transition-colors">
                    <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-2xl shrink-0"><i class="ph-fill ph-calendar-x"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Alpa (Bulan Ini)</p>
                        <p class="text-2xl font-black text-amber-600">{{ $stats['alfa_count'] }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- KIRI: EARLY WARNING LIST (Siswa Perlu Perhatian) --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-rose-100 overflow-hidden h-full relative">
                        <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                            <i class="ph-fill ph-siren text-9xl text-rose-500"></i>
                        </div>
                        
                        <div class="p-6 border-b border-rose-50 bg-rose-50/50 flex justify-between items-center relative z-10">
                            <h3 class="font-black text-rose-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-siren text-rose-500 animate-pulse"></i> Perlu Perhatian Khusus
                            </h3>
                            <span class="bg-rose-100 text-rose-700 text-xs font-bold px-3 py-1 rounded-full">{{ $warningStudents->count() }} Terdeteksi</span>
                        </div>
                        
                        <div class="p-6 relative z-10">
                            @if($warningStudents->count() > 0)
                                <div class="space-y-4">
                                    @foreach($warningStudents as $ws)
                                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-rose-200 hover:bg-rose-50/30 transition-colors bg-white shadow-sm">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-full bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                                    @if($ws->photo)
                                                        <img src="{{ asset('storage/' . $ws->photo) }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center font-bold text-slate-400">{{ substr($ws->name, 0, 1) }}</div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h4 class="font-bold text-slate-800">{{ $ws->name }}</h4>
                                                    <div class="flex flex-wrap gap-2 mt-1.5">
                                                        @if($ws->violation_points >= 50)
                                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-rose-100 text-rose-700 border border-rose-200"><i class="ph-bold ph-minus"></i> {{ $ws->violation_points }} Poin</span>
                                                        @endif
                                                        @if($ws->alfa_count >= 3)
                                                            <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-amber-100 text-amber-700 border border-amber-200"><i class="ph-bold ph-calendar-x"></i> {{ $ws->alfa_count }}x Alpa</span>
                                                        @endif
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">{{ $ws->issue }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <a href="{{ route('students.show', $ws->id) }}" class="w-10 h-10 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 hover:text-blue-600 hover:border-blue-300 hover:bg-blue-50 shadow-sm transition-all shrink-0" title="Buku Induk & Detail">
                                                <i class="ph-bold ph-arrow-right"></i>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-emerald-50 rounded-full mb-4 ring-8 ring-emerald-50/50">
                                        <i class="ph-fill ph-check-circle text-5xl text-emerald-500"></i>
                                    </div>
                                    <p class="font-bold text-slate-700 text-lg">Aman Terkendali!</p>
                                    <p class="text-sm text-slate-500 max-w-sm mx-auto mt-1">Sistem tidak mendeteksi adanya siswa dengan tingkat pelanggaran tinggi atau absensi kritis di kelas Anda.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- KANAN: LEADERBOARD PRESTASI --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-crown text-amber-500"></i> Bintang Kelas
                            </h3>
                        </div>
                        <div class="p-6">
                            @if($topStudents->count() > 0)
                                <div class="space-y-4">
                                    @foreach($topStudents as $index => $ts)
                                        <div class="flex items-center gap-3 group">
                                            <div class="w-6 font-black {{ $index == 0 ? 'text-amber-500 text-xl' : ($index == 1 ? 'text-slate-400 text-lg' : ($index == 2 ? 'text-amber-700 text-lg' : 'text-slate-300')) }} text-center">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1 flex items-center justify-between p-3 rounded-2xl bg-slate-50 border border-slate-100 group-hover:bg-white group-hover:border-emerald-200 transition-colors shadow-sm">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 overflow-hidden shrink-0">
                                                        @if($ts->photo)
                                                            <img src="{{ asset('storage/' . $ts->photo) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center font-bold text-slate-400 text-xs">{{ substr($ts->name, 0, 1) }}</div>
                                                        @endif
                                                    </div>
                                                    <h4 class="font-bold text-sm text-slate-700 truncate max-w-[110px]" title="{{ $ts->name }}">{{ $ts->name }}</h4>
                                                </div>
                                                <span class="text-[10px] font-black text-emerald-700 bg-emerald-100 px-2 py-1.5 rounded-lg border border-emerald-200 shadow-sm">+{{ $ts->merit_points }} Poin</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-10">
                                    <i class="ph-duotone ph-star text-4xl text-slate-300 mb-2 block"></i>
                                    <p class="text-sm font-bold text-slate-500">Belum ada data prestasi tercatat bulan ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>