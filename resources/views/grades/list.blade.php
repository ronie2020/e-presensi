<x-app-layout>
    <div class="py-8 sm:py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                        <a href="{{ route('grades.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </a>
                        <span class="text-slate-300">/</span>
                        <span class="text-blue-600 font-bold">Daftar Siswa</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-800">Cetak Rapor Kelas {{ $class->name }}</h1>
                    <p class="text-slate-500">Tahun Ajaran {{ $academic_year }} - Semester {{ $semester }}</p>
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">NISN</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Progress Nilai</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach($students as $index => $student)
                                @php $count = $progress[$student->id] ?? 0; @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-700">{{ $student->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 font-mono text-sm">{{ $student->student_id }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold {{ $count > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $count }} Mapel
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        {{-- PERBAIKAN DI SINI: Mengubah 'student' menjadi 'student_id' --}}
                                        <a href="{{ route('grades.report', ['student_id' => $student->id, 'year' => $academic_year, 'semester' => $semester]) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-blue-50 hover:text-blue-600 hover:border-blue-200 transition shadow-sm">
                                            <i class="ph-bold ph-printer"></i> Cetak
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>