<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-800" x-data="{ searchQuery: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 mb-8 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60">
                {{-- Abstract Shapes Ornaments --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 text-[#2c3f61]/70 text-sm font-bold mb-2">
                            <a href="{{ route('grades.index') }}" class="hover:text-[#0d52a1] transition flex items-center gap-1">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="opacity-50">/</span>
                            <span>Daftar Siswa</span>
                        </div>
                        <h1 class="text-4xl font-extrabold tracking-tight leading-none mb-1">Cetak E-Rapor</h1>
                        <div class="flex items-center justify-center md:justify-start gap-2 mt-3">
                            <span class="bg-white/60 px-3 py-1 rounded-lg text-xs font-bold border border-white shadow-sm uppercase">{{ $class->name }}</span>
                            <span class="text-[#2c3f61]/40 text-xs">●</span>
                            <span class="text-[#2c3f61]/80 text-sm font-medium">TA {{ $academic_year }} ({{ $semester }})</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-center md:items-end gap-2">
                        <div class="bg-white/70 backdrop-blur-md px-5 py-3 rounded-2xl border border-white text-center shadow-sm">
                            <span class="block text-2xl font-black text-[#2c3f61]">{{ $students->count() }}</span>
                            <span class="text-[9px] uppercase font-bold text-[#2c3f61]/60 tracking-wider">Total Siswa</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CONTENT CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                {{-- TOOLBAR --}}
               <div class="p-6 border-b border-slate-100 bg-[#e5eff5]/30 flex flex-col sm:flex-row justify-between gap-4">
                    <div class="relative w-full sm:w-96">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        <input type="text" 
                               x-model="searchQuery" 
                               class="w-full pl-11 pr-4 py-3.5 rounded-xl border-slate-200 bg-white focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm font-bold shadow-sm placeholder:font-medium placeholder:text-slate-400"
                               placeholder="Cari nama siswa atau NISN...">
                    </div>
                    
                    <div class="flex gap-2">
                        <a href="{{ route('grades.template_leger', ['class_id' => $class->id]) }}" class="px-5 py-3 bg-white border border-[#2c3f61] text-[#2c3f61] font-bold rounded-xl hover:bg-slate-50 transition shadow-sm text-sm flex items-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-file-csv text-lg"></i>
                            <span>Leger Nilai</span>
                        </a>
                        <a href="{{ route('grades.print_all', ['class_id' => $class->id, 'year' => $academic_year, 'semester' => $semester]) }}" target="_blank" class="px-5 py-3 bg-[#2c3f61] border border-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] transition shadow-lg shadow-[#2c3f61]/20 text-sm flex items-center gap-2 whitespace-nowrap">
                            <i class="ph-bold ph-printer text-lg"></i>
                            <span>Cetak Semua</span>
                        </a>
                    </div>
                </div>

                {{-- TABLE LIST --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-16 text-center">No</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider">Identitas Siswa</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-1/3">Progres Penilaian</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($students as $index => $student)
                                @php 
                                    $completedSubjects = $progress[$student->id] ?? 0; 
                                    $maxSubjects = $totalSubjects ?? 12; 
                                    $percentage = $maxSubjects > 0 ? min(100, round(($completedSubjects / $maxSubjects) * 100)) : 0;
                                    
                                    $barColor = $percentage == 100 ? 'bg-emerald-500' : ($percentage > 50 ? 'bg-[#56bbf1]' : 'bg-[#f9a282]');
                                    $textColor = $percentage == 100 ? 'text-emerald-600' : 'text-[#2c3f61]';
                                @endphp
                                
                                <tr class="hover:bg-[#e5eff5]/40 transition-colors group"
                                    x-show="searchQuery === '' || String(@js(strtolower($student->name))).includes(searchQuery.toLowerCase()) || String(@js($student->student_id)).includes(searchQuery)"
                                    x-transition.opacity>
                                    
                                    <td class="px-6 py-4 text-center text-slate-400 font-bold text-sm">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#56bbf1] to-[#0d52a1] text-white flex items-center justify-center font-bold text-sm shadow-md shadow-[#56bbf1]/20 shrink-0">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-[#2c3f61] text-sm group-hover:text-[#0d52a1] transition-colors">{{ $student->name }}</div>
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
                                            <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                                <div class="h-full {{ $barColor }} rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            @if($percentage < 100)
                                                <p class="text-[10px] text-[#f9a282] mt-1 italic">Belum Lengkap</p>
                                            @endif
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('grades.report', ['student_id' => $student->id, 'year' => $academic_year, 'semester' => $semester]) }}" 
                                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-slate-200 text-[#2c3f61] hover:border-[#56bbf1] hover:text-[#0d52a1] text-sm font-bold rounded-xl shadow-sm transition-all duration-200 transform hover:-translate-y-0.5 group/btn">
                                            <i class="ph-bold ph-eye text-lg text-slate-400 group-hover/btn:text-[#0d52a1]"></i> 
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
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                        </div>
                        <p class="text-slate-500 font-bold">Tidak ditemukan siswa dengan pencarian tersebut.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>