<x-app-layout>
    <div class="py-8 sm:py-12" x-data="{ searchQuery: '' }">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                <div>
                    <div class="flex items-center gap-2 text-sm text-slate-500 mb-1">
                        <a href="{{ route('grades.index') }}" class="hover:text-blue-600 transition flex items-center gap-1 font-medium">
                            <i class="ph-bold ph-arrow-left"></i> Kembali
                        </a>
                        <span class="text-slate-300">/</span>
                        <span class="text-blue-600 font-bold">Daftar Siswa</span>
                    </div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Cetak Rapor</h1>
                    <div class="flex items-center gap-2 text-slate-500 mt-2">
                        <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded text-xs font-bold border border-blue-100 uppercase tracking-wide">{{ $class->name }}</span>
                        <span class="text-xs">&bull;</span>
                        <span class="text-sm">TA {{ $academic_year }} ({{ $semester }})</span>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                
                {{-- Toolbar Pencarian --}}
                <div class="p-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative w-full sm:w-96">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" 
                               x-model="searchQuery" 
                               class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 text-sm shadow-sm"
                               placeholder="Cari nama siswa atau NISN...">
                    </div>
                    
                    <button class="px-5 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition shadow-sm text-sm flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-printer"></i>
                        <span>Cetak Leger (Semua)</span>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider">Identitas Siswa</th>
                                <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Kelengkapan</th>
                                <th class="px-6 py-5 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($students as $index => $student)
                                @php 
                                    $count = $progress[$student->id] ?? 0; 
                                    $badgeColor = $count > 10 ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : ($count > 0 ? 'bg-amber-100 text-amber-700 border-amber-200' : 'bg-slate-100 text-slate-500 border-slate-200');
                                @endphp
                                
                                {{-- Filter Logic pada 'x-show' --}}
                                <tr class="hover:bg-blue-50/40 transition-colors group"
                                    x-show="searchQuery === '' || '{{ strtolower($student->name) }}'.includes(searchQuery.toLowerCase()) || '{{ $student->student_id }}'.includes(searchQuery)"
                                    x-transition.opacity>
                                    
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold text-sm">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-blue-500/20">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors">{{ $student->name }}</div>
                                                <div class="text-xs text-slate-400 font-mono mt-0.5">NISN: {{ $student->student_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold border {{ $badgeColor }} uppercase tracking-wide">
                                            {{ $count }} Mapel Dinilai
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('grades.report', ['student_id' => $student->id, 'year' => $academic_year, 'semester' => $semester]) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-blue-600 hover:text-white hover:border-blue-600 hover:shadow-lg hover:shadow-blue-500/30 transition-all duration-200">
                                            <i class="ph-bold ph-printer"></i> 
                                            <span>Cetak</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-8 text-center">Data kosong</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    {{-- Empty State untuk Pencarian --}}
                    <div class="p-12 text-center" x-show="searchQuery !== '' && $el.previousElementSibling.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                        <i class="ph-duotone ph-magnifying-glass text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500 font-bold">Tidak ditemukan siswa dengan nama tersebut.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>