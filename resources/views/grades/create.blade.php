<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header Navigasi --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
                <div>
                    {{-- Breadcrumb Kecil --}}
                    <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                        <a href="{{ route('grades.index') }}" class="hover:text-blue-600 transition flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </a>
                        <span class="text-slate-300">/</span>
                        <span class="text-blue-600 font-bold">Isi Data</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-none">Form Penilaian</h1>
                </div>
                
                {{-- Info Badge (Warna Biru) --}}
                <div class="flex gap-2">
                    <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                            <i class="ph-bold ph-chalkboard-teacher"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Kelas</p>
                            <p class="text-sm font-bold text-slate-800">{{ $class->name }}</p>
                        </div>
                    </div>
                    <div class="bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center">
                            <i class="ph-bold ph-book-open"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase">Mapel</p>
                            <p class="text-sm font-bold text-slate-800">{{ $subject->name }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('grades.store') }}" method="POST">
                @csrf
                <input type="hidden" name="class_id" value="{{ $class->id }}">
                <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                <input type="hidden" name="academic_year" value="{{ $academic_year }}">
                <input type="hidden" name="semester" value="{{ $semester }}">

                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden relative">
                    {{-- Table Wrapper --}}
                    <div class="overflow-x-auto max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse relative">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-16 text-center">No</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider min-w-[200px]">Nama Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-32 text-center">Nilai Akhir</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider min-w-[300px]">Capaian Kompetensi / Deskripsi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 bg-white">
                                @foreach($students as $index => $student)
                                    @php
                                        $existingScore = $existingGrades[$student->id]->score ?? '';
                                        $existingDesc = $existingGrades[$student->id]->description ?? '';
                                    @endphp
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4 text-center font-bold text-slate-400">{{ $index + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-xs shadow-sm group-hover:scale-110 transition-transform">
                                                    {{ substr($student->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-700 text-sm">{{ $student->name }}</div>
                                                    <div class="text-[10px] text-slate-400 font-mono mt-0.5">NIS: {{ $student->student_id }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="relative">
                                                <input type="number" name="grades[{{ $student->id }}]" value="{{ $existingScore }}" min="0" max="100" 
                                                       class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-center font-black text-blue-700 py-2.5 transition-all shadow-sm placeholder:font-normal placeholder:text-slate-300"
                                                       placeholder="0-100">
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <input type="text" name="descriptions[{{ $student->id }}]" value="{{ $existingDesc }}" 
                                                   class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm text-slate-600 py-2.5 px-4 transition-all shadow-sm"
                                                   placeholder="Contoh: Sangat baik dalam memahami materi...">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Sticky Action Bar (Bottom) --}}
                    <div class="p-4 bg-white border-t border-slate-100 flex items-center justify-between sticky bottom-0 z-20 shadow-[0_-10px_40px_rgba(0,0,0,0.05)]">
                        <div class="text-xs text-slate-400 hidden sm:block">
                            <span class="font-bold text-slate-600">{{ count($students) }}</span> Siswa terdaftar di kelas ini.
                        </div>
                        <div class="flex gap-3 w-full sm:w-auto">
                            <!-- Tombol Batal/Kembali di Bawah -->
                            <a href="{{ route('grades.index') }}" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-sm hover:bg-slate-50 hover:text-slate-800 transition text-center w-full sm:w-auto">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition flex items-center justify-center gap-2 w-full sm:w-auto transform active:scale-95">
                                <i class="ph-bold ph-floppy-disk"></i>
                                Simpan Data
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>