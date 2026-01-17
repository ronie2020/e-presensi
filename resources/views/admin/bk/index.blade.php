<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('E-Counseling (Bimbingan Konseling)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Statistik Ringkas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-amber-500">
                    <div class="text-slate-500 text-xs font-bold uppercase">Menunggu Respon</div>
                    <div class="text-2xl font-bold text-slate-800">{{ \App\Models\BkSession::where('status', 'pending')->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-blue-500">
                    <div class="text-slate-500 text-xs font-bold uppercase">Terjadwal</div>
                    <div class="text-2xl font-bold text-slate-800">{{ \App\Models\BkSession::where('status', 'approved')->count() }}</div>
                </div>
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-5 border-l-4 border-green-500">
                    <div class="text-slate-500 text-xs font-bold uppercase">Selesai Bulan Ini</div>
                    <div class="text-2xl font-bold text-slate-800">{{ \App\Models\BkSession::where('status', 'finished')->whereMonth('created_at', now()->month)->count() }}</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-slate-700">Daftar Pengajuan Konseling</h3>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.bk.index', ['status' => 'pending']) }}" class="px-3 py-1 text-xs rounded-full border {{ request('status') == 'pending' ? 'bg-amber-100 text-amber-800 border-amber-300' : 'bg-white text-slate-500 border-slate-200' }}">Pending</a>
                            <a href="{{ route('admin.bk.index', ['status' => 'approved']) }}" class="px-3 py-1 text-xs rounded-full border {{ request('status') == 'approved' ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-white text-slate-500 border-slate-200' }}">Terjadwal</a>
                            <a href="{{ route('admin.bk.index', ['status' => 'all']) }}" class="px-3 py-1 text-xs rounded-full border {{ request('status') == 'all' || !request('status') ? 'bg-slate-100 text-slate-800 border-slate-300' : 'bg-white text-slate-500 border-slate-200' }}">Semua</a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topik & Pesan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($sessions as $session)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-slate-100 rounded-full flex items-center justify-center font-bold text-slate-500">
                                                {{ substr($session->student->name, 0, 1) }}
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $session->student->name }}</div>
                                                {{-- PERBAIKAN: Gunakan schoolClass --}}
                                                <div class="text-xs text-gray-500">{{ $session->student->schoolClass->name ?? 'Kelas -' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-slate-100 text-slate-800 mb-1">
                                            {{ $session->category->name }}
                                        </span>
                                        <p class="text-sm text-gray-500 truncate w-48" title="{{ $session->initial_message }}">
                                            {{ $session->initial_message }}
                                        </p>
                                        <div class="text-xs text-gray-400 mt-1">{{ $session->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        @if($session->method == 'online')
                                            <span class="flex items-center gap-1"><i class="ph-fill ph-globe"></i> Online</span>
                                        @else
                                            <span class="flex items-center gap-1"><i class="ph-fill ph-users"></i> Tatap Muka</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $colors = [
                                                'pending' => 'bg-amber-100 text-amber-800',
                                                'approved' => 'bg-blue-100 text-blue-800',
                                                'ongoing' => 'bg-purple-100 text-purple-800',
                                                'finished' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                            ];
                                        @endphp
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $colors[$session->status] }}">
                                            {{ ucfirst($session->status) }}
                                        </span>
                                        @if($session->scheduled_at && $session->status == 'approved')
                                            <div class="text-xs text-blue-600 font-bold mt-1">{{ $session->scheduled_at->format('d M H:i') }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.bk.show', $session->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded-md">Proses</a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        Belum ada data pengajuan.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $sessions->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>