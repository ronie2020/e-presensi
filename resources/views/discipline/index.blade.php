{{-- Halaman ini adalah tampilan untuk resources/views/discipline/index.blade.php --}}
<x-app-layout>
    {{-- Header Page --}}
    <div class="mb-8">
        <h1 class="text-2xl font-extrabold text-gray-800 tracking-tight">
            Catatan Disiplin & Prestasi
        </h1>
        <p class="text-sm text-gray-500 mt-1">
            Kelola perilaku siswa untuk menciptakan lingkungan sekolah yang positif.
        </p>
    </div>

    {{-- Pesan Flash --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-center gap-3 shadow-sm">
            <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif
    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-100 text-red-700 rounded-xl shadow-sm">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- BAGIAN 1: INPUT FORM (GRID 2 KOLOM MODERN) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
        
        <!-- Form Pelanggaran (Card Merah Soft) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative group">
            <div class="absolute top-0 left-0 w-2 h-full bg-rose-500"></div>
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-rose-100 text-rose-600 rounded-2xl flex items-center justify-center text-xl shadow-sm group-hover:rotate-6 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Catat Pelanggaran</h3>
                        <p class="text-xs text-gray-500">Input data indisipliner siswa</p>
                    </div>
                </div>

                <form action="{{ route('discipline.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Siswa</label>
                        <select name="student_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm py-2.5">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Pelanggaran</label>
                        <select name="discipline_type_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm py-2.5">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($violationTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }} ({{ $type->point_value }} Poin)</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Detail (Opsional)</label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm" placeholder="Kronologi singkat..."></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 px-4 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 transition shadow-lg shadow-rose-600/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            Simpan Pelanggaran
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Form Kebaikan (Card Hijau Soft) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden relative group">
            <div class="absolute top-0 left-0 w-2 h-full bg-emerald-500"></div>
            <div class="p-6 md:p-8">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-xl shadow-sm group-hover:rotate-6 transition-transform">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Catat Prestasi / Kebaikan</h3>
                        <p class="text-xs text-gray-500">Apresiasi siswa berprestasi</p>
                    </div>
                </div>

                <form action="{{ route('discipline.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Siswa</label>
                        <select name="student_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5">
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $student)
                                <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Jenis Kebaikan</label>
                        <select name="discipline_type_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm py-2.5">
                            <option value="">-- Pilih Jenis --</option>
                            @foreach ($meritTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->name }} (+{{ $type->point_value }} Poin)</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Detail (Opsional)</label>
                        <textarea name="notes" rows="2" class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm" placeholder="Keterangan tambahan..."></textarea>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 px-4 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path></svg>
                            Simpan Kebaikan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- BAGIAN 2: RINGKASAN POIN (Leaderboard Style) -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden mb-10">
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-lg font-bold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                Ringkasan Poin Siswa (Top 10 Aktivitas)
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Peringkat</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Poin Pelanggaran</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Poin Kebaikan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($studentSummaries as $index => $summary)
                        <tr class="hover:bg-gray-50/80 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($index == 0)
                                    <span class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center font-bold text-sm">1</span>
                                @elseif($index == 1)
                                    <span class="w-8 h-8 rounded-full bg-gray-200 text-gray-600 flex items-center justify-center font-bold text-sm">2</span>
                                @elseif($index == 2)
                                    <span class="w-8 h-8 rounded-full bg-orange-100 text-orange-600 flex items-center justify-center font-bold text-sm">3</span>
                                @else
                                    <span class="w-8 h-8 rounded-full bg-white text-gray-500 flex items-center justify-center font-bold text-sm border border-gray-200">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-800">{{ $summary->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $summary->class }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($summary->total_violation > 0)
                                    <span class="px-3 py-1 text-xs font-bold text-rose-700 bg-rose-100 rounded-full">
                                        {{ $summary->total_violation }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @if($summary->total_merit > 0)
                                    <span class="px-3 py-1 text-xs font-bold text-emerald-700 bg-emerald-100 rounded-full">
                                        +{{ $summary->total_merit }}
                                    </span>
                                @else
                                    <span class="text-gray-300">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada data poin disiplin yang tercatat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- BAGIAN 3: RIWAYAT (LOG LENGKAP) -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h3 class="text-lg font-bold text-gray-800">Riwayat Aktivitas</h3>

            <form method="GET" action="{{ route('discipline.index') }}" class="flex flex-wrap gap-2">
                <div class="relative">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama..." class="pl-9 pr-4 py-2 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm w-48">
                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="date" name="filter_date" value="{{ request('filter_date') }}" class="py-2 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                </button>
                @if(request('search') || request('filter_date'))
                    <a href="{{ route('discipline.index') }}" class="px-4 py-2 bg-gray-100 text-gray-600 rounded-xl hover:bg-gray-200 transition font-medium text-sm flex items-center">Reset</a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Siswa</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Jenis Kejadian</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Poin</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Dicatat Oleh</th>
                        <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($historyRecords as $record)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($record->date)->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
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
                                    <div class="text-xs text-gray-500 mt-1 italic">"{{ $record->notes }}"</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-bold {{ $record->disciplineType->type == 'Pelanggaran' ? 'text-rose-600' : 'text-emerald-600' }}">
                                {{ $record->disciplineType->type == 'Pelanggaran' ? '-' : '+' }}{{ $record->disciplineType->point_value }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ $record->recorder->name ?? 'Admin' }}
                            </td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('discipline.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus catatan ini?');" class="opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="bg-gray-50 p-4 rounded-full mb-3">
                                        <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    </div>
                                    <p class="text-gray-500 font-medium">Belum ada catatan disiplin sesuai filter.</p>
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
</x-app-layout>