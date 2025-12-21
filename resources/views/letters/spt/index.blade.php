<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl">✈️</span> Surat Perintah Tugas
                        </h1>
                        <p class="text-blue-200 text-sm font-medium leading-relaxed max-w-lg">
                            Kelola penugasan dinas pegawai, cetak SPT, dan pantau riwayat perjalanan dinas sekolah.
                        </p>
                        
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3">
                            <a href="{{ route('letters.spt.create') }}" class="px-6 py-3 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:bg-blue-50 hover:scale-105 transition-all flex items-center gap-2 transform active:scale-95">
                                <i class="ph-bold ph-plus-circle text-lg"></i>
                                <span>Buat SPT Baru</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Statistik Ringkas --}}
                    <div class="flex gap-3">
                        <div class="bg-blue-950/40 backdrop-blur-md px-5 py-4 rounded-2xl border border-blue-400/20 text-center min-w-[120px] shadow-lg">
                            <span class="block text-3xl font-black text-white">{{ $spts->total() }}</span>
                            <span class="text-[10px] uppercase font-bold text-blue-300 tracking-wider">Total Data</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Toolbar & Table --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                {{-- Toolbar --}}
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-slate-800 text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-blue-900"></i> Riwayat Penugasan
                    </h3>

                    <form action="{{ route('letters.spt.index') }}" method="GET" class="relative w-full sm:w-80 group">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-blue-600 transition-colors"></i>
                        <input type="text" name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Cari Nomor / Pegawai / Tujuan..." 
                               class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-600 transition-all">
                    </form>
                </div>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-blue-900 text-white text-xs font-bold uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-5">Info SPT & Tujuan</th>
                                <th class="px-6 py-5">Pegawai Ditugaskan</th>
                                <th class="px-6 py-5">Dasar Surat</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($spts as $spt)
                            <tr class="hover:bg-blue-50/40 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-bold text-blue-700 bg-blue-50 px-3 py-1.5 rounded-lg border border-blue-100 inline-block text-xs mb-2">
                                        {{ $spt->nomor_spt }}
                                    </div>
                                    <div class="font-bold text-slate-800 text-sm mb-1">{{ $spt->tempat_tujuan }}</div>
                                    
                                    <div class="flex items-center gap-2 text-xs text-slate-500 mt-1">
                                        <i class="ph-bold ph-calendar-blank"></i>
                                        <span>{{ $spt->tgl_berangkat->format('d/m/Y') }} s.d {{ $spt->tgl_kembali->format('d/m/Y') }}</span>
                                        <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-bold text-[10px]">
                                            {{ $spt->lama_hari }} Hari
                                        </span>
                                    </div>
                                    
                                    <div class="text-[11px] text-slate-400 mt-2 italic line-clamp-1 border-t border-slate-100 pt-1">
                                        "{{ $spt->untuk }}"
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($spt->users as $user)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[10px] font-bold bg-white border border-slate-200 text-slate-600 shadow-sm">
                                                <i class="ph-fill ph-user text-blue-400"></i> {{ $user->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    @if($spt->letterIncoming)
                                        <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100 max-w-xs">
                                            <div class="text-[10px] font-bold text-blue-600 mb-0.5">Ref: Surat Masuk</div>
                                            <div class="text-xs font-bold text-slate-700 truncate">{{ $spt->letterIncoming->nomor_surat }}</div>
                                            <div class="text-[10px] text-slate-500 line-clamp-1 italic mt-0.5">{{ $spt->letterIncoming->perihal }}</div>
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic flex items-center gap-1">
                                            <i class="ph-bold ph-minus-circle"></i> Tanpa Dasar
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('letters.spt.print', $spt->id) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-600 text-white hover:bg-blue-700 rounded-lg text-xs font-bold transition-all shadow-sm shadow-blue-500/30">
                                            <i class="ph-bold ph-printer"></i> Cetak
                                        </a>
                                        
                                        <div class="flex items-center gap-2">
                                            {{-- Tombol Edit (Jika ada route edit) --}}
                                            {{-- <a href="#" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-blue-600 hover:border-blue-200 transition-all">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a> --}}
                                            
                                            <button type="button" onclick="confirmDelete('{{ $spt->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:shadow-sm transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-{{ $spt->id }}" action="{{ route('letters.spt.destroy', $spt->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="ph-duotone ph-airplane-tilt text-4xl"></i>
                                    </div>
                                    <h3 class="text-slate-700 font-bold text-lg">Belum ada SPT</h3>
                                    <p class="text-slate-400 text-sm mt-1">Silakan buat surat perintah tugas baru.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-50 bg-slate-50/50">
                    {{ $spts->withQueryString()->links() }}
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
                title: 'Hapus SPT?', text: "Data penugasan ini akan dihapus permanen.",
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