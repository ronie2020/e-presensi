@php
    \Carbon\Carbon::setLocale('id');
    $todayName = \Carbon\Carbon::now()->translatedFormat('l');
    $defaultDay = ($todayName == 'Minggu') ? 'Senin' : $todayName;

    // --- MENGAMBIL DATA DARI SISTEM TIMETABLE YANG BARU ---
    $classId = $student->school_class_id ?? $student->class_id ?? optional($student->schoolClass)->id;
    $schedules = collect([]);
    $timeslots = collect([]);

    if ($classId && class_exists(\App\Models\Timetable::class)) {
        $schedules = \App\Models\Timetable::with(['timeslot', 'subject', 'teacher'])
                        ->where('class_id', $classId)
                        ->get();
        $timeslots = \App\Models\Timeslot::orderBy('order_sequence')->get();
    }
@endphp

<div x-data="{ 
        viewMode: 'mingguan', 
        activeDay: '{{ $defaultDay }}',
        triggerCalendarResize() {
            setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 150);
        }
    }" class="space-y-8 animate-in fade-in duration-500 font-sans">

    {{-- TOGGLE SUB-MENU --}}
    <div class="flex justify-center mb-4">
        <div class="bg-slate-100 p-1.5 rounded-[1.5rem] inline-flex shadow-inner border border-slate-200">
            <button @click="viewMode = 'mingguan'" 
                class="px-6 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 flex items-center gap-2"
                :class="viewMode === 'mingguan' ? 'bg-white shadow-md text-elevate-primary ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700'">
                <i class="ph-bold ph-list-numbers text-lg"></i> Mingguan
            </button>
            <button @click="viewMode = 'kalender'; triggerCalendarResize()" 
                class="px-6 py-2.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 flex items-center gap-2"
                :class="viewMode === 'kalender' ? 'bg-white shadow-md text-elevate-primary ring-1 ring-black/5' : 'text-slate-500 hover:text-slate-700'">
                <i class="ph-bold ph-calendar-blank text-lg"></i> Kalender
            </button>
        </div>
    </div>

    {{-- VIEW 1: JADWAL MINGGUAN --}}
    <div x-show="viewMode === 'mingguan'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="space-y-8">
         
        {{-- NAVIGASI HARI --}}
        <div class="sticky top-20 z-30 bg-slate-50/90 backdrop-blur-md py-2 -mx-4 px-4 sm:mx-0 sm:px-0 sm:bg-transparent sm:backdrop-filter-none transition-all">
            <div class="flex overflow-x-auto gap-3 no-scrollbar snap-x py-2 custom-scrollbar">
                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                    <button @click="activeDay = '{{ $day }}'" 
                        class="snap-start shrink-0 relative px-6 py-3 rounded-2xl font-bold text-sm transition-all duration-300 border overflow-hidden group"
                        :class="activeDay === '{{ $day }}' 
                            ? 'bg-elevate-dark text-white border-elevate-dark shadow-lg shadow-elevate-dark/30 scale-105' 
                            : 'bg-white text-slate-500 border-slate-200 hover:border-elevate-accent/50 hover:text-elevate-primary'">
                        
                        @if($day == $todayName)
                            <span class="absolute top-2 right-2 flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-peach opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-elevate-peach-dark border border-white"></span>
                            </span>
                        @endif
                        <span class="relative z-10">{{ $day }}</span>
                    </button>
                @endforeach
            </div>
        </div>

        {{-- KONTAINER JADWAL --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 min-h-[500px] relative overflow-hidden">
            <div class="absolute top-0 right-0 w-96 h-96 bg-elevate-primary/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

            @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'] as $day)
                <div x-show="activeDay === '{{ $day }}'" 
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 translate-x-8"
                     x-transition:enter-end="opacity-100 translate-x-0"
                     class="p-6 sm:p-10 relative z-10"
                     style="display: none;">
                    
                    {{-- Header Hari --}}
                    <div class="flex items-end justify-between mb-10 border-b border-slate-50 pb-4">
                        <div>
                            <h2 class="text-4xl font-black text-elevate-dark tracking-tighter mb-1">{{ $day }}</h2>
                            <div class="flex items-center gap-2 text-slate-500 text-sm font-medium">
                                <i class="ph-bold ph-chalkboard-teacher text-elevate-primary"></i>
                                <span>Kelas {{ $student->schoolClass->name ?? '-' }}</span>
                            </div>
                        </div>
                        @if($day == $todayName)
                            <div class="hidden sm:flex flex-col items-end">
                                <span class="text-[10px] font-black uppercase tracking-widest text-elevate-peach-dark mb-1">Status</span>
                                <span class="px-4 py-1.5 bg-elevate-peach-light/20 text-elevate-peach-dark text-xs font-black rounded-full border border-elevate-peach/30 shadow-sm flex items-center gap-1">
                                    <i class="ph-fill ph-sun"></i> Hari Ini
                                </span>
                            </div>
                        @endif
                    </div>

                   <div class="relative space-y-0">
                        <div class="absolute left-[2.25rem] top-4 bottom-4 w-0.5 bg-slate-100"></div>

                        @php 
                            $daySchedules = $schedules->where('day_of_week', $day)->sortBy(function($q) { return $q->timeslot->order_sequence ?? 0; })->values();
                            
                            // LOGIKA GROUPING KHUSUS TABEL TIMELINE
                            $groupedMap = []; // Menyimpan grup berdasarkan slot pertama
                            $skipSlots = [];  // Menyimpan slot lanjutan yang harus di-skip agar tidak double render
                            $currG = null;
                            
                            foreach($daySchedules as $s) {
                                if(!$currG) { 
                                    $currG = collect([$s]); 
                                } else {
                                    $last = $currG->last();
                                    if($last->subject_id == $s->subject_id && $last->teacher_id == $s->teacher_id) {
                                        $currG->push($s);
                                        $skipSlots[] = $s->timeslot_id;
                                    } else {
                                        $groupedMap[$currG->first()->timeslot_id] = $currG;
                                        $currG = collect([$s]);
                                    }
                                }
                            }
                            if($currG) { $groupedMap[$currG->first()->timeslot_id] = $currG; }

                            $hasAnySession = false;
                        @endphp

                        {{-- Loop berdasarkan Slot Waktu --}}
                        @foreach($timeslots as $slot)
                            @php
                                $slotDays = array_map('trim', explode(',', $slot->day_of_week ?? 'Semua Hari'));
                                $isValidDay = in_array($day, $slotDays) || $slot->day_of_week === 'Semua Hari' || ($slot->day_of_week === 'Selain Senin' && $day !== 'Senin') || ($slot->day_of_week === 'Selain Jumat' && $day !== 'Jumat');
                            @endphp

                            @if($isValidDay)
                                @php $hasAnySession = true; @endphp

                                @if($slot->is_break)
                                    {{-- UI ISTIRAHAT / UPACARA --}}
                                    <div class="relative pl-24 py-6">
                                        <div class="absolute left-[2.25rem] top-1/2 -translate-y-1/2 w-4 h-4 bg-elevate-peach rounded-full border-4 border-white shadow-sm z-10"></div>
                                        <div class="bg-elevate-peach-light/10 rounded-2xl p-4 border border-elevate-peach/30 border-dashed flex flex-col sm:flex-row sm:items-center gap-4 opacity-80 hover:opacity-100 transition-opacity">
                                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-elevate-peach-dark shadow-sm shrink-0 border border-elevate-peach/20">
                                                <i class="ph-fill {{ (str_contains(strtolower($slot->name), 'sholat') || str_contains(strtolower($slot->name), 'dhuha')) ? 'ph-mosque' : (str_contains(strtolower($slot->name), 'upacara') ? 'ph-flag' : 'ph-coffee') }} text-xl"></i>
                                            </div>
                                            <div>
                                                <h4 class="font-black text-elevate-peach-dark text-sm">{{ $slot->name }}</h4>
                                                <span class="text-[10px] font-bold text-elevate-peach bg-white px-2 py-0.5 rounded shadow-sm border border-elevate-peach/20 mt-1 inline-block">
                                                    <i class="ph-bold ph-clock"></i> {{ \Carbon\Carbon::parse($slot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($slot->end_time)->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- JIKA SLOT INI ADALAH LANJUTAN DARI BLOK SEBELUMNYA, SKIP RENDER --}}
                                    @if(in_array($slot->id, $skipSlots))
                                        @continue
                                    @endif

                                    {{-- UI MATA PELAJARAN (DIGABUNGKAN) --}}
                                    @if(isset($groupedMap[$slot->id]))
                                        @php
                                            $group = $groupedMap[$slot->id];
                                            $firstSched = $group->first();
                                            $lastSched = $group->last();
                                            
                                            $orderDisplay = $firstSched->timeslot->order_sequence == $lastSched->timeslot->order_sequence 
                                                            ? $firstSched->timeslot->order_sequence 
                                                            : $firstSched->timeslot->order_sequence . '-' . $lastSched->timeslot->order_sequence;
                                            
                                            $colorThemes = [
                                                ['bg' => 'bg-elevate-soft/50', 'border' => 'border-elevate-accent/30', 'text' => 'text-elevate-primary', 'line' => 'bg-elevate-accent', 'hover' => 'hover:border-elevate-primary/40'],
                                                ['bg' => 'bg-elevate-peach-light/10', 'border' => 'border-elevate-peach/30', 'text' => 'text-elevate-peach-dark', 'line' => 'bg-elevate-peach', 'hover' => 'hover:border-elevate-peach/60'],
                                                ['bg' => 'bg-emerald-50/50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-600', 'line' => 'bg-emerald-400', 'hover' => 'hover:border-emerald-400']
                                            ];
                                            $t = $colorThemes[crc32($firstSched->subject->name ?? 'X') % count($colorThemes)];
                                        @endphp

                                        <div class="relative pl-24 py-4 group">
                                            <!-- Box Urutan Sesi (Kiri) -->
                                            <div class="absolute left-2 top-4 w-16 h-16 rounded-2xl bg-white border-2 border-slate-100 shadow-sm flex flex-col items-center justify-center z-10 group-hover:scale-110 transition-transform duration-300 {{ $t['hover'] }}">
                                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-tight">Sesi Ke</span>
                                                <div class="flex items-center text-lg font-black text-elevate-dark">
                                                    {{ $orderDisplay }}
                                                </div>
                                            </div>

                                            <!-- Kartu Pelajaran (Kanan) -->
                                            <div class="bg-white rounded-3xl p-4 sm:p-5 border border-slate-100 shadow-sm hover:shadow-lg transition-all duration-300 relative overflow-hidden group-hover:-translate-y-1 {{ $t['hover'] ?? '' }}">
                                                <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $t['line'] ?? '' }}"></div>
                                                <div class="flex flex-col sm:flex-row justify-between sm:items-start gap-4 pl-2">
                                                    <div>
                                                        <div class="flex items-center gap-2 mb-1.5">
                                                            <span class="text-[9px] font-black uppercase tracking-wider text-slate-500 bg-slate-50 px-2 py-0.5 rounded border border-slate-100">{{ $group->count() > 1 ? $group->count() . ' Jam Pelajaran' : '1 Jam Pelajaran' }}</span>
                                                        </div>
                                                        <h3 class="text-base sm:text-lg font-black text-elevate-dark mb-2 {{ "group-hover:{$t['text']}" }} transition-colors line-clamp-1">
                                                            {{ optional($firstSched->subject)->name ?? 'Mata Pelajaran Tidak Diketahui' }}
                                                        </h3>
                                                        <div class="flex flex-wrap items-center gap-3">
                                                            <div class="flex items-center gap-1.5 bg-slate-50 px-3 py-1.5 rounded-lg border border-slate-100">
                                                                <i class="ph-fill ph-user-circle text-slate-400"></i>
                                                                <span class="text-xs font-bold text-slate-600">{{ optional($firstSched->teacher)->name ?? 'Guru Tidak Diketahui' }}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="shrink-0 mt-1 sm:mt-0 text-left sm:text-right">
                                                        <div class="text-[10px] font-mono font-bold text-slate-500 bg-slate-50 px-2.5 py-1.5 rounded-lg border border-slate-100 inline-block">
                                                            <i class="ph-bold ph-clock"></i> 
                                                            {{ \Carbon\Carbon::parse($firstSched->timeslot->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($lastSched->timeslot->end_time)->format('H:i') }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            @endif
                        @endforeach

                        @if(!$hasAnySession)
                            <div class="text-center py-24">
                                <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6">
                                    <i class="ph-duotone ph-coffee text-5xl text-elevate-primary"></i>
                                </div>
                                <h3 class="text-xl font-black text-elevate-dark">Libur / Bebas Pelajaran</h3>
                                <p class="text-sm text-slate-500 mt-2">Tidak ada sesi akademik di hari ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- VIEW 2: KALENDER PENDIDIKAN --}}
    <div x-show="viewMode === 'kalender'" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         style="display: none;">
         
         <div class="bg-white p-6 sm:p-8 rounded-[2.5rem] shadow-sm border border-slate-100 relative">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 bg-elevate-soft text-elevate-primary rounded-2xl flex items-center justify-center text-2xl border border-elevate-accent/20">
                    <i class="ph-duotone ph-calendar-check"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-black text-elevate-dark">Kalender Pendidikan</h2>
                    <p class="text-sm text-slate-500">Jadwal kegiatan akademik dan libur.</p>
                </div>
            </div>

            <div id="calendar" class="min-h-[600px] fc-theme-standard"></div>
            
            {{-- Legenda --}}
            <div class="mt-6 pt-6 border-t border-slate-100 flex flex-wrap gap-4">
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-elevate-peach"></div> Ujian / Assesmen
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-rose-500"></div> Libur
                </div>
                <div class="flex items-center gap-2 text-xs font-bold text-slate-600">
                    <div class="w-3 h-3 rounded-full bg-elevate-primary"></div> Kegiatan Sekolah
                </div>
            </div>
         </div>
    </div>

    {{-- FOOTER INFO --}}
    <div class="bg-elevate-dark rounded-[2rem] p-8 text-white relative overflow-hidden shadow-xl shadow-elevate-dark/10 border border-elevate-primary/30">
        <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-primary/40 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="relative z-10 flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
            <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-2xl backdrop-blur-md border border-white/20">
                <i class="ph-fill ph-info text-elevate-accent"></i>
            </div>
            <div>
                <h4 class="font-black text-lg mb-1">Catatan</h4>
                <p class="text-white/70 text-sm leading-relaxed max-w-xl">
                    Jadwal mingguan dan kalender akademik dapat berubah sewaktu-waktu sesuai kebijakan sekolah.
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    .fc-theme-standard .fc-scrollgrid { border-color: #f1f5f9; border-radius: 1rem; overflow: hidden; }
    .fc-theme-standard th, .fc-theme-standard td { border-color: #f1f5f9; }
    .fc-col-header-cell { padding: 12px 0; background-color: #f8fafc; }
    .fc-col-header-cell-cushion { color: #2c3f61; font-weight: 900; text-transform: uppercase; font-size: 0.75rem; text-decoration: none;}
    .fc-daygrid-day-number { color: #2c3f61; font-weight: 700; font-size: 0.875rem; text-decoration: none; padding: 8px !important; }
    .fc-day-today { background-color: #e5eff5 !important; }
    .fc-event { border-radius: 6px; padding: 2px 4px; font-size: 0.7rem; font-weight: bold; border: none; cursor: pointer; }
    .fc .fc-button-primary { background-color: #fff; border-color: #e2e8f0; color: #2c3f61; font-weight: 700; text-transform: capitalize; border-radius: 0.75rem; }
    .fc .fc-button-primary:not(:disabled):active, .fc .fc-button-primary:not(:disabled).fc-button-active { background-color: #e5eff5; border-color: #56bbf1; color: #0d52a1; }
    .fc .fc-button-primary:hover { background-color: #f8fafc; color: #0d52a1; }
</style>