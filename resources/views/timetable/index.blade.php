<x-app-layout>
    <div class="py-6 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <div class="relative rounded-[2rem] sm:rounded-[2.5rem] bg-elevate-gradient-main p-6 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 sm:gap-8">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-magic-wand"></i> Generator Cerdas & Manual
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Penyusun Jadwal Pelajaran
                        </h1>
                        <p class="text-elevate-dark/80 text-xs sm:text-sm md:text-base font-semibold leading-relaxed max-w-lg">
                            Otomatisasi penyusunan jadwal dengan algoritma cerdas, atau gunakan fitur *Drag and Drop* untuk menyusun secara manual.
                        </p>
                    </div>
                    
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        <div class="bg-white/60 backdrop-blur-md px-5 py-4 sm:px-6 sm:py-5 rounded-2xl border border-white/80 flex-1 md:flex-none min-w-[140px] text-center md:text-left shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-chalkboard-teacher text-lg"></i>
                                <span class="text-[10px] font-bold uppercase tracking-wider">Total Beban</span>
                            </div>
                            <span class="block text-2xl sm:text-3xl font-black text-elevate-dark tracking-tight">{{ $totalTeachingLoads }} <span class="text-sm font-bold text-elevate-dark/50">JP</span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Pesan Flash --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <i class="ph-bold ph-check-circle text-xl text-emerald-600"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if (session('warning'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-amber-50 border border-amber-100 text-amber-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <i class="ph-bold ph-warning-circle text-xl text-amber-600"></i>
                        <span class="font-bold text-sm">{{ session('warning') }}</span>
                    </div>
                    <button @click="show = false" class="text-amber-400 hover:text-amber-600"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <i class="ph-bold ph-warning-circle text-xl text-rose-600"></i>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start mb-10">
                <!-- BAGIAN KIRI: DAFTAR KELAS & BEBAN -->
                <div class="lg:col-span-8 bg-white p-5 sm:p-6 md:p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20">
                                <i class="ph-duotone ph-list-checks"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-black text-elevate-dark">Prasyarat Beban Mengajar</h2>
                                <p class="text-xs text-elevate-dark/60 font-medium mt-1">Pastikan setiap kelas memiliki JP yang valid.</p>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-bold text-elevate-primary uppercase border-b border-slate-100">
                                <tr>
                                    <th class="px-5 py-4 rounded-tl-xl">Nama Kelas</th>
                                    <th class="px-5 py-4">Guru Pengajar (Diset)</th>
                                    <th class="px-5 py-4 text-center rounded-tr-xl">Total JP Sepekan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($classes as $class)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-4 font-black text-elevate-dark flex items-center gap-2">
                                        <i class="ph-bold ph-chalkboard text-slate-400"></i> {{ $class->name }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($class->teachingLoads as $load)
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                                    {{ $load->teacher->name ?? 'Anonim' }} <span class="ml-1 text-elevate-primary">({{ $load->hours_per_week }})</span>
                                                </span>
                                            @empty
                                                <span class="text-xs text-slate-400 italic">Belum di-set</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @php $totalJP = $class->teachingLoads->sum('hours_per_week'); @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black {{ $totalJP > 0 ? 'bg-emerald-50 text-emerald-600 border border-emerald-100' : 'bg-rose-50 text-rose-600 border border-rose-100' }}">
                                            {{ $totalJP }} Jam
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="px-5 py-8 text-center text-sm font-bold text-slate-400">Data kelas belum tersedia.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BAGIAN KANAN: KONTROL EKSEKUSI & EKSPOR -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-white p-5 sm:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-peach to-elevate-peach-dark"></div>
                        <h3 class="text-lg font-black text-elevate-dark mb-4 text-center">Mesin Generator</h3>

                        @if($hasGenerated)
                            <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-5 text-center">
                                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-2 shadow-sm"><i class="ph-fill ph-check-circle"></i></div>
                                <h4 class="font-black text-emerald-700 text-sm">Jadwal Sedang Aktif</h4>
                            </div>
                        @else
                            <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 mb-5 text-center">
                                <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-2xl mx-auto mb-2 shadow-sm"><i class="ph-duotone ph-clock-countdown"></i></div>
                                <h4 class="font-black text-amber-700 text-sm">Belum Ada Jadwal / Kosong</h4>
                                <p class="text-[10px] mt-1 text-amber-800">Tarik dari Bank Sisa untuk menyusun manual.</p>
                            </div>
                        @endif

                        <form action="{{ route('timetable.generate') }}" method="POST" id="form-generate" class="mb-3">
                            @csrf
                            <button type="button" onclick="confirmGenerate()" class="w-full py-3 px-4 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark transition-all shadow-lg flex items-center justify-center gap-2 text-sm">
                                <i class="ph-bold ph-magic-wand text-lg"></i> {{ $hasGenerated ? 'Generate Ulang' : 'Mulai Generate' }}
                            </button>
                        </form>

                        <form action="{{ route('timetable.reset') }}" method="POST" id="form-reset">
                            @csrf
                            <button type="button" onclick="confirmReset()" class="w-full py-3 px-4 bg-white border-2 border-rose-100 text-rose-600 font-bold rounded-xl hover:bg-rose-50 transition-all flex items-center justify-center gap-2 text-sm">
                                <i class="ph-bold ph-trash text-lg"></i> Kosongkan Jadwal
                            </button>
                        </form>
                    </div>

                    {{-- IMPORT JADWAL DARI LUAR APLIKASI --}}
                    <div class="bg-white p-5 sm:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-400 to-sky-600"></div>
                        <h3 class="text-lg font-black text-elevate-dark mb-1 text-center">Import Jadwal</h3>
                        <p class="text-[11px] text-elevate-dark/60 font-medium text-center mb-4">Unggah jadwal yang sudah disusun di luar aplikasi (format Excel).</p>

                        <a href="{{ route('timetable.template') }}" class="w-full mb-4 py-2.5 px-4 bg-slate-50 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-all flex items-center justify-center gap-2 text-xs">
                            <i class="ph-bold ph-download-simple text-base"></i> Unduh Template Excel
                        </a>

                        <form id="form-import" onsubmit="handleImportJadwal(event)">
                            <input type="file" name="file" id="importFileInput" accept=".xlsx,.xls" required
                                   class="w-full text-xs font-bold text-slate-500 rounded-xl border border-slate-200 bg-slate-50 mb-3 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-elevate-soft file:text-elevate-primary file:font-bold file:text-xs">

                            <label class="flex items-center gap-2 mb-4 text-xs font-bold text-elevate-dark/70 cursor-pointer">
                                <input type="checkbox" id="importOverwrite" class="rounded border-slate-300">
                                Timpa jadwal yang bentrok dengan data di file
                            </label>

                            <button type="submit" class="w-full py-3 px-4 bg-sky-500 text-white font-bold rounded-xl hover:bg-sky-600 transition-all shadow-lg flex items-center justify-center gap-2 text-sm">
                                <i class="ph-bold ph-upload-simple text-lg"></i> Import Jadwal
                            </button>
                        </form>
                    </div>

                    {{-- UNDUH JADWAL (Hanya tampil jika ada jadwal yang di set) --}}
                    @if($hasGenerated)
                    <div class="bg-white p-5 sm:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100" x-data="{ tabExp: 'kelas' }">
                        <h3 class="text-lg font-black text-elevate-dark mb-4 text-center">Unduh Jadwal</h3>
                        <div class="flex bg-slate-100 rounded-xl p-1 mb-5">
                            <button @click="tabExp = 'kelas'" :class="tabExp === 'kelas' ? 'bg-white shadow-sm text-elevate-primary' : 'text-slate-400'" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all">Per Kelas</button>
                            <button @click="tabExp = 'guru'" :class="tabExp === 'guru' ? 'bg-white shadow-sm text-elevate-primary' : 'text-slate-400'" class="flex-1 py-2 text-xs font-bold rounded-lg transition-all">Per Guru</button>
                        </div>

                        <div x-show="tabExp === 'kelas'">
                            <select id="exportClassSelect" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold mb-4">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <button onclick="exportData('class')" class="w-full py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition-all flex justify-center items-center gap-2 text-sm"><i class="ph-bold ph-file-xls text-lg"></i> Unduh Excel Kelas</button>
                        </div>

                        <div x-show="tabExp === 'guru'" style="display: none;">
                            <select id="exportTeacherSelect" class="w-full rounded-xl border-slate-200 bg-slate-50 text-sm font-bold mb-4">
                                <option value="">-- Pilih Guru --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <button onclick="exportData('teacher')" class="w-full py-3 bg-blue-500 text-white font-bold rounded-xl hover:bg-blue-600 transition-all flex justify-center items-center gap-2 text-sm"><i class="ph-bold ph-file-xls text-lg"></i> Unduh Excel Guru</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- VISUALISASI JADWAL (TIDAK DIBUNGKUS HAS_GENERATED LAGI AGAR SELALU TAMPIL) --}}
            
            {{-- FITUR DRAG AND DROP: BANK SISA JADWAL --}}
            @if(isset($unassignedLoadsList) && count($unassignedLoadsList) > 0)
            <div class="bg-amber-50 p-6 rounded-[2rem] shadow-lg border border-amber-200 mb-8 relative overflow-hidden transition-all duration-300"
                 id="bank-sisa-container"
                 ondragover="event.preventDefault(); this.classList.add('bg-amber-100', 'border-amber-400', 'scale-[1.01]');"
                 ondragleave="this.classList.remove('bg-amber-100', 'border-amber-400', 'scale-[1.01]');"
                 ondrop="handleRemoveDrop(event); this.classList.remove('bg-amber-100', 'border-amber-400', 'scale-[1.01]');">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center text-xl shadow-sm"><i class="ph-fill ph-warning-circle"></i></div>
                        <div>
                            <h3 class="text-lg font-black text-amber-900">Bank Sisa Jadwal ({{ count($unassignedLoadsList) }} Mapel)</h3>
                            <p class="text-xs font-bold text-amber-700">Tarik ke tabel untuk menyusun manual. Lempar kotak dari tabel ke sini untuk menghapusnya.</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    @foreach($unassignedLoadsList as $item)
                        @php $load = $item['load']; @endphp
                        <div draggable="true"
                             ondragstart="event.dataTransfer.setData('type', 'unassigned'); event.dataTransfer.setData('load_id', '{{ $load->id }}'); event.dataTransfer.setData('class_id', '{{ $load->class_id }}');"
                             class="bg-white border-2 border-amber-300 p-3 rounded-xl shadow-sm cursor-move hover:shadow-md hover:-translate-y-1 transition-all w-full sm:w-auto min-w-[200px]">
                            <div class="flex justify-between items-start mb-2 gap-4">
                                <span class="text-[10px] font-bold bg-amber-100 text-amber-800 px-2 py-1 rounded-md">{{ $load->studentClass->name ?? 'Kelas ?' }}</span>
                                <span class="text-[10px] font-black text-rose-600 border border-rose-200 bg-rose-50 px-2 py-1 rounded-md">Sisa {{ $item['sisa'] }} JP</span>
                            </div>
                            <div class="font-black text-elevate-dark text-sm leading-tight">{{ $load->subject->name }}</div>
                            <div class="text-[10px] text-slate-500 font-bold mt-1.5 uppercase tracking-wide"><i class="ph-fill ph-user"></i> {{ $load->teacher->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white p-6 sm:p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative mb-10 overflow-hidden" 
                 x-data="{ viewType: 'kelas', selectedClass: '{{ $classes->first()->id ?? '' }}', selectedTeacher: '{{ $teachers->first()->id ?? '' }}' }">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-xl font-black text-elevate-dark flex items-center gap-2">
                            <i class="ph-fill ph-calendar-check text-emerald-500"></i> Hasil Jadwal Pelajaran
                        </h3>
                    </div>
                    
                    {{-- Toggle & Filter --}}
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        <div class="flex bg-slate-100 rounded-xl p-1 w-full sm:w-auto border border-slate-200">
                            <button @click="viewType = 'kelas'" :class="viewType === 'kelas' ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all w-full sm:w-auto">Jadwal Kelas</button>
                            <button @click="viewType = 'guru'" :class="viewType === 'guru' ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 text-xs font-bold rounded-lg transition-all w-full sm:w-auto">Jadwal Guru (PDF Style)</button>
                        </div>

                        {{-- Filter Kelas --}}
                        <div x-show="viewType === 'kelas'" class="bg-slate-50 p-2 rounded-2xl border border-slate-200 w-full sm:w-auto">
                            <select x-model="selectedClass" class="w-full sm:w-auto rounded-xl border-none bg-white text-sm font-bold text-elevate-dark shadow-sm py-2">
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Guru --}}
                        <div x-show="viewType === 'guru'" style="display: none;" class="bg-slate-50 p-2 rounded-2xl border border-slate-200 w-full sm:w-auto">
                            <select x-model="selectedTeacher" class="w-full sm:w-auto rounded-xl border-none bg-white text-sm font-bold text-elevate-dark shadow-sm py-2">
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- PRATINJAU KELAS (TAMPILAN UI MODERN) --}}
                <div x-show="viewType === 'kelas'" class="overflow-x-auto rounded-2xl border border-slate-200">
                    @foreach($classes as $class)
                    <div x-show="selectedClass == '{{ $class->id }}'" style="display: none;">
                        <table class="w-full text-left text-sm min-w-[800px]">
                            <thead>
                                <tr>
                                    <th class="p-4 bg-slate-100 text-elevate-dark font-black border-b border-r border-slate-200 text-center w-28 uppercase text-xs">Waktu</th>
                                    @foreach($days as $day)
                                        <th class="p-4 bg-slate-100 text-elevate-dark font-black border-b border-r border-slate-200 text-center uppercase text-xs">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timeslots as $slot)
                                <tr>
                                    <td class="p-3 border-b border-r border-slate-200 bg-slate-50/80 text-center">
                                        <div class="font-black text-xs mb-1">{{ $slot->name }}</div>
                                        <div class="inline-block px-2 py-1 bg-white border border-slate-200 rounded-md text-[10px]">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</div>
                                    </td>
                                    
                                    @foreach($days as $day)
                                        @php
                                            $slotDays = array_map('trim', explode(',', $slot->day_of_week));
                                            $isValidDay = in_array($day, $slotDays) || $slot->day_of_week === 'Semua Hari' || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
                                            $cellData = $timetables[$class->id][$day][$slot->id] ?? null;
                                        @endphp

                                        @if($slot->is_break && $isValidDay)
                                            <td class="p-4 border-b border-r border-slate-200 bg-amber-50 text-center text-amber-700 font-black uppercase text-xs">
                                                <i class="ph-bold ph-coffee"></i> {{ $slot->name }}
                                            </td>
                                        @elseif(!$isValidDay)
                                            <td class="p-3 border-b border-r border-slate-200 bg-slate-100/50 text-center"><span class="text-[10px] text-slate-400">-</span></td>
                                        @else
                                            
                                            {{-- AREA DROP JADWAL (td) --}}
                                            <td ondragover="event.preventDefault(); this.classList.add('bg-emerald-100', 'border-emerald-400');"
                                                ondragleave="this.classList.remove('bg-emerald-100', 'border-emerald-400');"
                                                ondrop="handleDrop(event, '{{ $day }}', '{{ $slot->id }}', '{{ $class->id }}'); this.classList.remove('bg-emerald-100', 'border-emerald-400');"
                                                class="p-2 border-b border-r border-slate-200 text-center transition-all drop-zone relative min-w-[140px] bg-white group">
                                                
                                                @if($cellData)
                                                    {{-- ITEM YANG BISA DI-DRAG (MATA PELAJARAN) --}}
                                                    <div draggable="true"
                                                         ondragstart="event.dataTransfer.setData('type', 'scheduled'); event.dataTransfer.setData('timetable_id', '{{ $cellData->id }}'); event.dataTransfer.setData('class_id', '{{ $class->id }}');"
                                                         class="cursor-move bg-white p-2 rounded-xl border border-slate-200 group-hover:border-emerald-400 hover:shadow-md transition-all shadow-sm">
                                                        <div class="font-black text-elevate-dark text-xs mb-2">{{ $cellData->subject->name }}</div>
                                                        <div class="inline-flex items-center gap-1 px-2 py-1 bg-slate-100 text-[9px] font-bold uppercase rounded-lg border border-slate-200">
                                                            <i class="ph-fill ph-user"></i> {{ $cellData->teacher->name }}
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- SLOT KOSONG --}}
                                                    <div class="flex items-center justify-center w-full h-full min-h-[4rem]">
                                                        <span class="inline-flex px-3 py-1 bg-slate-50 text-slate-400 rounded-md text-[10px] font-bold italic border border-slate-200 border-dashed pointer-events-none">Kosong</span>
                                                    </div>
                                                @endif
                                            </td>

                                        @endif
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endforeach
                </div>

                {{-- PRATINJAU GURU (TAMPILAN PDF / FORMAL) --}}
                <div x-show="viewType === 'guru'" style="display: none;" class="overflow-x-auto bg-gray-100 p-6 rounded-xl border border-gray-300">
                    <div class="min-w-[900px] bg-white p-8 mx-auto border-2 border-black font-sans text-black shadow-lg" style="font-family: Arial, sans-serif;">
                        
                        {{-- KOP JADWAL (Dinamis berdasarkan pilihan Guru) --}}
                        @foreach($teachers as $teacher)
                        <div x-show="selectedTeacher == '{{ $teacher->id }}'" style="display: none;">
                            
                            @php
                                // Ambil mapel pertama yang diajarkan guru ini untuk kop
                                $subjectName = "Belum Ada";
                                foreach($days as $d) {
                                    foreach($timeslots as $s) {
                                        if(isset($teacherTimetables[$teacher->id][$d][$s->id])) {
                                            $subjectName = $teacherTimetables[$teacher->id][$d][$s->id]->subject->name;
                                            break 2;
                                        }
                                    }
                                }
                            @endphp

                            <div class="text-center mb-6">
                                <h1 class="font-bold text-lg leading-tight">JADWAL PELAJARAN SEMESTER GENAP<br>SMP NEGERI / SEKOLAH ANDA<br>TAHUN AJARAN 2025/2026</h1>
                            </div>

                            <div class="flex justify-between items-end font-bold text-sm mb-2 px-2">
                                <div>
                                    <p>Nama &nbsp;: {{ strtoupper($teacher->name) }}</p>
                                    <p>Kode &nbsp;&nbsp;: {{ $teacher->id }}</p>
                                </div>
                                <div>
                                    <p>Mata Pelajaran : {{ strtoupper($subjectName) }}</p>
                                </div>
                            </div>

                            <table class="w-full text-center text-xs border-collapse border-2 border-black">
                                <thead>
                                    <tr>
                                        <th class="border border-black p-2 font-bold w-12">Jam<br>Ke</th>
                                        <th class="border border-black p-2 font-bold w-32">Alokasi<br>Waktu</th>
                                        @foreach($days as $day)
                                            <th class="border border-black p-2 font-bold">{{ $day }}<br><br>KELAS</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $jamKe = 1; @endphp
                                    @foreach($timeslots as $slot)
                                    <tr>
                                        @php
                                            // Format Waktu
                                            $timeStr = \Carbon\Carbon::parse($slot->start_time)->format('H.i') . '-' . \Carbon\Carbon::parse($slot->end_time)->format('H.i');
                                        @endphp
                                        
                                        {{-- Kondisi jika ini Jam Istirahat / Upacara --}}
                                        @if($slot->is_break)
                                            <td class="border border-black p-2 font-bold"></td>
                                            <td class="border border-black p-2 font-bold">{{ $timeStr }}</td>
                                            <td colspan="{{ count($days) }}" class="border border-black p-2 font-bold uppercase bg-gray-100 tracking-[0.3em]">
                                                {{ $slot->name }}
                                            </td>
                                        @else
                                            <td class="border border-black p-2 font-bold">{{ $jamKe++ }}</td>
                                            <td class="border border-black p-2 font-bold">{{ $timeStr }}</td>
                                            
                                            @foreach($days as $day)
                                                @php
                                                    $slotDays = array_map('trim', explode(',', $slot->day_of_week));
                                                    $isValidDay = in_array($day, $slotDays) || $slot->day_of_week === 'Semua Hari' || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
                                                    
                                                    // Ambil data dari teacherTimetables berdasarkan id guru yang sedang di loop
                                                    $cellData = $teacherTimetables[$teacher->id][$day][$slot->id] ?? null;
                                                @endphp

                                                @if(!$isValidDay)
                                                    <td class="border border-black p-2"></td>
                                                @else
                                                    <td class="border border-black p-2 font-bold text-sm">
                                                        {{-- Cek class untuk menampilkan nama kelas seperti "9F", "8A" --}}
                                                        @if($cellData && isset($cellData->studentClass))
                                                            {{ $cellData->studentClass->name }}
                                                        @elseif($cellData && isset($cellData->schoolClass))
                                                            {{ $cellData->schoolClass->name }}
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                @endif
                                            @endforeach
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
            {{-- AKHIR VISUALISASI JADWAL --}}

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmGenerate() {
            Swal.fire({
                title: 'Jalankan Auto-Generate?',
                text: "Proses ini akan menimpa seluruh jadwal lama. Algoritma telah disempurnakan dengan Prioritas Beban.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#2563eb',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Susun Jadwal!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-[2rem] font-sans' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyusun...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); document.getElementById('form-generate').submit(); } });
                }
            });
        }

        function confirmReset() {
            Swal.fire({
                title: 'Kosongkan Jadwal?', 
                text: "Semua jadwal akan dihapus dan kembali ke Bank Sisa Jadwal. Anda yakin?",
                icon: 'warning', 
                showCancelButton: true, 
                confirmButtonColor: '#e11d48', 
                confirmButtonText: 'Ya, Kosongkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { popup: 'rounded-[2rem] font-sans' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('form-reset').submit();
            });
        }

        function exportData(type) {
            const id = document.getElementById(type === 'class' ? 'exportClassSelect' : 'exportTeacherSelect').value;
            if(!id) return Swal.fire({ icon: 'error', title: 'Oops...', text: 'Pilih data terlebih dahulu!' });
            window.location.href = "{{ url('/') }}" + `/timetable/export/${type}/${id}`;
        }

        // ==========================================
        //  FUNGSI DRAG AND DROP JADWAL (JS LOGIC)
        // ==========================================
        function handleDrop(event, targetDay, targetTimeslotId, targetClassId) {
            event.preventDefault();
            
            const type = event.dataTransfer.getData('type');
            const draggedClassId = event.dataTransfer.getData('class_id');

            // Validasi Pencegahan: Memastikan guru/admin tidak keliru menarik jadwal ke tab kelas yang berbeda
            if (draggedClassId && draggedClassId !== targetClassId) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Salah Kelas!',
                    text: 'Anda mencoba memindahkan jadwal ke kelas yang berbeda. Harap drop jadwal ini ke tab kelas yang sesuai!',
                    customClass: { popup: 'rounded-2xl font-sans' }
                });
                return;
            }

            if (type === 'unassigned') {
                // Menaruh dari Bank Sisa Jadwal
                const loadId = event.dataTransfer.getData('load_id');
                processDragDropRequest('{{ route("timetable.place") }}', {
                    teaching_load_id: loadId,
                    target_day: targetDay,
                    target_timeslot_id: targetTimeslotId
                });
            } else if (type === 'scheduled') {
                // Menggeser jadwal yang sudah ada di tabel
                const timetableId = event.dataTransfer.getData('timetable_id');
                processDragDropRequest('{{ route("timetable.move") }}', {
                    timetable_id: timetableId,
                    target_day: targetDay,
                    target_timeslot_id: targetTimeslotId
                });
            }
        }

        function processDragDropRequest(url, payload) {
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    Swal.fire({
                        icon: 'success', 
                        title: 'Berhasil!', 
                        text: data.message, 
                        timer: 1500, 
                        showConfirmButton: false,
                        customClass: { popup: 'rounded-2xl font-sans' }
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal (Bentrok)!',
                        text: data.message,
                        customClass: { popup: 'rounded-2xl font-sans' }
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error!', 'Terjadi kesalahan sistem saat menghubungi server.', 'error');
            });
        }

        function handleSaveJadwal(event) {
            event.preventDefault(); // Mencegah form melakukan submit standar yang me-reload halaman
            
            let form = document.getElementById('form-generate');
            let formData = new FormData(form);

            // Menampilkan loading sementara
            Swal.fire({
                title: 'Menyimpan jadwal...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            // Mengirim data ke controller via fetch
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Berhasil: Tutup loading dan tampilkan notifikasi sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: 'Jadwal berhasil disimpan.',
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire('Gagal!', data.message || 'Terjadi kesalahan.', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
            });
        }

        function handleImportJadwal(event) {
            event.preventDefault(); // Cegah reload halaman

            let form = document.getElementById('form-import');
            let fileInput = document.getElementById('importFileInput');
            let overwrite = document.getElementById('importOverwrite').checked;

            if (!fileInput.files.length) {
                return Swal.fire({ icon: 'error', title: 'Oops...', text: 'Pilih file Excel terlebih dahulu!' });
            }

            let formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('overwrite', overwrite ? '1' : '0');

            Swal.fire({
                title: 'Mengimport jadwal...',
                allowOutsideClick: false,
                didOpen: () => { Swal.showLoading(); }
            });

            fetch('{{ route("timetable.import") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                // Susun daftar error (kalau ada) jadi list HTML sederhana
                let errorListHtml = '';
                if (data.errors && data.errors.length > 0) {
                    let shown = data.errors.slice(0, 15);
                    errorListHtml = '<div class="text-left text-xs mt-3 max-h-48 overflow-y-auto bg-rose-50 border border-rose-100 rounded-xl p-3">' +
                        shown.map(e => `<div class="mb-1">&bull; ${e}</div>`).join('') +
                        (data.errors.length > 15 ? `<div class="italic text-slate-400">...dan ${data.errors.length - 15} baris lainnya</div>` : '') +
                        '</div>';
                }

                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Import Selesai' : 'Import Gagal',
                    html: `<p class="text-sm">${data.message}</p>${errorListHtml}`,
                    customClass: { popup: 'rounded-2xl font-sans' },
                    confirmButtonText: 'OK'
                }).then(() => {
                    if (data.success) location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Terjadi kesalahan sistem saat mengimport.', 'error');
            });
        }

         // Fungsi untuk menangani pelepasan (drop) jadwal kembali ke Bank
        function handleRemoveDrop(event) {
            event.preventDefault();
            const type = event.dataTransfer.getData('type');
                    
            // Hanya izinkan jika yang dilempar adalah jadwal yang sudah ada di tabel ('scheduled')
            if (type === 'scheduled') {
                const timetableId = event.dataTransfer.getData('timetable_id');
                        
                Swal.fire({ title: 'Mengembalikan...', allowOutsideClick: false, didOpen: () => Swal.showLoading() });
                        
                fetch('{{ route("timetable.remove") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ timetable_id: timetableId })
                })
                .then(res => res.json())
                .then(data => {
                    if(data.success) {
                        location.reload(); // Muat ulang layar agar JP kembali bertambah di Bank
                    } else {
                        Swal.fire('Gagal!', data.message || 'Gagal menghapus jadwal.', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire('Error!', 'Terjadi kesalahan sistem.', 'error');
                });
            }
        }

        // ==========================================
        //  PENCEGAHAN SCROLL SAAT RELOAD (DRAG & DROP)
        // ==========================================
        
        // 1. Simpan posisi scroll tepat sebelum halaman dimuat ulang (menangkap location.reload)
        window.addEventListener('beforeunload', () => {
            sessionStorage.setItem('scrollPosition', window.scrollY);
        });

        // 2. Kembalikan posisi scroll saat halaman selesai dimuat ulang
        window.addEventListener('load', () => {
            const scrollPosition = sessionStorage.getItem('scrollPosition');
            if (scrollPosition !== null) {
                // Gunakan setTimeout kecil untuk memastikan DOM tabel sudah di-render AlpineJS
                setTimeout(() => {
                    window.scrollTo(0, parseInt(scrollPosition));
                }, 50); 
                sessionStorage.removeItem('scrollPosition'); // Hapus memori setelah dikembalikan
            }
        });

    </script>
</x-app-layout>