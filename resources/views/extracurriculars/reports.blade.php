<x-app-layout>
    {{-- Load Library Tambahan --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <div class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION ELEVATE DARK GLASS THEME --}}
            <div class="relative rounded-[2.5rem] bg-gradient-to-r from-[#031d3d] via-[#021124] to-[#031d3d] p-8 mb-10 text-white shadow-2xl border border-white/10 overflow-hidden">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#56bbf1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#56bbf1]/5 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-2 mb-2">
                            <a href="{{ route('extracurriculars.index') }}" class="text-xs font-bold text-[#56bbf1] hover:text-white transition flex items-center gap-1 bg-white/10 px-3 py-1 rounded-full border border-white/10 backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="text-[10px] font-bold text-[#56bbf1] uppercase tracking-wider bg-[#56bbf1]/10 px-3 py-1 rounded-full border border-[#56bbf1]/20 backdrop-blur-sm shadow-sm">Laporan</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Rekap Absensi Ekskul
                        </h1>
                        <p class="text-slate-300 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Pantau riwayat partisipasi siswa. Gunakan filter untuk melihat performa kehadiran per kegiatan atau periode tertentu.
                        </p>
                    </div>

                    {{-- Stats Ringkas --}}
                    <div class="flex gap-3">
                        <div class="bg-white/5 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white/10 text-center min-w-[150px]">
                            <span class="block text-4xl font-black text-white mb-1">
                                {{ $attendances instanceof \Illuminate\Pagination\LengthAwarePaginator ? $attendances->total() : $attendances->count() }}
                            </span>
                            <span class="text-[10px] uppercase font-bold text-[#56bbf1] tracking-wider">Total Entri Data</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden">
                
                {{-- Filter Section --}}
                <div class="p-8 border-b border-white/10 bg-white/5">
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
                            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3.5 px-4 font-bold text-white transition-all shadow-sm">
                        </div>

                        <div class="md:col-span-3">
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 ml-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3.5 px-4 font-bold text-white transition-all shadow-sm">
                        </div>

                        <div class="md:col-span-1 flex gap-2">
                            <button type="submit" class="w-full h-[52px] bg-gradient-to-r from-[#56bbf1] to-[#3b82f6] text-white rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-[#56bbf1]/20 flex items-center justify-center group active:scale-95" title="Terapkan Filter">
                                <i class="ph-bold ph-magnifying-glass text-xl group-hover:scale-110 transition-transform"></i>
                            </button>
                        </div>
                    </form>

                    {{-- Alert Info Jika Ekskul Belum Dipilih untuk Export --}}
                    @if(!$selectedEkskulId)
                        <div class="mt-6 flex items-center gap-3 p-4 bg-amber-500/10 rounded-2xl border border-amber-500/20 text-xs font-bold text-amber-300 tracking-wide">
                            <div class="w-8 h-8 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center shrink-0"><i class="ph-fill ph-info text-lg"></i></div>
                            Pilih satu kegiatan ekskul di filter atas untuk mengaktifkan fitur cetak laporan PDF.
                        </div>
                    @else
                        <div class="mt-6 flex justify-end">
                            <a href="{{ route('extracurriculars.reports.export', request()->query()) }}" target="_blank" class="inline-flex items-center gap-2 px-6 py-3.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-2xl font-bold text-sm hover:bg-emerald-600 hover:text-white transition-all shadow-sm active:scale-95">
                                <i class="ph-bold ph-printer text-lg"></i>
                                <span>Ekspor Laporan PDF</span>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead class="bg-white/5 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-white/10">
                            <tr>
                                <th class="px-8 py-5">Identitas Siswa</th>
                                <th class="px-8 py-5">Kegiatan & Waktu</th>
                                <th class="px-8 py-5 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($attendances as $log)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-8 py-5">
                                        <div class="flex items-center gap-4">
                                            <div class="w-11 h-11 rounded-2xl bg-[#021124] border border-white/10 flex items-center justify-center text-[#56bbf1] font-black text-sm shadow-sm group-hover:border-[#56bbf1]/40 transition-colors uppercase shrink-0">
                                                {{ substr($log->student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-white text-sm group-hover:text-[#56bbf1] transition-colors">{{ $log->student->name }}</div>
                                                <div class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">{{ $log->student->schoolClass->name ?? 'Tanpa Kelas' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5">
                                        <div class="flex flex-col gap-2">
                                            <div class="flex items-center gap-2">
                                                <span class="px-2.5 py-1 rounded-lg bg-[#56bbf1]/10 text-[#56bbf1] text-[10px] font-black uppercase tracking-wide border border-[#56bbf1]/20">
                                                    {{ $log->extracurricular->name }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-3 text-xs font-bold text-slate-400">
                                                <span class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-blank"></i> {{ \Carbon\Carbon::parse($log->date)->isoFormat('D MMM Y') }}</span>
                                                <span class="w-1 h-1 rounded-full bg-slate-600"></span>
                                                <span class="flex items-center gap-1.5 text-[#56bbf1]"><i class="ph-bold ph-clock"></i> {{ $log->time_in }} WIB</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-5 text-center">
                                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 shadow-sm">
                                            <i class="ph-bold ph-check-circle text-xl"></i>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-24 text-center border border-dashed border-white/10 rounded-b-[2.5rem] bg-white/5">
                                        <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 shadow-sm border border-white/10">
                                            <i class="ph-duotone ph-files text-4xl text-slate-500"></i>
                                        </div>
                                        <h3 class="text-lg font-black text-white mb-1">Data Tidak Ditemukan</h3>
                                        <p class="text-sm text-slate-400 font-medium max-w-sm mx-auto">Silakan ubah filter pencarian untuk melihat riwayat kehadiran lainnya.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($attendances instanceof \Illuminate\Pagination\LengthAwarePaginator)
                    <div class="p-8 border-t border-white/10 bg-white/5">
                        {{ $attendances->withQueryString()->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        /* Custom Styling untuk TomSelect agar senada dengan Dark Glass Theme */
        .ts-control {
            border-radius: 1rem !important;
            padding: 0.875rem 1rem !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            background-color: rgba(2, 17, 36, 0.9) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #fff !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #56bbf1 !important;
            box-shadow: 0 0 0 1px #56bbf1 !important;
        }
        .ts-dropdown {
            border-radius: 1rem !important;
            background-color: #021124 !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5) !important;
            color: #fff !important;
        }
        .ts-dropdown .option {
            color: #e2e8f0 !important;
        }
        .ts-dropdown .option.active, .ts-dropdown .option:hover {
            background-color: rgba(86, 187, 241, 0.15) !important;
            color: #56bbf1 !important;
        }
        .ts-dropdown .dropdown-input-wrap input {
            background-color: #031d3d !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #fff !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('#filter-ekskul', {
                create: false,
                sortField: { field: "text", direction: "asc" },
                placeholder: "Pilih atau cari kegiatan...",
                plugins: ['dropdown_input'],
                render: {
                    option: function(data, escape) {
                        return '<div class="py-2 px-3 hover:bg-white/5 transition-colors">' +
                                '<span class="font-bold text-slate-200 block text-sm">' + escape(data.text) + '</span>' +
                            '</div>';
                    },
                    item: function(data, escape) {
                        return '<div class="font-bold text-sm text-white">' + escape(data.text) + '</div>';
                    }
                }
            });
        });
    </script>
</x-app-layout>