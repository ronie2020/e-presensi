{{-- Halaman ini adalah tampilan untuk resources/views/discipline/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8 flex flex-col sm:flex-row sm:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                    Catatan Kedisiplinan
                </h1>
                <p class="text-gray-500 mt-1">
                    Kelola poin pelanggaran dan prestasi siswa untuk membangun karakter positif.
                </p>
            </div>
            <a href="{{ route('discipline-types.index') }}" class="group flex items-center gap-2 bg-white border border-gray-200 text-gray-600 px-4 py-2.5 rounded-xl font-bold text-sm hover:bg-gray-50 hover:text-blue-600 transition-all shadow-sm">
                <svg class="w-5 h-5 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Kelola Jenis Pelanggaran
            </a>
        </div>

        {{-- Pesan Flash --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl font-medium text-sm flex justify-between items-center shadow-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        <!-- BAGIAN 1: INPUT FORM (GRID 2 KOLOM MODERN) -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-10">
            
            <!-- Form Pelanggaran (Rose Theme) -->
            <div class="bg-white rounded-3xl shadow-sm border border-rose-100 overflow-hidden relative group hover:shadow-lg hover:shadow-rose-100/50 transition-all duration-300">
                <div class="p-6 md:p-8 relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-rose-100 group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Input Pelanggaran</h3>
                            <p class="text-sm text-gray-500">Catat perilaku indisipliner siswa</p>
                        </div>
                    </div>

                    <form action="{{ route('discipline.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Siswa</label>
                            <select name="student_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm py-3 font-medium transition-colors">
                                <option value="">-- Cari Nama Siswa --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis Pelanggaran</label>
                            <select name="discipline_type_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm py-3 font-medium transition-colors">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($violationTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} (-{{ $type->point_value }} Poin)</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kronologi / Catatan</label>
                            <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm transition-colors" placeholder="Jelaskan singkat kejadiannya..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-200 flex items-center justify-center gap-2 mt-2 group-hover:translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Simpan Data Pelanggaran
                        </button>
                    </form>
                </div>
                {{-- Background Decor --}}
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-rose-50 rounded-full blur-2xl opacity-50 pointer-events-none"></div>
            </div>

            <!-- Form Kebaikan (Emerald Theme) -->
            <div class="bg-white rounded-3xl shadow-sm border border-emerald-100 overflow-hidden relative group hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300">
                <div class="p-6 md:p-8 relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-emerald-100 group-hover:rotate-12 transition-transform duration-300">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-gray-800">Input Prestasi</h3>
                            <p class="text-sm text-gray-500">Apresiasi kebaikan siswa</p>
                        </div>
                    </div>

                    <form action="{{ route('discipline.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Pilih Siswa</label>
                            <select name="student_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 font-medium transition-colors">
                                <option value="">-- Cari Nama Siswa --</option>
                                @foreach ($students as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Jenis Kebaikan</label>
                            <select name="discipline_type_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-3 font-medium transition-colors">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($meritTypes as $type)
                                    <option value="{{ $type->id }}">{{ $type->name }} (+{{ $type->point_value }} Poin)</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Detail Tambahan</label>
                            <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm transition-colors" placeholder="Keterangan prestasi..."></textarea>
                        </div>

                        <button type="submit" class="w-full py-3.5 px-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 mt-2 group-hover:translate-y-0.5">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            Simpan Data Kebaikan
                        </button>
                    </form>
                </div>
                {{-- Background Decor --}}
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-emerald-50 rounded-full blur-2xl opacity-50 pointer-events-none"></div>
            </div>
        </div>

        <!-- BAGIAN 2: RINGKASAN POIN (Leaderboard Style) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
            <div class="p-6 border-b border-gray-100 bg-gray-50/30">
                <h3 class="text-lg font-black text-gray-800 flex items-center gap-2">
                    <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    </span>
                    Top Aktivitas Siswa
                </h3>
                <p class="text-xs text-gray-500 mt-1 ml-10">10 Siswa dengan aktivitas tercatat terbanyak</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 bg-white">
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider w-16 text-center">Rank</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Poin Pelanggaran</th>
                            <th class="px-6 py-4 text-center text-xs font-bold text-gray-400 uppercase tracking-wider">Poin Kebaikan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($studentSummaries as $index => $summary)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($index == 0)
                                        <div class="w-8 h-8 mx-auto rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-black text-sm ring-4 ring-amber-50">1</div>
                                    @elseif($index == 1)
                                        <div class="w-8 h-8 mx-auto rounded-full bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm ring-4 ring-slate-50">2</div>
                                    @elseif($index == 2)
                                        <div class="w-8 h-8 mx-auto rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm ring-4 ring-orange-50">3</div>
                                    @else
                                        <span class="text-sm font-bold text-gray-400">#{{ $index + 1 }}</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-gray-800 group-hover:text-blue-600 transition-colors">{{ $summary->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 rounded bg-gray-100 text-xs font-bold text-gray-500">{{ $summary->class }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($summary->total_violation > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-rose-700 bg-rose-100">
                                            - {{ $summary->total_violation }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs font-medium">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    @if($summary->total_merit > 0)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold text-emerald-700 bg-emerald-100">
                                            + {{ $summary->total_merit }}
                                        </span>
                                    @else
                                        <span class="text-gray-300 text-xs font-medium">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-400">Belum ada data poin disiplin yang tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- BAGIAN 3: RIWAYAT (LOG LENGKAP) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <h3 class="text-lg font-black text-gray-800">Riwayat Aktivitas</h3>

                <form method="GET" action="{{ route('discipline.index') }}" class="flex flex-wrap gap-2 items-center">
                    <div class="relative group">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
                               class="pl-10 pr-4 py-2.5 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm w-full md:w-64 transition-all">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3.5 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                    <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="py-2.5 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm font-medium text-gray-600">
                    <button type="submit" class="px-4 py-2.5 bg-gray-800 text-white rounded-xl hover:bg-gray-900 transition shadow-md flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    </button>
                    @if(request('search') || request('filter_date'))
                        <a href="{{ route('discipline.index') }}" class="px-4 py-2.5 bg-red-50 text-red-600 rounded-xl hover:bg-red-100 transition font-bold text-sm flex items-center">Reset</a>
                    @endif
                </form>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Kejadian</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Poin</th>
                            <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($historyRecords as $record)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-500">
                                    {{ \Carbon\Carbon::parse($record->date)->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ substr($record->student->name ?? 'X', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ $record->student->name ?? 'Siswa Dihapus' }}</p>
                                            <p class="text-xs text-gray-400">{{ $record->student->schoolClass->name ?? '-' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($record->disciplineType->type == 'Pelanggaran')
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-rose-50 text-rose-700 border border-rose-100">
                                            {{ $record->disciplineType->name }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            {{ $record->disciplineType->name }}
                                        </span>
                                    @endif
                                    @if($record->notes)
                                        <div class="text-xs text-gray-500 mt-1 italic max-w-xs truncate">"{{ $record->notes }}"</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-sm font-black {{ $record->disciplineType->type == 'Pelanggaran' ? 'text-rose-600' : 'text-emerald-600' }}">
                                        {{ $record->disciplineType->type == 'Pelanggaran' ? '-' : '+' }}{{ $record->disciplineType->point_value }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('discipline.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-gray-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-all" title="Hapus Data">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="bg-gray-50 p-4 rounded-full mb-3">
                                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        </div>
                                        <p class="text-gray-500 font-medium">Belum ada catatan disiplin.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="p-6 border-t border-gray-100">
                {{ $historyRecords->links() }}
            </div>
        </div>
    </div>
</x-app-layout>