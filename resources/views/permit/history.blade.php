<x-app-layout>
    @push('styles')
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>        
        body, .font-sans { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        
        /* Animations */
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .delay-100 { animation-delay: 100ms; }
        .delay-200 { animation-delay: 200ms; }
        @keyframes wiggle { 0%, 100% { transform: rotate(0deg); } 25% { transform: rotate(-10deg); } 75% { transform: rotate(10deg); } }
        .group:hover .animate-wiggle { animation: wiggle 0.5s ease-in-out; }

        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }

          /* --- FLUENT UI SHADOWS --- */
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); }
                
       /* Table Styles */
        .table-row-hover:hover td { background-color: #f8fafc; }
        .status-border-left { border-left: 3px solid transparent; transition: border-color 0.2s; }
        .tr-active:hover .status-border-left { border-left-color: #D83B01; } 
        .tr-returned:hover .status-border-left { border-left-color: #107C10; } 
        .tr-overdue:hover .status-border-left { border-left-color: #D13438; } 
        /* Glass Utility */
        .glass-panel { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
    @endpush

 <div class="py-6 font-sans text-slate-800 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- HERO SECTION (ELEVATED THEME) --}}
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-6 md:p-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden group border border-white/40">
                
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-[10px] font-bold uppercase tracking-wider mb-3 backdrop-blur-sm shadow-sm">
                            <i class="ph-bold ph-archive"></i> Arsip Digital
                        </div>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2 leading-tight">
                            Riwayat Perizinan
                        </h2>
                        <p class="text-[#2A3B52]/80 text-sm max-w-xl leading-relaxed font-medium">
                            Pantau jejak aktivitas keluar-masuk siswa secara lengkap dan terperinci.
                        </p>
                    </div>
                    
                    {{-- TOMBOL EXPORT --}}
                    <div class="bg-white/40 backdrop-blur-md p-2 rounded-xl border border-white/50 shadow-sm flex gap-2 w-full md:w-auto">
                        <a href="{{ route('permit.export', request()->all()) }}" target="_blank" class="flex-1 md:flex-none justify-center group flex items-center gap-2 px-5 py-3 bg-[#DFF6DD] hover:bg-[#107C10] border border-[#B7DFB9] rounded-lg text-sm font-bold transition-all cursor-pointer text-[#107C10] hover:text-white shadow-sm border border-transparent">
                            <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                            <span>Excel</span>
                        </a>
                        <a href="{{ route('permit.print', request()->all()) }}" target="_blank" class="flex-1 md:flex-none justify-center group flex items-center gap-2 px-5 py-3 bg-white hover:bg-[#2A3B52] border border-white/50 rounded-lg text-sm font-bold transition-all cursor-pointer text-[#2A3B52] hover:text-white shadow-sm border border-transparent">
                            <i class="ph-bold ph-printer text-lg"></i>
                            <span>Print</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 animate-enter delay-100">
                <div class="group bg-white p-5 rounded-xl fluent-card transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-[#5295FF] transition-colors">Total Izin</div>
                        <div class="w-10 h-10 rounded-lg bg-[#F3F9FD] text-[#5295FF] flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-[#D0E7F8]">
                            <i class="ph-duotone ph-files text-xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $permits->total() }}</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-medium">Data sesuai filter</div>
                </div>

                <div class="group bg-white p-5 rounded-xl fluent-card transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-[#D83B01] transition-colors">Sedang Keluar</div>
                        <div class="w-10 h-10 rounded-lg bg-[#FFEFD6] text-[#D83B01] flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-[#FFD8A8]">
                            <i class="ph-duotone ph-timer text-xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $permits->whereNull('time_in')->count() }}</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-medium">Siswa belum kembali</div>
                </div>

                <div class="group bg-white p-5 rounded-xl fluent-card transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-[#107C10] transition-colors">Sudah Kembali</div>
                        <div class="w-10 h-10 rounded-lg bg-[#DFF6DD] text-[#107C10] flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-[#B7DFB9]">
                            <i class="ph-duotone ph-check-circle text-xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $permits->whereNotNull('time_in')->count() }}</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-medium">Proses selesai</div>
                </div>

                <div class="group bg-white p-5 rounded-xl fluent-card transition-all duration-300">
                    <div class="flex justify-between items-start mb-3">
                        <div class="text-[10px] text-slate-400 font-bold uppercase tracking-widest group-hover:text-[#5295FF] transition-colors">Tanggal Data</div>
                        <div class="w-10 h-10 rounded-lg bg-[#F3F9FD] text-[#5295FF] flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform border border-[#D0E7F8]">
                            <i class="ph-duotone ph-calendar-blank text-xl animate-wiggle"></i>
                        </div>
                    </div>
                    <div class="text-base font-bold text-slate-800 mt-1 truncate">
                        {{ request('date') ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Semua Waktu' }}
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1 font-medium">Filter terpilih</div>
                </div>
            </div>

            {{-- MAIN CONTENT: FILTER & TABLE --}}
            <div class="bg-white rounded-xl fluent-card overflow-hidden animate-enter delay-200">
                
                {{-- FILTER SECTION --}}
                <div class="p-6 md:p-8 border-b border-slate-50 bg-slate-50/30">
                    <form action="{{ route('permit.history') }}" method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                        
                        {{-- Search --}}
                        <div class="md:col-span-4 relative group">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2 ml-1">Cari Siswa</label>
                            <div class="relative">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                    class="w-full pl-11 pr-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-[#5295FF] focus:ring-0 text-sm font-bold placeholder:text-slate-300 shadow-sm transition-colors" 
                                    placeholder="Nama atau NIS...">
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 transition-colors">
                                    <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                </div>
                            </div>
                        </div>

                        {{-- Tanggal dengan Preset --}}
                        <div class="md:col-span-5">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2 ml-1">Tanggal</label>
                            <div class="flex flex-col sm:flex-row gap-2">
                                <div class="relative flex-1 group">
                                    <input type="date" name="date" id="dateInput" value="{{ request('date', date('Y-m-d')) }}" 
                                        class="w-full px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-[#5295FF] focus:ring-0 text-sm font-bold text-slate-600 shadow-sm transition-colors cursor-pointer">
                                </div>
                                <div class="flex gap-1.5">
                                    <button type="button" onclick="setDate('{{ date('Y-m-d') }}')" class="px-4 py-2 bg-slate-100 hover:bg-[#F3F9FD] text-slate-500 hover:text-[#5295FF] border border-slate-200 hover:border-[#D0E7F8] rounded-xl text-xs font-bold transition-all shadow-sm">Hari Ini</button>
                                    <button type="button" onclick="setDate('{{ date('Y-m-d', strtotime('-1 days')) }}')" class="px-4 py-2 bg-slate-100 hover:bg-[#F3F9FD] text-slate-500 hover:text-[#5295FF] border border-slate-200 hover:border-[#D0E7F8] rounded-xl text-xs font-bold transition-all shadow-sm">Kemarin</button>
                                </div>
                            </div>
                        </div>

                        {{-- Status & Submit --}}
                        <div class="md:col-span-3">
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide mb-2 ml-1">Status</label>
                            <div class="flex gap-2">
                                <div class="relative flex-1">
                                    <select name="status" class="w-full appearance-none px-4 py-3 rounded-xl border border-slate-200 bg-white focus:border-[#5295FF] focus:ring-0 text-sm font-bold text-slate-600 shadow-sm cursor-pointer">
                                        <option value="">Semua Status</option>
                                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Di Luar</option>
                                        <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Kembali</option>
                                    </select>
                                    <div class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                                <button type="submit" class="bg-[#2A3B52] hover:bg-[#182436] text-white px-4 rounded-xl transition-all shadow-md active:scale-95 flex items-center justify-center border border-transparent">
                                    <i class="ph-bold ph-funnel text-xl"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- DATA TABLE --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full whitespace-nowrap text-left text-sm">
                        <thead class="bg-slate-50/80 backdrop-blur-sm border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Siswa</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keperluan</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Durasi</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($permits as $permit)
                                @php
                                    $isReturned = $permit->time_in != null;
                                    $duration = $isReturned ? $permit->duration_minutes : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                    $isOverdue = $duration > 15 && !$isReturned;
                                    
                                    $rowClass = $isReturned ? 'tr-returned' : ($isOverdue ? 'tr-overdue' : 'tr-active');
                                @endphp
                            <tr class="table-row-hover transition-colors group {{ $rowClass }} status-border-left hover:bg-slate-50">
                                {{-- WAKTU --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($permit->time_out)->format('H:i') }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">{{ \Carbon\Carbon::parse($permit->time_out)->format('d M') }}</span>
                                    </div>
                                </td>

                                {{-- SISWA --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center font-black text-sm border shadow-sm transition-transform group-hover:scale-105
                                            {{ $isOverdue ? 'bg-[#FDE7E9] text-[#D13438] border-[#F4C3C9]' : 'bg-slate-100 text-[#2A3B52] border-slate-200' }}">
                                            {{ substr($permit->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-[#2A3B52] text-sm group-hover:text-[#5295FF] transition-colors">{{ $permit->student->name }}</div>
                                            <div class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                                <span>{{ $permit->student->schoolClass->name ?? '-' }}</span>
                                                <span class="text-slate-300">•</span> 
                                                <span class="font-mono">{{ $permit->student->student_id }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                {{-- ALASAN --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wide bg-white text-slate-600 border border-slate-200 shadow-sm">
                                            {{ $permit->reason_category }}
                                        </span>
                                        @if($permit->notes)
                                            <span class="text-xs text-slate-400 italic max-w-[150px] truncate" title="{{ $permit->notes }}">
                                                "{{ $permit->notes }}"
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- DURASI --}}
                                <td class="px-6 py-4">
                                    <div class="font-mono font-bold text-base {{ $isOverdue ? 'text-[#D13438] animate-pulse' : 'text-slate-600' }}">
                                        {{ $duration }}<span class="text-[10px] text-slate-400 ml-0.5 font-sans font-bold">m</span>
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4">
                                    @if($isReturned)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] shadow-sm">
                                            <i class="ph-bold ph-check"></i> Kembali
                                        </span>
                                    @else
                                        @if($isOverdue)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase bg-[#FDE7E9] text-[#D13438] border border-[#F4C3C9] shadow-sm animate-pulse">
                                                <i class="ph-bold ph-warning"></i> Telat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8] shadow-sm">
                                                <i class="ph-bold ph-timer"></i> Di Luar
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                {{-- WAKTU KEMBALI --}}
                                <td class="px-6 py-4 text-right">
                                    @if($permit->time_in)
                                        <span class="font-bold text-[#2A3B52] bg-slate-100 px-2 py-1 rounded-md border border-slate-200">{{ \Carbon\Carbon::parse($permit->time_in)->format('H:i') }}</span>
                                    @else
                                        <span class="text-slate-300 italic text-xl px-2">...</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-20 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-20 h-20 bg-slate-50 rounded-xl flex items-center justify-center mb-4 border border-slate-100">
                                            <i class="ph-duotone ph-magnifying-glass text-4xl opacity-50"></i>
                                        </div>
                                        <p class="font-bold text-[#2A3B52] text-lg">Data tidak ditemukan</p>
                                        <p class="text-sm opacity-70 mt-1">Coba sesuaikan filter tanggal atau kata kunci.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="bg-slate-50/50 px-6 py-6 border-t border-slate-100">
                    {{ $permits->withQueryString()->links() }}
                </div>
            </div>


            {{-- MOBILE VIEW (Cards) - Optimized --}}
            <div class="md:hidden space-y-4">
                @forelse($permits as $permit)
                    @php
                        $isReturned = $permit->time_in != null;
                        $duration = $isReturned ? $permit->duration_minutes : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                        $isOverdue = $duration > 15 && !$isReturned;
                        $borderColor = $isReturned ? 'border-emerald-500' : ($isOverdue ? 'border-rose-500' : 'border-orange-500');
                    @endphp
                    <div class="bg-white p-5 rounded-[1.5rem] shadow-sm border border-slate-100 relative overflow-hidden">
                        <div class="absolute left-0 top-0 bottom-0 w-1.5 {{ $borderColor }}"></div>
                        
                        <div class="flex justify-between items-start mb-3 pl-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-sm">
                                    {{ substr($permit->student->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-800">{{ $permit->student->name }}</div>
                                    <div class="text-xs text-slate-500 font-mono">{{ $permit->student->student_id }}</div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="font-mono font-bold text-slate-700 text-lg">{{ \Carbon\Carbon::parse($permit->time_out)->format('H:i') }}</div>
                                <div class="text-[10px] text-slate-400 uppercase font-bold">{{ \Carbon\Carbon::parse($permit->time_out)->format('d M') }}</div>
                            </div>
                        </div>
                        
                        <div class="pl-2 space-y-3">
                            <div class="flex items-center justify-between bg-slate-50 rounded-xl p-3 border border-slate-100">
                                <div>
                                    <div class="text-[10px] uppercase font-bold text-slate-400">Keperluan</div>
                                    <div class="text-sm font-bold text-slate-700">{{ $permit->reason_category }}</div>
                                </div>
                                @if($permit->notes)
                                <div class="text-right max-w-[50%]">
                                    <div class="text-[10px] uppercase font-bold text-slate-400">Catatan</div>
                                    <div class="text-xs italic text-slate-500 truncate">{{ $permit->notes }}</div>
                                </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between pt-1">
                                <div class="font-bold text-xs text-slate-500">
                                    Durasi: <span class="{{ $isOverdue ? 'text-rose-600' : 'text-slate-800' }} font-mono text-sm">{{ $duration }}m</span>
                                </div>
                                <div>
                                    @if($isReturned)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-700 text-[10px] font-bold border border-emerald-200">
                                            Kembali {{ \Carbon\Carbon::parse($permit->time_in)->format('H:i') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg {{ $isOverdue ? 'bg-rose-100 text-rose-700 border-rose-200' : 'bg-orange-100 text-orange-700 border-orange-200' }} text-[10px] font-bold border">
                                            {{ $isOverdue ? 'Telat' : 'Di Luar' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-10 text-center text-slate-400 bg-white rounded-[2rem] border border-slate-100 border-dashed">
                        <i class="ph-duotone ph-magnifying-glass text-4xl opacity-50 mb-3"></i>
                        <p class="font-bold text-sm">Tidak ada data ditemukan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function setDate(dateStr) {
            document.getElementById('dateInput').value = dateStr;
            document.getElementById('filterForm').submit();
        }
    </script>
</x-app-layout>