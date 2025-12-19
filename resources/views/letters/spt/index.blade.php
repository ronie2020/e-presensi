<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Surat Perintah Tugas') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Success -->
            @if(session('success'))
                <div class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4" role="alert">
                    <p class="font-bold">Sukses!</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="mb-4 flex justify-between items-center">
                <div class="text-gray-600 text-sm">
                    Menampilkan daftar tugas pegawai.
                </div>
                <a href="{{ route('letters.spt.create') }}" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 font-bold shadow transition transform hover:scale-105">
                    + Buat SPT Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Info SPT</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dasar Surat</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pegawai Ditugaskan</th>
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($spts as $spt)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-bold text-indigo-700 text-sm font-mono">{{ $spt->nomor_spt }}</div>
                                    <div class="text-xs text-gray-500 mt-1">
                                        <span class="block">Ke: <strong>{{ $spt->tempat_tujuan }}</strong></span>
                                        <span class="block">Tgl: {{ $spt->tgl_berangkat->format('d/m/Y') }} ({{ $spt->lama_hari }} hari)</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-600">
                                    @if($spt->letterIncoming)
                                        <div class="text-xs font-bold text-gray-700">{{ $spt->letterIncoming->nomor_surat }}</div>
                                        <div class="text-xs italic text-gray-500 line-clamp-2">{{ $spt->letterIncoming->perihal }}</div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">- Tidak ada dasar surat -</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($spt->users as $user)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                {{ $user->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                    <div class="text-xs text-gray-500 font-normal italic mt-2 border-t pt-1">
                                        "{{ Str::limit($spt->untuk, 50) }}"
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex flex-col space-y-2">
                                        {{-- TOMBOL CETAK DIPERBAIKI DISINI --}}
                                        <a href="{{ route('letters.spt.print', $spt->id) }}" target="_blank" class="text-white bg-blue-600 hover:bg-blue-700 border border-blue-600 px-3 py-1 rounded text-center text-xs font-bold shadow-sm transition">
                                            <i class="fas fa-print mr-1"></i> Cetak SPT
                                        </a>
                                        
                                        {{-- Tombol Edit (Opsional) --}}
                                        <a href="#" class="text-gray-600 hover:text-gray-900 border border-gray-300 px-3 py-1 rounded text-center text-xs hover:bg-gray-50">
                                            Edit
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    <p class="mt-2 text-sm font-medium">Belum ada Surat Perintah Tugas.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    
                    <div class="mt-4">
                        {{ $spts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>