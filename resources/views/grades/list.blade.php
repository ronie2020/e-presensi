<x-app-layout>
    <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden pb-20" x-data="{ searchQuery: '' }">
        
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HEADER MICROSOFT ELEVATE DARK THEME --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-8 mb-8 text-white shadow-2xl backdrop-blur-xl overflow-hidden border border-white/10">
                {{-- Decorative Shapes --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-sky-500/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-blue-600/10 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 text-slate-400 text-sm font-bold mb-2">
                            <a href="{{ route('grades.index') }}" class="hover:text-sky-300 transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span class="text-sky-400">Daftar Siswa</span>
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight leading-none text-white mb-1">Cetak E-Rapor</h1>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                            <span class="bg-sky-500/20 text-sky-300 border border-sky-400/30 px-3 py-1 rounded-lg text-xs font-bold uppercase shadow-sm">{{ $class->name }}</span>
                            <span class="text-slate-500 text-xs">●</span>
                            <span class="text-slate-300 text-sm font-medium">TA {{ $academic_year }} ({{ $semester }})</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-center md:items-end gap-2">
                        <div class="bg-slate-900/80 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/10 text-center shadow-sm">
                            <span class="block text-2xl font-black text-sky-400">{{ $students->count() }}</span>
                            <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Total Siswa</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENT CARD --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative backdrop-blur-xl">
                
                {{-- TOOLBAR --}}
                <div class="p-6 border-b border-white/10 bg-slate-900/60 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative w-full sm:w-96">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="text" 
                               x-model="searchQuery" 
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border border-white/10 bg-slate-900 text-white focus:border-sky-400 focus:ring-sky-400 text-sm font-bold shadow-sm placeholder:font-medium placeholder:text-slate-500"
                               placeholder="Cari nama siswa atau NISN...">
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('grades.template_leger', ['class_id' => $class->id]) }}" class="px-5 py-3 bg-slate-900 border border-white/10 text-slate-200 hover:text-white font-bold rounded-xl hover:bg-slate-800 transition shadow-sm text-sm flex items-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-file-csv text-lg text-emerald-400"></i>
                            <span>Leger Nilai</span>
                        </a>
                        <a href="{{ route('grades.print_all', ['class_id' => $class->id, 'year' => $academic_year, 'semester' => $semester]) }}" target="_blank" class="px-5 py-3 bg-gradient-to-r from-sky-400 to-[#0d52a1] text-white font-bold rounded-xl hover:from-sky-300 hover:to-sky-700 transition shadow-lg shadow-sky-500/20 text-sm flex items-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-printer text-lg"></i>
                            <span>Cetak Semua</span>
                        </a>
                    </div>
                </div>

                {{-- TABLE LIST --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900/90 border-b border-white/10 text-slate-300">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-16 text-center text-slate-400">No</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-sky-400">Identitas Siswa</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-1/3 text-sky-400">Progres Penilaian</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-right text-sky-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 bg-slate-900/40">
                            @forelse($students as $index => $student)
                                @php 
                                    $completedSubjects = $progress[$student->id] ?? 0; 
                                    $maxSubjects = $totalSubjects ?? 12; 
                                    $percentage = $maxSubjects > 0 ? min(100, round(($completedSubjects / $maxSubjects) * 100)) : 0;
                                    
                                    $barColor = $percentage == 100 ? 'bg-emerald-500' : ($percentage > 50 ? 'bg-sky-400' : 'bg-amber-400');
                                    $textColor = $percentage == 100 ? 'text-emerald-400' : ($percentage > 50 ? 'text-sky-300' : 'text-amber-300');
                                @endphp
                                
                                <tr class="hover:bg-white/5 transition-colors group"
                                    x-show="searchQuery === '' || String(@js(strtolower($student->name))).includes(searchQuery.toLowerCase()) || String(@js($student->student_id)).includes(searchQuery)"
                                    x-transition.opacity>
                                    
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold text-sm">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-sky-400 to-[#0d52a1] text-white flex items-center justify-center font-bold text-sm shadow-md shadow-sky-500/20 shrink-0">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-white text-sm group-hover:text-sky-300 transition-colors">{{ $student->name }}</div>
                                                <div class="text-xs text-slate-400 font-mono font-medium mt-0.5 tracking-wide">NISN: {{ $student->student_id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="px-6 py-4">
                                        <div class="w-full">
                                            <div class="flex justify-between items-end mb-1">
                                                <span class="text-xs font-bold {{ $textColor }}">
                                                    {{ $completedSubjects }} / {{ $maxSubjects }} Mapel
                                                </span>
                                                <span class="text-[10px] font-black text-slate-400">{{ $percentage }}%</span>
                                            </div>
                                            <div class="w-full h-2.5 bg-slate-900 rounded-full overflow-hidden border border-white/5">
                                                <div class="h-full {{ $barColor }} rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            @if($percentage < 100)
                                                <p class="text-[10px] text-amber-400 mt-1 italic">Belum Lengkap</p>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('grades.report', ['student_id' => $student->id, 'year' => $academic_year, 'semester' => $semester]) }}" 
                                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-slate-900 border border-white/10 text-slate-200 hover:border-sky-400 hover:text-sky-300 text-sm font-bold rounded-xl shadow-sm transition-all duration-200 transform hover:-translate-y-0.5 group/btn">
                                            <i class="ph-bold ph-eye text-lg text-slate-400 group-hover/btn:text-sky-300"></i> 
                                            <span>Lihat</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="p-12 text-center text-slate-400 font-medium">Data siswa kosong.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="p-16 text-center" x-show="searchQuery !== '' && $el.previousElementSibling.querySelectorAll('tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                        <div class="w-20 h-20 bg-slate-900 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500 border border-white/10">
                            <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                        </div>
                        <p class="text-slate-400 font-bold">Tidak ditemukan siswa dengan pencarian tersebut.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>