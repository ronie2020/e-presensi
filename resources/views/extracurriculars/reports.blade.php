<x-app-layout>
    {{-- Load Library Tambahan --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <div class="py-6 sm:py-8">
        
        {{-- HERO SECTION (Konsisten dengan modul lain) --}}
        <div class="mb-10 px-4 sm:px-0">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-blue-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-blue-500/30 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-file-text"></i> Laporan Kehadiran
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 text-white leading-tight">
                            Rekap Absensi Ekskul
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Pantau riwayat partisipasi siswa. Gunakan filter untuk melihat performa kehadiran per kegiatan atau periode tertentu.
                        </p>
                    </div>

                    {{-- Stats Ringkas --}}
                    <div class="grid grid-cols-1 gap-4 w-full md:w-auto">
                        <div class="bg-white/10 backdrop-blur-md px-6 py-4 rounded-2xl border border-white/10 min-w-[200px]">
                            <span class="block text-[10px] font-bold uppercase tracking-wider text-blue-300 mb-1">Total Records</span>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-black text-white leading-none">{{ $attendances instanceof \Illuminate\Pagination\LengthAwarePaginator ? $attendances->total() : $attendances->count() }}</span>
                                <span class="text-xs font-bold text-blue-200 mb-1">Entri</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden mx-4 sm:mx-0">
            
            {{-- Filter Section --}}
            <div class="p-8 border-b border-slate-50 bg-slate-50/30">
                <form method="GET" action="{{ route('extracurriculars.reports') }}" class="grid grid-cols-1 md:grid-cols-12 gap-6 items-end">
                    
                    <div class="md:col-span-5">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih Kegiatan</label>
                        <select id="filter-ekskul" name="ekskul_id" class="w-full">
                            <option value="">-- Tampilkan Semua Kegiatan --</option>
                            @foreach($extracurriculars as $ekskul)
                                <option value="{{ $ekskul->id }}" {{ $selectedEkskulId == $ekskul->id ? 'selected' : '' }}>{{ $ekskul->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 px-4 font-bold text-slate-700 transition-all shadow-sm">
                    </div>

                    <div class="md:col-span-3">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-2xl border-slate-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 px-4 font-bold text-slate-700 transition-all shadow-sm">
                    </div>

                    <div class="md:col-span-1 flex gap-2">
                        <button type="submit" class="w-full h-[48px] bg-blue-900 text-white rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/20 flex items-center justify-center group" title="Terapkan Filter">
                            <i class="ph-bold ph-magnifying-glass text-xl group-hover:scale-125 transition-transform"></i>
                        </button>
                    </div>
                </form>

                {{-- Alert Info Jika Ekskul Belum Dipilih untuk Export --}}
                @if(!$selectedEkskulId)
                    <div class="mt-4 flex items-center gap-2 p-3 bg-amber-50 rounded-xl border border-amber-100 text-[10px] font-bold text-amber-600 uppercase tracking-tight">
                        <i class="ph-fill ph-info text-base"></i>
                        Pilih satu kegiatan ekskul untuk mengaktifkan fitur cetak laporan PDF.
                    </div>
                @else
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('extracurriculars.reports.export', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-600/20">
                            <i class="ph-bold ph-printer text-lg"></i>
                            <span>Ekspor ke PDF</span>
                        </a>
                    </div>
                @endif
            </div>

            {{-- Table --}}
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 text-[10px] font-black text-slate-400 uppercase tracking-[0.15em]">
                            <th class="px-8 py-5">Identitas Siswa</th>
                            <th class="px-8 py-5">Kegiatan & Waktu</th>
                            <th class="px-8 py-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($attendances as $log)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-11 h-11 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-blue-600 font-black text-sm shadow-sm group-hover:border-blue-200 transition-colors uppercase">
                                            {{ substr($log->student->name, 0, 2) }}
                                        </div>
                                        <div>
                                            <div class="font-black text-slate-800 text-sm group-hover:text-blue-900 transition-colors">{{ $log->student->name }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">{{ $log->student->schoolClass->name ?? 'Tanpa Kelas' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-[10px] font-black uppercase border border-blue-100">
                                                {{ $log->extracurricular->name }}
                                            </span>
                                        </div>
                                        <div class="flex items-center gap-3 text-xs font-bold text-slate-500">
                                            <span class="flex items-center gap-1"><i class="ph-bold ph-calendar"></i> {{ \Carbon\Carbon::parse($log->date)->isoFormat('D MMM Y') }}</span>
                                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                                            <span class="flex items-center gap-1 text-blue-600"><i class="ph-bold ph-clock"></i> {{ $log->time_in }} WIB</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-center">
                                    <div class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 shadow-sm shadow-emerald-500/10">
                                        <i class="ph-bold ph-check-circle text-xl"></i>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-8 py-24 text-center">
                                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner border border-slate-100">
                                        <i class="ph-duotone ph-files text-5xl text-slate-300"></i>
                                    </div>
                                    <h3 class="text-lg font-black text-slate-700 mb-1">Data Tidak Ditemukan</h3>
                                    <p class="text-sm text-slate-400 font-medium max-w-xs mx-auto">Silakan ubah filter pencarian untuk melihat riwayat kehadiran lainnya.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="p-8 border-t border-slate-50 bg-slate-50/20">
                    {{ $attendances->withQueryString()->links() }}
                </div>
            @endif
        </div>
    </div>

    <style>
        /* Custom Styling untuk TomSelect agar senada dengan input lain */
        .ts-control {
            border-radius: 1rem !important;
            padding: 0.75rem 1rem !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            background-color: #fff !important;
            border-color: #e2e8f0 !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #1e3a8a !important;
            box-shadow: 0 0 0 1px #1e3a8a !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#filter-ekskul', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Pilih atau cari kegiatan...",
                plugins: ['dropdown_input'],
            });
        });
    </script>
</x-app-layout>