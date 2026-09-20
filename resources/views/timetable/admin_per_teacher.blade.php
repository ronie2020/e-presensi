<x-app-layout>
    <div class="py-6 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
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
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-user-circle text-sky-400"></i> Pilih Guru
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-table text-emerald-400"></i> Matriks Senin-Jumat
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-floppy-disk text-cyan-400"></i> Auto Save
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                        <i class="ph-fill ph-users text-sky-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Total Guru:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $teachers->count() }}</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-200 mt-6 mb-6 flex flex-col sm:flex-row items-center gap-4">
                <div class="w-full sm:w-1/3">
                    <label class="block text-xs font-bold text-elevate-primary uppercase mb-2">Pilih Guru untuk Diisi Jadwalnya</label>
                    <select onchange="window.location.href='?teacher_id=' + this.value" class="w-full rounded-xl border-slate-300 bg-slate-50 text-sm font-bold text-elevate-dark py-2.5">
                        <option value="">-- Pilih Guru --</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}" {{ $selectedTeacherId == $teacher->id ? 'selected' : '' }}>
                                {{ $teacher->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full sm:w-2/3 text-sm text-slate-500 font-medium">
                    <i class="ph-fill ph-info text-elevate-primary"></i> Pilih guru terlebih dahulu untuk menampilkan form matriks jadwal mengajar.
                </div>
            </div>

            @if($selectedTeacherId)
            <div class="bg-white p-6 sm:p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-x-auto">
                <div id="save-indicator" class="hidden absolute top-4 right-4 bg-amber-100 text-amber-700 px-3 py-1.5 rounded-xl text-xs font-bold items-center gap-2 shadow-sm">
                    <i class="ph-duotone ph-spinner animate-spin text-lg"></i> Menyimpan...
                </div>

                <table class="w-full text-left text-sm min-w-[900px]">
                    <thead>
                        <tr>
                            <th class="p-4 bg-slate-100 text-elevate-dark font-black border border-slate-200 text-center w-28 text-xs uppercase rounded-tl-xl">Waktu</th>
                            @foreach($days as $day)
                                <th class="p-4 bg-slate-100 text-elevate-dark font-black border border-slate-200 text-center text-xs uppercase {{ $loop->last ? 'rounded-tr-xl' : '' }}">{{ $day }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($timeslots as $slot)
                        <tr>
                            <td class="p-3 border border-slate-200 bg-slate-50 text-center">
                                <div class="font-black text-xs">{{ $slot->name }}</div>
                                <div class="text-[10px] text-slate-500 font-bold">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</div>
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
                                    <td class="p-2 border border-slate-200 bg-amber-50 text-center text-amber-700 font-black uppercase text-xs tracking-widest">{{ $slot->name }}</td>
                                @elseif(!$isValidDay)
                                    <td class="p-2 border border-slate-200 bg-slate-100/50 text-center"></td>
                                @else
                                    <td class="p-2 border border-slate-200">
                                        <select onchange="autoSaveJadwal(this, '{{ $day }}', '{{ $slot->id }}')" class="w-full rounded-xl border-slate-300 bg-white text-xs font-bold text-elevate-dark focus:ring-elevate-primary outline-none cursor-pointer py-2">
                                            <option value="" class="text-slate-400">-- Kosong --</option>
                                            @foreach($teachingLoads as $load)
                                                <option value="{{ $load->id }}" {{ $currentLoadId == $load->id ? 'selected' : '' }}>
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
                    Swal.fire({ icon: 'error', title: 'Bentrok!', text: data.message, customClass: { popup: 'rounded-[2rem] font-sans' } });
                    selectElement.value = '';
                }
            })
            .catch(error => {
                indicator.style.display = 'none';
                selectElement.disabled = false;
                Swal.fire('Error', 'Gagal menghubungi server', 'error');
            });
        }
    </script>
</x-app-layout>