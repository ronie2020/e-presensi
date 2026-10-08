<x-app-layout>
   <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .table-fixed-column { position: sticky; left: 0; z-index: 10; background-color: #fff; box-shadow: 2px 0 5px rgba(0,0,0,0.05); }
        @media print {
            .no-print { display: none !important; }
            body { background-color: white; zoom: 70%; }
            .table-fixed-column { position: static; box-shadow: none; }
            .overflow-x-auto { overflow: visible !important; }
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-32" x-data="{ loading: false }">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- LOADING OVERLAY --}}
        <div x-show="loading" class="fixed inset-0 z-[100] bg-elevate-dark/40 backdrop-blur-sm flex items-center justify-center" style="display: none;">
            <div class="bg-white p-8 rounded-[2rem] shadow-2xl flex flex-col items-center">
                <div class="w-12 h-12 border-4 border-elevate-primary border-t-transparent rounded-full animate-spin mb-4"></div>
                <span class="text-sm font-black text-elevate-dark tracking-wider">Memproses Data...</span>
            </div>
        </div>

        <div class="max-w-[95%] mx-auto px-2 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <div class="mb-8 no-print">
                <x-hero-section
                    badge="Laporan Presensi Rombel"
                    badgeIcon="ph-chalkboard-teacher"
                    title="Laporan Harian"
                    titleHighlight="Per Kelas"
                    description="Detail rekaman presensi siswa per tanggal dan kelas dengan kalkulasi otomatis status Hadir, Izin, Sakit, dan Alpha."
                    :chips="[
                        ['icon' => 'ph-chalkboard', 'label' => 'Analisis Rombel'],
                        ['icon' => 'ph-calendar-blank', 'label' => 'Rekap Per Tanggal'],
                        ['icon' => 'ph-printer', 'label' => 'Siap Cetak']
                    ]"
                    heroIcon="ph-chalkboard-teacher"
                    statusOrb="Aktif"
                    statusColor="sky"
                    ctaPrimaryText="Rekap Semua Kelas"
                    ctaPrimaryHref="{{ route('reports.class') }}"
                    ctaPrimaryIcon="ph-arrow-left"
                    ctaSecondaryText="Dashboard Utama"
                    ctaSecondaryHref="{{ route('dashboard') }}"
                    ctaSecondaryIcon="ph-squares-four"
                />
            </div>

            <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-white/10 p-6 md:p-8 shadow-2xl backdrop-blur-xl text-white relative overflow-hidden flex items-center mb-8 no-print" style="animation-delay: 100ms">
                     
                     {{-- Form Filter --}}
                     <form action="{{ route('reports.class.detail') }}" method="GET" class="w-full flex flex-col md:flex-row gap-5 items-end md:items-center" @submit="loading = true">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                            <div class="relative">
                                <i class="ph-bold ph-chalkboard-teacher absolute left-4 top-4 text-sky-400 text-xl pointer-events-none z-10"></i>
                                <select name="class_id" class="w-full pl-12 rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white h-14 text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-colors [color-scheme:dark]" onchange="this.form.submit()">
                                    <option value="" disabled {{ !$classId ? 'selected' : '' }} class="bg-slate-900 text-white">-- Pilih Kelas --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="w-full md:w-64">
                            <label class="block text-xs font-bold text-sky-300 uppercase tracking-wider mb-2 ml-1">Bulan</label>
                            <input type="month" name="month" value="{{ $monthStr }}" class="w-full rounded-2xl border border-white/10 bg-slate-900/80 font-bold text-white h-14 text-sm px-5 focus:border-sky-400 focus:ring-2 focus:ring-sky-400/20 transition-colors [color-scheme:dark]">
                        </div>
                        <button type="submit" class="w-full md:w-auto bg-gradient-to-r from-[#0d52a1] to-sky-600 hover:from-sky-600 hover:to-[#0d52a1] text-white px-8 rounded-2xl h-14 font-bold text-sm shadow-lg shadow-sky-950/40 flex items-center justify-center gap-2 transition-all active:scale-95 border border-sky-400/30">
                            <i class="ph-bold ph-magnifying-glass"></i> Tampilkan
                        </button>
                     </form>
            </div>

            @if($classId && $students->count() > 0)
                <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl overflow-hidden backdrop-blur-xl" style="animation-delay: 200ms">                    
                   <div class="p-6 md:p-8 border-b border-white/10 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <h3 class="font-black text-white text-2xl mb-1 flex items-center gap-2.5">
                                <i class="ph-bold ph-chalkboard text-sky-400"></i> Kelas {{ optional($classes->where('id', $classId)->first())->name ?? 'Kelas' }}
                            </h3>
                            <p class="text-sm text-sky-300 font-bold flex items-center gap-1.5">
                                <i class="ph-bold ph-calendar text-xs"></i> {{ $startDate->translatedFormat('F Y') }}
                            </p>
                        </div>
                        <div class="flex gap-3">
                             <div class="hidden sm:flex items-center gap-3 text-xs font-bold uppercase tracking-wider bg-white/5 border border-white/10 px-5 py-2.5 rounded-2xl shadow-sm text-slate-300">
                                <span class="w-3 h-3 rounded-full bg-[#107C10]"></span> H
                                <span class="w-3 h-3 rounded-full bg-[#D83B01] ml-2"></span> B
                                <span class="w-3 h-3 rounded-full bg-sky-400 ml-2"></span> S/I
                                <span class="w-3 h-3 rounded-full bg-[#D13438] ml-2"></span> A
                            </div>
                            <a href="{{ route('reports.printClassReport', request()->all()) }}" target="_blank" class="bg-white/10 border border-white/15 text-white hover:text-sky-300 hover:bg-white/20 w-12 h-12 flex items-center justify-center rounded-2xl transition-colors shadow-sm no-print" title="Cetak Laporan"><i class="ph-bold ph-printer text-xl"></i></a>
                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar pb-2 bg-transparent">
                        <table class="w-full border-collapse text-sm text-left">
                            <thead>
                                <tr class="bg-[#021124]/80 border-b border-white/10 text-slate-200">
                                    <th rowspan="2" class="p-4 font-black uppercase text-[10px] tracking-wider text-center w-12 sticky left-0 z-20 bg-[#031d3d] border-r border-white/10 align-middle">No</th>
                                    <th rowspan="2" class="p-4 font-black uppercase text-[10px] tracking-wider min-w-[200px] sticky left-12 z-20 bg-[#031d3d] border-r border-white/10 align-middle">Nama Siswa</th>
                                    @foreach($dates as $date)
                                        <th colspan="2" class="p-1.5 font-bold text-[10px] text-center border-r border-white/10 {{ ($date->isSaturday() || $date->isSunday()) ? 'bg-rose-500/15 text-rose-300' : 'text-slate-300' }}">{{ $date->format('d') }}</th>
                                    @endforeach
                                    <th rowspan="2" class="p-3 font-black text-[#107C10] bg-[#107C10]/15 text-center w-12 border-l border-white/10 align-middle">H</th>
                                    <th rowspan="2" class="p-3 font-black text-[#D83B01] bg-[#D83B01]/15 text-center w-12 align-middle">B</th>
                                    <th rowspan="2" class="p-3 font-black text-sky-400 bg-sky-500/15 text-center w-12 align-middle">S</th>
                                    <th rowspan="2" class="p-3 font-black text-slate-300 bg-white/5 text-center w-12 align-middle">I</th>
                                    <th rowspan="2" class="p-3 font-black text-[#D13438] bg-[#D13438]/15 text-center w-12 align-middle">A</th>
                                </tr>
                                <tr class="bg-[#021124]/60 border-b border-white/10 text-slate-400">
                                    @foreach($dates as $date)
                                        <th class="p-1 font-bold text-[8px] text-center border-r border-white/5 min-w-[20px] {{ ($date->isSaturday() || $date->isSunday()) ? 'bg-rose-500/10 text-rose-300/80' : '' }}">M</th>
                                        <th class="p-1 font-bold text-[8px] text-center border-r border-white/10 min-w-[20px] {{ ($date->isSaturday() || $date->isSunday()) ? 'bg-rose-500/10 text-rose-300/80' : '' }}">P</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach($students as $index => $student)
                                    <tr class="hover:bg-white/5 transition-colors group">
                                        <td class="p-4 text-center text-xs font-bold text-slate-300 sticky left-0 bg-[#031d3d] group-hover:bg-[#082b54] z-10 border-r border-white/5">{{ $index + 1 }}</td>
                                        <td class="p-4 font-bold text-white whitespace-nowrap sticky left-12 bg-[#031d3d] group-hover:bg-[#082b54] z-10 border-r border-white/10">{{ data_get($student, 'name') }}</td>
                                        @foreach($dates as $date)
                                            @php 
                                                $dateStr = $date->format('Y-m-d');
                                                $data = data_get($student, 'attendance_map.' . $dateStr, ['in_code' => '-', 'in_class' => 'text-slate-400', 'out_code' => '-', 'out_class' => 'text-slate-400']);
                                            @endphp
                                            <td class="p-1 text-center border-r border-white/5 text-[10px] font-bold {{ $data['in_class'] ?? 'text-slate-400' }}">{{ $data['in_code'] ?? '-' }}</td>
                                            <td class="p-1 text-center border-r border-white/10 text-[10px] font-bold {{ $data['out_class'] ?? 'text-slate-400' }}">{{ $data['out_code'] ?? '-' }}</td>
                                        @endforeach
                                        <td class="p-3 text-center font-black text-[#107C10] bg-[#107C10]/10 text-xs border-l border-white/10">{{ data_get($student, 'summary.H', 0) }}</td>
                                        <td class="p-3 text-center font-black text-[#D83B01] bg-[#D83B01]/10 text-xs">{{ data_get($student, 'summary.B', 0) }}</td>
                                        <td class="p-3 text-center font-black text-sky-400 bg-sky-500/10 text-xs">{{ data_get($student, 'summary.S', 0) }}</td>
                                        <td class="p-3 text-center font-black text-slate-300 bg-white/5 text-xs">{{ data_get($student, 'summary.I', 0) }}</td>
                                        <td class="p-3 text-center font-black text-[#D13438] bg-[#D13438]/10 text-xs">{{ data_get($student, 'summary.A', 0) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @elseif($classId)
                <div class="animate-enter text-center py-24 bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40" style="animation-delay: 200ms">
                     <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary"><i class="ph-duotone ph-student text-5xl"></i></div>
                    <h3 class="text-xl font-black text-elevate-dark mb-2">Data Siswa Kosong</h3>
                    <p class="text-elevate-dark/60 font-semibold">Tidak ada siswa aktif ditemukan di kelas ini.</p>
                </div>
            @else
                <div class="animate-enter text-center py-24 bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-elevate-peach-light rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-peach-dark border border-elevate-peach"><i class="ph-duotone ph-chalkboard-teacher text-5xl"></i></div>
                    <h3 class="text-xl font-black text-elevate-dark mb-2">Pilih Kelas Terlebih Dahulu</h3>
                    <p class="text-elevate-dark/60 font-semibold">Silakan pilih kelas dan bulan untuk melihat rekapitulasi.</p>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>