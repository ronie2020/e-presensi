<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="ADMINISTRASI SURAT DINAS"
                    badgeIcon="ph-fill ph-paper-plane-tilt"
                    showcaseIcon="ph-duotone ph-briefcase"
                    showcaseTitle="SPT Digital"
                    showcaseSubtitle="Surat Perintah Tugas">
                    <x-slot:title>
                        <span class="block text-slate-100">Surat Perintah</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Tugas Dinas (SPT)
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Kelola SPT, cetak dokumen penugasan dinas resmi untuk pegawai/guru, dan pantau arsip perjalanan dinas luar.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-briefcase text-sky-400"></i> Penugasan Resmi
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-printer text-emerald-400"></i> Cetak Dokumen
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-users-three text-cyan-400"></i> Pegawai & Pengikut
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <a href="{{ route('letters.spt.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-[#56bbf1] to-blue-600 hover:brightness-110 text-white font-bold text-sm shadow-lg shadow-[#56bbf1]/20 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                            <i class="ph-bold ph-plus-circle text-lg"></i>
                            <span>Buat SPT Baru</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <i class="ph-fill ph-briefcase text-[#56bbf1] text-sm"></i>
                            <span class="text-xs font-bold text-slate-300">Total SPT:</span>
                            <span class="text-sm font-black text-white font-mono">{{ $spts->total() }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- Toolbar & Table --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden">
                
                {{-- Toolbar (Search) --}}
                <div class="p-6 border-b border-white/10 bg-white/5 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-white text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-[#56bbf1]"></i> Data Surat Perintah Tugas
                    </h3>

                    <form action="{{ route('letters.spt.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-80 group">
                            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-[#56bbf1] transition-colors"></i>
                            <input type="text" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari No SPT / Pegawai / Perihal..." 
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all placeholder:text-slate-500">
                        </div>
                        @if(request('search'))
                            <a href="{{ route('letters.spt.index') }}" class="w-12 h-12 flex items-center justify-center shrink-0 bg-slate-800 text-slate-400 hover:bg-rose-500/20 hover:text-rose-400 rounded-2xl text-lg font-bold transition-colors border border-white/10" title="Reset Pencarian">
                                <i class="ph-bold ph-x"></i>
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/10">
                            <tr>
                                <th class="px-6 py-5 w-[28%]">Identitas SPT & Pegawai</th>
                                <th class="px-6 py-5">Tempat & Waktu</th>
                                <th class="px-6 py-5 w-[30%]">Perihal Penugasan</th>
                                <th class="px-6 py-5 text-right w-[15%]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($spts as $spt)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-bold text-[#56bbf1] bg-[#56bbf1]/10 px-3 py-1.5 rounded-lg border border-[#56bbf1]/20 inline-block text-xs mb-3">
                                        {{ $spt->nomor_spt }}
                                    </div>
                                    <div class="flex items-center gap-3 mb-2">
                                        {{-- Inisial Nama Pegawai Pertama --}}
                                        <div class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 border border-white/10 flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-[#56bbf1] group-hover:text-slate-950 transition-colors shadow-sm">
                                            {{ $spt->users->count() > 0 ? substr($spt->users->first()->name, 0, 1) : '?' }}
                                        </div>
                                        <div>
                                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter leading-none mb-1">Pegawai Ditugaskan</div>
                                            <div class="font-bold text-white text-sm group-hover:text-[#56bbf1] transition-colors leading-tight">
                                                @if($spt->users->count() > 0)
                                                    {{ $spt->users->first()->name }}
                                                    @if($spt->users->count() > 1)
                                                        <span class="text-[10px] text-slate-400 block font-medium mt-0.5">
                                                            <i class="ph-bold ph-plus"></i> {{ $spt->users->count() - 1 }} Pegawai lainnya
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="text-rose-400 italic text-xs">Belum dipilih</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Info Pengikut (Jika ada) --}}
                                    @if(is_array($spt->pengikut) && count($spt->pengikut) > 0)
                                        <div class="text-[10px] font-bold text-slate-400 flex items-center gap-1 mt-2 pl-11">
                                            <i class="ph-fill ph-users text-[#56bbf1]"></i> +{{ count($spt->pengikut) }} Pengikut Luar
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-white text-sm mb-2 flex items-center gap-1.5">
                                        <i class="ph-fill ph-map-pin text-rose-400"></i> {{ $spt->tempat_tujuan }}
                                    </div>
                                    <div class="text-xs text-slate-300 flex flex-col gap-1 pl-5 font-medium">
                                        <span class="flex items-center gap-1.5"><i class="ph-bold ph-calendar text-[10px] text-slate-400"></i> {{ \Carbon\Carbon::parse($spt->tgl_berangkat)->isoFormat('D MMM YYYY') }}</span>
                                        @if($spt->lama_hari > 1)
                                            <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest pl-4">s.d</span>
                                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-check text-[10px] text-slate-400"></i> {{ \Carbon\Carbon::parse($spt->tgl_kembali)->isoFormat('D MMM YYYY') }}</span>
                                        @endif
                                        <span class="inline-block mt-2 px-2.5 py-1 bg-slate-900/60 border border-white/10 rounded-lg text-[10px] text-[#56bbf1] font-bold w-fit shadow-sm">
                                            {{ $spt->lama_hari }} Hari Tugas
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">Maksud Penugasan:</div>
                                    <p class="text-sm text-slate-300 leading-relaxed line-clamp-3 font-medium">
                                        {{ $spt->untuk }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('letters.spt.print', $spt->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-[#56bbf1]/10 border border-[#56bbf1]/20 text-[#56bbf1] hover:bg-[#56bbf1] hover:text-slate-950 rounded-xl text-xs font-bold transition-all shadow-sm group/btn">
                                            <i class="ph-bold ph-printer text-base group-hover/btn:scale-110 transition-transform"></i> Cetak SPT
                                        </a>
                                        
                                        {{-- Tombol Aksi (Detail, Edit, Hapus) --}}
                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button" onclick="showDetailModal({{ json_encode($spt) }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/80 border border-white/10 text-slate-400 hover:text-sky-400 hover:border-sky-500/30 hover:bg-sky-500/10 transition-all" title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </button>
                                            <a href="{{ route('letters.spt.edit', $spt->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/80 border border-white/10 text-slate-400 hover:text-amber-400 hover:border-amber-500/30 hover:bg-amber-500/10 transition-all" title="Edit">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            <button type="button" onclick="confirmDelete('{{ $spt->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/80 border border-white/10 text-slate-400 hover:text-rose-400 hover:border-rose-500/30 hover:bg-rose-500/10 transition-all" title="Hapus">
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
                                    <div class="w-20 h-20 bg-slate-900/60 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500 border border-white/10">
                                        <i class="ph-duotone ph-paper-plane-tilt text-4xl"></i>
                                    </div>
                                    <h3 class="text-white font-bold text-lg">Data Tidak Ditemukan</h3>
                                    <p class="text-slate-400 text-sm mt-1 mb-6">Belum ada SPT atau hasil pencarian Anda tidak cocok.</p>
                                    <a href="{{ route('letters.spt.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white rounded-xl font-bold text-sm hover:brightness-110 transition-all shadow-lg shadow-[#56bbf1]/20">
                                        <i class="ph-bold ph-plus"></i> Buat SPT Baru
                                    </a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-white/10 bg-white/5">
                    {{ $spts->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL SPT --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity opacity-0" id="modalBackdrop" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto custom-scrollbar">
            <div class="flex min-h-full items-start justify-center p-4 py-16 sm:p-6 sm:py-24 text-center">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2.5rem] bg-[#021124] text-left shadow-2xl transition-all w-full max-w-2xl border border-white/10 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-[#031d3d] via-[#0b2545] to-[#134074] p-6 text-white relative overflow-hidden border-b border-white/10">
                        <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                            <i class="ph-fill ph-briefcase"></i>
                        </div>
                        <div class="flex justify-between items-center relative z-10">
                            <div>
                                <h3 class="text-xl font-black flex items-center gap-2">
                                    <i class="ph-duotone ph-info text-[#56bbf1]"></i> Detail Surat Perintah Tugas
                                </h3>
                                <p class="text-slate-300 text-sm font-medium mt-1 flex items-center gap-2">
                                    Nomor: <span id="modal_nomor" class="font-mono bg-[#56bbf1]/10 text-[#56bbf1] px-2 rounded font-bold"></span>
                                </p>
                            </div>
                            <button onclick="closeDetailModal()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-6 sm:p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/5">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tempat Tujuan</span>
                                <span id="modal_tempat" class="font-bold text-white text-sm flex items-center gap-1.5"><i class="ph-fill ph-map-pin text-rose-400"></i> <span id="modal_tempat_text"></span></span>
                            </div>
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/5">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Waktu Penugasan (<span id="modal_lama" class="text-[#56bbf1]"></span>)</span>
                                <span id="modal_waktu" class="font-bold text-white text-sm"></span>
                            </div>
                        </div>

                        {{-- Section Pegawai Ditugaskan --}}
                        <div class="bg-slate-900/60 p-5 rounded-2xl border border-white/5 mb-6">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3"><i class="ph-bold ph-users text-[#56bbf1]"></i> Pegawai Yang Ditugaskan</span>
                            <div id="modal_pegawai" class="flex flex-wrap gap-2">
                                <!-- Data pegawai masuk via JS -->
                            </div>
                        </div>
                        
                        {{-- Section Perihal/Maksud --}}
                        <div class="bg-slate-900/60 p-5 rounded-2xl border border-white/5 mb-6">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Maksud Penugasan / Untuk</span>
                            <p id="modal_untuk" class="text-sm font-medium text-slate-300 leading-relaxed"></p>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="pt-6 border-t border-white/10 flex items-center justify-between">
                            <a href="#" id="modal_btn_cetak" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white rounded-xl font-bold text-sm hover:brightness-110 transition-all shadow-lg shadow-[#56bbf1]/20">
                                <i class="ph-bold ph-printer"></i> Cetak Dokumen SPT
                            </a>
                            <button onclick="closeDetailModal()" class="px-6 py-3 bg-slate-800 text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-700 transition-colors border border-white/10">
                                Tutup Panel
                            </button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        // Notifikasi Sukses
        @if(session('success'))
            const Toast = Swal.mixin({
                toast: true, position: 'top-end', showConfirmButton: false, timer: 3000,
                timerProgressBar: true,
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-[1.5rem] border border-white/10' }
            });
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus SPT?', text: "Data penugasan ini akan dihapus permanen.",
                icon: 'warning', showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#021124', color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2 border border-white/10'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) document.getElementById('delete-form-' + id).submit();
            })
        }

        // Fungsi JavaScript untuk Modal Detail
        function showDetailModal(spt) {
            const modal = document.getElementById('detailModal');
            const backdrop = document.getElementById('modalBackdrop');
            const panel = document.getElementById('modalPanel');

            // Set Data Teks
            document.getElementById('modal_nomor').innerText = spt.nomor_spt;
            document.getElementById('modal_tempat_text').innerText = spt.tempat_tujuan;
            document.getElementById('modal_lama').innerText = spt.lama_hari + ' Hari';
            document.getElementById('modal_untuk').innerText = spt.untuk;

            // Format Tanggal Waktu Penugasan
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const tglBerangkat = new Date(spt.tgl_berangkat).toLocaleDateString('id-ID', options);
            
            let waktuText = tglBerangkat;
            if(spt.tgl_berangkat !== spt.tgl_kembali && spt.tgl_kembali) {
                const tglKembali = new Date(spt.tgl_kembali).toLocaleDateString('id-ID', options);
                waktuText += ' <span class="text-[10px] text-slate-400 mx-1">s/d</span> ' + tglKembali;
            }
            document.getElementById('modal_waktu').innerHTML = waktuText;

            // Mapping Pegawai (Relasi Users)
            let pegawaiHtml = '';
            if (spt.users && spt.users.length > 0) {
                spt.users.forEach(user => {
                    pegawaiHtml += `
                        <span class="inline-flex items-center gap-1.5 bg-slate-900 border border-white/10 shadow-sm rounded-lg px-3 py-1.5 text-xs font-bold text-slate-200">
                            <i class="ph-fill ph-user text-[#56bbf1]"></i> ${user.name}
                        </span>
                    `;
                });
            } else {
                pegawaiHtml = '<span class="text-rose-400 italic text-xs font-medium">Pegawai belum ditugaskan.</span>';
            }
            
            // Cek jika ada pengikut luar
            if (spt.pengikut && Array.isArray(spt.pengikut) && spt.pengikut.length > 0) {
                pegawaiHtml += `
                    <span class="inline-flex items-center gap-1.5 bg-slate-800 border border-white/10 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-400">
                        <i class="ph-fill ph-users text-slate-400"></i> +${spt.pengikut.length} Pengikut
                    </span>
                `;
            }
            document.getElementById('modal_pegawai').innerHTML = pegawaiHtml;

            // Update Link Tombol Cetak
            const printUrl = `{{ url('letters/spt/print') }}/${spt.id}`;
            document.getElementById('modal_btn_cetak').href = printUrl;

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
            }, 300); // Tunggu durasi transisi Tailwind
        }
    </script>
</x-app-layout>