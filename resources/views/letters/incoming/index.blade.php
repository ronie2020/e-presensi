<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="ADMINISTRASI PERSURATAN"
                    badgeIcon="ph-fill ph-tray-arrow-down"
                    showcaseIcon="ph-duotone ph-envelope-open"
                    showcaseTitle="Persuratan Masuk"
                    showcaseSubtitle="Arsip & Disposisi SPT">
                    <x-slot:title>
                        <span class="block text-slate-100">Arsip & Tata Usaha</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Surat Masuk Sekolah
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Kelola dokumen persuratan masuk secara tertib digital. Integrasikan surat masuk dengan pembuatan Surat Perintah Tugas (SPT) secara otomatis.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-envelope text-sky-400"></i> Registrasi Surat
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-file-arrow-down text-emerald-400"></i> Lampiran PDF
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-file-text text-cyan-400"></i> Disposisi SPT Otomatis
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <a href="{{ route('letters.incoming.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-[#56bbf1] to-blue-600 hover:brightness-110 text-white font-bold text-sm shadow-lg shadow-[#56bbf1]/20 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                            <i class="ph-bold ph-plus-circle text-lg"></i>
                            <span>Catat Surat Masuk</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <i class="ph-fill ph-tray text-[#56bbf1] text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">Total Masuk:</span>
                            <span class="text-sm font-black text-white font-mono">{{ $letters->total() }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- Toolbar Pencarian & Tabel --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden">
                
                {{-- Toolbar (Search & Filter) --}}
                <div class="p-6 border-b border-white/10 bg-white/5 flex flex-col xl:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-white text-lg flex items-center gap-2 whitespace-nowrap">
                        <i class="ph-fill ph-list-dashes text-[#56bbf1]"></i> Data Surat Masuk
                    </h3>
                    
                    <form action="{{ route('letters.incoming.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full xl:w-auto">
                        <div class="relative w-full sm:w-64">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, perihal, pengirim..." class="w-full pl-10 pr-4 py-2.5 rounded-xl border-white/15 bg-[#021124] text-sm font-medium text-white focus:border-[#56bbf1] focus:ring-[#56bbf1] transition-all placeholder:text-slate-500">
                            <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                        </div>
                        <div class="relative w-full sm:w-40">
                            <select name="sifat_surat" onchange="this.form.submit()" class="w-full pl-4 pr-10 py-2.5 rounded-xl border-white/15 bg-[#021124] text-sm font-medium text-white appearance-none transition-all cursor-pointer focus:border-[#56bbf1]">
                                <option value="" class="bg-[#021124] text-white">Semua Sifat</option>
                                <option value="Biasa" {{ request('sifat_surat') == 'Biasa' ? 'selected' : '' }} class="bg-[#021124] text-white">Biasa</option>
                                <option value="Penting" {{ request('sifat_surat') == 'Penting' ? 'selected' : '' }} class="bg-[#021124] text-white">Penting</option>
                                <option value="Segera" {{ request('sifat_surat') == 'Segera' ? 'selected' : '' }} class="bg-[#021124] text-white">Segera</option>
                                <option value="Rahasia" {{ request('sifat_surat') == 'Rahasia' ? 'selected' : '' }} class="bg-[#021124] text-white">Rahasia</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                        </div>
                        @if(request('search') || request('sifat_surat'))
                            <a href="{{ route('letters.incoming.index') }}" class="px-4 py-2.5 bg-white/10 text-slate-400 hover:bg-rose-500/20 hover:text-rose-400 border border-white/15 hover:border-rose-500/30 rounded-xl text-sm font-bold transition-colors flex items-center justify-center gap-2" title="Reset Filter">
                                <i class="ph-bold ph-x"></i>
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Table Content --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white/5 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/10">
                            <tr>
                                <th class="px-6 py-5 w-48">Agenda & Diterima</th>
                                <th class="px-6 py-5">Identitas Surat</th>
                                <th class="px-6 py-5 w-1/3">Asal & Perihal</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($letters as $letter)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-black text-[#56bbf1] bg-[#56bbf1]/10 px-3 py-1.5 rounded-lg border border-[#56bbf1]/20 inline-block text-sm mb-2 shadow-sm">
                                        #{{ $letter->nomor_agenda }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-medium flex items-center gap-1.5 mt-1" title="Tanggal Diterima">
                                        <i class="ph-bold ph-calendar-check text-[#56bbf1]"></i> {{ \Carbon\Carbon::parse($letter->tgl_diterima)->translatedFormat('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-white text-sm mb-2 leading-snug">{{ $letter->nomor_surat }}</div>
                                    <span class="inline-flex px-2 py-1 rounded border border-white/15 bg-white/10 text-[10px] font-bold text-slate-300 tracking-wider">
                                        {{ $letter->sifat_surat }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-start gap-2 mb-2">
                                        <i class="ph-fill ph-buildings text-[#56bbf1] mt-0.5"></i>
                                        <span class="font-bold text-white text-sm">{{ $letter->asal_surat }}</span>
                                    </div>
                                    <p class="text-sm text-slate-300 leading-relaxed line-clamp-2 font-medium">
                                        {{ $letter->perihal }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        @if($letter->file_path)
                                            <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-[#56bbf1]/10 border border-[#56bbf1]/20 text-[#56bbf1] hover:bg-[#56bbf1] hover:text-[#020b18] rounded-xl text-xs font-bold transition-all shadow-sm">
                                                <i class="ph-bold ph-download-simple text-base"></i> Lampiran
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white/5 text-slate-500 rounded-lg text-xs font-medium border border-white/10">
                                                <i class="ph-bold ph-file-dashed"></i> No File
                                            </span>
                                        @endif

                                        {{-- Tombol Aksi (Detail, Edit, Hapus) --}}
                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button" onclick="showDetailModal({{ json_encode($letter) }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/10 border border-white/15 text-slate-300 hover:text-[#56bbf1] hover:border-[#56bbf1]/50 hover:bg-[#56bbf1]/20 transition-all" title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </button>
                                            <a href="{{ route('letters.incoming.edit', $letter->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/10 border border-white/15 text-slate-300 hover:text-amber-400 hover:border-amber-500/50 hover:bg-amber-500/20 transition-all" title="Edit">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            <button type="button" onclick="confirmDelete('{{ $letter->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/10 border border-white/15 text-slate-300 hover:text-rose-400 hover:border-rose-500/50 hover:bg-rose-500/20 transition-all" title="Hapus">
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
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-white/5 border border-white/10 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500">
                                        <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                                    </div>
                                    <h3 class="text-white font-bold text-lg">Data Tidak Ditemukan</h3>
                                    <p class="text-slate-400 text-sm mt-1 mb-6">Belum ada surat masuk atau hasil pencarian Anda tidak cocok.</p>
                                    <a href="{{ route('letters.incoming.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-[#56bbf1] text-[#020b18] hover:bg-[#56bbf1]/80 rounded-xl font-bold text-sm transition-all shadow-lg shadow-[#56bbf1]/20">
                                        <i class="ph-bold ph-plus"></i> Catat Surat Baru
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="p-6 border-t border-white/10 bg-white/5">
                    {{ $letters->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL SURAT MASUK --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-md transition-opacity opacity-0" id="modalBackdrop" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto custom-scrollbar">
            <div class="flex min-h-full items-start justify-center p-4 py-16 sm:p-6 sm:py-24 text-center">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2.5rem] bg-[#021124] text-left shadow-2xl transition-all w-full max-w-2xl border border-white/15 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-[#031d3d] to-[#021124] p-6 text-white relative overflow-hidden border-b border-white/10">
                        <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                            <i class="ph-fill ph-tray-arrow-down"></i>
                        </div>
                        <div class="flex justify-between items-center relative z-10">
                            <div>
                                <h3 class="text-xl font-black flex items-center gap-2 text-white">
                                    <i class="ph-duotone ph-info text-[#56bbf1]"></i> Detail Surat Masuk
                                </h3>
                                <p class="text-[#56bbf1] text-sm font-medium mt-1">
                                    Agenda: <span id="modal_agenda" class="font-mono bg-white/10 px-2 rounded font-bold"></span>
                                </p>
                            </div>
                            <button onclick="closeDetailModal()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-x text-lg text-white"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Surat</span>
                                <span id="modal_nomor" class="font-bold text-white text-sm"></span>
                            </div>
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Asal / Pengirim</span>
                                <span id="modal_asal" class="font-bold text-white text-sm"></span>
                            </div>
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tgl Surat Dibuat</span>
                                <span id="modal_tanggal_surat" class="font-bold text-white text-sm"></span>
                            </div>
                            <div class="bg-white/5 p-4 rounded-2xl border border-white/10">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tgl Diterima</span>
                                <span id="modal_tanggal_diterima" class="font-bold text-[#56bbf1] text-sm"></span>
                            </div>
                        </div>
                        
                        <div class="bg-white/5 p-5 rounded-2xl border border-white/10 mb-6 relative">
                            <div class="absolute top-4 right-4">
                                <span id="modal_sifat" class="inline-flex px-2.5 py-1 rounded-md border border-white/15 bg-white/10 text-[10px] font-bold text-slate-300"></span>
                            </div>
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Perihal / Isi Surat</span>
                            <p id="modal_perihal" class="text-sm font-medium text-slate-300 leading-relaxed pr-16"></p>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                            <div id="modal_lampiran_container">
                                <!-- Tombol Lampiran diinject via JS -->
                            </div>
                            <button onclick="closeDetailModal()" class="px-6 py-3 bg-white/10 text-slate-300 hover:bg-white/20 rounded-xl font-bold text-sm transition-colors border border-white/15">
                                Tutup Panel
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Alert Konfirmasi dan Modal --}}
    <script>
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                timerProgressBar: true, background: '#021124', color: '#ffffff',
                customClass: { popup: 'rounded-[1.5rem] font-sans border border-white/15' }
            });
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Surat Masuk?', 
                text: "Data surat beserta lampirannya akan dihapus secara permanen.",
                icon: 'warning', 
                showCancelButton: true,
                confirmButtonColor: '#e11d48', 
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!', 
                cancelButtonText: 'Batal',
                background: '#021124',
                color: '#ffffff',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/15 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-white/10 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-white/20 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + id).submit();
                }
            });
        }

        // Fungsi JavaScript untuk Modal Detail
        function showDetailModal(letter) {
            const modal = document.getElementById('detailModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            // Format Tanggal
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const dateSurat = new Date(letter.tgl_surat).toLocaleDateString('id-ID', options);
            const dateDiterima = new Date(letter.tgl_diterima).toLocaleDateString('id-ID', options);

            // Set Data
            document.getElementById('modal_agenda').innerText = '#' + letter.nomor_agenda;
            document.getElementById('modal_nomor').innerText = letter.nomor_surat;
            document.getElementById('modal_asal').innerText = letter.asal_surat;
            document.getElementById('modal_tanggal_surat').innerText = dateSurat;
            document.getElementById('modal_tanggal_diterima').innerText = dateDiterima;
            document.getElementById('modal_sifat').innerText = letter.sifat_surat;
            document.getElementById('modal_perihal').innerText = letter.perihal;

            // Set Link Lampiran Jika Ada
            const lampiranContainer = document.getElementById('modal_lampiran_container');
            if (letter.file_path) {
                const fileUrl = '{{ asset("storage/") }}/' + letter.file_path;
                lampiranContainer.innerHTML = `
                    <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-2 px-5 py-3 bg-[#56bbf1]/10 border border-[#56bbf1]/20 text-[#56bbf1] rounded-xl font-bold text-sm hover:bg-[#56bbf1] hover:text-[#020b18] transition-all shadow-sm">
                        <i class="ph-bold ph-download-simple"></i> Unduh Lampiran
                    </a>
                `;
            } else {
                lampiranContainer.innerHTML = `
                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-white/5 text-slate-400 rounded-xl text-xs font-medium border border-white/10">
                        <i class="ph-bold ph-file-dashed text-sm"></i> Tidak Ada File
                    </span>
                `;
            }

            // Tampilkan Modal dengan Animasi
            modal.classList.remove('hidden');
            setTimeout(() => {
                backdrop.classList.remove('opacity-0');
                panel.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            }, 10);
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            // Sembunyikan dengan Animasi
            backdrop.classList.add('opacity-0');
            panel.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</x-app-layout>