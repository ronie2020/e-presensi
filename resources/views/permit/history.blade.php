<x-app-layout>
    @push('styles')
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>        
        body, .font-sans { font-family: 'Plus Jakarta Sans', sans-serif !important; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .table-row-hover:hover td { background-color: #f8fafc; }
        
        /* New: Status Indicators on Table Row */
        .status-border-left { border-left: 3px solid transparent; transition: border-color 0.2s; }
        .tr-active:hover .status-border-left { border-left-color: #f97316; } /* Orange */
        .tr-returned:hover .status-border-left { border-left-color: #10b981; } /* Emerald */
        .tr-overdue:hover .status-border-left { border-left-color: #f43f5e; } /* Rose */
    </style>
    @endpush

    <div class="py-6 font-sans text-slate-800 bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="relative rounded-[2.5rem] bg-slate-900 p-8 mb-8 text-white shadow-2xl shadow-slate-900/10 overflow-hidden border border-white/10 group">
                <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-600 rounded-full mix-blend-overlay filter blur-[120px] opacity-20 group-hover:opacity-30 transition-opacity duration-1000 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-600 rounded-full mix-blend-overlay filter blur-[100px] opacity-20 pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center">
                                <i class="ph-duotone ph-scroll text-blue-300"></i>
                            </div>
                            Riwayat Perizinan
                        </h2>
                        <p class="text-slate-400 text-sm max-w-xl leading-relaxed ml-14">
                            Arsip digital aktivitas keluar-masuk siswa.
                        </p>
                    </div>
                    
                    {{-- TOMBOL EXPORT --}}
                    <div class="flex gap-2">
                        <a href="{{ route('permit.export', request()->all()) }}" target="_blank" class="group flex items-center gap-2 px-5 py-3 bg-emerald-600/20 hover:bg-emerald-600 border border-emerald-500/30 rounded-xl text-sm font-bold transition-all cursor-pointer text-emerald-300 hover:text-white">
                            <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                            <span class="hidden sm:inline">Excel</span>
                        </a>
                        <a href="{{ route('permit.print', request()->all()) }}" target="_blank" class="group flex items-center gap-2 px-5 py-3 bg-white/10 hover:bg-white border border-white/20 rounded-xl text-sm font-bold transition-all cursor-pointer text-white hover:text-slate-900">
                            <i class="ph-bold ph-printer text-lg"></i>
                            <span class="hidden sm:inline">Print / PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <!-- Card 1 -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:border-indigo-100 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Total Izin</div>
                        <div class="text-indigo-500 bg-indigo-50 rounded-lg p-1"><i class="ph-bold ph-files"></i></div>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $permits->total() }}</div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:border-orange-100 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Sedang Keluar</div>
                        <div class="text-orange-500 bg-orange-50 rounded-lg p-1"><i class="ph-bold ph-timer"></i></div>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $permits->whereNull('time_in')->count() }}</div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:border-emerald-100 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Sudah Kembali</div>
                        <div class="text-emerald-500 bg-emerald-50 rounded-lg p-1"><i class="ph-bold ph-check-circle"></i></div>
                    </div>
                    <div class="text-3xl font-black text-slate-800">{{ $permits->whereNotNull('time_in')->count() }}</div>
                </div>
                <!-- Card 4 -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-100 hover:border-blue-100 transition-colors">
                    <div class="flex justify-between items-start mb-2">
                        <div class="text-xs text-slate-400 font-bold uppercase tracking-wider">Tanggal Data</div>
                        <div class="text-blue-500 bg-blue-50 rounded-lg p-1"><i class="ph-bold ph-calendar-blank"></i></div>
                    </div>
                    <div class="text-sm font-bold text-slate-800 mt-2">
                        {{ request('date') ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Semua Waktu' }}
                    </div>
                </div>
            </div>

            {{-- FILTER SECTION (IMPROVED) --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100 mb-6">
                <form action="{{ route('permit.history') }}" method="GET" id="filterForm" class="grid grid-cols-1 md:grid-cols-12 gap-5 items-end">
                    
                    {{-- Search --}}
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Cari Siswa</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="w-full pl-10 pr-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-bold placeholder:text-slate-300" 
                                placeholder="Nama atau NIS...">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal dengan Preset --}}
                    <div class="md:col-span-5">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Tanggal</label>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <div class="relative flex-1">
                                <input type="date" name="date" id="dateInput" value="{{ request('date', date('Y-m-d')) }}" 
                                    class="w-full px-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-bold text-slate-600">
                            </div>
                            {{-- Preset Buttons --}}
                            <div class="flex gap-1">
                                <button type="button" onclick="setDate('{{ date('Y-m-d') }}')" class="px-3 py-2 bg-slate-50 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 border border-slate-200 rounded-lg text-xs font-bold transition">Hari Ini</button>
                                <button type="button" onclick="setDate('{{ date('Y-m-d', strtotime('-1 days')) }}')" class="px-3 py-2 bg-slate-50 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 border border-slate-200 rounded-lg text-xs font-bold transition">Kemarin</button>
                            </div>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-3">
                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wide mb-2">Status</label>
                        <div class="flex gap-2">
                            <select name="status" class="flex-1 px-3 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-bold text-slate-600">
                                <option value="">Semua Status</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Di Luar</option>
                                <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Kembali</option>
                            </select>
                            <button type="submit" class="bg-indigo-600 text-white p-3 rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200">
                                <i class="ph-bold ph-funnel text-lg"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- DATA TABLE --}}
            <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100 text-left">
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
                                    
                                    // Tentukan class baris berdasarkan status untuk styling border kiri
                                    $rowClass = $isReturned ? 'tr-returned' : ($isOverdue ? 'tr-overdue' : 'tr-active');
                                @endphp
                            <tr class="table-row-hover transition-colors group {{ $rowClass }} status-border-left">
                                {{-- WAKTU --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700 text-sm">{{ \Carbon\Carbon::parse($permit->time_out)->format('H:i') }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($permit->time_out)->format('d M') }}</span>
                                    </div>
                                </td>

                                {{-- SISWA --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm border 
                                            {{ $isOverdue ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-slate-100 text-slate-600 border-slate-200' }}">
                                            {{ substr($permit->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-700 text-sm">{{ $permit->student->name }}</div>
                                            <div class="text-xs text-slate-500 font-medium">{{ $permit->student->schoolClass->name ?? '-' }} <span class="text-slate-300">•</span> {{ $permit->student->student_id }}</div>
                                        </div>
                                    </div>
                                </td>

                                {{-- ALASAN --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col items-start gap-1">
                                        <span class="px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 border border-slate-200">
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
                                    <div class="font-mono font-bold {{ $isOverdue ? 'text-rose-500 animate-pulse' : 'text-slate-600' }}">
                                        {{ $duration }}<span class="text-[10px] text-slate-400 ml-0.5">m</span>
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4">
                                    @if($isReturned)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <i class="ph-bold ph-check"></i> Kembali
                                        </span>
                                    @else
                                        @if($isOverdue)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-rose-100 text-rose-700 border border-rose-200 shadow-sm animate-pulse">
                                                <i class="ph-bold ph-warning"></i> Telat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-orange-100 text-orange-700 border border-orange-200">
                                                <i class="ph-bold ph-timer"></i> Di Luar
                                            </span>
                                        @endif
                                    @endif
                                </td>

                                {{-- WAKTU KEMBALI --}}
                                <td class="px-6 py-4 text-right">
                                    @if($permit->time_in)
                                        <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($permit->time_in)->format('H:i') }}</span>
                                    @else
                                        <span class="text-[10px] text-slate-300 italic">...</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400 py-6">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 border border-slate-100">
                                            <i class="ph-duotone ph-magnifying-glass text-3xl opacity-50"></i>
                                        </div>
                                        <p class="font-bold text-slate-600">Data tidak ditemukan</p>
                                        <p class="text-sm">Coba sesuaikan tanggal atau kata kunci.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- PAGINATION --}}
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-100">
                    {{ $permits->withQueryString()->links() }}
                </div>
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