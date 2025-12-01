<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header --}}
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                <i class="ph-duotone ph-files text-purple-600"></i> Rekap Kehadiran
            </h1>
            <p class="text-slate-500 mt-2 text-lg">
                Pantau histori kehadiran siswa dalam kegiatan ekstrakurikuler.
            </p>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden mx-4 sm:mx-0">
            
            {{-- Filter Section --}}
            <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                <form method="GET" action="{{ route('extracurriculars.reports') }}" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    <!-- Pilih Kegiatan -->
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Kegiatan Ekskul</label>
                        <select name="ekskul_id" class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500 text-sm py-2.5 font-bold text-slate-700">
                            <option value="">-- Tampilkan Semua --</option>
                            @foreach($extracurriculars as $ekskul)
                                <option value="{{ $ekskul->id }}" {{ $selectedEkskulId == $ekskul->id ? 'selected' : '' }}>{{ $ekskul->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Periode Tanggal -->
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500 text-sm py-2.5 text-slate-600">
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-xl border-slate-300 focus:border-purple-500 focus:ring-purple-500 text-sm py-2.5 text-slate-600">
                    </div>

                    <!-- Action Buttons -->
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 bg-purple-600 text-white px-4 py-2.5 rounded-xl hover:bg-purple-700 font-bold text-sm shadow-lg shadow-purple-500/20 transition-all">
                            Filter
                        </button>
                        @if($selectedEkskulId)
                            <a href="{{ route('extracurriculars.reports.export', request()->query()) }}" target="_blank" class="px-3 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 hover:text-purple-600 transition-colors" title="Cetak PDF">
                                <i class="ph-bold ph-printer text-xl"></i>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                        <tr>
                            <th class="px-6 py-4">Waktu</th>
                            <th class="px-6 py-4">Siswa</th>
                            <th class="px-6 py-4">Kegiatan</th>
                            <th class="px-6 py-4 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($attendances as $log)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700 text-sm">{{ \Carbon\Carbon::parse($log->date)->format('d M Y') }}</span>
                                        <span class="text-xs text-slate-400 font-mono">{{ $log->time_in }} WIB</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-700 text-sm">{{ $log->student->name }}</div>
                                    <div class="text-xs text-slate-500 mt-0.5">Kelas {{ $log->student->schoolClass->name ?? '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-purple-50 text-purple-700 text-xs font-bold border border-purple-100">
                                        {{ $log->extracurricular->name }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-emerald-100 text-emerald-600 mx-auto">
                                        <i class="ph-bold ph-check"></i>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                        <i class="ph-duotone ph-clipboard-text text-3xl"></i>
                                    </div>
                                    <p class="text-sm font-bold text-slate-600">Tidak ada data kehadiran.</p>
                                    <p class="text-xs text-slate-400 mt-1">Coba ubah filter tanggal atau pilih kegiatan lain.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>