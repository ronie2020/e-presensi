<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Data SPPD (Surat Perjalanan Dinas)') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Toolbar: Pencarian dan Tombol Tambah -->
            <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <!-- Form Pencarian -->
                <div class="w-full sm:w-1/3">
                    <form action="{{ route('sppd.index') }}" method="GET" class="relative group">
                        <input type="text" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari No SPPD / Tujuan / Pegawai..." 
                               class="w-full rounded-full border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 pl-10 transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-indigo-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </form>
                </div>

                <a href="{{ route('sppd.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-indigo-600 to-indigo-700 text-white rounded-full font-semibold text-xs uppercase tracking-widest hover:from-indigo-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Input SPPD Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="bg-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. SPPD & Pegawai</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tujuan & Waktu</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Maksud Perjalanan</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($sppds as $sppd)
                                <tr class="hover:bg-indigo-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-indigo-700 font-mono bg-indigo-50 inline-block px-2 py-0.5 rounded border border-indigo-100 mb-2">
                                            {{ $sppd->nomor_sppd }}
                                        </div>
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8 bg-indigo-200 rounded-full flex items-center justify-center text-indigo-700 font-bold text-xs border border-indigo-300">
                                                {{ substr($sppd->user->name ?? '?', 0, 1) }}
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $sppd->user->name ?? 'Pegawai Terhapus' }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    NIP. {{ $sppd->user->nip ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-bold flex items-center">
                                            <svg class="w-4 h-4 text-gray-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $sppd->tempat_tujuan }}
                                        </div>
                                        <div class="text-xs text-gray-500 mt-1 ml-5">
                                            {{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->format('d M Y') }} s.d {{ \Carbon\Carbon::parse($sppd->tgl_kembali)->format('d M Y') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                            {{ $sppd->maksud_perjalanan }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col space-y-2 items-center">
                                            <a href="{{ route('sppd.print', $sppd->id) }}" target="_blank" class="w-full text-center text-white bg-blue-600 hover:bg-blue-700 border border-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold shadow-sm hover:shadow-md transition">
                                                <i class="fas fa-print mr-1"></i> Cetak SPPD
                                            </a>
                                            
                                            <div class="flex space-x-2 w-full justify-center">
                                                {{-- Tombol Hapus dengan SweetAlert --}}
                                                <button type="button" onclick="confirmDelete('{{ $sppd->id }}')" class="flex-1 text-center text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 border border-red-200 px-2 py-1.5 rounded-lg text-xs transition">
                                                    Hapus
                                                </button>
                                                
                                                <form id="delete-form-{{ $sppd->id }}" action="{{ route('sppd.destroy', $sppd->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-16 text-center text-gray-500 bg-white">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 rounded-full p-4 mb-4">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <p class="text-base font-medium text-gray-600">Belum ada data SPPD.</p>
                                            @if(request('search'))
                                                <p class="text-sm text-gray-400 mt-1">Tidak ditemukan dengan kata kunci "{{ request('search') }}"</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination Links -->
                    <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                        {{ $sppds->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SWEETALERT 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
            Toast.fire({
                icon: 'success',
                title: '{{ session('success') }}'
            });
        @endif

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus SPPD?',
                text: "Data SPPD dan pengikutnya akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#fff',
                borderRadius: '1rem',
                customClass: {
                    confirmButton: 'px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700',
                    cancelButton: 'px-4 py-2 rounded-lg bg-gray-200 text-gray-700 hover:bg-gray-300 ml-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-app-layout>