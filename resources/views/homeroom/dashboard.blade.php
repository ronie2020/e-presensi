<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <div class="py-8 bg-slate-50 min-h-screen font-sans relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Panel --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 md:p-10 mb-8 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/20 overflow-hidden border border-white/60">
                
                {{-- Abstract Shapes Ornaments --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/60 backdrop-blur-md text-xs font-bold uppercase tracking-widest text-[#0d52a1] border border-white/50 mb-4 shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher text-lg"></i> Dashboard Wali Kelas
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2 text-[#0d52a1]">Kelas {{ $class->name ?? 'IX-A' }}</h1>
                        <p class="font-medium text-sm md:text-base max-w-2xl text-[#2c3f61]/80">Pantau statistik kedisiplinan, literasi, pembiasaan, dan kehadiran anak didik Anda secara komprehensif.</p>
                    </div>
                                        
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        {{-- Filter Periode Waktu --}}
                        <form action="{{ route('homeroom.dashboard') }}" method="GET" class="relative w-full sm:w-auto">
                            @if(request('class_id'))
                                <input type="hidden" name="class_id" value="{{ request('class_id') }}">
                            @endif
                            <select name="period" onchange="this.form.submit()" class="w-full pl-5 pr-12 py-3 bg-white/60 hover:bg-white/80 backdrop-blur-md text-[#0d52a1] border border-white/50 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-[#56bbf1] cursor-pointer font-bold shadow-sm transition-all">
                                <option value="this_month" {{ request('period', 'this_month') == 'this_month' ? 'selected' : '' }} class="text-slate-800 font-semibold">Bulan Ini</option>
                                <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }} class="text-slate-800 font-semibold">Bulan Lalu</option>
                                <option value="semester_1" {{ request('period') == 'semester_1' ? 'selected' : '' }} class="text-slate-800 font-semibold">Semester Ganjil</option>
                                <option value="semester_2" {{ request('period') == 'semester_2' ? 'selected' : '' }} class="text-slate-800 font-semibold">Semester Genap</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#0d52a1]">
                                <i class="ph-bold ph-calendar-blank text-lg"></i>
                            </div>
                        </form>

                         @if(isset($isAdminOrKepsek) && $isAdminOrKepsek && isset($allClasses))
                            <form action="{{ route('homeroom.dashboard') }}" method="GET" class="relative w-full sm:w-auto">
                                @if(request('period'))
                                    <input type="hidden" name="period" value="{{ request('period') }}">
                                @endif
                                <select name="class_id" onchange="this.form.submit()" class="w-full pl-5 pr-12 py-3 bg-white/60 hover:bg-white/80 backdrop-blur-md text-[#0d52a1] border border-white/50 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-[#56bbf1] cursor-pointer font-bold shadow-sm transition-all">
                                    @foreach($allClasses as $c)
                                        <option value="{{ $c->id }}" {{ isset($class) && $class->id == $c->id ? 'selected' : '' }} class="text-slate-800 font-semibold">
                                            Pantau Kelas {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#0d52a1]">
                                    <i class="ph-bold ph-caret-down text-lg"></i>
                                </div>
                            </form>
                        @endif

                        <a href="{{ route('homeroom.print', ['class_id' => $class->id ?? '', 'period' => request('period', 'this_month')]) }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-[#0d52a1] text-white font-bold rounded-xl hover:bg-[#0a4282] transition-all shadow-lg shadow-[#0d52a1]/20 flex items-center justify-center gap-2 group active:scale-95 border border-[#0d52a1]/20">
                            <i class="ph-bold ph-printer group-hover:scale-110 transition-transform"></i> Rekap PDF
                        </a>
                    </div>
                </div>
            </div>

            {{-- 7 Statistik Cepat (Grid disesuaikan agar rapi menampung 7 kartu) --}}
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
                <!-- Kartu Total Siswa -->
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-blue-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="ph-fill ph-users-three"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                        <p class="text-xl font-black text-slate-800">{{ $stats['total_students'] ?? 32 }}</p>
                    </div>
                </div>
                
                <!-- Kartu Poin Karakter -->
                <div onclick="openDrilldownModal('merits')" class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-emerald-400 hover:shadow-md cursor-pointer transition-all transform hover:-translate-y-1 group relative">
                    <div class="absolute -top-3 right-0 bg-emerald-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">Klik detail</div>
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl group-hover:bg-emerald-100 transition-colors"><i class="ph-fill ph-star"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-emerald-600 transition-colors">Poin Karakter</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-emerald-600">+{{ $stats['total_merits'] ?? 0 }}</p>
                            @if(isset($trends['merits']))
                                @php $isUp = $trends['merits'] >= 0; @endphp
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border {{ $isUp ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}" title="{{ $isUp ? 'Naik' : 'Turun' }} dari periode sebelumnya">
                                    <i class="ph-bold {{ $isUp ? 'ph-arrow-up-right' : 'ph-arrow-down-right' }} mr-0.5"></i> {{ abs($trends['merits']) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kartu Literasi -->
                <div onclick="openDrilldownModal('literacy')" class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-purple-400 hover:shadow-md cursor-pointer transition-all transform hover:-translate-y-1 group relative">
                    <div class="absolute -top-3 right-0 bg-purple-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">Klik detail</div>
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl group-hover:bg-purple-100 transition-colors"><i class="ph-fill ph-books"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-purple-600 transition-colors">Literasi</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-purple-600">{{ $stats['total_literacy'] ?? 0 }}</p>
                            @if(isset($trends['literacy']))
                                @php $isUp = $trends['literacy'] >= 0; @endphp
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border {{ $isUp ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}" title="{{ $isUp ? 'Naik' : 'Turun' }} dari periode sebelumnya">
                                    <i class="ph-bold {{ $isUp ? 'ph-arrow-up-right' : 'ph-arrow-down-right' }} mr-0.5"></i> {{ abs($trends['literacy']) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kartu Jurnal Habit -->
                <div onclick="openDrilldownModal('habits')" class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-teal-400 hover:shadow-md cursor-pointer transition-all transform hover:-translate-y-1 group relative">
                    <div class="absolute -top-3 right-0 bg-teal-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">Klik detail</div>
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl group-hover:bg-teal-100 transition-colors"><i class="ph-fill ph-list-checks"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-teal-600 transition-colors">Jurnal Habit</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-teal-600">{{ $stats['total_habits'] ?? 0 }}</p>
                            @if(isset($trends['habits']))
                                @php $isUp = $trends['habits'] >= 0; @endphp
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border {{ $isUp ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}" title="{{ $isUp ? 'Naik' : 'Turun' }} dari periode sebelumnya">
                                    <i class="ph-bold {{ $isUp ? 'ph-arrow-up-right' : 'ph-arrow-down-right' }} mr-0.5"></i> {{ abs($trends['habits']) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Kartu Total Alpa -->
                <div onclick="openDrilldownModal('alpa')" class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-amber-400 hover:shadow-md cursor-pointer transition-all transform hover:-translate-y-1 group relative">
                    <div class="absolute -top-3 right-0 bg-amber-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">Klik detail</div>
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl group-hover:bg-amber-100 transition-colors"><i class="ph-fill ph-calendar-x"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-amber-600 transition-colors">Total Alpa</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-amber-600">{{ $stats['alfa_count'] ?? 0 }}</p>
                            @if(isset($trends['alfa']))
                                @php $isGood = $trends['alfa'] <= 0; @endphp
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border {{ $isGood ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}" title="{{ $isGood ? 'Menurun (Meningkatnya Kehadiran)' : 'Meningkat (Banyak Alpa)' }}">
                                    <i class="ph-bold {{ $isGood ? 'ph-arrow-down-right' : 'ph-arrow-up-right' }} mr-0.5"></i> {{ abs($trends['alfa']) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kartu Terlambat (BARU) -->
                <div onclick="openDrilldownModal('late')" class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-cyan-400 hover:shadow-md cursor-pointer transition-all transform hover:-translate-y-1 group relative">
                    <div class="absolute -top-3 right-0 bg-cyan-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">Klik detail</div>
                    <div class="w-12 h-12 rounded-2xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-xl group-hover:bg-cyan-100 transition-colors"><i class="ph-fill ph-clock"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-cyan-600 transition-colors">Terlambat</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-cyan-600">{{ $stats['late_count'] ?? 0 }}</p>
                            @if(isset($trends['late']))
                                @php $isGood = $trends['late'] <= 0; @endphp
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border {{ $isGood ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}" title="{{ $isGood ? 'Menurun (Makin Disiplin)' : 'Meningkat (Banyak Terlambat)' }}">
                                    <i class="ph-bold {{ $isGood ? 'ph-arrow-down-right' : 'ph-arrow-up-right' }} mr-0.5"></i> {{ abs($trends['late']) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Kartu Pelanggaran -->
                <div onclick="openDrilldownModal('violations')" class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-rose-400 hover:shadow-md cursor-pointer transition-all transform hover:-translate-y-1 group relative">
                    <div class="absolute -top-3 right-0 bg-rose-500 text-white text-[9px] font-bold px-2 py-0.5 rounded-full shadow-sm opacity-0 group-hover:opacity-100 transition-opacity">Klik detail</div>
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl group-hover:bg-rose-100 transition-colors"><i class="ph-fill ph-warning-circle"></i></div>
                    <div class="flex flex-col items-center">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider group-hover:text-rose-600 transition-colors">Pelanggaran</p>
                        <div class="flex items-center gap-1.5">
                            <p class="text-xl font-black text-rose-600">-{{ $stats['total_violations'] ?? 0 }}</p>
                            @if(isset($trends['violations']))
                                @php $isGood = $trends['violations'] <= 0; @endphp
                                <span class="text-[9px] font-bold px-1.5 py-0.5 rounded flex items-center border {{ $isGood ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-rose-50 text-rose-600 border-rose-100' }}" title="{{ $isGood ? 'Menurun (Lebih Disiplin)' : 'Meningkat (Banyak Pelanggaran)' }}">
                                    <i class="ph-bold {{ $isGood ? 'ph-arrow-down-right' : 'ph-arrow-up-right' }} mr-0.5"></i> {{ abs($trends['violations']) }}%
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                
                {{-- KIRI: EARLY WARNING LIST --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-rose-100 overflow-hidden h-full relative">
                        <div class="absolute top-0 right-0 p-6 opacity-5 pointer-events-none">
                            <i class="ph-fill ph-siren text-9xl text-rose-500"></i>
                        </div>
                        
                        <div class="p-6 border-b border-rose-50 bg-rose-50/50 flex justify-between items-center relative z-10">
                            <h3 class="font-black text-rose-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-siren text-rose-500 animate-pulse"></i> Perlu Perhatian Khusus
                            </h3>
                            @if(isset($warningStudents))
                                <span class="bg-rose-100 text-rose-700 text-xs font-bold px-3 py-1 rounded-full">{{ $warningStudents->count() }} Terdeteksi</span>
                            @endif
                        </div>
                        
                        <div class="p-6 relative z-10">
                            @if(isset($warningStudents) && $warningStudents->count() > 0)
                                <div class="space-y-4">
                                    @foreach($warningStudents as $ws)
                                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-rose-200 hover:bg-rose-50/30 transition-colors bg-white shadow-sm">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-full bg-slate-100 overflow-hidden shrink-0 border border-slate-200">
                                                    @if($ws->photo)
                                                        <img src="{{ asset('storage/' . $ws->photo) }}" alt="Foto profil {{ $ws->name }}" class="w-full h-full object-cover">
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
                                                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">{{ $ws->issue ?? 'Perlu pantauan' }}</span>
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
                                <i class="ph-fill ph-crown text-amber-500"></i> Bintang Karakter
                            </h3>
                        </div>
                        <div class="p-6">
                            @if(isset($topStudents) && $topStudents->count() > 0)
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

            {{-- BARIS KEDUA: LITERASI & PEMBIASAAN --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                
                {{-- LEADERBOARD LITERASI --}}
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-purple-100 overflow-hidden">
                    <div class="p-6 border-b border-purple-50 bg-purple-50/50 flex justify-between items-center">
                        <h3 class="font-black text-purple-800 text-lg flex items-center gap-2">
                            <i class="ph-fill ph-books text-purple-500"></i> Duta Literasi Kelas (Top 5)
                        </h3>
                    </div>
                    <div class="p-6">
                        @if(isset($topLiteracy) && $topLiteracy->count() > 0)
                            <div class="space-y-4">
                                @foreach($topLiteracy as $index => $tl)
                                    <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:border-purple-200 transition-colors shadow-sm">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 font-black text-slate-300 text-center">{{ $index + 1 }}</div>
                                            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 overflow-hidden shrink-0">
                                                @if($tl->photo)
                                                    <img src="{{ asset('storage/' . $tl->photo) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center font-bold text-slate-400">{{ substr($tl->name, 0, 1) }}</div>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-slate-700">{{ $tl->name }}</h4>
                                        </div>
                                        <span class="text-[10px] font-black text-purple-700 bg-purple-100 px-3 py-1.5 rounded-xl border border-purple-200">{{ $tl->count }} Buku</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="ph-duotone ph-book-open-text text-4xl text-slate-300 mb-2 block"></i>
                                <p class="text-sm font-bold text-slate-500">Belum ada siswa yang mengisi Jurnal Literasi bulan ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- LEADERBOARD PEMBIASAAN --}}
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-teal-100 overflow-hidden">
                    <div class="p-6 border-b border-teal-50 bg-teal-50/50 flex justify-between items-center">
                        <h3 class="font-black text-teal-800 text-lg flex items-center gap-2">
                            <i class="ph-fill ph-list-checks text-teal-500"></i> Terajin Lapor Pembiasaan
                        </h3>
                    </div>
                    <div class="p-6">
                        @if(isset($topHabits) && $topHabits->count() > 0)
                            <div class="space-y-4">
                                @foreach($topHabits as $index => $th)
                                    <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-100 bg-slate-50 hover:bg-white hover:border-teal-200 transition-colors shadow-sm">
                                        <div class="flex items-center gap-4">
                                            <div class="w-8 font-black text-slate-300 text-center">{{ $index + 1 }}</div>
                                            <div class="w-10 h-10 rounded-full bg-white border border-slate-200 overflow-hidden shrink-0">
                                                @if($th->photo)
                                                    <img src="{{ asset('storage/' . $th->photo) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center font-bold text-slate-400">{{ substr($th->name, 0, 1) }}</div>
                                                @endif
                                            </div>
                                            <h4 class="font-bold text-slate-700">{{ $th->name }}</h4>
                                        </div>
                                        <span class="text-[10px] font-black text-teal-700 bg-teal-100 px-3 py-1.5 rounded-xl border border-teal-200">{{ $th->count }} Hari</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i class="ph-duotone ph-mosque text-4xl text-slate-300 mb-2 block"></i>
                                <p class="text-sm font-bold text-slate-500">Belum ada siswa yang mengisi Jurnal Pembiasaan Keagamaan bulan ini.</p>
                            </div>
                        @endif
                    </div>
                </div>

            </div>

            {{-- NOMINASI PENGHARGAAN SISWA TELADAN --}}
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-[2.5rem] shadow-lg border border-amber-200 overflow-hidden relative group">
                <div class="absolute top-0 right-0 p-8 opacity-10 pointer-events-none group-hover:scale-110 transition-transform duration-700">
                    <i class="ph-fill ph-trophy text-9xl text-amber-500"></i>
                </div>
                
                <div class="p-8 border-b border-amber-200/50 bg-white/50 backdrop-blur-sm relative z-10">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h2 class="font-black text-amber-900 text-2xl flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shadow-lg">
                                    <i class="ph-fill ph-trophy text-xl"></i>
                                </div>
                                Nominasi Siswa Teladan (Top 10)
                            </h2>
                            <p class="text-amber-700/80 text-sm mt-2 font-medium">Sistem merekomendasikan 10 siswa berdasarkan perhitungan gabungan: <strong>Kelengkapan Tugas (Habit & Literasi), Sikap Positif, Tingkat Kehadiran, dan Akademik</strong>.</p>
                        </div>
                        <div class="shrink-0 bg-white px-4 py-2 rounded-xl border border-amber-200 shadow-sm text-xs font-bold text-amber-800 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Smart Evaluation System
                        </div>
                    </div>
                </div>

                <div class="p-8 relative z-10">
                    @if(isset($awardNominees) && $awardNominees->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 xl:grid-cols-2 gap-4">
                            @foreach($awardNominees as $index => $nominee)
                                <div class="flex items-center gap-4 p-4 rounded-2xl bg-white border border-amber-100 shadow-sm hover:shadow-md hover:border-amber-300 transition-all">
                                    <div class="shrink-0 w-12 h-12 rounded-full {{ $index == 0 ? 'bg-gradient-to-br from-yellow-300 to-yellow-500 text-yellow-900 ring-4 ring-yellow-100' : ($index == 1 ? 'bg-gradient-to-br from-slate-200 to-slate-400 text-slate-800' : ($index == 2 ? 'bg-gradient-to-br from-orange-300 to-orange-500 text-orange-950' : 'bg-amber-100 text-amber-700')) }} flex items-center justify-center font-black text-xl shadow-inner">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-slate-800 truncate text-lg">{{ $nominee->name }}</h4>
                                        <div class="flex flex-wrap gap-2 mt-1.5">
                                            @if($nominee->alfa_count == 0 && $nominee->violation_points == 0)
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center gap-1" title="Tidak pernah Alpa dan tidak ada Pelanggaran">
                                                    <i class="ph-bold ph-shield-check"></i> Catatan Sempurna
                                                </span>
                                            @else
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-700 border border-blue-200 flex items-center gap-1" title="Sikap Bersih = Poin Prestasi - Poin Pelanggaran">
                                                    <i class="ph-bold ph-star"></i> Sikap: +{{ $nominee->net_discipline ?? 0 }} Pts
                                                </span>
                                            @endif
                                            
                                            @if(isset($nominee->task_score) && $nominee->task_score > 0)
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-100 text-purple-700 border border-purple-200 flex items-center gap-1" title="Total akumulasi poin dari Habit & Literasi">
                                                    <i class="ph-bold ph-check-square-offset"></i> Tugas: {{ $nominee->task_score }} Pts
                                                </span>
                                            @endif

                                            @if(isset($nominee->academic_score) && $nominee->academic_score > 0)
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 flex items-center gap-1">
                                                    <i class="ph-bold ph-graduation-cap"></i> Akad: {{ $nominee->academic_score }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="shrink-0 text-right">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5" title="Gabungan Skor Kehadiran, Sikap, Tugas, dan Akademik">Total Skor</p>
                                        <p class="text-2xl font-black text-amber-600">{{ isset($nominee->total_score) ? number_format($nominee->total_score, 0) : 0 }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 bg-white/50 rounded-2xl border border-amber-200/50 border-dashed">
                            <i class="ph-duotone ph-magnifying-glass text-5xl text-amber-300 mb-3 block"></i>
                            <p class="font-bold text-amber-800 text-lg">Belum Ada Kandidat Valid</p>
                            <p class="text-sm text-amber-700/70 max-w-md mx-auto mt-1">Sistem belum menemukan siswa dengan nilai dan partisipasi yang cukup untuk direkomendasikan.</p>
                        </div>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- KOMPONEN MODAL DRILL-DOWN (Dinamis & Terhubung Route)    --}}
    {{-- ========================================================= --}}
    <div id="drilldownModal" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-8 hidden opacity-0 transition-opacity duration-300">
        
        <!-- Background Overlay Backdrop -->
        <div onclick="closeDrilldownModal()" class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm cursor-pointer"></div>

        <!-- Modal Content Box -->
        <div class="relative bg-white rounded-[2rem] w-full max-w-2xl max-h-[90vh] shadow-2xl flex flex-col transform scale-95 transition-transform duration-300" id="modalBox">
            
            <!-- Header Modal -->
            <div id="modalHeader" class="flex items-center justify-between p-6 border-b border-slate-100 rounded-t-[2rem]">
                <div class="flex items-center gap-4">
                    <div id="modalIconContainer" class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-inner">
                        <i id="modalIcon" class="ph-fill"></i>
                    </div>
                    <div>
                        <h2 id="modalTitle" class="text-xl font-black text-slate-800">Rincian Data</h2>
                        <p id="modalSubtitle" class="text-xs font-bold mt-0.5">Siswa Kelas {{ $class->name ?? 'IX-A' }}</p>
                    </div>
                </div>
                <button onclick="closeDrilldownModal()" class="w-10 h-10 flex items-center justify-center rounded-full bg-slate-100 text-slate-500 hover:bg-rose-100 hover:text-rose-600 transition-colors">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <!-- Body Modal (Scrollable Content) -->
            <div class="p-6 overflow-y-auto flex-1 custom-scrollbar">
                <div id="modalItemsContainer" class="space-y-4">
                    <!-- Dinonaktifkan sementara / akan diisi via JavaScript -->
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="p-6 border-t border-slate-100 bg-slate-50 rounded-b-[2rem] flex justify-between items-center">
                <p id="modalFooterHint" class="text-xs text-slate-500 font-medium"></p>
                <button onclick="closeDrilldownModal()" class="px-5 py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-700 transition-colors shadow-lg shadow-slate-200">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Script untuk mengontrol interaktivitas & Data Binding --}}
    <script>
        // 1. Parsing Seluruh Data Siswa Kelas ke JSON JS dengan Aman
        const studentsData = @json($mappedStudents ?? []);

        // 2. Base Route dari Laravel untuk Profil Siswa
        const profileRoutePattern = "{{ route('students.show', ':id') }}";

        // Helper untuk memformat dan membersihkan nomor telepon untuk integrasi WhatsApp
        function getWhatsAppUrl(phone, name, detailMessage) {
            if (!phone) return null;
            let formattedPhone = phone.replace(/[^0-9]/g, '');
            if (formattedPhone.startsWith('0')) {
                formattedPhone = '62' + formattedPhone.slice(1);
            }
            const baseText = `Assalamu'alaikum Wr. Wb. Bapak/Ibu Wali Murid dari ${name}. Kami dari pihak sekolah ingin menyampaikan perkembangan anak terkait ${detailMessage}. Mohon kerjasamanya untuk selalu membimbing ananda di rumah. Terima kasih.`;
            return `https://wa.me/${formattedPhone}?text=${encodeURIComponent(baseText)}`;
        }

        // Fungsi Membuka Modal Drill-down dengan data dinamis riil
        function openDrilldownModal(type) {
            const modal = document.getElementById('drilldownModal');
            
            // FIX TERBAIK (TELEPORT)
            if (modal.parentNode !== document.body) {
                document.body.appendChild(modal);
            }

            const modalBox = document.getElementById('modalBox');
            const itemsContainer = document.getElementById('modalItemsContainer');
            
            const modalTitle = document.getElementById('modalTitle');
            const modalSubtitle = document.getElementById('modalSubtitle');
            const modalHeader = document.getElementById('modalHeader');
            const modalIconContainer = document.getElementById('modalIconContainer');
            const modalIcon = document.getElementById('modalIcon');
            const modalFooterHint = document.getElementById('modalFooterHint');

            itemsContainer.innerHTML = '';

            let filteredStudents = [];
            let headerBgClass = '';
            let iconBgClass = '';
            let iconTextClass = '';
            let iconPhClass = '';
            let titleText = '';
            let subtitleText = '';
            let footerHintText = '';
            let valueFormatter = (student) => '';

            switch(type) {
                case 'alpa':
                    filteredStudents = studentsData.filter(s => s.alfa_count > 0).sort((a,b) => b.alfa_count - a.alfa_count);
                    headerBgClass = 'bg-amber-50/50 border-amber-100';
                    iconBgClass = 'bg-amber-100 text-amber-600';
                    iconPhClass = 'ph-calendar-x';
                    titleText = 'Rincian Siswa Alpa';
                    subtitleText = `${filteredStudents.length} Siswa membolos / tanpa keterangan`;
                    footerHintText = 'Segera hubungi orang tua apabila siswa alpa lebih dari 2 hari berturut-turut.';
                    valueFormatter = (s) => `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-1"><i class="ph-bold ph-calendar-x"></i> ${s.alfa_count}x Alpa</span>`;
                    break;
                
                // === TAMBAHAN UNTUK DATA SISWA TERLAMBAT ===
                case 'late':
                    filteredStudents = studentsData.filter(s => s.late_count > 0).sort((a,b) => b.late_count - a.late_count);
                    headerBgClass = 'bg-cyan-50/50 border-cyan-100';
                    iconBgClass = 'bg-cyan-100 text-cyan-600';
                    iconPhClass = 'ph-clock';
                    titleText = 'Rincian Siswa Terlambat';
                    subtitleText = `${filteredStudents.length} Siswa terdeteksi datang terlambat`;
                    footerHintText = 'Kedisiplinan waktu adalah cerminan karakter. Harap berikan teguran atau peringatan secara simpatik.';
                    valueFormatter = (s) => `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-cyan-100 text-cyan-700 border border-cyan-200 flex items-center gap-1"><i class="ph-bold ph-clock"></i> ${s.late_count}x Terlambat</span>`;
                    break;

                case 'violations':
                    filteredStudents = studentsData.filter(s => s.violation_points > 0).sort((a,b) => b.violation_points - a.violation_points);
                    headerBgClass = 'bg-rose-50/50 border-rose-100';
                    iconBgClass = 'bg-rose-100 text-rose-600';
                    iconPhClass = 'ph-warning-circle';
                    titleText = 'Rincian Pelanggaran Siswa';
                    subtitleText = `${filteredStudents.length} Siswa terdeteksi memiliki poin minus`;
                    footerHintText = 'Poin pelanggaran di atas 50 poin memerlukan konseling khusus bersama Guru BK.';
                    valueFormatter = (s) => `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-rose-100 text-rose-700 border border-rose-200 flex items-center gap-1"><i class="ph-bold ph-minus"></i> -${s.violation_points} Poin</span>`;
                    break;

                case 'merits':
                    filteredStudents = studentsData.filter(s => s.merit_points > 0).sort((a,b) => b.merit_points - a.merit_points);
                    headerBgClass = 'bg-emerald-50/50 border-emerald-100';
                    iconBgClass = 'bg-emerald-100 text-emerald-600';
                    iconPhClass = 'ph-star';
                    titleText = 'Rincian Bintang Karakter';
                    subtitleText = `${filteredStudents.length} Siswa berprestasi bulan ini`;
                    footerHintText = 'Berikan apresiasi berkala untuk mempertahankan kedisiplinan positif siswa.';
                    valueFormatter = (s) => `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center gap-1"><i class="ph-bold ph-plus"></i> +${s.merit_points} Pts</span>`;
                    break;

                case 'literacy':
                    filteredStudents = studentsData.filter(s => s.literacy_count > 0).sort((a,b) => b.literacy_count - a.literacy_count);
                    headerBgClass = 'bg-purple-50/50 border-purple-100';
                    iconBgClass = 'bg-purple-100 text-purple-600';
                    iconPhClass = 'ph-books';
                    titleText = 'Rincian Kegiatan Literasi';
                    subtitleText = `${filteredStudents.length} Siswa rajin membaca & mengisi jurnal`;
                    footerHintText = 'Siswa diharapkan merangkum minimal 2 buku setiap bulannya.';
                    valueFormatter = (s) => `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-purple-100 text-purple-700 border border-purple-200 flex items-center gap-1"><i class="ph-bold ph-book-open"></i> ${s.literacy_count} Buku</span>`;
                    break;

                case 'habits':
                    filteredStudents = studentsData.filter(s => s.habits_count > 0).sort((a,b) => b.habits_count - a.habits_count);
                    headerBgClass = 'bg-teal-50/50 border-teal-100';
                    iconBgClass = 'bg-teal-100 text-teal-600';
                    iconPhClass = 'ph-list-checks';
                    titleText = 'Rincian Jurnal Habit';
                    subtitleText = `${filteredStudents.length} Siswa aktif melaporkan pembiasaan harian`;
                    footerHintText = 'Pembiasaan keagamaan mandiri dipantau berkala melalui laporan orang tua.';
                    valueFormatter = (s) => `<span class="text-[10px] font-bold px-2.5 py-1 rounded-lg bg-teal-100 text-teal-700 border border-teal-200 flex items-center gap-1"><i class="ph-bold ph-calendar"></i> ${s.habits_count} Hari</span>`;
                    break;
            }

            modalHeader.className = `flex items-center justify-between p-6 border-b rounded-t-[2rem] ${headerBgClass}`;
            modalIconContainer.className = `w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-inner ${iconBgClass}`;
            modalIcon.className = `ph-fill ${iconPhClass}`;
            modalTitle.innerText = titleText;
            modalSubtitle.innerText = subtitleText;
            modalFooterHint.innerText = footerHintText;

            if (filteredStudents.length > 0) {
                filteredStudents.forEach(student => {
                    const profileUrl = profileRoutePattern.replace(':id', student.id);
                    
                    // TAMBAHAN PESAN WHATSAPP UNTUK KETERLAMBATAN
                    const detailMessageText = type === 'alpa' ? `kehadiran (Alpa sebanyak ${student.alfa_count} hari)` :
                                              type === 'late' ? `kedisiplinan waktu (Terlambat masuk sekolah sebanyak ${student.late_count} kali)` :
                                              type === 'violations' ? `pelanggaran kedisiplinan sekolah (poin minus: ${student.violation_points})` :
                                              type === 'merits' ? `prestasi karakter positif siswa (+${student.merit_points} poin)` : 'pembiasaan tugas sekolah';
                    
                    const waUrl = getWhatsAppUrl(student.parent_phone, student.name, detailMessageText);

                    let waButtonHtml = '';
                    if (waUrl) {
                        waButtonHtml = `
                            <a href="${waUrl}" target="_blank" class="text-[10px] font-bold text-emerald-700 hover:text-white bg-emerald-100 hover:bg-emerald-600 px-3 py-1.5 rounded-lg border border-emerald-200 flex items-center gap-1 transition-all">
                                <i class="ph-fill ph-whatsapp-logo text-xs"></i> Hubungi Ortu
                            </a>
                        `;
                    } else {
                        waButtonHtml = `
                            <button disabled class="text-[10px] font-bold text-slate-400 bg-slate-100 px-3 py-1.5 rounded-lg border border-slate-200 flex items-center gap-1 cursor-not-allowed" title="Nomor telepon orang tua belum diisi">
                                <i class="ph-bold ph-phone-slash text-xs"></i> Tanpa Kontak
                            </button>
                        `;
                    }

                    const itemHtml = `
                        <div class="flex items-center justify-between p-4 rounded-2xl border border-slate-100 hover:border-slate-200 hover:bg-slate-50/50 transition-all bg-white group shadow-sm">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-10 h-10 rounded-full bg-slate-100 overflow-hidden shrink-0 border border-slate-200 flex items-center justify-center font-bold text-slate-400">
                                    ${student.photo ? `<img src="${student.photo}" class="w-full h-full object-cover">` : student.name.charAt(0)}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-bold text-slate-800 truncate text-sm">${student.name}</h4>
                                    <p class="text-[10px] text-slate-400 font-semibold">NISN: ${student.nisn}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3 shrink-0">
                                ${valueFormatter(student)}
                                <div class="flex flex-col sm:flex-row gap-1.5">
                                    <a href="${profileUrl}" class="text-[10px] font-bold text-blue-600 hover:text-white bg-blue-50 hover:bg-blue-600 px-3 py-1.5 rounded-lg border border-blue-100 transition-all text-center">
                                        Buku Induk
                                    </a>
                                    ${waButtonHtml}
                                </div>
                            </div>
                        </div>
                    `;
                    itemsContainer.insertAdjacentHTML('beforeend', itemHtml);
                });
            } else {
                itemsContainer.innerHTML = `
                    <div class="text-center py-12">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-slate-50 rounded-full mb-3 text-slate-300 border border-slate-100">
                            <i class="ph-bold ph-tray text-3xl"></i>
                        </div>
                        <p class="font-bold text-slate-600">Tidak Ada Data</p>
                        <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Sistem tidak mendeteksi adanya catatan siswa untuk kategori ini dalam periode terpilih.</p>
                    </div>
                `;
            }

            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                modalBox.classList.remove('scale-95');
                modalBox.classList.add('scale-100');
            }, 10);

            document.body.style.overflow = 'hidden';
        }

        function closeDrilldownModal() {
            const modal = document.getElementById('drilldownModal');
            const modalBox = document.getElementById('modalBox');
            
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            modalBox.classList.remove('scale-100');
            modalBox.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }, 300);
        }
    </script>
    
    <style>
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
    </style>
</x-app-layout>