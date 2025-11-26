<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4">
            
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-black text-gray-800">Input Nilai</h1>
                    <p class="text-gray-500">
                        Kelas: <span class="font-bold text-violet-600">{{ $class->name }}</span> | 
                        Mapel: <span class="font-bold text-violet-600">{{ $subject->name }}</span>
                    </p>
                </div>
                <a href="{{ route('grades.index') }}" class="px-4 py-2 bg-gray-100 rounded-lg text-sm font-bold hover:bg-gray-200">Kembali</a>
            </div>

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <form action="{{ route('grades.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="class_id" value="{{ $class->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <input type="hidden" name="academic_year" value="{{ $academic_year }}">
                    <input type="hidden" name="semester" value="{{ $semester }}">

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 border-b border-gray-200">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase w-10">No</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Nama Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase w-32">Nilai Akhir</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase">Capaian Kompetensi / Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @foreach($students as $index => $student)
                                    @php
                                        $existingScore = $existingGrades[$student->id]->score ?? '';
                                        $existingDesc = $existingGrades[$student->id]->description ?? '';
                                    @endphp
                                    <tr class="hover:bg-violet-50/30">
                                        <td class="px-6 py-4 text-center font-mono text-gray-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4 font-bold text-gray-700">
                                            {{ $student->name }}
                                            <div class="text-[10px] text-gray-400 font-normal">{{ $student->student_id }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="number" name="grades[{{ $student->id }}]" value="{{ $existingScore }}" min="0" max="100" 
                                                   class="w-full rounded-lg border-gray-300 focus:ring-violet-500 text-center font-bold text-violet-700">
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="descriptions[{{ $student->id }}]" value="{{ $existingDesc }}" placeholder="Contoh: Sangat baik dalam memahami..." 
                                                   class="w-full rounded-lg border-gray-300 focus:ring-violet-500 text-sm">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="p-6 bg-gray-50 border-t border-gray-100 flex justify-end">
                        <button type="submit" class="px-8 py-3 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg transition">
                            Simpan Semua Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>