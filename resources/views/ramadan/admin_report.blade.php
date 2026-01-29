<x-app-layout>
    <div class="p-6 md:p-8 space-y-8 min-h-screen bg-slate-50">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="ph-fill ph-book-open text-emerald-600"></i> Rekap Mutabaah Ramadhan
                </h1>
                <p class="text-sm text-slate-500 mt-1">Monitoring kedisiplinan ibadah harian siswa selama bulan Ramadhan.</p>
            </div>
            
            {{-- FILTER FORM --}}
            <form action="{{ route('admin.ramadan.reports') }}" method="GET" class="flex flex-wrap gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
                <div class="flex items-center px-3 gap-2 border-r border-slate-100">
                    <i class="ph-bold ph-chalkboard text-slate-400"></i>
                    <select name="class_id" class="border-none bg-transparent text-sm font-bold focus:ring-0 cursor-pointer min-w-[140px]">
                        <option value="">Pilih Kelas</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center px-3 gap-2">
                    <i class="ph-bold ph-calendar text-slate-400"></i>
                    <input type="date" name="date" value="{{ $date }}" class="border-none bg-transparent text-sm font-bold focus:ring-0 cursor-pointer">
                </div>
                <button type="submit" class="bg-emerald-600 text-white px-6 py-2.5 rounded-xl font-bold hover:bg-emerald-700 transition shadow-lg shadow-emerald-200 flex items-center gap-2">
                    <i class="ph-bold ph-magnifying-glass"></i> Filter
                </button>
            </form>
        </div>

        {{-- CONTENT --}}
        @if($selectedClass)
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden animate-in fade-in duration-500">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50/50 border-b border-slate-100">
                            <tr>
                                <th class="px-8 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Puasa</th>
                                <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Shalat 5W</th>
                                <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest text-center">Sunnah</th>
                                <th class="px-6 py-5 text-xs font-black text-slate-400 uppercase tracking-widest">Tilawah / Murojaah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($reports as $student)
                            @php $log = $student->ramadanLogs->first(); @endphp
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="font-black text-slate-800">{{ $student->name }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 mt-0.5 tracking-wider">{{ $student->student_id }}</div>
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($log)
                                        @if($log->is_fasting)
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 shadow-sm">
                                                <i class="ph-fill ph-check-circle text-xl"></i>
                                            </span>
                                        @else
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-rose-100 text-rose-600 shadow-sm">
                                                <i class="ph-fill ph-x-circle text-xl"></i>
                                            </span>
                                        @endif
                                    @else
                                        <div class="w-2 h-2 rounded-full bg-slate-200 mx-auto"></div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($log && is_array($log->prayers))
                                        @php $count = count(array_filter($log->prayers)); @endphp
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-sm font-black {{ $count == 5 ? 'text-emerald-600' : ($count > 0 ? 'text-amber-500' : 'text-slate-300') }}">
                                                {{ $count }}/5
                                            </span>
                                            <div class="flex gap-0.5">
                                                @foreach(['subuh','dzuhur','ashar','maghrib','isya'] as $p)
                                                    <div class="w-1.5 h-1.5 rounded-full {{ ($log->prayers[$p] ?? false) ? 'bg-emerald-500' : 'bg-slate-200' }}"></div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-[10px] font-bold text-slate-300">BELUM ISI</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @if($log && is_array($log->sunnah_deeds))
                                        @php $countS = count(array_filter($log->sunnah_deeds)); @endphp
                                        <span class="px-3 py-1 rounded-full bg-amber-50 text-amber-600 text-[10px] font-black border border-amber-100">
                                            {{ $countS }} AMALAN
                                        </span>
                                    @else
                                        <span class="text-slate-300 text-[10px]">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5">
                                    @if($log && ($log->tadarus_surah || $log->murojaah_surah))
                                        @if($log->tadarus_surah)
                                            <div class="flex items-center gap-2 mb-1">
                                                <i class="ph-fill ph-book-open text-blue-500"></i>
                                                <span class="text-xs font-black text-slate-700">{{ $log->tadarus_surah }} ({{ $log->tadarus_ayah }})</span>
                                            </div>
                                        @endif
                                        @if($log->murojaah_surah)
                                            <div class="flex items-center gap-2">
                                                <i class="ph-fill ph-headset text-purple-500"></i>
                                                <span class="text-[10px] font-bold text-slate-500">Murojaah: {{ $log->murojaah_surah }}</span>
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-slate-300 text-[10px] italic">Tidak ada data</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <p class="text-slate-400 font-bold">Tidak ada siswa ditemukan di kelas ini.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- EMPTY STATE --}}
            <div class="text-center py-24 bg-white rounded-[3rem] border border-dashed border-slate-200">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-duotone ph-selection-all text-5xl text-slate-300"></i>
                </div>
                <h3 class="text-xl font-black text-slate-600">Pilih Kelas Monitoring</h3>
                <p class="text-slate-400 text-sm mt-2 max-w-xs mx-auto">Gunakan filter di pojok kanan atas untuk melihat laporan mutabaah siswa.</p>
            </div>
        @endif

        {{-- LEGEND / KETERANGAN --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-info"></i></div>
                <div class="text-xs font-bold text-slate-500 uppercase tracking-wider">Data dihitung secara real-time dari input jurnal siswa.</div>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><i class="ph-bold ph-printer"></i></div>
                <button onclick="window.print()" class="text-xs font-bold text-slate-800 hover:text-emerald-600 transition uppercase tracking-wider">Cetak Laporan Harian Kelas</button>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 flex items-center gap-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center"><i class="ph-bold ph-export"></i></div>
                <button class="text-xs font-bold text-slate-800 hover:text-emerald-600 transition uppercase tracking-wider">Export ke Excel (Coming Soon)</button>
            </div>
        </div>
    </div>
</x-app-layout>