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
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8 no-print">
                <div class="animate-enter lg:col-span-1 rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-6 md:p-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 relative flex flex-col justify-center min-h-[160px]">
                     <div class="absolute -top-10 -left-10 w-40 h-40 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                     <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/30 rounded-full blur-2xl pointer-events-none"></div>
                     
                     <div class="relative z-10">
                         <a href="{{ route('reports.class') }}" class="group bg-white/40 hover:bg-white text-elevate-dark px-4 py-2.5 rounded-xl font-bold text-sm backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit mb-4">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali</span>
                        </a>
                        <h1 class="text-2xl font-black mb-1 tracking-tight text-elevate-dark flex items-center gap-2">Laporan Harian</h1>
                        <p class="text-elevate-dark/80 text-xs font-semibold">Detail absensi per tanggal.</p>
                    </div>
                </div>

                <div class="animate-enter lg:col-span-3 bg-white rounded-[2rem] border border-slate-100 p-6 md:p-8 shadow-xl shadow-slate-200/40 relative overflow-hidden flex items-center" style="animation-delay: 100ms">
                     
                     {{-- Form Filter --}}
                     <form action="{{ route('reports.class.detail') }}" method="GET" class="w-full flex flex-col md:flex-row gap-5 items-end md:items-center" @submit="loading = true">
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                            <div class="relative">
                                <i class="ph-bold ph-chalkboard-teacher absolute left-4 top-3.5 text-elevate-primary text-xl"></i>
                                <select name="class_id" class="w-full pl-12 rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark h-14 text-sm focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent transition-colors" onchange="this.form.submit()">
                                    <option value="" disabled {{ !$classId ? 'selected' : '' }}>-- Pilih Kelas --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="w-full md:w-64">
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Bulan</label>
                            <input type="month" name="month" value="{{ $monthStr }}" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark h-14 text-sm px-5 focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent transition-colors">
                        </div>
                        <button type="submit" class="w-full md:w-auto bg-elevate-dark hover:bg-elevate-primary text-white px-8 rounded-2xl h-14 font-bold text-sm shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 transition-all active:scale-95">
                            <i class="ph-bold ph-magnifying-glass"></i> Tampilkan
                        </button>
                    </form>
                </div>
            </div>

            @if($classId && $students->count() > 0)
                <div class="animate-enter bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden" style="animation-delay: 200ms">                    
                   <div class="p-6 md:p-8 border-b border-slate-100 bg-elevate-gradient-card flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <h3 class="font-black text-elevate-dark text-2xl mb-1">{{ optional($classes->where('id', $classId)->first())->name ?? 'Kelas' }}</h3>
                            <p class="text-sm text-elevate-primary font-bold">{{ $startDate->translatedFormat('F Y') }}</p>
                        </div>
                        <div class="flex gap-3">
                             <div class="hidden sm:flex items-center gap-3 text-xs font-bold uppercase tracking-wider bg-white border border-slate-100 px-5 py-2.5 rounded-2xl shadow-sm text-slate-600">
                                <span class="w-3 h-3 rounded-full bg-[#107C10]"></span> H
                                <span class="w-3 h-3 rounded-full bg-[#D83B01] ml-2"></span> B
                                <span class="w-3 h-3 rounded-full bg-elevate-primary ml-2"></span> S/I
                                <span class="w-3 h-3 rounded-full bg-[#D13438] ml-2"></span> A
                            </div>
                            <a href="{{ route('reports.printClassReport', request()->all()) }}" target="_blank" class="bg-white border border-slate-200 text-elevate-dark hover:text-white hover:bg-elevate-dark w-12 h-12 flex items-center justify-center rounded-2xl transition-colors shadow-sm no-print"><i class="ph-bold ph-printer text-xl"></i></a>
                        </div>
                    </div>

                    <div class="overflow-x-auto custom-scrollbar pb-2 bg-white">
                        <table class="w-full border-collapse text-sm text-left">
                            <thead>
                                <tr class="bg-elevate-soft/50 border-b border-slate-100 text-elevate-dark">
                                    <th rowspan="2" class="p-4 font-black uppercase text-[10px] tracking-wider text-center w-12 sticky left-0 z-20 bg-elevate-soft/90 backdrop-blur-sm shadow-[2px_0_5px_rgba(0,0,0,0.02)] align-middle">No</th>
                                    <th rowspan="2" class="p-4 font-black uppercase text-[10px] tracking-wider min-w-[200px] sticky left-12 z-20 bg-elevate-soft/90 backdrop-blur-sm shadow-[2px_0_5px_rgba(0,0,0,0.02)] border-r border-slate-100 align-middle">Nama Siswa</th>
                                    @foreach($dates as $date)
                                        <th colspan="2" class="p-1.5 font-bold text-[10px] text-center border-r border-slate-100 {{ ($date->isSaturday() || $date->isSunday()) ? 'bg-elevate-peach-light/30 text-elevate-peach-dark' : '' }}">{{ $date->format('d') }}</th>
                                    @endforeach
                                    <th rowspan="2" class="p-3 font-black text-[#107C10] bg-[#DFF6DD]/50 text-center w-12 border-l border-slate-100 align-middle">H</th>
                                    <th rowspan="2" class="p-3 font-black text-[#D83B01] bg-[#FFEFD6]/50 text-center w-12 align-middle">B</th>
                                    <th rowspan="2" class="p-3 font-black text-elevate-primary bg-elevate-soft/50 text-center w-12 align-middle">S</th>
                                    <th rowspan="2" class="p-3 font-black text-elevate-dark bg-slate-100 text-center w-12 align-middle">I</th>
                                    <th rowspan="2" class="p-3 font-black text-[#D13438] bg-[#FDE7E9]/50 text-center w-12 align-middle">A</th>
                                </tr>
                                <tr class="bg-white border-b border-slate-100 text-slate-400">
                                    @foreach($dates as $date)
                                        <th class="p-1 font-bold text-[8px] text-center border-r border-slate-50 min-w-[20px] {{ ($date->isSaturday() || $date->isSunday()) ? 'bg-elevate-peach-light/20' : '' }}">M</th>
                                        <th class="p-1 font-bold text-[8px] text-center border-r border-slate-100 min-w-[20px] {{ ($date->isSaturday() || $date->isSunday()) ? 'bg-elevate-peach-light/20' : '' }}">P</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($students as $index => $student)
                                    <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                        <td class="p-4 text-center text-xs font-bold text-slate-400 sticky left-0 bg-white group-hover:bg-elevate-soft/10 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.02)]">{{ $index + 1 }}</td>
                                        <td class="p-4 font-bold text-elevate-dark whitespace-nowrap sticky left-12 bg-white group-hover:bg-elevate-soft/10 z-10 shadow-[2px_0_5px_rgba(0,0,0,0.02)] border-r border-slate-50">{{ data_get($student, 'name') }}</td>
                                        @foreach($dates as $date)
                                            @php 
                                                $dateStr = $date->format('Y-m-d');
                                                $data = data_get($student, 'attendance_map.' . $dateStr, ['in_code' => '-', 'in_class' => 'text-slate-300', 'out_code' => '-', 'out_class' => 'text-slate-300']);
                                            @endphp
                                            <td class="p-1 text-center border-r border-slate-50 text-[10px] font-bold {{ $data['in_class'] ?? 'text-slate-300' }}">{{ $data['in_code'] ?? '-' }}</td>
                                            <td class="p-1 text-center border-r border-slate-100 text-[10px] font-bold {{ $data['out_class'] ?? 'text-slate-300' }}">{{ $data['out_code'] ?? '-' }}</td>
                                        @endforeach
                                        <td class="p-3 text-center font-black text-[#107C10] bg-[#DFF6DD]/30 text-xs border-l border-slate-50">{{ data_get($student, 'summary.H', 0) }}</td>
                                        <td class="p-3 text-center font-black text-[#D83B01] bg-[#FFEFD6]/30 text-xs">{{ data_get($student, 'summary.B', 0) }}</td>
                                        <td class="p-3 text-center font-black text-elevate-primary bg-elevate-soft/30 text-xs">{{ data_get($student, 'summary.S', 0) }}</td>
                                        <td class="p-3 text-center font-black text-elevate-dark bg-slate-50 text-xs">{{ data_get($student, 'summary.I', 0) }}</td>
                                        <td class="p-3 text-center font-black text-[#D13438] bg-[#FDE7E9]/30 text-xs">{{ data_get($student, 'summary.A', 0) }}</td>
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