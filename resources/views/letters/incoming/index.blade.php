<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Surat Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Toolbar: Pencarian dan Tombol Tambah -->
            <div class="mb-4 flex flex-col sm:flex-row justify-between items-center gap-4">
                <!-- Form Pencarian -->
                <div class="w-full sm:w-1/3">
                    <form action="{{ route('letters.incoming.index') }}" method="GET" class="relative group">
                        <input type="text" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari No Surat / Perihal..." 
                               class="w-full rounded-full border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 pl-10 transition-all duration-300">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </form>
                </div>

                <a href="{{ route('letters.incoming.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full font-semibold text-xs uppercase tracking-widest hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-lg transform hover:-translate-y-0.5 transition-all duration-200 flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Input Surat Masuk
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100">
                <div class="bg-white">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Surat / File</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Pengirim / Tanggal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Perihal</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Disposisi</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($letters as $letter)
                                <tr class="hover:bg-blue-50 transition-colors duration-200">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-blue-700 font-mono bg-blue-50 inline-block px-3 py-1 rounded-lg border border-blue-100">{{ $letter->nomor_surat }}</div>
                                        <div class="mt-2">
                                            @if($letter->file_path)
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="group inline-flex items-center text-xs text-gray-500 hover:text-blue-600 transition-colors">
                                                    <span class="p-1 rounded-full bg-gray-100 group-hover:bg-blue-100 mr-2 transition-colors">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                                    </span>
                                                    Lihat PDF
                                                </a>
                                            @else
                                                <span class="text-gray-400 italic text-xs flex items-center">
                                                    Tidak ada file
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 font-bold">{{ $letter->pengirim }}</div>
                                        <div class="text-xs text-gray-500 mt-1 space-y-1">
                                            <div class="flex items-center"><span class="w-14 inline-block text-gray-400 font-medium">Surat</span> : {{ \Carbon\Carbon::parse($letter->tgl_surat)->format('d/m/Y') }}</div>
                                            <div class="flex items-center"><span class="w-14 inline-block text-gray-400 font-medium">Terima</span> : {{ \Carbon\Carbon::parse($letter->tgl_terima)->format('d/m/Y') }}</div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <div class="text-sm text-gray-700 line-clamp-2 leading-relaxed" title="{{ $letter->perihal }}">{{ $letter->perihal }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($letter->status_disposisi == 'Sudah')
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                                Sudah
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-amber-100 text-amber-800 border border-amber-200">
                                                Belum
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex flex-col space-y-2 items-center">
                                            {{-- Tombol Buat SPT --}}
                                            <a href="{{ route('letters.spt.create', ['from_letter' => $letter->id]) }}" class="w-full text-center text-white bg-green-500 hover:bg-green-600 px-3 py-1.5 rounded-lg text-xs transition duration-150 shadow-sm hover:shadow">
                                                + Buat SPT
                                            </a>
                                            
                                            <div class="flex space-x-2 w-full justify-center">
                                                {{-- Tombol Edit --}}
                                                <a href="{{ route('letters.incoming.edit', $letter->id) }}" class="flex-1 text-center text-indigo-600 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 px-2 py-1.5 rounded-lg text-xs transition">
                                                    Edit
                                                </a>

                                                {{-- Tombol Hapus dengan SweetAlert --}}
                                                <button type="button" onclick="confirmDelete('{{ $letter->id }}')" class="flex-1 text-center text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 border border-red-200 px-2 py-1.5 rounded-lg text-xs transition">
                                                    Hapus
                                                </button>
                                                
                                                {{-- Form Hapus Tersembunyi --}}
                                                <form id="delete-form-{{ $letter->id }}" action="{{ route('letters.incoming.destroy', $letter->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('DELETE')
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-gray-500 bg-white">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="bg-gray-100 rounded-full p-4 mb-4">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            </div>
                                            <p class="text-base font-medium text-gray-600">Belum ada surat masuk.</p>
                                            @if(request('search'))
                                                <p class="text-sm text-gray-400 mt-1">Tidak ditemukan surat dengan kata kunci "{{ request('search') }}"</p>
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
                        {{ $letters->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SWEETALERT 2 CDN & SCRIPTS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        // 1. Notifikasi Sukses (Toast Modern)
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

        // 2. Konfirmasi Hapus Modern
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data surat ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                background: '#fff',
                borderRadius: '1rem',
                customClass: {
                    confirmButton: 'px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700',
                    cancelButton: 'px-4 py-2 rounded-lg bg-gray-300 text-gray-700 hover:bg-gray-400 ml-2'
                },
                buttonsStyling: false // Gunakan styling Tailwind di atas
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form penghapusan
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
</x-app-layout>