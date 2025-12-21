<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Surat Perintah Tugas') }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Toolbar: Pencarian dan Tombol Tambah -->
            <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <!-- Form Pencarian -->
                <div class="w-full sm:w-1/3">
                    <form action="{{ route('letters.spt.index') }}" method="GET" class="relative group">
                        <input type="text" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari No. SPT / Pegawai / Tujuan..." 
                               class="w-full rounded-full border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200 focus:ring-opacity-50 pl-10 transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-green-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </form>
                </div>

                <a href="{{ route('letters.spt.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-full font-semibold text-xs uppercase tracking-widest hover:from-green-700 hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat SPT Baru
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="bg-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Info SPT & Tujuan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pegawai Ditugaskan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Dasar Surat</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($spts as $spt)
                                <tr class="hover:bg-green-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-green-700 font-mono bg-green-50 inline-block px-3 py-1 rounded-lg border border-green-100 mb-2">{{ $spt->nomor_spt }}</div>
                                        <div class="text-sm text-gray-900 font-semibold">{{ $spt->tempat_tujuan }}</div>
                                        <div class="text-xs text-gray-500 mt-1">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                {{ $spt->tgl_berangkat->format('d/m/Y') }} s.d {{ $spt->tgl_kembali->format('d/m/Y') }}
                                            </span>
                                            <span class="text-green-600 font-medium ml-4">({{ $spt->lama_hari }} Hari)</span>
                                        </div>
                                        <div class="text-xs text-gray-500 mt-2 italic border-t pt-1 border-gray-100 line-clamp-2">
                                            "{{ $spt->untuk }}"
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1.5">
                                            @foreach($spt->users as $user)
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                                                    {{ $user->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs">
                                        @if($spt->letterIncoming)
                                            <div class="bg-gray-50 p-2 rounded border border-gray-200">
                                                <div class="text-xs font-bold text-gray-700 mb-1">Ref: {{ $spt->letterIncoming->nomor_surat }}</div>
                                                <div class="text-xs italic text-gray-500 line-clamp-2">{{ $spt->letterIncoming->perihal }}</div>
                                            </div>
                                        @else
                                            <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-500">
                                                - Tanpa Dasar -
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col space-y-2 items-center w-full">
                                            {{-- TOMBOL CETAK --}}
                                            <a href="{{ route('letters.spt.print', $spt->id) }}" target="_blank" class="w-full text-center text-white bg-blue-600 hover:bg-blue-700 border border-blue-600 px-3 py-1.5 rounded-lg text-xs font-bold shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                                <i class="fas fa-print mr-1"></i> Cetak SPT
                                            </a>
                                            
                                            <div class="flex space-x-2 w-full">
                                                {{-- Edit (Placeholder jika belum ada route edit) --}}
                                                {{-- <a href="#" class="flex-1 text-center text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-2 py-1.5 rounded-lg text-xs transition">Edit</a> --}}
                                                
                                                {{-- Tombol Hapus dengan SweetAlert --}}
                                                <button type="button" onclick="confirmDelete('{{ $spt->id }}')" class="flex-1 text-center text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 border border-red-200 px-2 py-1.5 rounded-lg text-xs transition">
                                                    Hapus
                                                </button>
                                                
                                                {{-- Form Hapus Tersembunyi --}}
                                                <form id="delete-form-{{ $spt->id }}" action="{{ route('letters.spt.destroy', $spt->id) }}" method="POST" class="hidden">
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
                                            <p class="text-base font-medium text-gray-600">Belum ada Surat Perintah Tugas.</p>
                                            @if(request('search'))
                                                <p class="text-sm text-gray-400 mt-1">Tidak ditemukan data dengan kata kunci "{{ request('search') }}"</p>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-4 border-t border-gray-200 bg-gray-50 rounded-b-2xl">
                        {{ $spts->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SWEETALERT 2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Toast Notifikasi
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

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus SPT?',
                text: "Data penugasan dan relasi pegawai akan dihapus permanen.",
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