<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 font-sans text-slate-800 pb-32" x-data="{ 
        activeTab: '{{ request('activeTab', 'hadir') }}',
        reportType: '{{ request('report_type', 'daily') }}' 
    }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 no-print">
                {{-- Kiri: Judul & Tanggal Aktif --}}
                {{-- UPDATED: Menggunakan Dark Blue Gradient dari Navigation --}}
                <div class="bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 rounded-[2rem] p-6 lg:p-8 text-white shadow-xl shadow-blue-900/30 relative overflow-hidden flex flex-col justify-between min-h-[200px] border border-white/10">
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-blue-500/20 rounded-full blur-2xl"></div>
                    <div class="absolute -left-10 bottom-0 w-32 h-32 bg-blue-400/10 rounded-full blur-xl"></div>
                     <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                    
                    <div class="relative z-10">
                        <h1 class="text-xl lg:text-2xl font-extrabold mb-1 tracking-tight text-white">Rekap Absensi</h1>
                        <p class="text-blue-300 text-sm font-medium tracking-wide">Kehadiran siswa harian.</p>
                    </div>

                    <div class="relative z-10 mt-6">
                        <div class="inline-flex items-center gap-2 bg-slate-900/40 backdrop-blur-md border border-white/10 px-4 py-2 rounded-xl text-sm font-bold shadow-sm">
                            <i class="ph-bold ph-calendar-blank text-blue-300"></i>
                            <span>{{ $selectedDate_db->translatedFormat('d F Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Filter & Tools --}}
                <div class="lg:col-span-2 bg-white rounded-[2rem] p-6 lg:p-8 border border-slate-100 shadow-sm relative overflow-hidden">
                    <div class="absolute inset-0 opacity-40 bg-[radial-gradient(#cbd5e1_1px,transparent_1px)] [background-size:20px_20px]"></div>
                    
                    <div class="relative z-10">
                         <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                            <h2 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                <span class="w-1.5 h-6 bg-blue-900 rounded-full"></span>
                                Filter & Laporan
                            </h2>
                            <div class="bg-slate-100 p-1 rounded-xl flex w-full md:w-auto">
                                <button @click="reportType = 'daily'" :class="reportType === 'daily' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all">Harian</button>
                                <button @click="reportType = 'weekly'" :class="reportType === 'weekly' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all">Mingguan</button>
                                <button @click="reportType = 'monthly'" :class="reportType === 'monthly' ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:text-slate-700'" class="flex-1 px-4 py-2 rounded-lg text-xs font-bold transition-all">Bulanan</button>
                            </div>
                        </div>

                        <form action="{{ route('reports.daily') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full">
                            <input type="hidden" name="report_type" x-model="reportType">
                            <input type="hidden" name="activeTab" x-model="activeTab">

                            <div class="flex-1 w-full">
                                <div x-show="reportType === 'daily'">
                                    <input type="date" name="date" value="{{ request('date', $selectedDate_db->format('Y-m-d')) }}" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900">
                                </div>
                                <div x-show="reportType === 'weekly'" style="display: none;">
                                    <input type="week" name="week" value="{{ request('week') }}" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900">
                                </div>
                                <div x-show="reportType === 'monthly'" style="display: none;">
                                    <input type="month" name="month" value="{{ request('month') }}" 
                                           class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold h-11 text-sm px-4 focus:ring-blue-900 focus:border-blue-900">
                                </div>
                            </div>

                            <div class="flex gap-2 w-full md:w-auto">
                                <button type="submit" class="flex-1 md:flex-none bg-blue-900 hover:bg-slate-900 text-white px-5 rounded-xl h-11 font-bold text-sm shadow-lg flex items-center justify-center gap-2 transition-all">
                                    <i class="ph-bold ph-magnifying-glass"></i> <span class="md:hidden">Tampilkan</span>
                                </button>
                                
                                <div class="w-px h-11 bg-slate-200 hidden md:block"></div>

                                <a href="{{ route('reports.printDaily', request()->all()) }}" target="_blank" class="flex-1 md:flex-none bg-white border border-slate-200 text-slate-600 hover:text-blue-900 hover:border-blue-900 px-5 rounded-xl h-11 font-bold text-sm flex items-center justify-center gap-2 transition-colors">
                                    <i class="ph-bold ph-printer text-lg"></i> <span class="md:hidden">Cetak</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-bold text-sm flex justify-between items-center shadow-sm no-print">
                    <div class="flex items-center gap-2"><i class="ph-fill ph-check-circle text-lg"></i> <span>{{ session('success') }}</span></div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- KPI CARDS --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-6 mb-8">
                <div class="bg-white p-5 lg:p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition-all">
                    <div class="min-w-0">
                         <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Hadir</p>
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-800 truncate">{{ $hadirCount }}</h3>
                        @if($terlambatCount > 0)
                            <div class="mt-2 inline-block px-2 py-0.5 bg-amber-50 text-amber-700 rounded text-[10px] font-bold border border-amber-100">
                                {{ $terlambatCount }} Terlambat
                            </div>
                        @endif
                    </div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl lg:text-3xl group-hover:scale-110 transition-transform shrink-0"><i class="ph-fill ph-check-circle"></i></div>
                </div>

                <div class="bg-white p-5 lg:p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition-all">
                     <div class="min-w-0">
                         <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Izin / Alfa</p>
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-800 truncate">{{ $sakitCount + $izinCount + $alfaCount }}</h3>
                    </div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center text-2xl lg:text-3xl group-hover:scale-110 transition-transform shrink-0"><i class="ph-fill ph-warning-circle"></i></div>
                </div>

                <div class="bg-white p-5 lg:p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between group hover:shadow-md transition-all">
                     <div class="min-w-0">
                         <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Belum Absen</p>
                        <h3 class="text-3xl lg:text-4xl font-black text-slate-800 truncate">{{ $belumAbsenList->count() }}</h3>
                    </div>
                    <div class="w-14 h-14 lg:w-16 lg:h-16 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center text-2xl lg:text-3xl group-hover:scale-110 transition-transform shrink-0"><i class="ph-fill ph-x-circle"></i></div>
                </div>
            </div>

            {{-- LIST DATA UTAMA --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden min-h-[500px]">
                
                {{-- Tabs Header (DIBUAT WRAP DI MOBILE) --}}
                <div class="flex flex-wrap md:flex-nowrap border-b border-slate-100 bg-slate-50/50 p-2 gap-2 sticky top-0 z-20 no-print">
                    <button @click="activeTab = 'hadir'" :class="activeTab === 'hadir' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-6 rounded-xl text-sm font-bold whitespace-nowrap transition-all">Hadir / Terlambat</button>
                    <button @click="activeTab = 'belum'" :class="activeTab === 'belum' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-6 rounded-xl text-sm font-bold whitespace-nowrap transition-all">
                        Belum <span class="hidden sm:inline">Absen</span> <span class="ml-1 px-1.5 py-0.5 bg-rose-100 text-rose-600 rounded-md text-[10px]">{{ $belumAbsenList->count() }}</span>
                    </button>
                    <button @click="activeTab = 'lain'" :class="activeTab === 'lain' ? 'bg-white text-blue-900 shadow-sm ring-1 ring-slate-200' : 'text-slate-500 hover:bg-white/60'" class="flex-1 md:flex-none py-2.5 px-6 rounded-xl text-sm font-bold whitespace-nowrap transition-all">Sakit / Izin / Alfa</button>
                </div>

                <div class="w-full">
                    
                    {{-- TAB HADIR --}}
                    <div x-show="activeTab === 'hadir'" class="w-full">
                        <div class="grid grid-cols-1 gap-0">
                            @forelse ($attendancesHadir as $att)
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    {{-- Status Bar Indicator --}}
                                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $att->status_final == 'Terlambat' ? 'bg-amber-500' : 'bg-emerald-500' }} hidden group-hover:block"></div>
                                    
                                    <div class="flex items-center gap-3 md:gap-4 overflow-hidden w-full">
                                        <div class="w-12 h-12 rounded-2xl {{ $att->status_final == 'Terlambat' ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center font-bold text-xs shrink-0">
                                             {{ $loop->iteration }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-bold text-slate-800 truncate group-hover:text-blue-600 transition-colors">{{ $att->student->name }}</h4>
                                            <div class="flex flex-wrap items-center gap-2 md:gap-3 mt-1 text-xs">
                                                <span class="font-medium text-slate-400 bg-slate-100 px-2 py-0.5 rounded">{{ $att->student->schoolClass->name ?? '-' }}</span>
                                                
                                                <span class="flex items-center gap-1 font-bold text-slate-600">
                                                    <i class="ph-bold ph-arrow-right-circle text-emerald-500"></i> {{ $att->time_in_final ? \Carbon\Carbon::parse($att->time_in_final)->format('H:i') : '-' }}
                                                </span>
                                                <span class="flex items-center gap-1 font-bold text-slate-600">
                                                    <i class="ph-bold ph-arrow-left-circle text-blue-500"></i> {{ $att->time_out_final ? \Carbon\Carbon::parse($att->time_out_final)->format('H:i') : '-' }}
                                                </span>
                                                @if($att->status_final == 'Terlambat')
                                                    <span class="text-amber-600 font-bold uppercase tracking-wider text-[10px] bg-amber-50 px-2 py-0.5 rounded-full border border-amber-100">Terlambat</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <button onclick="openEditModal({{ $att->id }}, '{{ $att->student->name }}', '{{ $att->status_final }}', `{{ $att->notes_final }}`, '{{ $att->time_in_final }}', '{{ $att->time_out_final }}')" 
                                        class="p-2 ml-4 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all no-print shrink-0">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-20">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300"><i class="ph-duotone ph-coffee text-4xl"></i></div>
                                    <p class="text-slate-400 font-bold">Belum ada data kehadiran.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TAB BELUM ABSEN --}}
                    <div x-show="activeTab === 'belum'" style="display: none;" class="w-full">
                         @if($belumAbsenList->count() > 0)
                            <div class="p-5 bg-rose-50 border-b border-rose-100 flex flex-col md:flex-row items-center justify-between gap-4 no-print">
                                <div class="flex items-center gap-3 text-rose-700">
                                    <div class="p-2 bg-white rounded-lg shadow-sm shrink-0"><i class="ph-fill ph-warning-octagon text-xl"></i></div>
                                    <div>
                                        <h4 class="font-bold text-sm">Absensi Massal</h4>
                                        <p class="text-xs opacity-80">{{ $belumAbsenList->count() }} siswa akan ditandai Alfa.</p>
                                    </div>
                                </div>
                                <form id="bulk-alpha-form" action="{{ route('reports.bulkAlpha') }}" method="POST" class="w-full md:w-auto">
                                    @csrf
                                    <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                                    <input type="hidden" name="type" value="Harian">
                                    <button type="button" onclick="confirmBulkAlpha('{{ $belumAbsenList->count() }}')" 
                                        class="w-full bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold px-5 py-3 rounded-xl shadow-lg shadow-rose-200 transition-all flex items-center justify-center gap-2">
                                        <i class="ph-bold ph-check-circle"></i> Proses Alfa
                                    </button>
                                </form>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 gap-0">
                            @forelse ($belumAbsenList as $student)
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500 hidden group-hover:block"></div>
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center font-bold text-xs shrink-0">!</div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-800 truncate">{{ $student->name }}</h4>
                                            <p class="text-xs text-slate-500">{{ $student->schoolClass->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                    <button onclick="openManualModalDaily({{ $student->id }}, '{{ $student->name }}')" 
                                        class="inline-flex items-center gap-2 bg-white border border-slate-200 text-slate-600 px-4 py-2 rounded-xl text-xs font-bold hover:bg-blue-50 hover:text-blue-600 hover:border-blue-100 transition-all shadow-sm no-print shrink-0">
                                        Input <span class="hidden md:inline">Manual</span>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-20 text-emerald-600 font-bold">Semua Aman!</div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TAB LAINNYA --}}
                    <div x-show="activeTab === 'lain'" style="display: none;" class="w-full">
                         <div class="grid grid-cols-1 gap-0">
                            @forelse ($attendancesLain as $att)
                                <div class="relative p-4 md:p-5 border-b border-slate-50 hover:bg-slate-50 transition-colors group flex items-center justify-between">
                                    <div class="absolute left-0 top-0 bottom-0 w-1 {{ $att->status_final == 'Alfa' ? 'bg-rose-500' : 'bg-blue-500' }} hidden group-hover:block"></div>
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="w-10 h-10 rounded-xl {{ $att->status_final == 'Alfa' ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }} flex items-center justify-center font-bold text-xs shrink-0">
                                             {{ substr($att->status_final, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-bold text-slate-800 truncate">{{ $att->student->name }}</h4>
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $att->status_final == 'Alfa' ? 'bg-rose-50 text-rose-700' : 'bg-blue-50 text-blue-700' }} uppercase">{{ $att->status_final }}</span>
                                                @if($att->notes_final)
                                                    <span class="text-xs text-slate-400 italic max-w-[100px] md:max-w-none truncate">"{{ $att->notes_final }}"</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <button onclick="openEditModal({{ $att->id }}, '{{ $att->student->name }}', '{{ $att->status_final }}', `{{ $att->notes_final }}`, '', '')" 
                                        class="p-2 ml-4 text-slate-300 hover:text-blue-600 hover:bg-blue-50 rounded-xl transition-all no-print shrink-0">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-20 text-slate-400 italic">Tidak ada data lain.</div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- MODAL & JS --}}
    <div id="manualModalDaily" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100">
             <div class="bg-blue-900 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-bold ph-pencil-line"></i> Input Manual</h3>
                <button onclick="closeManualModalDaily()" class="text-white/70 hover:text-white transition"><i class="ph-bold ph-x text-xl"></i></button>
            </div>
            <form action="{{ route('reports.storeManual') }}" method="POST" class="p-6 space-y-4">
                 @csrf
                <input type="hidden" name="attendance_type" value="Harian">
                <input type="hidden" name="date" value="{{ $selectedDate_db->format('Y-m-d') }}">
                <input type="hidden" name="student_id" id="daily-manual-id">
                
                <div class="bg-blue-50 p-3 rounded-2xl border border-blue-100 text-center">
                    <span class="block text-xs font-bold text-blue-900 uppercase tracking-widest mb-1">Siswa</span>
                    <p id="daily-manual-name-display" class="text-lg font-black text-blue-900 truncate px-4"></p>
                </div>

                 <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Status</label>
                    <select name="status" id="daily-manual-status" onchange="toggleTimeInput()" class="w-full border-slate-200 bg-slate-50 rounded-xl focus:ring-blue-900 font-bold text-slate-700 h-12">
                        <option value="Hadir">Hadir</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alfa">Alfa</option> 
                    </select>
                </div>

                <div id="time-input-container" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Masuk</label>
                        <input type="time" name="time_in" id="daily-manual-time-in" class="w-full border-slate-200 bg-slate-50 rounded-xl font-bold text-slate-700 h-12">
                    </div>
                     <div>
                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Catatan</label>
                        <input type="text" name="notes" placeholder="Opsional" class="w-full border-slate-200 bg-slate-50 rounded-xl h-12">
                    </div>
                </div>

                <button type="submit" class="w-full bg-blue-900 hover:bg-slate-900 text-white font-bold h-12 rounded-xl transition-colors shadow-lg shadow-blue-200 mt-2">Simpan Data</button>
            </form>
        </div>
    </div>

    <div id="editAttendanceModal" class="hidden fixed inset-0 bg-slate-900/40 backdrop-blur-sm z-50 flex items-center justify-center p-4 transition-opacity no-print">
        <div class="bg-white rounded-[2rem] w-full max-w-md shadow-2xl overflow-hidden border border-slate-100">
             <div class="bg-slate-800 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white flex items-center gap-2"><i class="ph-bold ph-pencil-simple"></i> Edit Kehadiran</h3>
                <button onclick="closeEditModal()" class="text-white/70 hover:text-white transition"><i class="ph-bold ph-x text-xl"></i></button>
            </div>
            <form id="editForm" method="POST" class="p-6 space-y-4">
                 @csrf @method('PUT')
                 <div class="text-center mb-4">
                    <p class="text-xs font-bold text-slate-400 uppercase mb-1">Mengedit Siswa</p>
                    <p id="modal-student-name" class="text-xl font-black text-slate-800 truncate px-4"></p>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Status</label>
                    <select name="status" id="modal-status" onchange="toggleEditTimeInput()" class="w-full border-slate-200 bg-slate-50 rounded-xl font-bold text-slate-700 h-12">
                        <option value="Hadir">Hadir</option>
                        <option value="Terlambat">Terlambat (Otomatis)</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alfa">Alfa</option> 
                    </select>
                </div>

                <div id="edit-time-container" class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Masuk</label>
                        <input type="time" name="time_in" id="modal-time_in" class="w-full border-slate-200 bg-slate-50 rounded-xl font-bold text-slate-700 h-12">
                    </div>
                    <div>
                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Pulang</label>
                        <input type="time" name="time_out" id="modal-time_out" class="w-full border-slate-200 bg-slate-50 rounded-xl font-bold text-slate-700 h-12">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Catatan</label>
                     <textarea name="notes" id="modal-notes" class="w-full border-slate-200 bg-slate-50 rounded-xl" rows="2"></textarea>
                </div>

                 <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="flex-1 h-12 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Batal</button>
                    <button type="submit" class="flex-1 h-12 bg-blue-900 text-white font-bold rounded-xl hover:bg-slate-900 shadow-md">Update</button>
                </div>
            </form>
        </div>
    </div>

  <script>
        function confirmBulkAlpha(count) {
            Swal.fire({
                title: 'Tandai ' + count + ' Siswa Alpa?',
                text: "Siswa yang belum absen akan otomatis tercatat sebagai ALPA dan mendapat Poin Pelanggaran.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Proses Sekarang!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-[2rem]' }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('bulk-alpha-form').submit();
                }
            })
        }
        function toggleTimeInput() {
            const status = document.getElementById('daily-manual-status').value;
            const timeContainer = document.getElementById('time-input-container');
            if (status === 'Hadir' || status === 'Terlambat') {
                timeContainer.classList.remove('hidden', 'opacity-50', 'pointer-events-none');
            } else {
                timeContainer.classList.add('hidden', 'opacity-50', 'pointer-events-none');
            }
        }
        function toggleEditTimeInput() {
            const status = document.getElementById('modal-status').value;
            const timeContainer = document.getElementById('edit-time-container');
            if (status === 'Hadir' || status === 'Terlambat') {
                timeContainer.classList.remove('hidden');
            } else {
                timeContainer.classList.add('hidden');
            }
        }
        function openManualModalDaily(id, name) { 
            document.getElementById('daily-manual-id').value = id; 
            document.getElementById('daily-manual-name-display').textContent = name; 
            document.getElementById('daily-manual-status').value = 'Hadir';
            toggleTimeInput(); 
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, '0');
            const minutes = String(now.getMinutes()).padStart(2, '0');
            document.getElementById('daily-manual-time-in').value = `${hours}:${minutes}`;
            document.getElementById('manualModalDaily').classList.remove('hidden'); 
        }
        function closeManualModalDaily() { 
            document.getElementById('manualModalDaily').classList.add('hidden'); 
        }
        const modal = document.getElementById('editAttendanceModal');
        const form = document.getElementById('editForm');
        function openEditModal(id, name, status, notes, timeIn, timeOut) {
            form.action = '{{ route('reports.update', ['attendance' => '__ID__']) }}'.replace('__ID__', id);
            document.getElementById('modal-student-name').textContent = name;
            document.getElementById('modal-status').value = status;
            document.getElementById('modal-notes').value = notes;
            document.getElementById('modal-time_in').value = timeIn ? timeIn.substring(0,5) : '';
            document.getElementById('modal-time_out').value = timeOut ? timeOut.substring(0,5) : '';
            toggleEditTimeInput(); 
            modal.classList.remove('hidden');
        }
        function closeEditModal() { modal.classList.add('hidden'); }
    </script>
</x-app-layout>