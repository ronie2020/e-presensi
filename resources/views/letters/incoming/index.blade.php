<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                {{-- Background Pattern --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl">📩</span> Arsip Surat Masuk
                        </h1>
                        <p class="text-blue-200 text-sm font-medium leading-relaxed max-w-lg">
                            Kelola surat dinas yang masuk, disposisi kepada staf, dan arsipkan dokumen digital dalam satu panel terpusat.
                        </p>
                        
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3">
                            <a href="{{ route('letters.incoming.create') }}" class="px-6 py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:bg-blue-50 hover:scale-105 transition-all flex items-center gap-2 transform active:scale-95">
                                <i class="ph-bold ph-plus-circle text-lg"></i>
                                <span>Input Surat</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Statistik Ringkas --}}
                    <div class="flex gap-3">
                        <div class="bg-blue-950/40 backdrop-blur-md px-5 py-4 rounded-2xl border border-blue-400/20 text-center min-w-[120px] shadow-lg">
                            <span class="block text-3xl font-black text-white">{{ $letters->total() }}</span>
                            <span class="text-[10px] uppercase font-bold text-blue-300 tracking-wider">Total Surat</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Toolbar: Pencarian --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                        <i class="ph-fill ph-tray text-blue-900"></i> Daftar Surat
                    </h3>

                    <form action="{{ route('letters.incoming.index') }}" method="GET" class="relative w-full sm:w-80 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                        <input type="text" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari Nomor / Perihal..." 
                               class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-600 transition-all">
                    </form>
                </div>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-blue-900 text-white text-xs font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-5">No. Surat / File</th>
                                <th class="px-6 py-5">Pengirim / Tanggal</th>
                                <th class="px-6 py-5 w-1/3">Perihal</th>
                                <th class="px-6 py-5 text-center">Status</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($letters as $letter)
                            <tr class="hover:bg-blue-50/40 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-bold text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 inline-block text-xs mb-2">
                                        {{ $letter->nomor_surat }}
                                    </div>
                                    <div>
                                        @if($letter->file_path)
                                            <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-red-600 hover:underline transition-colors">
                                                <i class="ph-fill ph-file-pdf text-red-500 text-lg"></i> Lihat File
                                            </a>
                                        @else
                                            <span class="text-xs text-slate-400 italic flex items-center gap-1">
                                                <i class="ph-bold ph-prohibit"></i> Tidak ada
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-slate-800 text-sm mb-1">{{ $letter->pengirim }}</div>
                                    <div class="text-[11px] text-slate-500 space-y-1 font-medium">
                                        <div class="flex items-center gap-1">
                                            <span class="text-slate-400">Tgl Surat:</span> {{ \Carbon\Carbon::parse($letter->tgl_surat)->format('d/m/Y') }}
                                        </div>
                                        <div class="flex items-center gap-1 text-blue-600">
                                            <span>Diterima:</span> {{ \Carbon\Carbon::parse($letter->tgl_terima)->format('d/m/Y') }}
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-3 font-medium">
                                        {{ $letter->perihal }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-center">
                                    @if($letter->status_disposisi == 'Sudah')
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-100 text-[10px] font-black uppercase tracking-wide">
                                            <i class="ph-bold ph-check-circle"></i> Disposisi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-amber-50 text-amber-600 border border-amber-100 text-[10px] font-black uppercase tracking-wide">
                                            <i class="ph-bold ph-clock"></i> Pending
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        {{-- Tombol SPT --}}
                                        <a href="{{ route('letters.spt.create', ['from_letter' => $letter->id]) }}" class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 border border-emerald-200 rounded-lg text-xs font-bold transition-all shadow-sm">
                                            <i class="ph-bold ph-paper-plane-tilt"></i> Buat SPT
                                        </a>
                                        
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('letters.incoming.edit', $letter->id) }}" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 hover:shadow-sm transition-all" title="Edit">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            
                                            <button type="button" onclick="confirmDelete('{{ $letter->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:shadow-sm transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-{{ $letter->id }}" action="{{ route('letters.incoming.destroy', $letter->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="ph-duotone ph-tray text-4xl"></i>
                                    </div>
                                    <h3 class="text-slate-700 font-bold text-lg">Belum ada surat masuk</h3>
                                    <p class="text-slate-400 text-sm mt-1">Silakan input surat baru melalui tombol di atas.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-6 border-t border-slate-50 bg-slate-50/50">
                    {{ $letters->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        // Notifikasi Sukses
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                timerProgressBar: true, customClass: { popup: 'rounded-xl' }
            });
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Surat?', text: "Data surat dan file lampiran akan dihapus permanen.",
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                borderRadius: '1.5rem',
                customClass: {
                    popup: 'rounded-[2rem]', confirmButton: 'rounded-xl px-6 py-2.5 font-bold', cancelButton: 'rounded-xl px-6 py-2.5 font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            })
        }
    </script>
</x-app-layout>