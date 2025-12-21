<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="{ searchQuery: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 text-blue-300 text-sm font-bold mb-2">
                            <a href="{{ route('grades.index') }}" class="hover:text-white transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span>Daftar Siswa</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight leading-none mb-1">Cetak E-Rapor</h1>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-2">
                            <span class="bg-white/10 px-3 py-1 rounded-lg text-xs font-bold border border-white/10">{{ $class->name }}</span>
                            <span class="text-blue-200 text-xs">●</span>
                            <span class="text-blue-200 text-sm font-medium">TA {{ $academic_year }} ({{ $semester }})</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-center md:items-end gap-2">
                        <div class="bg-white/10 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center shadow-lg">
                            <span class="block text-2xl font-black text-white">{{ count($students) }}</span>
                            <span class="text-[9px] uppercase font-bold text-blue-300 tracking-wider">Total Siswa</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                {{-- Toolbar Pencarian --}}
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative w-full sm:w-96">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="text" 
                               x-model="searchQuery" 
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border-slate-200 bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-bold shadow-sm placeholder:font-medium placeholder:text-slate-400"
                               placeholder="Cari nama siswa atau NISN...">
                    </div>
                    
                    <button class="px-5 py-3 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 hover:text-blue-600 transition shadow-sm text-sm flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-bold ph-printer text-lg"></i>
                        <span>Cetak Leger</span>
                    </button>
                </div>

                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider">Identitas Siswa</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center">Kelengkapan</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($students as $index => $student)
                                @php 
                                    $count = $progress[$student->id] ?? 0; 
                                    // Logic Badge Warna
                                    $badgeColor = $count >= 10 
                                        ? 'bg-emerald-100 text-emerald-700 border-emerald-200 ring-emerald-500/20' 
                                        : ($count > 0 ? 'bg-amber-100 text-amber-700 border-amber-200 ring-amber-500/20' : 'bg-slate-100 text-slate-500 border-slate-200');
                                @endphp
                                
                                <tr class="hover:bg-blue-50/20 transition-colors group"
                                    x-show="searchQuery === '' || '{{ strtolower($student->name) }}'.includes(searchQuery.toLowerCase()) || '{{ $student->student_id }}'.includes(searchQuery)"
                                    x-transition.opacity>
                                    
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold text-sm">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-bold text-sm shadow-md shadow-blue-500/20 shrink-0">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors">{{ $student->name }}</div>
                                                <div class="text-xs text-slate-400 font-mono font-medium mt-0.5 tracking-wide">NISN: {{ $student->student_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-[10px] font-black border {{ $badgeColor }} uppercase tracking-wide ring-1 ring-inset">
                                            {{ $count }} Mapel
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('grades.report', ['student_id' => $student->id, 'year' => $academic_year, 'semester' => $semester]) }}" 
                                           target="_blank"
                                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 text-white text-sm font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-all duration-200 transform hover:-translate-y-0.5">
                                            <i class="ph-bold ph-printer text-lg"></i> 
                                            <span>Cetak</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-12 text-center text-slate-400 font-medium">Data siswa kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    {{-- Empty State untuk Pencarian --}}
                    <div class="p-16 text-center" x-show="searchQuery !== '' && $el.previousElementSibling.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                        </div>
                        <p class="text-slate-500 font-bold">Tidak ditemukan siswa dengan nama tersebut.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>