@if($academic_record)
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6 relative overflow-hidden">
        <div class="h-72 w-full relative">
            <canvas id="academicChart"></canvas>
        </div>
    </div>
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead class="bg-slate-50/50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4 rounded-tl-2xl">Mata Pelajaran</th>
                        <th class="px-6 py-4 text-center">Nilai</th>
                        <th class="px-6 py-4 text-center">Predikat</th>
                        <th class="px-6 py-4 hidden md:table-cell rounded-tr-2xl">Deskripsi Capaian</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-sm">
                    @foreach($academic_record->items as $item)
                        <tr class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-6 py-4 font-bold text-slate-700">{{ $item->subject->name ?? 'Mapel Dihapus' }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block font-black text-slate-700 text-lg">{{ $item->score }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php 
                                    $gradeColor = match($item->predicate) { 
                                        'A' => 'bg-emerald-100 text-emerald-700 ring-emerald-200', 
                                        'B' => 'bg-blue-100 text-blue-700 ring-blue-200', 
                                        'C' => 'bg-amber-100 text-amber-700 ring-amber-200', 
                                        default => 'bg-rose-100 text-rose-700 ring-rose-200' 
                                    }; 
                                @endphp
                                <span class="px-3 py-1 rounded-lg text-xs font-bold ring-1 {{ $gradeColor }}">{{ $item->predicate }}</span>
                            </td>
                            <td class="px-6 py-4 text-slate-500 hidden md:table-cell max-w-sm leading-relaxed text-xs">
                                {{Str::limit($item->description, 100) ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
            <h3 class="font-bold text-slate-800 text-lg">Belum Ada Data Nilai</h3>
    </div>
@endif