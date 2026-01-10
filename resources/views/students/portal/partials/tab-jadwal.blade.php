@php
    $classSchedules = $student->schoolClass ? $student->schoolClass->schedules->sortBy('start_time')->groupBy('day') : collect();
    $daysOrder = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
@endphp

@if($classSchedules->count() > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($daysOrder as $day)
            @if(isset($classSchedules[$day]))
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-all">
                <div class="px-6 py-4 border-b border-gray-100 bg-slate-50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800 text-lg">{{ $day }}</h3>
                    <span class="text-xs font-bold px-2 py-1 rounded bg-white border border-slate-200 text-slate-500">
                        {{ $classSchedules[$day]->count() }} Mapel
                    </span>
                </div>
                <div class="divide-y divide-gray-50">
                    @foreach($classSchedules[$day] as $sched)
                    <div class="p-5 flex gap-4 group hover:bg-blue-50/30 transition-colors">
                        <div class="flex flex-col items-center justify-center w-14 shrink-0 text-slate-400">
                            <span class="text-xs font-bold">{{ \Carbon\Carbon::parse($sched->start_time)->format('H:i') }}</span>
                            <div class="h-4 w-px bg-slate-200 my-0.5"></div>
                            <span class="text-xs font-bold">{{ \Carbon\Carbon::parse($sched->end_time)->format('H:i') }}</span>
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 group-hover:text-blue-600 transition-colors">
                                {{ $sched->subject->name ?? 'Mata Pelajaran' }}
                            </h4>
                            <p class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                <i class="ph-fill ph-user-circle"></i> 
                                {{ $sched->teacher->name ?? 'Guru Pengampu' }}
                            </p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        @endforeach
    </div>
@else
    <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
            <i class="ph-duotone ph-calendar-slash text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
        </div>
        <h3 class="font-bold text-slate-800 text-lg">Jadwal Belum Tersedia</h3>
        <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto">
            Jadwal pelajaran untuk kelas <strong class="text-slate-700">{{ $student->schoolClass->name ?? '' }}</strong> belum diatur oleh admin.
        </p>
    </div>
@endif