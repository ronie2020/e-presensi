<x-app-layout>
    <div class="py-6 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-r from-sky-600/20 via-blue-600/10 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <x-hero-section
                badge="GENERATOR CERDAS & MANUAL"
                badgeIcon="ph-fill ph-magic-wand"
                showcaseIcon="ph-duotone ph-calendar-blank"
                showcaseTitle="Penyusun Jadwal"
                showcaseSubtitle="Timetable Master">
                <x-slot:title>
                    <span class="block text-slate-100">Penyusun & Generator</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Jadwal Pelajaran
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Otomatisasi penyusunan jadwal dengan algoritma cerdas anti-bentrok, atau gunakan fitur drag & drop interaktif secara manual.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-lightning text-amber-400"></i> Auto-Generate
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-hand-grabbing text-sky-400"></i> Drag & Drop Manual
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-shield-check text-emerald-400"></i> Validasi Bentrok
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                        <i class="ph-fill ph-chalkboard-teacher text-sky-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Total Beban:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $totalTeachingLoads }} JP</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- Pesan Flash --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-950/60 border border-emerald-500/30 text-emerald-300 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                    <div class="flex items-center gap-3 px-2">
                        <i class="ph-bold ph-check-circle text-xl text-emerald-400"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-300"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if (session('warning'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-amber-950/60 border border-amber-500/30 text-amber-300 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                    <div class="flex items-center gap-3 px-2">
                        <i class="ph-bold ph-warning-circle text-xl text-amber-400"></i>
                        <span class="font-bold text-sm">{{ session('warning') }}</span>
                    </div>
                    <button @click="show = false" class="text-amber-400 hover:text-amber-300"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-950/60 border border-rose-500/30 text-rose-300 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                    <div class="flex items-center gap-3 px-2">
                        <i class="ph-bold ph-warning-circle text-xl text-rose-400"></i>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-300"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 sm:gap-8 items-start mb-10">
                <!-- BAGIAN KIRI: DAFTAR KELAS & BEBAN -->
                <div class="lg:col-span-8 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 sm:p-6 md:p-8 rounded-[2rem] shadow-2xl border border-white/10 relative overflow-hidden backdrop-blur-xl text-white">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-500 to-blue-600"></div>
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-sky-500/20 text-sky-400 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-sky-500/30">
                                <i class="ph-duotone ph-list-checks"></i>
                            </div>
                            <div>
                                <h2 class="text-lg font-black text-white">Prasyarat Beban Mengajar</h2>
                                <p class="text-xs text-slate-400 font-medium mt-1">Pastikan setiap kelas memiliki JP yang valid.</p>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-900/80 text-xs font-bold text-sky-400 uppercase border-b border-white/10">
                                <tr>
                                    <th class="px-5 py-4 rounded-tl-xl">Nama Kelas</th>
                                    <th class="px-5 py-4">Guru Pengajar (Diset)</th>
                                    <th class="px-5 py-4 text-center rounded-tr-xl">Total JP Sepekan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($classes as $class)
                                <tr class="hover:bg-slate-900/40 transition-colors">
                                    <td class="px-5 py-4 font-black text-white flex items-center gap-2">
                                        <i class="ph-bold ph-chalkboard text-slate-400"></i> {{ $class->name }}
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @forelse($class->teachingLoads as $load)
                                                <span class="inline-flex items-center px-2 py-1 rounded-lg text-[10px] font-bold bg-slate-800 text-slate-300 border border-white/10">
                                                    {{ $load->teacher->name ?? 'Anonim' }} <span class="ml-1 text-sky-400">({{ $load->hours_per_week }})</span>
                                                </span>
                                            @empty
                                                <span class="text-xs text-slate-500 italic">Belum di-set</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-center">
                                        @php $totalJP = $class->teachingLoads->sum('hours_per_week'); @endphp
                                        <span class="inline-flex items-center px-3 py-1 rounded-xl text-xs font-black {{ $totalJP > 0 ? 'bg-emerald-950/60 text-emerald-300 border border-emerald-500/30' : 'bg-rose-950/60 text-rose-300 border border-rose-500/30' }}">
                                            {{ $totalJP }} Jam
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="px-5 py-8 text-center text-sm font-bold text-slate-500">Data kelas belum tersedia.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- BAGIAN KANAN: KONTROL EKSEKUSI & EKSPOR -->
                <div class="lg:col-span-4 space-y-6">
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 sm:p-6 rounded-[2rem] shadow-2xl border border-white/10 relative backdrop-blur-xl text-white">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-amber-500 to-amber-700"></div>
                        <h3 class="text-lg font-black text-white mb-4 text-center">Mesin Generator</h3>

                        @if($hasGenerated)
                            <div class="bg-emerald-950/60 border border-emerald-500/30 rounded-2xl p-4 mb-5 text-center">
                                <div class="w-12 h-12 bg-emerald-500/20 text-emerald-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-2 shadow-sm border border-emerald-500/30"><i class="ph-fill ph-check-circle"></i></div>
                                <h4 class="font-black text-emerald-300 text-sm">Jadwal Sedang Aktif</h4>
                            </div>
                        @else
                            <div class="bg-amber-950/60 border border-amber-500/30 rounded-2xl p-4 mb-5 text-center">
                                <div class="w-12 h-12 bg-amber-500/20 text-amber-400 rounded-full flex items-center justify-center text-2xl mx-auto mb-2 shadow-sm border border-amber-500/30"><i class="ph-duotone ph-clock-countdown"></i></div>
                                <h4 class="font-black text-amber-300 text-sm">Belum Ada Jadwal / Kosong</h4>
                                <p class="text-[10px] mt-1 text-amber-200/80">Tarik dari Bank Sisa untuk menyusun manual.</p>
                            </div>
                        @endif

                        <form action="{{ route('timetable.generate') }}" method="POST" id="form-generate" class="mb-3">
                            @csrf
                            <button type="button" onclick="confirmGenerate()" class="w-full py-3 px-4 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg flex items-center justify-center gap-2 text-sm border border-white/10">
                                <i class="ph-bold ph-magic-wand text-lg"></i> {{ $hasGenerated ? 'Generate Ulang' : 'Mulai Generate' }}
                            </button>
                        </form>

                        <form action="{{ route('timetable.reset') }}" method="POST" id="form-reset">
                            @csrf
                            <button type="button" onclick="confirmReset()" class="w-full py-3 px-4 bg-slate-900/80 border border-rose-500/30 text-rose-400 font-bold rounded-xl hover:bg-rose-950/40 transition-all flex items-center justify-center gap-2 text-sm">
                                <i class="ph-bold ph-trash text-lg"></i> Kosongkan Jadwal
                            </button>
                        </form>
                    </div>

                    {{-- IMPORT JADWAL DARI LUAR APLIKASI --}}
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 sm:p-6 rounded-[2rem] shadow-2xl border border-white/10 relative backdrop-blur-xl text-white">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-400 to-sky-600"></div>
                        <h3 class="text-lg font-black text-white mb-1 text-center">Import Jadwal</h3>
                        <p class="text-[11px] text-slate-400 font-medium text-center mb-4">Unggah jadwal yang sudah disusun di luar aplikasi (format Excel).</p>

                        <a href="{{ route('timetable.template') }}" class="w-full mb-4 py-2.5 px-4 bg-slate-900/80 border border-white/10 text-slate-300 font-bold rounded-xl hover:bg-slate-800 transition-all flex items-center justify-center gap-2 text-xs">
                            <i class="ph-bold ph-download-simple text-base text-sky-400"></i> Unduh Template Excel
                        </a>

                        <form id="form-import" onsubmit="handleImportJadwal(event)">
                            <input type="file" name="file" id="importFileInput" accept=".xlsx,.xls" required
                                   class="w-full text-xs font-bold text-slate-400 rounded-xl border border-white/10 bg-slate-900/80 mb-3 file:mr-3 file:py-2 file:px-3 file:rounded-lg file:border-0 file:bg-sky-500/20 file:text-sky-300 file:font-bold file:text-xs [color-scheme:dark]">

                            <label class="flex items-center gap-2 mb-4 text-xs font-bold text-slate-300 cursor-pointer">
                                <input type="checkbox" id="importOverwrite" class="rounded border-white/20 bg-slate-900 text-sky-500 focus:ring-sky-500">
                                Timpa jadwal yang bentrok dengan data di file
                            </label>

                            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg flex items-center justify-center gap-2 text-sm border border-white/10">
                                <i class="ph-bold ph-upload-simple text-lg"></i> Import Jadwal
                            </button>
                        </form>
                    </div>

                    {{-- UNDUH JADWAL (Hanya tampil jika ada jadwal yang di set) --}}
                    @if($hasGenerated)
                    <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 sm:p-6 rounded-[2rem] shadow-2xl border border-white/10 text-white backdrop-blur-xl" x-data="{ tabExp: 'kelas' }">
                        <h3 class="text-lg font-black text-white mb-4 text-center">Unduh Jadwal</h3>
                        <div class="flex bg-slate-900/80 rounded-xl p-1 mb-5 border border-white/10">
                            <button @click="tabExp = 'kelas'" :class="tabExp === 'kelas' ? 'bg-[#0d52a1] shadow-sm text-white font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-xs rounded-lg transition-all">Per Kelas</button>
                            <button @click="tabExp = 'guru'" :class="tabExp === 'guru' ? 'bg-[#0d52a1] shadow-sm text-white font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-xs rounded-lg transition-all">Per Guru</button>
                        </div>

                        <div x-show="tabExp === 'kelas'">
                            <select id="exportClassSelect" class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-white text-sm font-bold mb-4 [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-slate-400">-- Pilih Kelas --</option>
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" class="bg-slate-900 text-white">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <button onclick="exportData('class')" class="w-full py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold rounded-xl shadow-lg transition-all flex justify-center items-center gap-2 text-sm border border-white/10"><i class="ph-bold ph-file-xls text-lg"></i> Unduh Excel Kelas</button>
                        </div>

                        <div x-show="tabExp === 'guru'" style="display: none;">
                            <select id="exportTeacherSelect" class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-white text-sm font-bold mb-4 [color-scheme:dark]">
                                <option value="" class="bg-slate-900 text-slate-400">-- Pilih Guru --</option>
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" class="bg-slate-900 text-white">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                            <button onclick="exportData('teacher')" class="w-full py-3 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg transition-all flex justify-center items-center gap-2 text-sm border border-white/10"><i class="ph-bold ph-file-xls text-lg"></i> Unduh Excel Guru</button>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            {{-- VISUALISASI JADWAL (TIDAK DIBUNGKUS HAS_GENERATED LAGI AGAR SELALU TAMPIL) --}}
            
            {{-- FITUR DRAG AND DROP: BANK SISA JADWAL --}}
            @if(isset($unassignedLoadsList) && count($unassignedLoadsList) > 0)
            <div class="bg-amber-950/40 p-6 rounded-[2rem] shadow-2xl border border-amber-500/30 mb-8 relative overflow-hidden transition-all duration-300 text-white backdrop-blur-xl"
                 id="bank-sisa-container"
                 ondragover="event.preventDefault(); this.classList.add('bg-amber-900/60', 'border-amber-400', 'scale-[1.01]');"
                 ondragleave="this.classList.remove('bg-amber-900/60', 'border-amber-400', 'scale-[1.01]');"
                 ondrop="handleRemoveDrop(event); this.classList.remove('bg-amber-900/60', 'border-amber-400', 'scale-[1.01]');">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-amber-400 to-amber-600"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-amber-500/20 text-amber-400 rounded-full flex items-center justify-center text-xl shadow-sm border border-amber-500/30"><i class="ph-fill ph-warning-circle"></i></div>
                        <div>
                            <h3 class="text-lg font-black text-amber-300">Bank Sisa Jadwal ({{ count($unassignedLoadsList) }} Mapel)</h3>
                            <p class="text-xs font-bold text-amber-200/80">Tarik ke tabel untuk menyusun manual. Lempar kotak dari tabel ke sini untuk menghapusnya.</p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    @foreach($unassignedLoadsList as $item)
                        @php $load = $item['load']; @endphp
                        <div draggable="true"
                             ondragstart="event.dataTransfer.setData('type', 'unassigned'); event.dataTransfer.setData('load_id', '{{ $load->id }}'); event.dataTransfer.setData('class_id', '{{ $load->class_id }}');"
                             class="bg-slate-900/80 border border-amber-500/30 p-3 rounded-xl shadow-sm cursor-move hover:shadow-md hover:-translate-y-1 transition-all w-full sm:w-auto min-w-[200px]">
                            <div class="flex justify-between items-start mb-2 gap-4">
                                <span class="text-[10px] font-bold bg-amber-500/20 text-amber-300 px-2 py-1 rounded-md border border-amber-500/30">{{ $load->studentClass->name ?? 'Kelas ?' }}</span>
                                <span class="text-[10px] font-black text-rose-300 border border-rose-500/30 bg-rose-950/60 px-2 py-1 rounded-md">Sisa {{ $item['sisa'] }} JP</span>
                            </div>
                            <div class="font-black text-white text-sm leading-tight">{{ $load->subject->name }}</div>
                            <div class="text-[10px] text-slate-400 font-bold mt-1.5 uppercase tracking-wide"><i class="ph-fill ph-user text-amber-400"></i> {{ $load->teacher->name }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-6 sm:p-8 rounded-[2rem] shadow-2xl border border-white/10 relative mb-10 overflow-hidden text-white backdrop-blur-xl" 
                 x-data="{ viewType: 'kelas', selectedClass: '{{ $classes->first()->id ?? '' }}', selectedTeacher: '{{ $teachers->first()->id ?? '' }}' }">
                
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>

                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
                    <div>
                        <h3 class="text-xl font-black text-white flex items-center gap-2">
                            <i class="ph-fill ph-calendar-check text-emerald-400"></i> Hasil Jadwal Pelajaran
                        </h3>
                    </div>
                    
                    {{-- Toggle & Filter --}}
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        <div class="flex bg-slate-900/80 rounded-xl p-1 w-full sm:w-auto border border-white/10">
                            <button @click="viewType = 'kelas'" :class="viewType === 'kelas' ? 'bg-[#0d52a1] text-white font-black' : 'text-slate-400 font-bold hover:text-white'" class="px-4 py-2 text-xs rounded-lg transition-all w-full sm:w-auto">Jadwal Kelas</button>
                            <button @click="viewType = 'guru'" :class="viewType === 'guru' ? 'bg-[#0d52a1] text-white font-black' : 'text-slate-400 font-bold hover:text-white'" class="px-4 py-2 text-xs rounded-lg transition-all w-full sm:w-auto">Jadwal Guru (PDF Style)</button>
                        </div>

                        {{-- Filter Kelas --}}
                        <div x-show="viewType === 'kelas'" class="bg-slate-900/80 p-2 rounded-2xl border border-white/10 w-full sm:w-auto">
                            <select x-model="selectedClass" class="w-full sm:w-auto rounded-xl border-none bg-slate-900 text-sm font-bold text-white shadow-sm py-2 [color-scheme:dark]">
                                @foreach($classes as $class)
                                    <option value="{{ $class->id }}" class="bg-slate-900 text-white">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Guru --}}
                        <div x-show="viewType === 'guru'" style="display: none;" class="bg-slate-900/80 p-2 rounded-2xl border border-white/10 w-full sm:w-auto">
                            <select x-model="selectedTeacher" class="w-full sm:w-auto rounded-xl border-none bg-slate-900 text-sm font-bold text-white shadow-sm py-2 [color-scheme:dark]">
                                @foreach($teachers as $teacher)
                                    <option value="{{ $teacher->id }}" class="bg-slate-900 text-white">{{ $teacher->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- PRATINJAU KELAS (TAMPILAN UI MODERN) --}}
                <div x-show="viewType === 'kelas'" class="overflow-x-auto rounded-2xl border border-white/10">
                    @foreach($classes as $class)
                    <div x-show="selectedClass == '{{ $class->id }}'" style="display: none;">
                        <table class="w-full text-left text-sm min-w-[800px]">
                            <thead>
                                <tr>
                                    <th class="p-4 bg-slate-900/80 text-white font-black border-b border-r border-white/10 text-center w-28 uppercase text-xs">Waktu</th>
                                    @foreach($days as $day)
                                        <th class="p-4 bg-slate-900/80 text-white font-black border-b border-r border-white/10 text-center uppercase text-xs">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($timeslots as $slot)
                                <tr>
                                    <td class="p-3 border-b border-r border-white/10 bg-slate-900/40 text-center">
                                        <div class="font-black text-xs mb-1 text-white">{{ $slot->name }}</div>
                                        <div class="inline-block px-2 py-1 bg-slate-800 border border-white/10 rounded-md text-[10px] text-slate-300 font-mono">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</div>
                                    </td>
                                    
                                    @foreach($days as $day)
                                        @php
                                            $slotDays = array_map('trim', explode(',', $slot->day_of_week));
                                            $isValidDay = in_array($day, $slotDays) || $slot->day_of_week === 'Semua Hari' || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
                                            $cellData = $timetables[$class->id][$day][$slot->id] ?? null;
                                        @endphp

                                        @if($slot->is_break && $isValidDay)
                                            <td class="p-4 border-b border-r border-white/10 bg-amber-950/40 text-center text-amber-300 font-black uppercase text-xs">
                                                <i class="ph-bold ph-coffee"></i> {{ $slot->name }}
                                            </td>
                                        @elseif(!$isValidDay)
                                            <td class="p-3 border-b border-r border-white/10 bg-slate-900/20 text-center"><span class="text-[10px] text-slate-600">-</span></td>
                                        @else
                                            
                                            {{-- AREA DROP JADWAL (td) --}}
                                            <td ondragover="event.preventDefault(); this.classList.add('bg-emerald-950/60', 'border-emerald-500/40');"
                                                ondragleave="this.classList.remove('bg-emerald-950/60', 'border-emerald-500/40');"
                                                ondrop="handleDrop(event, '{{ $day }}', '{{ $slot->id }}', '{{ $class->id }}'); this.classList.remove('bg-emerald-950/60', 'border-emerald-500/40');"
                                                class="p-2 border-b border-r border-white/10 text-center transition-all drop-zone relative min-w-[140px] bg-slate-900/60 group">
                                                
                                                @if($cellData)
                                                    {{-- ITEM YANG BISA DI-DRAG (MATA PELAJARAN) --}}
                                                    <div draggable="true"
                                                         ondragstart="event.dataTransfer.setData('type', 'scheduled'); event.dataTransfer.setData('timetable_id', '{{ $cellData->id }}'); event.dataTransfer.setData('class_id', '{{ $class->id }}');"
                                                         class="cursor-move bg-slate-800 p-2 rounded-xl border border-white/10 group-hover:border-sky-400 hover:shadow-md transition-all shadow-sm">
                                                        <div class="font-black text-white text-xs mb-2">{{ $cellData->subject->name }}</div>
                                                        <div class="inline-flex items-center gap-1 px-2 py-1 bg-slate-900 text-[9px] font-bold uppercase rounded-lg border border-white/10 text-slate-300">
                                                            <i class="ph-fill ph-user text-sky-400"></i> {{ $cellData->teacher->name }}
                                                        </div>
                                                    </div>
                                                @else
                                                    {{-- SLOT KOSONG --}}
                                                    <div class="flex items-center justify-center w-full h-full min-h-[4rem]">
                                                        <span class="inline-flex px-3 py-1 bg-slate-900/60 text-slate-500 rounded-md text-[10px] font-bold italic border border-white/10 border-dashed pointer-events-none">Kosong</span>
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
                <div x-show="viewType === 'guru'" style="display: none;" class="overflow-x-auto bg-slate-900/60 p-6 rounded-xl border border-white/10">
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
                                        <th class="border border-black p-2 font-bold w-16">Sesi /<br>Jam Ke</th>
                                        <th class="border border-black p-2 font-bold w-32">Alokasi<br>Waktu</th>
                                        @foreach($days as $day)
                                            <th class="border border-black p-2 font-bold">{{ $day }}<br><br>KELAS</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
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
                                            <td class="border border-black p-2 font-bold">{{ $slot->name }}</td>
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
                confirmButtonColor: '#0d52a1',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Susun Jadwal!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#021124',
                color: '#fff',
                customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white' }
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Menyusun...',
                        allowOutsideClick: false,
                        background: '#021124',
                        color: '#fff',
                        customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white' },
                        didOpen: () => { Swal.showLoading(); document.getElementById('form-generate').submit(); }
                    });
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
                background: '#021124',
                color: '#fff',
                customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white' }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('form-reset').submit();
            });
        }

        function exportData(type) {
            const id = document.getElementById(type === 'class' ? 'exportClassSelect' : 'exportTeacherSelect').value;
            if(!id) return Swal.fire({ icon: 'error', title: 'Oops...', text: 'Pilih data terlebih dahulu!', background: '#021124', color: '#fff', customClass: { popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white' } });
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
                    background: '#021124',
                    color: '#fff',
                    customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' }
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
            Swal.fire({ title: 'Memproses...', allowOutsideClick: false, background: '#021124', color: '#fff', customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' }, didOpen: () => Swal.showLoading() });
            
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
                        background: '#021124', color: '#fff',
                        customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' }
                    }).then(() => location.reload());
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal (Bentrok)!',
                        text: data.message,
                        background: '#021124', color: '#fff',
                        customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' }
                    });
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire({ icon: 'error', title: 'Error!', text: 'Terjadi kesalahan sistem saat menghubungi server.', background: '#021124', color: '#fff', customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' } });
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
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' },
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
                        showConfirmButton: false,
                        background: '#021124', color: '#fff',
                        customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' }
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Gagal!', text: data.message || 'Terjadi kesalahan.', background: '#021124', color: '#fff', customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' } });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem.', background: '#021124', color: '#fff', customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' } });
            });
        }

        function handleImportJadwal(event) {
            event.preventDefault(); // Cegah reload halaman

            let form = document.getElementById('form-import');
            let fileInput = document.getElementById('importFileInput');
            let overwrite = document.getElementById('importOverwrite').checked;

            if (!fileInput.files.length) {
                return Swal.fire({ icon: 'error', title: 'Oops...', text: 'Pilih file Excel terlebih dahulu!', background: '#021124', color: '#fff', customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' } });
            }

            let formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('overwrite', overwrite ? '1' : '0');

            Swal.fire({
                title: 'Mengimport jadwal...',
                allowOutsideClick: false,
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' },
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
                    errorListHtml = '<div class="text-left text-xs mt-3 max-h-48 overflow-y-auto bg-rose-950/60 border border-rose-500/30 rounded-xl p-3 text-rose-300">' +
                        shown.map(e => `<div class="mb-1">&bull; ${e}</div>`).join('') +
                        (data.errors.length > 15 ? `<div class="italic text-slate-400">...dan ${data.errors.length - 15} baris lainnya</div>` : '') +
                        '</div>';
                }

                Swal.fire({
                    icon: data.success ? 'success' : 'error',
                    title: data.success ? 'Import Selesai' : 'Import Gagal',
                    html: `<p class="text-sm">${data.message}</p>${errorListHtml}`,
                    background: '#021124', color: '#fff',
                    customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' }
                }).then(() => {
                    if (data.success) location.reload();
                });
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({ icon: 'error', title: 'Error', text: 'Terjadi kesalahan sistem saat mengimport.', background: '#021124', color: '#fff', customClass: { popup: 'rounded-2xl font-sans border border-white/10 bg-[#021124] text-white' } });
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