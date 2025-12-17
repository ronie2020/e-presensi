<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Rekap Nilai Siswa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">📊 Gradebook Kelas</h2>
                    <p class="text-sm text-gray-500">Pantau perkembangan nilai tugas dan kuis dalam satu tampilan matriks.</p>
                </div>

                <!-- TOMBOL AKSI BARU -->
                @if($selectedClassId && $selectedSubjectId && !$assignments->isEmpty())
                    <div class="flex gap-2">
                        <a href="{{ route('lms.grades.export', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId]) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white text-sm font-bold rounded-lg hover:bg-green-700 shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Export Excel
                        </a>
                        <a href="{{ route('lms.grades.print', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId]) }}" target="_blank" class="px-4 py-2 bg-slate-800 text-white text-sm font-bold rounded-lg hover:bg-slate-900 shadow-md flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Cetak Laporan
                        </a>
                    </div>
                @endif
            </div>

            <!-- CARD FILTER -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-8">
                <form action="{{ route('lms.grades.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
                    
                    <div class="flex-1 w-full">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Kelas</label>
                        <select name="class_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                    {{ $c->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex-1 w-full">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Pilih Mata Pelajaran</label>
                        <select name="subject_id" class="w-full rounded-lg border-gray-300 focus:ring-blue-500" required>
                            <option value="">-- Pilih Mapel --</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>
                                    {{ $s->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full md:w-auto">
                        <button type="submit" class="w-full px-6 py-2.5 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Tampilkan Data
                        </button>
                    </div>
                </form>
            </div>

            <!-- TABEL NILAI -->
            @if($selectedClassId && $selectedSubjectId)
                <div class="bg-white rounded-xl shadow-xl border border-gray-100 overflow-hidden">
                    @if($assignments->isEmpty())
                        <div class="p-12 text-center">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-700">Belum Ada Tugas</h3>
                            <p class="text-gray-500 text-sm">Belum ada tugas atau kuis yang dibuat untuk kelas dan mapel ini.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-gray-50 text-gray-700 uppercase font-bold text-xs">
                                    <tr>
                                        <th class="px-4 py-4 border-b border-gray-200 sticky left-0 bg-gray-50 z-10 w-10">No</th>
                                        <th class="px-4 py-4 border-b border-gray-200 sticky left-10 bg-gray-50 z-10 min-w-[200px] border-r">Nama Siswa</th>
                                        
                                        <!-- Loop Judul Tugas (Kolom) -->
                                        @foreach($assignments as $task)
                                            <th class="px-4 py-4 border-b border-gray-200 text-center min-w-[100px] group relative border-r border-gray-100">
                                                <div class="flex flex-col items-center">
                                                    <span class="block truncate w-24 cursor-help font-bold text-blue-600" title="{{ $task->title }}">{{ Str::limit($task->title, 10) }}</span>
                                                    <span class="text-[9px] text-gray-400 font-normal mt-1">{{ $task->created_at->format('d/m') }}</span>
                                                    <span class="text-[9px] px-1.5 py-0.5 rounded-full bg-gray-100 text-gray-500 mt-1 uppercase">{{ $task->assignment_type == 'quiz' ? 'Kuis' : 'Tugas' }}</span>
                                                </div>
                                            </th>
                                        @endforeach

                                        <th class="px-4 py-4 border-b border-gray-200 text-center bg-blue-50 text-blue-800 min-w-[80px]">Total</th>
                                        <th class="px-4 py-4 border-b border-gray-200 text-center bg-green-50 text-green-800 min-w-[80px]">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach($students as $index => $student)
                                        @php
                                            $totalScore = 0;
                                            $countScore = 0;
                                        @endphp
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="px-4 py-3 border-r border-gray-100 sticky left-0 bg-white">{{ $index + 1 }}</td>
                                            <td class="px-4 py-3 border-r border-gray-200 sticky left-10 bg-white font-medium text-gray-800 shadow-[4px_0_10px_-4px_rgba(0,0,0,0.1)]">
                                                {{ $student->name }}
                                                <div class="text-[10px] text-gray-400">{{ $student->nisn }}</div>
                                            </td>

                                            <!-- Loop Nilai per Tugas -->
                                            @foreach($assignments as $task)
                                                @php
                                                    $score = $gradeBook[$student->id][$task->id] ?? null;
                                                    if ($score !== null) {
                                                        $totalScore += $score;
                                                        $countScore++;
                                                    }
                                                @endphp
                                                <td class="px-4 py-3 text-center border-r border-gray-100">
                                                    @if($score !== null)
                                                        <span class="inline-block w-10 py-1 rounded text-xs font-bold 
                                                            {{ $score < 70 ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700' }}">
                                                            {{ $score }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-300 text-xs">-</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            <td class="px-4 py-3 text-center border-r border-gray-100 bg-blue-50 font-bold text-blue-700">
                                                {{ $totalScore }}
                                            </td>
                                            <td class="px-4 py-3 text-center bg-green-50 font-bold text-green-700">
                                                {{ $countScore > 0 ? round($totalScore / $countScore, 1) : 0 }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-xl border-2 border-dashed border-gray-200">
                    <div class="w-20 h-20 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4 text-blue-300">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">Silakan Pilih Filter</h3>
                    <p class="text-gray-500 mt-1">Pilih Kelas dan Mata Pelajaran di atas untuk melihat rekap nilai.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>