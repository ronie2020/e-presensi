<x-app-layout>
    <div class="py-4 sm:py-6 font-sans text-white relative">
        <div class="mb-8 relative z-10">
            <x-hero-section
                badge="MATRIKS JADWAL GURU"
                badgeIcon="ph-fill ph-chalkboard-teacher"
                showcaseIcon="ph-duotone ph-calendar-check"
                showcaseTitle="Jadwal Per Guru"
                showcaseSubtitle="Plotting Matriks Mingguan">
                <x-slot:title>
                    <span class="block text-slate-100">Matriks Jadwal</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Per Tenaga Pendidik
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Isi dan sesuaikan matriks jadwal mengajar mingguan untuk setiap guru secara spesifik dan terstruktur.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-slate-300 text-xs font-semibold backdrop-blur-sm">
                        <i class="ph-bold ph-user-circle text-sky-400"></i> Pilih Guru
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-slate-300 text-xs font-semibold backdrop-blur-sm">
                        <i class="ph-bold ph-table text-emerald-400"></i> Matriks Senin-Jumat
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/5 border border-white/10 text-slate-300 text-xs font-semibold backdrop-blur-sm">
                        <i class="ph-bold ph-floppy-disk text-cyan-400"></i> Auto Save
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-[#021124]/80 border border-white/15 backdrop-blur-md">
                        <i class="ph-fill ph-users text-sky-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Total Guru:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $teachers->count() }}</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>

            {{-- Filter Guru Card --}}
            <div class="rounded-2xl bg-[#031d3d]/80 border border-white/15 p-5 sm:p-6 backdrop-blur-xl mb-6 shadow-xl shadow-black/30 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-full sm:w-1/3">
                    <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2">Pilih Guru untuk Diisi Jadwalnya</label>
                    <select onchange="window.location.href='?teacher_id=' + this.value" class="w-full rounded-xl border border-white/15 bg-[#021124] text-sm font-bold text-white py-2.5 px-3 focus:ring-2 focus:ring-sky-400 outline-none">
                        <option value="" class="bg-[#031d3d] text-slate-400">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $selectedTeacherId == $teacher->id ? 'selected' : '' }} class="bg-[#031d3d] text-white">
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-2/3 text-xs sm:text-sm text-slate-400 font-medium flex items-center gap-2">
                    <i class="ph-fill ph-info text-sky-400 text-lg shrink-0"></i>
                    <span>Pilih guru terlebih dahulu untuk menampilkan formulir matriks jadwal mengajar mingguan.</span>
                </div>
            </div>

            @if($selectedTeacherId)
            <div class="rounded-[2.5rem] bg-[#031d3d]/85 backdrop-blur-2xl border border-white/15 p-5 sm:p-8 shadow-2xl shadow-black/50 relative overflow-hidden">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-400 via-blue-500 to-indigo-500"></div>

                <div id="save-indicator" class="hidden absolute top-5 right-6 bg-sky-500/20 text-sky-300 border border-sky-400/40 px-4 py-2 rounded-2xl text-xs font-bold items-center gap-2.5 shadow-xl backdrop-blur-xl z-50 animate-pulse">
                    <i class="ph-bold ph-spinner animate-spin text-lg text-sky-400"></i>
                    <span>Menyimpan jadwal...</span>
                </div>

                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-black text-white flex items-center gap-2.5">
                            <i class="ph-duotone ph-calendar-check text-sky-400 text-2xl"></i>
                            <span>Matriks Jadwal Mingguan</span>
                        </h3>
                        <p class="text-xs text-slate-400 mt-1">Plotting jadwal mengajar untuk guru terpilih.</p>
                    </div>

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
                                <td class="p-3 bg-[#021124]/60 border border-white/10 rounded-2xl text-center backdrop-blur-md">
                                    <div class="font-black text-xs text-slate-100 tracking-tight">{{ $slot->name }}</div>
                                    <div class="mt-1 inline-flex items-center gap-1 px-2 py-0.5 rounded-lg bg-sky-500/10 border border-sky-400/20 text-[10px] font-bold font-mono text-sky-300">
                                        <i class="ph-bold ph-clock text-[9px]"></i>
                                        {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                    </div>
                                </td>

                                @foreach($days as $day)
                                    @php
                                        $isValidDay = in_array($day, array_map('trim', explode(',', $slot->day_of_week))) || $slot->day_of_week === 'Semua Hari' || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
                                        $key = $day . '-' . $slot->id;
                                        
                                        $currentLoadId = '';
                                        if(isset($myTimetables[$key])) {
                                            $matchedLoad = $teachingLoads->where('class_id', $myTimetables[$key]->class_id)
                                                                         ->where('subject_id', $myTimetables[$key]->subject_id)
                                                                         ->first();
                                            if($matchedLoad) $currentLoadId = $matchedLoad->id;
                                        }
                                    @endphp

                                    @if($slot->is_break && $isValidDay)
                                        <td class="p-3 bg-amber-500/10 border border-amber-400/25 rounded-2xl text-center backdrop-blur-md">
                                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-400/15 border border-amber-400/30 text-amber-300 font-extrabold uppercase text-[11px] tracking-wider shadow-sm">
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
                                            <select onchange="autoSaveJadwal(this, '{{ $day }}', '{{ $slot->id }}')" class="w-full rounded-xl text-xs font-bold transition-all outline-none cursor-pointer py-2.5 px-3 border {{ $currentLoadId ? 'bg-sky-500/15 border-sky-400/40 text-sky-200 focus:ring-2 focus:ring-sky-400 shadow-sm' : 'bg-[#021124]/80 border-white/10 text-slate-400 hover:border-white/25 focus:ring-2 focus:ring-sky-400' }}">
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
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function autoSaveJadwal(selectElement, day, timeslotId) {
            const loadId = selectElement.value;
            const teacherId = '{{ $selectedTeacherId }}';
            const indicator = document.getElementById('save-indicator');
            
            indicator.style.display = 'flex';
            selectElement.disabled = true;

            fetch('{{ route("admin.timetable_manual.save_ajax") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    teacher_id: teacherId,
                    day: day,
                    timeslot_id: timeslotId,
                    teaching_load_id: loadId
                })
            })
            .then(response => response.json())
            .then(data => {
                indicator.style.display = 'none';
                selectElement.disabled = false;
                
                if(!data.success) {
                    Swal.fire({ 
                        icon: 'error', 
                        title: 'Bentrok!', 
                        text: data.message, 
                        customClass: { popup: 'rounded-[2rem] font-sans bg-[#031d3d] text-white border border-white/20' } 
                    });
                    selectElement.value = '';
                    selectElement.className = "w-full rounded-xl text-xs font-bold transition-all outline-none cursor-pointer py-2.5 px-3 border bg-[#021124]/80 border-white/10 text-slate-400 hover:border-white/25 focus:ring-2 focus:ring-sky-400";
                } else {
                    if (loadId) {
                        selectElement.className = "w-full rounded-xl text-xs font-bold transition-all outline-none cursor-pointer py-2.5 px-3 border bg-sky-500/15 border-sky-400/40 text-sky-200 focus:ring-2 focus:ring-sky-400 shadow-sm";
                    } else {
                        selectElement.className = "w-full rounded-xl text-xs font-bold transition-all outline-none cursor-pointer py-2.5 px-3 border bg-[#021124]/80 border-white/10 text-slate-400 hover:border-white/25 focus:ring-2 focus:ring-sky-400";
                    }
                }
            })
            .catch(error => {
                indicator.style.display = 'none';
                selectElement.disabled = false;
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal menghubungi server.',
                    customClass: { popup: 'rounded-[2rem] font-sans bg-[#031d3d] text-white border border-white/20' }
                });
            });
        }
    </script>
</x-app-layout>