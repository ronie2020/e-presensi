<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-semibold text-xl text-slate-800 leading-tight">
                {{ __('Penerimaan Peserta Didik Baru') }}
            </h2>
            <!-- Filter Tahun -->
            <form method="GET" class="flex items-center gap-2">
                <select name="year" onchange="this.form.submit()" class="text-sm border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    @for($i = date('Y'); $i >= date('Y')-2; $i--)
                        <option value="{{ $i }}" {{ $year == $i ? 'selected' : '' }}>T.P {{ $i }}/{{ $i+1 }}</option>
                    @endfor
                </select>
            </form>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistik Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-blue-500">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Total Pendaftar</div>
                    <div class="text-3xl font-black text-slate-800 mt-1">{{ $stats['total'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-yellow-400">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Menunggu Verifikasi</div>
                    <div class="text-3xl font-black text-yellow-600 mt-1">{{ $stats['pending'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-emerald-500">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Diterima</div>
                    <div class="text-3xl font-black text-emerald-600 mt-1">{{ $stats['accepted'] }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl p-6 border-l-4 border-indigo-500">
                    <div class="text-slate-500 text-xs font-bold uppercase tracking-wider">Terverifikasi</div>
                    <div class="text-3xl font-black text-indigo-600 mt-1">{{ $stats['verified'] }}</div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl">
                <div class="p-6">
                    
                    <!-- Search & Filter Bar -->
                    <div class="flex flex-col md:flex-row justify-between gap-4 mb-6">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.ppdb.index') }}" class="px-4 py-2 text-sm font-bold rounded-lg {{ !request('status') ? 'bg-slate-800 text-white' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">Semua</a>
                            <a href="{{ route('admin.ppdb.index', ['status' => 'pending']) }}" class="px-4 py-2 text-sm font-bold rounded-lg {{ request('status') == 'pending' ? 'bg-yellow-500 text-white' : 'bg-yellow-50 text-yellow-700 hover:bg-yellow-100' }}">Pending</a>
                        </div>
                        <form method="GET" class="relative">
                            <input type="hidden" name="status" value="{{ request('status') }}">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama / NISN..." class="pl-10 pr-4 py-2 border-slate-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-2.5 text-slate-400"></i>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-3">No. Daftar</th>
                                    <th class="px-6 py-3">Nama Siswa</th>
                                    <th class="px-6 py-3">Jalur</th>
                                    <th class="px-6 py-3">Nilai</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3 text-right">Aksi</th>
                                </a>
                            </thead>
                            <tbody>
                                @forelse($registrants as $item)
                                <tr class="bg-white border-b border-slate-100 hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 font-mono font-bold text-slate-500">{{ $item->registration_number }}</td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-slate-800">{{ $item->full_name }}</div>
                                        <div class="text-xs text-slate-500">NISN: {{ $item->nisn }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded text-xs font-bold uppercase 
                                            {{ $item->track == 'prestasi' ? 'bg-purple-100 text-purple-700' : 
                                              ($item->track == 'afirmasi' ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700') }}">
                                            {{ $item->track }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold">{{ $item->average_grade }}</td>
                                    <td class="px-6 py-4">
                                        @if($item->status == 'pending')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-500 animate-pulse"></span> Pending
                                            </span>
                                        @elseif($item->status == 'verified')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="ph-bold ph-check"></i> Terverifikasi
                                            </span>
                                        @elseif($item->status == 'accepted')
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                                <i class="ph-bold ph-medal"></i> Diterima
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="ph-bold ph-x"></i> Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.ppdb.show', $item->id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400 italic">
                                        Tidak ada data pendaftar yang ditemukan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $registrants->withQueryString()->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>