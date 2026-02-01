<x-app-layout>
    {{-- Style Tambahan --}}
    @push('styles')
    {{-- [PERBAIKAN] Load Font Plus Jakarta Sans --}}
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <style>
        /* [PERBAIKAN] Paksa penggunaan font Plus Jakarta Sans */
        body, .font-sans { font-family: 'Plus Jakarta Sans', sans-serif !important; }

        .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        .table-row-hover:hover td { background-color: #f8fafc; }
    </style>
    @endpush

    <div class="py-6 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER SECTION --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-indigo-900 to-slate-900 p-8 mb-8 text-white shadow-xl overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <h2 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center gap-3">
                            <i class="ph-duotone ph-scroll text-indigo-400"></i>
                            Riwayat Perizinan
                        </h2>
                        <p class="text-indigo-200 text-sm max-w-xl">
                            Rekap data siswa yang meninggalkan kelas. Gunakan filter di bawah untuk mencari data spesifik atau mencetak laporan.
                        </p>
                    </div>
                    
                    {{-- TOMBOL EXPORT AKTIF --}}
                    <div class="flex gap-2">
                        <a href="{{ route('permit.export', request()->all()) }}" target="_blank" class="group flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl text-sm font-bold transition border border-white/10 cursor-pointer text-indigo-100 hover:text-white">
                            <i class="ph-bold ph-microsoft-excel-logo text-emerald-400 group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline">Export Excel</span>
                        </a>
                        <a href="{{ route('permit.print', request()->all()) }}" target="_blank" class="group flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 backdrop-blur-md rounded-xl text-sm font-bold transition border border-white/10 cursor-pointer text-indigo-100 hover:text-white">
                            <i class="ph-bold ph-printer text-rose-400 group-hover:scale-110 transition-transform"></i>
                            <span class="hidden sm:inline">Print / PDF</span>
                        </a>
                    </div>
                </div>
            </div>

            {{-- STATISTIK RINGKAS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <!-- Card 1: Total Data -->
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-files"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase">Total Izin</div>
                        <div class="text-2xl font-black text-slate-800">{{ $permits->total() }}</div>
                    </div>
                </div>

                <!-- Card 2: Status Keluar -->
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-timer"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase">Status Keluar</div>
                        <div class="text-xl font-bold text-slate-800">
                             {{ $permits->whereNull('time_in')->count() }} <span class="text-xs font-normal text-slate-400">(Hlm ini)</span>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Sudah Kembali -->
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-check-circle"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase">Sudah Kembali</div>
                        <div class="text-xl font-bold text-slate-800">
                            {{ $permits->whereNotNull('time_in')->count() }} <span class="text-xs font-normal text-slate-400">(Hlm ini)</span>
                        </div>
                    </div>
                </div>
                
                 <!-- Card 4: Info Tanggal -->
                 <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-xl">
                        <i class="ph-duotone ph-calendar-blank"></i>
                    </div>
                    <div>
                        <div class="text-xs text-slate-500 font-bold uppercase">Filter Tanggal</div>
                        <div class="text-sm font-bold text-slate-800">
                            {{ request('date') ? \Carbon\Carbon::parse(request('date'))->format('d M Y') : 'Semua Waktu' }}
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER SECTION --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-lg border border-slate-100 mb-6">
                <form action="{{ route('permit.history') }}" method="GET" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                    
                    {{-- Search --}}
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Cari Siswa</label>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" 
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-medium placeholder:text-slate-400" 
                                placeholder="Nama atau NIS siswa...">
                            <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                                <i class="ph-bold ph-magnifying-glass"></i>
                            </div>
                        </div>
                    </div>

                    {{-- [UPGRADE] Tanggal dengan Tombol Prev/Next --}}
                    <div class="md:col-span-4">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Tanggal</label>
                        <div class="flex gap-1">
                            {{-- Tombol Mundur 1 Hari --}}
                            @php
                                $currentDate = request('date', date('Y-m-d'));
                                $prevDate = \Carbon\Carbon::parse($currentDate)->subDay()->format('Y-m-d');
                                $nextDate = \Carbon\Carbon::parse($currentDate)->addDay()->format('Y-m-d');
                            @endphp
                            
                            <a href="{{ route('permit.history', array_merge(request()->all(), ['date' => $prevDate])) }}" 
                               class="px-3 py-2.5 bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-600 rounded-xl transition border border-slate-200">
                                <i class="ph-bold ph-caret-left"></i>
                            </a>

                            <input type="date" name="date" value="{{ $currentDate }}" 
                                class="w-full px-4 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-medium text-slate-600">

                            {{-- Tombol Maju 1 Hari --}}
                            <a href="{{ route('permit.history', array_merge(request()->all(), ['date' => $nextDate])) }}" 
                               class="px-3 py-2.5 bg-slate-100 hover:bg-indigo-100 text-slate-600 hover:text-indigo-600 rounded-xl transition border border-slate-200">
                                <i class="ph-bold ph-caret-right"></i>
                            </a>
                        </div>
                    </div>

                    {{-- Status --}}
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-2">Status</label>
                        <select name="status" class="w-full px-3 py-2.5 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-0 text-sm font-medium text-slate-600">
                            <option value="">Semua</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Di Luar</option>
                            <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Kembali</option>
                            <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Telat</option>
                        </select>
                    </div>

                    {{-- Actions --}}
                    <div class="md:col-span-2 flex gap-2">
                        <button type="submit" class="flex-1 bg-indigo-600 text-white py-2.5 rounded-xl font-bold text-sm hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-funnel"></i> Filter
                        </button>
                        <a href="{{ route('permit.history') }}" class="px-3 py-2.5 bg-slate-100 text-slate-500 rounded-xl hover:bg-slate-200 transition flex items-center justify-center" title="Reset Filter">
                            <i class="ph-bold ph-arrow-counter-clockwise text-lg"></i>
                        </a>
                    </div>
                </form>
            </div>

            {{-- DATA TABLE --}}
            <div class="bg-white rounded-[2rem] shadow-xl border border-slate-100 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100 text-left">
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Waktu Keluar</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Keperluan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Durasi</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Waktu Kembali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($permits as $permit)
                            <tr class="table-row-hover transition-colors">
                                {{-- WAKTU --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($permit->time_out)->format('H:i') }}</span>
                                        <span class="text-[10px] text-slate-400 font-mono">{{ \Carbon\Carbon::parse($permit->time_out)->format('d M Y') }}</span>
                                    </div>
                                </td>

                                {{-- SISWA --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm border border-indigo-100">
                                            {{ substr($permit->student->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-700 text-sm">{{ $permit->student->name }}</div>
                                            <div class="text-xs text-slate-500">{{ $permit->student->schoolClass->name ?? '-' }} • {{ $permit->student->student_id }}</div>
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
                                            <span class="text-xs text-slate-500 italic max-w-[200px] truncate" title="{{ $permit->notes }}">
                                                "{{ $permit->notes }}"
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- DURASI --}}
                                <td class="px-6 py-4">
                                    @php
                                        // Hitung durasi: Jika sudah kembali pakai data DB, jika belum hitung selisih real-time
                                        $duration = $permit->time_in 
                                            ? $permit->duration_minutes 
                                            : \Carbon\Carbon::parse($permit->time_out)->diffInMinutes(now());
                                        
                                        $isLongDuration = $duration > 15; // Threshold 15 menit
                                    @endphp

                                    <div class="font-mono font-bold {{ $isLongDuration && !$permit->time_in ? 'text-rose-500 animate-pulse' : 'text-slate-600' }}">
                                        {{ $duration }} <span class="text-xs font-normal text-slate-400">menit</span>
                                    </div>
                                </td>

                                {{-- STATUS --}}
                                <td class="px-6 py-4">
                                    @if($permit->time_in)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 border border-emerald-200">
                                            <i class="ph-bold ph-check"></i> Kembali
                                        </span>
                                    @else
                                        @if($isLongDuration)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-rose-100 text-rose-700 border border-rose-200 shadow-sm animate-pulse">
                                                <i class="ph-bold ph-warning"></i> Telat
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold bg-orange-100 text-orange-700 border border-orange-200">
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
                                        <span class="text-xs text-slate-400 italic">Belum kembali</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center text-slate-400">
                                        <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3">
                                            <i class="ph-duotone ph-magnifying-glass text-3xl"></i>
                                        </div>
                                        <p class="font-bold text-slate-600">Data tidak ditemukan</p>
                                        <p class="text-sm">Coba ubah filter pencarian atau tanggal.</p>
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
</x-app-layout>