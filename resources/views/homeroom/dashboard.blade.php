<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <div class="py-8 bg-slate-50 min-h-screen font-sans">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Panel (New Fresh Light Theme) --}}
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
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2 text-[#0d52a1]">Kelas {{ $class->name }}</h1>
                        <p class="font-medium text-sm md:text-base max-w-2xl text-[#2c3f61]/80">Pantau statistik kedisiplinan, literasi, pembiasaan, dan kehadiran anak didik Anda secara komprehensif.</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        @if(isset($isAdminOrKepsek) && $isAdminOrKepsek && isset($allClasses))
                            <form action="{{ route('homeroom.dashboard') }}" method="GET" class="relative w-full sm:w-auto">
                                <select name="class_id" onchange="this.form.submit()" class="w-full pl-5 pr-12 py-3 bg-white/60 hover:bg-white/80 backdrop-blur-md text-[#0d52a1] border border-white/50 rounded-xl appearance-none focus:outline-none focus:ring-2 focus:ring-[#56bbf1] cursor-pointer font-bold shadow-sm transition-all">
                                    @foreach($allClasses as $c)
                                        <option value="{{ $c->id }}" {{ $class->id == $c->id ? 'selected' : '' }} class="text-slate-800 font-semibold">
                                            Pantau Kelas {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-[#0d52a1]">
                                    <i class="ph-bold ph-caret-down text-lg"></i>
                                </div>
                            </form>
                        @endif

                        <a href="{{ route('homeroom.print', ['class_id' => $class->id]) }}" target="_blank" class="w-full sm:w-auto px-6 py-3 bg-[#0d52a1] text-white font-bold rounded-xl hover:bg-[#0a4282] transition-all shadow-lg shadow-[#0d52a1]/20 flex items-center justify-center gap-2 group active:scale-95 border border-[#0d52a1]/20">
                            <i class="ph-bold ph-printer group-hover:scale-110 transition-transform"></i> Rekap PDF
                        </a>
                    </div>
                </div>
            </div>

            {{-- 6 Statistik Cepat --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-blue-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center text-xl"><i class="ph-fill ph-users-three"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Siswa</p>
                        <p class="text-xl font-black text-slate-800">{{ $stats['total_students'] }}</p>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-emerald-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl"><i class="ph-fill ph-star"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Poin Karakter</p>
                        <p class="text-xl font-black text-emerald-600">+{{ $stats['total_merits'] }}</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-purple-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-purple-50 text-purple-600 flex items-center justify-center text-xl"><i class="ph-fill ph-books"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Literasi Bulan Ini</p>
                        <p class="text-xl font-black text-purple-600">{{ $stats['total_literacy'] }}</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-teal-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center text-xl"><i class="ph-fill ph-list-checks"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Jurnal Pembiasaan</p>
                        <p class="text-xl font-black text-teal-600">{{ $stats['total_habits'] }}</p>
                    </div>
                </div>
                
                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-amber-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl"><i class="ph-fill ph-calendar-x"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Total Alpa</p>
                        <p class="text-xl font-black text-amber-600">{{ $stats['alfa_count'] }}</p>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm flex flex-col items-center text-center gap-2 hover:border-rose-200 transition-colors">
                    <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl"><i class="ph-fill ph-warning-circle"></i></div>
                    <div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Pelanggaran</p>
                        <p class="text-xl font-black text-rose-600">-{{ $stats['total_violations'] }}</p>
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

                {{-- KANAN: LEADERBOARD PRESTASI (KARAKTER) --}}
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden h-full">
                        <div class="p-6 border-b border-slate-50 bg-slate-50/50 flex justify-between items-center">
                            <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                                <i class="ph-fill ph-crown text-amber-500"></i> Bintang Karakter
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
                        @if($topLiteracy->count() > 0)
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

                {{-- LEADERBOARD PEMBIASAAN (HABITS) --}}
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-teal-100 overflow-hidden">
                    <div class="p-6 border-b border-teal-50 bg-teal-50/50 flex justify-between items-center">
                        <h3 class="font-black text-teal-800 text-lg flex items-center gap-2">
                            <i class="ph-fill ph-list-checks text-teal-500"></i> Terajin Lapor Pembiasaan
                        </h3>
                    </div>
                    <div class="p-6">
                        @if($topHabits->count() > 0)
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

            {{-- ================================================================= --}}
            {{-- FITUR BARU: NOMINASI PENGHARGAAN SISWA TELADAN (TOP 10)           --}}
            {{-- ================================================================= --}}
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-[2.5rem] shadow-lg border border-amber-200 overflow-hidden relative group">
                <!-- Ornamen Latar -->
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
                                    
                                    {{-- Ranking Badge --}}
                                    <div class="shrink-0 w-12 h-12 rounded-full {{ $index == 0 ? 'bg-gradient-to-br from-yellow-300 to-yellow-500 text-yellow-900 ring-4 ring-yellow-100' : ($index == 1 ? 'bg-gradient-to-br from-slate-200 to-slate-400 text-slate-800' : ($index == 2 ? 'bg-gradient-to-br from-orange-300 to-orange-500 text-orange-950' : 'bg-amber-100 text-amber-700')) }} flex items-center justify-center font-black text-xl shadow-inner">
                                        #{{ $index + 1 }}
                                    </div>
                                    
                                    {{-- Foto & Nama --}}
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-bold text-slate-800 truncate text-lg">{{ $nominee->name }}</h4>
                                        
                                        <div class="flex flex-wrap gap-2 mt-1.5">
                                            {{-- Badge Spesial: Sempurna vs Toleransi --}}
                                            @if($nominee->alfa_count == 0 && $nominee->violation_points == 0)
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-700 border border-emerald-200 flex items-center gap-1" title="Tidak pernah Alpa dan tidak ada Pelanggaran">
                                                    <i class="ph-bold ph-shield-check"></i> Catatan Sempurna
                                                </span>
                                            @else
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-blue-100 text-blue-700 border border-blue-200 flex items-center gap-1" title="Sikap Bersih = Poin Prestasi - Poin Pelanggaran">
                                                    <i class="ph-bold ph-star"></i> Sikap: +{{ $nominee->net_discipline }} Pts
                                                </span>
                                            @endif
                                            
                                            {{-- Badge Tugas --}}
                                            @if($nominee->task_score > 0)
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-purple-100 text-purple-700 border border-purple-200 flex items-center gap-1" title="Total akumulasi poin dari Habit & Literasi">
                                                    <i class="ph-bold ph-check-square-offset"></i> Tugas: {{ $nominee->task_score }} Pts
                                                </span>
                                            @endif

                                            {{-- Badge Akademik --}}
                                            @if($nominee->academic_score > 0)
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200 flex items-center gap-1">
                                                    <i class="ph-bold ph-graduation-cap"></i> Akad: {{ $nominee->academic_score }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    {{-- Skor Akhir & Tombol --}}
                                    <div class="shrink-0 text-right">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5" title="Gabungan Skor Kehadiran, Sikap, Tugas, dan Akademik">Total Skor</p>
                                        <p class="text-2xl font-black text-amber-600">{{ number_format($nominee->total_score, 0) }}</p>
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
</x-app-layout>