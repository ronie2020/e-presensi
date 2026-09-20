<x-app-layout>
    @php
        $totalHoursAssigned = $teachingLoads->sum('hours_per_week');
        $totalSlotsFilled = $myTimetables->count();
        $remainingHours = max(0, $totalHoursAssigned - $totalSlotsFilled);
    @endphp

    <div class="py-4 sm:py-6 font-sans text-white relative">
        
        {{-- HERO SECTION --}}
        <div class="mb-8 relative z-10">
            <x-hero-section
                badge="Plotting Mandiri"
                badgeIcon="ph-calendar-plus"
                title="Susun Jadwal"
                titleHighlight="Pribadi Guru"
                description="Pilih mata pelajaran dan rombel kelas pada setiap jam pelajaran sesuai jadwal tugas. Perubahan tersimpan otomatis dan tersinkronisasi ke server."
                :chips="[
                    ['icon' => 'ph-calendar-check', 'label' => 'Jadwal Mingguan'],
                    ['icon' => 'ph-arrows-clockwise', 'label' => 'Auto-Save Cloud'],
                    ['icon' => 'ph-printer', 'label' => 'Export PDF & Excel']
                ]"
                heroIcon="ph-calendar-plus"
                :showcaseNumber="$totalSlotsFilled"
                showcaseLabel="Jam Terjadwal"
                showcaseSubtitle="dari total {{ $totalHoursAssigned }} JP"
                statusOrb="{{ $totalSlotsFilled >= $totalHoursAssigned && $totalHoursAssigned > 0 ? 'Lengkap' : 'Sedang Disusun' }}"
                statusColor="{{ $totalSlotsFilled >= $totalHoursAssigned && $totalHoursAssigned > 0 ? 'emerald' : 'amber' }}"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('teacher.timetable.export_pdf') }}" class="px-5 py-2.5 bg-gradient-to-r from-rose-500 to-red-600 hover:from-rose-600 hover:to-red-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-rose-500/25 hover:shadow-rose-500/40 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                            <i class="ph-bold ph-file-pdf text-base"></i> Cetak PDF
                        </a>
                        <a href="{{ route('teacher.timetable.export_excel') }}" class="px-5 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/40 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                            <i class="ph-bold ph-file-xls text-base"></i> Unduh Excel
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-arrow-left text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>
        </div>

        {{-- STATS & BEBAN SUMMARY CARDS --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
            {{-- Card 1: Total Beban --}}
            <div class="rounded-2xl bg-[#031d3d]/70 border border-white/10 p-4 backdrop-blur-xl flex items-center gap-4 shadow-lg shadow-black/20">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-sky-400 to-[#0d52a1] flex items-center justify-center text-white text-2xl shadow-md shrink-0 border border-white/20">
                    <i class="ph-bold ph-chalkboard-teacher"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-sky-300 uppercase tracking-wider">Total Beban Mengajar</div>
                    <div class="text-2xl font-black text-white font-mono tracking-tight leading-none mt-1">
                        {{ $totalHoursAssigned }} <span class="text-xs font-semibold text-slate-400">JP / Pekan</span>
                    </div>
                </div>
            </div>

            {{-- Card 2: Sudah Terplot --}}
            <div class="rounded-2xl bg-[#031d3d]/70 border border-white/10 p-4 backdrop-blur-xl flex items-center gap-4 shadow-lg shadow-black/20">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-tr from-emerald-400 to-teal-700 flex items-center justify-center text-white text-2xl shadow-md shrink-0 border border-white/20">
                    <i class="ph-bold ph-check-circle"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold text-emerald-300 uppercase tracking-wider">Sudah Diplot</div>
                    <div class="text-2xl font-black text-white font-mono tracking-tight leading-none mt-1">
                        {{ $totalSlotsFilled }} <span class="text-xs font-semibold text-slate-400">Jam Terisi</span>
                    </div>
                </div>
            </div>

            {{-- Card 3: Sisa Jam --}}
            <div class="rounded-2xl bg-[#031d3d]/70 border border-white/10 p-4 backdrop-blur-xl flex items-center gap-4 shadow-lg shadow-black/20">
                <div class="w-12 h-12 rounded-xl {{ $remainingHours == 0 ? 'bg-gradient-to-tr from-emerald-500 to-teal-700' : 'bg-gradient-to-tr from-amber-400 to-orange-600' }} flex items-center justify-center text-white text-2xl shadow-md shrink-0 border border-white/20">
                    <i class="ph-bold {{ $remainingHours == 0 ? 'ph-sparkle' : 'ph-clock-countdown' }}"></i>
                </div>
                <div>
                    <div class="text-[10px] font-bold {{ $remainingHours == 0 ? 'text-emerald-300' : 'text-amber-300' }} uppercase tracking-wider">
                        {{ $remainingHours == 0 ? 'Status Pengisian' : 'Sisa Belum Diplot' }}
                    </div>
                    <div class="text-2xl font-black text-white font-mono tracking-tight leading-none mt-1">
                        @if($remainingHours == 0)
                            <span class="text-emerald-400 text-lg">Selesai (100%)</span>
                        @else
                            {{ $remainingHours }} <span class="text-xs font-semibold text-slate-400">Jam Lagi</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- MATRIKS JADWAL CARD CONTAINER --}}
        <div class="rounded-[2.5rem] bg-[#031d3d]/85 backdrop-blur-2xl border border-white/15 p-5 sm:p-8 shadow-2xl shadow-black/50 relative overflow-hidden">
            {{-- Glowing Accent Top Rim --}}
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500"></div>

            {{-- Indikator Notifikasi Menyimpan --}}
            <div id="save-indicator" class="hidden absolute top-5 right-6 bg-sky-500/20 text-sky-300 border border-sky-400/40 px-4 py-2 rounded-2xl text-xs font-bold items-center gap-2.5 shadow-xl backdrop-blur-xl z-50 animate-pulse">
                <i class="ph-bold ph-spinner animate-spin text-lg text-sky-400"></i>
                <span>Menyimpan ke server...</span>
            </div>

            {{-- Card Header & Legend --}}
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                <div>
                    <h3 class="text-lg sm:text-xl font-black text-white flex items-center gap-2.5">
                        <i class="ph-duotone ph-calendar-check text-sky-400 text-2xl"></i>
                        <span>Matriks Jadwal Mingguan</span>
                    </h3>
                    <p class="text-xs text-slate-400 mt-1">Pilih mata pelajaran & rombel kelas pada setiap slot jam pelajaran.</p>
                </div>

                {{-- Legend Badges --}}
                <div class="flex flex-wrap items-center gap-2 text-[11px] font-bold">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-sky-500/15 border border-sky-400/30 text-sky-300">
                        <span class="w-2 h-2 rounded-full bg-sky-400"></span> Mapel Terjadwal
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-500/15 border border-amber-400/30 text-amber-300">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span> Pembiasaan / Upacara
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-slate-400">
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span> Kosong
                    </span>
                </div>
            </div>

            {{-- Hint Mobile Scroll --}}
            <div class="flex items-center gap-1.5 text-xs text-slate-400 sm:hidden mb-3">
                <i class="ph-bold ph-arrows-left-right text-sky-400"></i>
                <span>Geser tabel ke samping untuk melihat seluruh hari</span>
            </div>

            {{-- Table Wrapper --}}
            <div class="overflow-x-auto custom-scrollbar -mx-2 sm:mx-0">
                <table class="w-full text-left text-sm min-w-[950px] border-separate border-spacing-y-2 border-spacing-x-2">
                    <thead>
                        <tr>
                            <th class="p-3.5 bg-[#021124]/90 text-sky-300 font-extrabold text-xs uppercase tracking-wider text-center w-40 rounded-2xl border border-white/10 shadow-sm">
                                <div class="flex items-center justify-center gap-1.5">
                                    <i class="ph-bold ph-clock text-sky-400"></i>
                                    <span>Waktu</span>
                                </div>
                            </th>
                            @foreach($days as $day)
                                <th class="p-3.5 bg-[#021124]/90 text-white font-extrabold text-xs uppercase tracking-wider text-center rounded-2xl border border-white/10 shadow-sm">
                                    <div class="flex items-center justify-center gap-1.5">
                                        <i class="ph-bold ph-calendar-blank text-sky-400"></i>
                                        <span>{{ $day }}</span>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeslots as $slot)
                        <tr>
                            {{-- Kolom Info Jam --}}
                            <td class="p-3 bg-[#021124]/60 border border-white/10 rounded-2xl text-center backdrop-blur-md">
                                <div class="font-black text-xs text-slate-100 tracking-tight">{{ $slot->name }}</div>
                                <div class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-sky-500/10 border border-sky-400/20 text-[10px] font-bold font-mono text-sky-300">
                                    <i class="ph-bold ph-clock text-[9px]"></i>
                                    {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                </div>
                            </td>

                            {{-- Looping Hari --}}
                            @foreach($days as $day)
                                @php
                                    $dayOfWeekRaw = $slot->day_of_week ?? '';
                                    $allowedDays = array_map('trim', explode(',', $dayOfWeekRaw));
                                    
                                    $isValidDay = in_array($day, $allowedDays) 
                                        || $dayOfWeekRaw === 'Semua Hari' 
                                        || ($dayOfWeekRaw === 'Selain Senin' && $day !== 'Senin') 
                                        || ($dayOfWeekRaw === 'Selain Jumat' && $day !== 'Jumat');
                                        
                                    $key = $day . '-' . $slot->id;
                                    
                                    $currentLoadId = '';
                                    if(isset($myTimetables[$key])) {
                                        if (isset($myTimetables[$key]->teaching_load_id) && $myTimetables[$key]->teaching_load_id != null) {
                                            $currentLoadId = $myTimetables[$key]->teaching_load_id;
                                        } else {
                                            $matchedLoad = $teachingLoads->where('class_id', $myTimetables[$key]->class_id)
                                                                         ->where('subject_id', $myTimetables[$key]->subject_id)
                                                                         ->first();
                                            if($matchedLoad) $currentLoadId = $matchedLoad->id;
                                        }
                                    }
                                @endphp

                                @if($slot->is_break && $isValidDay)
                                    <td class="p-3 bg-amber-500/10 border border-amber-400/25 rounded-2xl text-center backdrop-blur-md">
                                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-400/15 border border-amber-400/30 text-amber-300 font-black uppercase text-[11px] tracking-wider shadow-sm">
                                            @if(str_contains(strtolower($slot->name), 'shalat') || str_contains(strtolower($slot->name), 'dhuha'))
                                                <i class="ph-fill ph-hands-praying text-sm text-amber-300"></i>
                                            @elseif(str_contains(strtolower($slot->name), 'upacara'))
                                                <i class="ph-fill ph-flag text-sm text-amber-300"></i>
                                            @elseif(str_contains(strtolower($slot->name), 'istirahat') || str_contains(strtolower($slot->name), 'makan'))
                                                <i class="ph-fill ph-coffee text-sm text-amber-300"></i>
                                            @else
                                                <i class="ph-fill ph-sparkle text-sm text-amber-300"></i>
                                            @endif
                                            <span>{{ $slot->name }}</span>
                                        </div>
                                    </td>
                                @elseif(!$isValidDay)
                                    <td class="p-2 bg-white/[0.015] border border-white/5 rounded-2xl text-center">
                                        <span class="text-slate-600 text-xs select-none">•</span>
                                    </td>
                                @else
                                    <td class="p-2 bg-[#021124]/40 border border-white/10 rounded-2xl transition-all hover:border-white/20">
                                        <select 
                                            onfocus="this.dataset.previousValue = this.value"
                                            onchange="autoSaveJadwal(this, '{{ $day }}', '{{ $slot->id }}')" 
                                            class="w-full rounded-xl text-xs font-bold transition-all outline-none cursor-pointer py-2.5 px-3 border {{ $currentLoadId ? 'bg-sky-500/15 border-sky-400/40 text-sky-200 focus:ring-2 focus:ring-sky-400 shadow-sm' : 'bg-[#021124]/80 border-white/10 text-slate-400 hover:border-white/25 focus:ring-2 focus:ring-sky-400' }}"
                                        >
                                            <option value="" class="bg-[#031d3d] text-slate-400">-- Kosong --</option>
                                            @foreach($teachingLoads as $load)
                                                <option value="{{ $load->id }}" {{ $currentLoadId == $load->id ? 'selected' : '' }} class="bg-[#031d3d] text-white py-1">
                                                    {{ $load->subject->name }} ({{ $load->studentClass->name }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                @endif
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Script Auto-Save AJAX --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        let activeRequests = 0;

        function autoSaveJadwal(selectElement, day, timeslotId) {
            const loadId = selectElement.value;
            const previousValue = selectElement.dataset.previousValue || '';
            const indicator = document.getElementById('save-indicator');
            
            activeRequests++;
            indicator.style.display = 'flex';
            selectElement.disabled = true;

            fetch('{{ route("teacher.timetable.save_ajax") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    day: day,
                    timeslot_id: timeslotId,
                    teaching_load_id: loadId
                })
            })
            .then(response => response.json().then(data => ({ status: response.status, body: data })))
            .then(({ status, body }) => {
                activeRequests--;
                if(activeRequests <= 0) {
                    indicator.style.display = 'none';
                }
                selectElement.disabled = false;
                
                if(!body.success) {
                    selectElement.value = previousValue;
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Bentrok!',
                        text: body.message || 'Jadwal bentrok atau gagal disimpan.',
                        customClass: { popup: 'rounded-[2rem] font-sans bg-[#031d3d] text-white border border-white/20' }
                    });
                } else {
                    selectElement.dataset.previousValue = loadId;
                    
                    // Update visual class styling dynamically
                    if (loadId) {
                        selectElement.className = "w-full rounded-xl text-xs font-bold transition-all outline-none cursor-pointer py-2.5 px-3 border bg-sky-500/15 border-sky-400/40 text-sky-200 focus:ring-2 focus:ring-sky-400 shadow-sm";
                    } else {
                        selectElement.className = "w-full rounded-xl text-xs font-bold transition-all outline-none cursor-pointer py-2.5 px-3 border bg-[#021124]/80 border-white/10 text-slate-400 hover:border-white/25 focus:ring-2 focus:ring-sky-400";
                    }
                }
            })
            .catch(error => {
                console.error(error);
                activeRequests--;
                if(activeRequests <= 0) {
                    indicator.style.display = 'none';
                }
                
                selectElement.disabled = false;
                selectElement.value = previousValue; 
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menghubungi server. Perubahan dibatalkan.',
                    customClass: { popup: 'rounded-[2rem] font-sans bg-[#031d3d] text-white border border-white/20' }
                });
            });
        }
    </script>
</x-app-layout>