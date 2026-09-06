<x-app-layout>
    <div class="py-6 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-6 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60">
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark">
                    <i class="ph-fill ph-calendar-plus text-elevate-primary"></i> Susun Jadwal Mandiri
                </h1>
                <p class="text-elevate-dark/80 text-sm font-semibold max-w-2xl">
                    Pilih mata pelajaran dan kelas pada setiap jam sesuai dengan jadwal yang telah Anda terima. Perubahan akan disimpan secara otomatis dan tersinkronisasi dengan admin.
                </p>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-6 sm:p-8 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-x-auto">
                
                {{-- Indikator Notifikasi Menyimpan --}}
                <div id="save-indicator" class="hidden absolute top-4 right-4 bg-amber-100 text-amber-700 px-3 py-1.5 rounded-xl text-xs font-bold items-center gap-2 shadow-sm z-50">
                    <i class="ph-duotone ph-spinner animate-spin text-lg"></i> Menyimpan...
                </div>

                <table class="w-full text-left text-sm min-w-[900px] mt-4">
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
                            {{-- Kolom Info Jam --}}
                            <td class="p-3 border border-slate-200 bg-slate-50 text-center">
                                <div class="font-black text-xs">{{ $slot->name }}</div>
                                <div class="text-[10px] text-slate-500 font-bold">{{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}</div>
                            </td>

                            {{-- Looping Hari --}}
                            @foreach($days as $day)
                                @php
                                    // Mencegah error explode() jika day_of_week null
                                    $dayOfWeekRaw = $slot->day_of_week ?? '';
                                    $allowedDays = array_map('trim', explode(',', $dayOfWeekRaw));
                                    
                                    $isValidDay = in_array($day, $allowedDays) 
                                        || $dayOfWeekRaw === 'Semua Hari' 
                                        || ($dayOfWeekRaw === 'Selain Senin' && $day !== 'Senin') 
                                        || ($dayOfWeekRaw === 'Selain Jumat' && $day !== 'Jumat');
                                        
                                    $key = $day . '-' . $slot->id;
                                    
                                    // Sinkronisasi logika pencarian data dengan halaman Admin
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
                                    <td class="p-2 border border-slate-200 bg-amber-50 text-center text-amber-700 font-black uppercase text-xs tracking-widest">
                                        {{ $slot->name }}
                                    </td>
                                @elseif(!$isValidDay)
                                    <td class="p-2 border border-slate-200 bg-slate-100/50 text-center"></td>
                                @else
                                    <td class="p-2 border border-slate-200">
                                        {{-- onfocus menyimpan nilai awal agar bisa dikembalikan jika server menolak --}}
                                        <select 
                                            onfocus="this.dataset.previousValue = this.value"
                                            onchange="autoSaveJadwal(this, '{{ $day }}', '{{ $slot->id }}')" 
                                            class="w-full rounded-xl border-slate-300 bg-white text-xs font-bold text-elevate-dark focus:ring-elevate-primary outline-none cursor-pointer py-2"
                                        >
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
        </div>
    </div>

    {{-- Script Auto-Save AJAX --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Variabel untuk melacak request (mengatasi Race Condition jika simpan beruntun)
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
            .then(response => response.json())
            .then(data => {
                activeRequests--;
                if(activeRequests <= 0) {
                    indicator.style.display = 'none';
                }
                selectElement.disabled = false;
                
                if(!data.success) {
                    // Kembalikan ke pilihan sebelumnya jika bentrok
                    selectElement.value = previousValue;
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Bentrok!',
                        text: data.message,
                        customClass: { popup: 'rounded-[2rem] font-sans' }
                    });
                } else {
                    // Jika sukses, perbarui data "previousValue" ke nilai yang baru
                    selectElement.dataset.previousValue = loadId;
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
                
                Swal.fire('Error', 'Gagal menghubungi server. Perubahan dibatalkan.', 'error');
            });
        }
    </script>
</x-app-layout>