<x-app-layout>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="ADMINISTRASI SEKOLAH"
                    badgeIcon="ph-fill ph-car-profile"
                    showcaseIcon="ph-duotone ph-briefcase"
                    showcaseTitle="SPPD Digital"
                    showcaseSubtitle="Perjalanan Dinas GTK">
                    <x-slot:title>
                        <span class="block text-slate-100">Surat Perintah</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Perjalanan Dinas (SPPD)
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Kelola pengajuan SPPD, tracking status verifikasi, cetak dokumen resmi, dan pantau rekapitulasi biaya perjalanan dinas.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-airplane-takeoff text-sky-400"></i> Tugas Dinas Luar
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-receipt text-emerald-400"></i> Rekap Anggaran
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-printer text-cyan-400"></i> Cetak Format Standar
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <a href="{{ route('sppd.create') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-sky-500/25 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                            <i class="ph-bold ph-plus-circle text-lg"></i>
                            <span>Input SPPD Baru</span>
                        </a>
                        <a href="{{ route('sppd.dashboard') }}"
                           class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-slate-800/80 hover:bg-slate-700 text-slate-200 hover:text-white font-bold text-sm border border-slate-700 transition-all duration-300">
                            <i class="ph-bold ph-chart-bar text-lg text-[#56bbf1]"></i>
                            <span>Dashboard Rekap</span>
                        </a>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <span class="text-xs font-bold text-slate-300">Diajukan:</span>
                            <span class="text-sm font-black text-amber-400 font-mono">{{ $stats['submitted'] ?? 0 }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <span class="text-xs font-bold text-slate-300">Disetujui:</span>
                            <span class="text-sm font-black text-sky-400 font-mono">{{ $stats['approved'] ?? 0 }}</span>
                        </div>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                            <span class="text-xs font-bold text-slate-300">Selesai:</span>
                            <span class="text-sm font-black text-emerald-400 font-mono">{{ $stats['selesai'] ?? 0 }}</span>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- Toolbar & Table Container --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl overflow-hidden">

                {{-- Toolbar --}}
                <div class="p-6 border-b border-white/10 bg-white/[0.02] flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="flex items-center gap-3">
                        <h3 class="font-black text-white text-lg flex items-center gap-2">
                            <i class="ph-fill ph-list-dashes text-[#56bbf1]"></i> Riwayat SPPD
                        </h3>
                        {{-- Filter Status --}}
                        <div class="flex gap-1 flex-wrap">
                            <a href="{{ route('sppd.index') }}" class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider transition-all {{ !request('status') ? 'bg-[#56bbf1] text-slate-950 shadow-md shadow-[#56bbf1]/20' : 'bg-slate-800/60 text-slate-400 hover:bg-slate-700/60 hover:text-white border border-white/5' }}">Semua</a>
                            @foreach(['draft' => 'Draft', 'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'selesai' => 'Selesai'] as $key => $lbl)
                            <a href="{{ route('sppd.index', ['status' => $key, 'search' => request('search')]) }}" class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider transition-all {{ request('status') === $key ? 'bg-[#56bbf1] text-slate-950 shadow-md shadow-[#56bbf1]/20' : 'bg-slate-800/60 text-slate-400 hover:bg-slate-700/60 hover:text-white border border-white/5' }}">{{ $lbl }}</a>
                            @endforeach
                        </div>
                    </div>

                    <form action="{{ route('sppd.index') }}" method="GET" class="relative w-full sm:w-80 group">
                        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No SPPD / Tujuan / Pegawai..."
                               class="w-full pl-11 pr-4 py-3 rounded-2xl border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm font-bold text-white placeholder-slate-400 transition-all">
                    </form>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/10">
                            <tr>
                                <th class="px-6 py-5">No. SPPD & Pegawai</th>
                                <th class="px-6 py-5">Tujuan & Waktu</th>
                                <th class="px-6 py-5">Status</th>
                                <th class="px-6 py-5">Total Biaya</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($sppds as $sppd)
                            @php
                                $statusColors = [
                                    'draft'     => 'bg-slate-800/80 text-slate-300 border border-slate-700',
                                    'submitted' => 'bg-amber-500/10 text-amber-400 border border-amber-500/20',
                                    'approved'  => 'bg-sky-500/10 text-[#56bbf1] border border-sky-500/20',
                                    'selesai'   => 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/20',
                                ];
                                $statusIcons = ['draft'=>'ph-pencil-simple','submitted'=>'ph-paper-plane-tilt','approved'=>'ph-check-circle','selesai'=>'ph-seal-check'];
                            @endphp
                            <tr class="hover:bg-white/[0.03] transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-bold text-[#56bbf1] bg-[#56bbf1]/10 px-3 py-1.5 rounded-lg border border-[#56bbf1]/20 inline-block text-xs mb-3">
                                        {{ $sppd->nomor_sppd }}
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-800 text-slate-300 border border-slate-700 flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-[#56bbf1] group-hover:text-slate-950 transition-colors">
                                            {{ substr($sppd->user?->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-white text-sm">{{ $sppd->user?->name ?? 'Pegawai Terhapus' }}</div>
                                            <div class="text-[10px] text-slate-400 font-mono">NIP. {{ $sppd->user?->nip ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-slate-200 text-sm mb-2 flex items-center gap-1.5">
                                        <i class="ph-fill ph-map-pin text-rose-400"></i> {{ $sppd->tempat_tujuan }}
                                    </div>
                                    <div class="text-xs text-slate-400 flex flex-col gap-1 pl-5 font-medium">
                                        <span>{{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->format('d M Y') }}</span>
                                        <span class="text-[10px] text-slate-500 uppercase font-bold tracking-widest">s/d</span>
                                        <span>{{ \Carbon\Carbon::parse($sppd->tgl_kembali)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $statusColors[$sppd->status] ?? 'bg-slate-800 text-slate-400 border border-slate-700' }}">
                                        <i class="ph-bold {{ $statusIcons[$sppd->status] ?? 'ph-question' }} text-sm"></i>
                                        {{ $sppd->status_label }}
                                    </span>

                                    {{-- Tombol Workflow --}}
                                    <div class="mt-2 flex flex-col gap-1">
                                        @if($sppd->status === 'draft')
                                            <button onclick="updateStatus('{{ $sppd->id }}', 'submitted', '{{ $sppd->nomor_sppd }}')"
                                                class="text-[10px] font-bold text-amber-400 bg-amber-500/10 border border-amber-500/20 hover:bg-amber-500/20 px-2 py-1 rounded-lg transition-colors flex items-center gap-1 w-full justify-center">
                                                <i class="ph-bold ph-paper-plane-tilt"></i> Ajukan
                                            </button>
                                        @elseif($sppd->status === 'submitted')
                                            <button onclick="updateStatus('{{ $sppd->id }}', 'approved', '{{ $sppd->nomor_sppd }}')"
                                                class="text-[10px] font-bold text-[#56bbf1] bg-sky-500/10 border border-sky-500/20 hover:bg-sky-500/20 px-2 py-1 rounded-lg transition-colors flex items-center gap-1 w-full justify-center">
                                                <i class="ph-bold ph-check-circle"></i> Setujui
                                            </button>
                                        @elseif($sppd->status === 'approved')
                                            <button onclick="updateStatus('{{ $sppd->id }}', 'selesai', '{{ $sppd->nomor_sppd }}')"
                                                class="text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 px-2 py-1 rounded-lg transition-colors flex items-center gap-1 w-full justify-center">
                                                <i class="ph-bold ph-seal-check"></i> Selesai
                                            </button>
                                        @endif
                                    </div>

                                    {{-- Form Update Status (Hidden) --}}
                                    <form id="status-form-{{ $sppd->id }}" action="{{ route('sppd.update-status', $sppd->id) }}" method="POST" class="hidden">
                                        @csrf @method('PATCH')
                                        <input type="hidden" name="status" id="status-val-{{ $sppd->id }}">
                                        <input type="hidden" name="catatan_kepala" id="catatan-val-{{ $sppd->id }}">
                                    </form>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    @if($sppd->total_biaya > 0)
                                        <div class="font-bold text-white text-sm">
                                            Rp {{ number_format($sppd->total_biaya, 0, ',', '.') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-1 space-y-0.5">
                                            @if($sppd->biaya_transport) <div>Transport: Rp {{ number_format($sppd->biaya_transport, 0, ',', '.') }}</div>@endif
                                            @if($sppd->biaya_penginapan)<div>Penginapan: Rp {{ number_format($sppd->biaya_penginapan, 0, ',', '.') }}</div>@endif
                                            @if($sppd->uang_harian)    <div>Harian: Rp {{ number_format($sppd->uang_harian, 0, ',', '.') }}</div>@endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-500 italic">Belum diisi</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('sppd.print', $sppd->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-[#56bbf1]/10 border border-[#56bbf1]/20 text-[#56bbf1] hover:bg-[#56bbf1] hover:text-slate-950 rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="ph-bold ph-printer text-base"></i> Cetak SPPD
                                        </a>
                                        @if($sppd->total_biaya > 0)
                                        <a href="{{ route('sppd.print-spj', $sppd->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 hover:bg-emerald-500 hover:text-slate-950 rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="ph-bold ph-receipt text-base"></i> Cetak SPJ
                                        </a>
                                        @endif

                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button" onclick="showDetailModal({{ json_encode($sppd->load('user','followers')) }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/60 border border-white/10 text-slate-400 hover:text-[#56bbf1] hover:border-[#56bbf1]/40 hover:bg-slate-800/80 transition-all" title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </button>
                                            <a href="{{ route('sppd.edit', $sppd->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/60 border border-white/10 text-slate-400 hover:text-[#56bbf1] hover:border-[#56bbf1]/40 hover:bg-slate-800/80 transition-all" title="Edit Data">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            <button type="button" onclick="confirmDelete('{{ $sppd->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/60 border border-white/10 text-slate-400 hover:text-rose-400 hover:border-rose-500/40 hover:bg-slate-800/80 transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-{{ $sppd->id }}" action="{{ route('sppd.destroy', $sppd->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-900/80 border border-white/10 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500">
                                        <i class="ph-duotone ph-car-profile text-4xl"></i>
                                    </div>
                                    <h3 class="text-white font-bold text-lg">Belum ada data SPPD</h3>
                                    <p class="text-slate-400 text-sm mt-1">Silakan input SPPD baru melalui tombol di atas.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-white/10 bg-white/[0.02]">
                    {{ $sppds->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity opacity-0" id="modalBackdrop" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto custom-scrollbar">
            <div class="flex min-h-full items-start justify-center p-4 py-16 sm:p-6 sm:py-24 text-center">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2.5rem] bg-[#021124] text-left shadow-2xl transition-all w-full max-w-2xl border border-white/10 opacity-0 translate-y-4 duration-300 text-slate-100">
                    <div class="bg-gradient-to-r from-[#031d3d] to-[#021124] p-6 border-b border-white/10 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-black flex items-center gap-2"><i class="ph-duotone ph-info text-[#56bbf1]"></i> Detail SPPD</h3>
                                <p class="text-[#56bbf1] text-sm font-medium mt-1">No: <span id="modal_nomor" class="font-mono bg-white/10 px-2 rounded font-bold"></span></p>
                            </div>
                            <button onclick="closeDetailModal()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center text-slate-300 hover:text-white"><i class="ph-bold ph-x text-lg"></i></button>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/10"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-user text-[#56bbf1]"></i> Pegawai</span><span id="modal_pegawai" class="font-bold text-white text-sm flex flex-col gap-0.5 pl-4"></span></div>
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/10"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-map-pin text-rose-400"></i> Rute</span><span id="modal_rute" class="font-bold text-white text-sm pl-4"></span></div>
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/10"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-car text-[#56bbf1]"></i> Transportasi</span><span id="modal_angkutan" class="font-bold text-white text-sm pl-4"></span></div>
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/10"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-calendar-blank text-[#56bbf1]"></i> Waktu (<span id="modal_lama"></span>)</span><span id="modal_waktu" class="font-bold text-white text-sm pl-4"></span></div>
                        </div>
                        <div class="bg-slate-900/60 p-5 rounded-2xl border border-white/10 mb-4"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Maksud Penugasan</span><p id="modal_maksud" class="text-sm font-medium text-slate-300 leading-relaxed"></p></div>
                        <div class="bg-emerald-500/10 p-5 rounded-2xl border border-emerald-500/20 mb-6 hidden" id="modal_biaya_container">
                            <span class="block text-[10px] font-bold text-emerald-400 uppercase tracking-wider mb-3"><i class="ph-bold ph-money text-emerald-400"></i> Rekap Biaya</span>
                            <div id="modal_biaya_detail" class="space-y-1 text-sm pl-4 text-emerald-200"></div>
                            <div class="border-t border-emerald-500/20 mt-3 pt-3 font-black text-emerald-300 pl-4">Total: <span id="modal_total_biaya"></span></div>
                        </div>
                        <div class="pt-4 border-t border-white/10 flex justify-between items-center">
                            <div class="flex gap-2">
                                <a href="#" id="modal_btn_cetak" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#56bbf1] text-slate-950 rounded-xl font-bold text-sm hover:bg-sky-400 transition-colors shadow-lg shadow-[#56bbf1]/20">
                                    <i class="ph-bold ph-printer"></i> Cetak SPPD
                                </a>
                                <a href="#" id="modal_btn_spj" target="_blank" class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-500 text-slate-950 rounded-xl font-bold text-sm hover:bg-emerald-400 transition-colors shadow-lg shadow-emerald-500/20">
                                    <i class="ph-bold ph-receipt"></i> Cetak SPJ
                                </a>
                            </div>
                            <button onclick="closeDetailModal()" class="px-5 py-2.5 bg-slate-800 text-slate-300 rounded-xl font-bold text-sm hover:bg-slate-700 hover:text-white transition-colors">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if(session('success'))
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, background: '#021124', color: '#fff' })
            .fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif

        // ===== WORKFLOW STATUS =====
        function updateStatus(id, newStatus, nomor) {
            const labels = { submitted: 'Ajukan', approved: 'Setujui', selesai: 'Tandai Selesai' };
            const icons  = { submitted: 'question', approved: 'question', selesai: 'success' };

            Swal.fire({
                title: `${labels[newStatus]} SPPD?`,
                html: `<p class="text-slate-400 text-sm">No. SPPD: <strong class="text-white">${nomor}</strong></p>`,
                icon: icons[newStatus],
                showCancelButton: true,
                confirmButtonText: `Ya, ${labels[newStatus]}!`,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#021124',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 shadow-2xl bg-[#021124]',
                    confirmButton: 'bg-[#56bbf1] text-slate-950 px-6 py-2.5 rounded-xl font-bold mx-2 hover:bg-sky-400',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-2.5 rounded-xl font-bold mx-2 hover:bg-slate-700'
                },
                buttonsStyling: false
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById('status-val-' + id).value = newStatus;
                    document.getElementById('status-form-' + id).submit();
                }
            });
        }

        // ===== HAPUS =====
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus SPPD?', text: 'Data perjalanan dinas ini akan dihapus permanen.', icon: 'warning',
                showCancelButton: true, confirmButtonColor: '#e11d48', cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal', reverseButtons: true,
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl bg-[#021124]', confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold mx-2 hover:bg-rose-500', cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold mx-2 hover:bg-slate-700' },
                buttonsStyling: false
            }).then(result => { if (result.isConfirmed) document.getElementById('delete-form-' + id).submit(); });
        }

        // ===== MODAL DETAIL =====
        function showDetailModal(sppd) {
            const modal = document.getElementById('detailModal');
            document.getElementById('modal_nomor').innerText   = sppd.nomor_sppd;
            document.getElementById('modal_rute').innerHTML    = `${sppd.tempat_berangkat} <i class="ph-bold ph-arrow-right text-slate-400"></i> ${sppd.tempat_tujuan}`;
            document.getElementById('modal_angkutan').innerText= sppd.alat_angkut || 'Belum Ditentukan';
            document.getElementById('modal_lama').innerText    = sppd.lama_hari + ' Hari';
            document.getElementById('modal_maksud').innerText  = sppd.maksud_perjalanan;

            document.getElementById('modal_pegawai').innerHTML = sppd.user
                ? `<span>${sppd.user.name}</span><span class="text-[10px] text-slate-400 font-mono">NIP. ${sppd.user.nip || '-'}</span>`
                : '<span class="text-rose-400 italic text-xs">Data Pegawai Terhapus</span>';

            const opts = { day: 'numeric', month: 'long', year: 'numeric' };
            let waktu  = new Date(sppd.tgl_berangkat).toLocaleDateString('id-ID', opts);
            if (sppd.tgl_berangkat !== sppd.tgl_kembali && sppd.tgl_kembali) {
                waktu += ' <span class="text-[10px] text-slate-400 mx-1">s/d</span> ' + new Date(sppd.tgl_kembali).toLocaleDateString('id-ID', opts);
            }
            document.getElementById('modal_waktu').innerHTML = waktu;

            // Rekap Biaya
            const totalBiaya = (parseFloat(sppd.biaya_transport)||0) + (parseFloat(sppd.biaya_penginapan)||0) + (parseFloat(sppd.uang_harian)||0);
            const biayaContainer = document.getElementById('modal_biaya_container');
            if (totalBiaya > 0) {
                let html = '';
                if (sppd.biaya_transport)  html += `<div class="flex justify-between"><span class="text-slate-400">Transport</span><span class="font-bold text-white">Rp ${parseInt(sppd.biaya_transport).toLocaleString('id-ID')}</span></div>`;
                if (sppd.biaya_penginapan) html += `<div class="flex justify-between"><span class="text-slate-400">Penginapan</span><span class="font-bold text-white">Rp ${parseInt(sppd.biaya_penginapan).toLocaleString('id-ID')}</span></div>`;
                if (sppd.uang_harian)      html += `<div class="flex justify-between"><span class="text-slate-400">Uang Harian</span><span class="font-bold text-white">Rp ${parseInt(sppd.uang_harian).toLocaleString('id-ID')}</span></div>`;
                document.getElementById('modal_biaya_detail').innerHTML = html;
                document.getElementById('modal_total_biaya').innerText = 'Rp ' + totalBiaya.toLocaleString('id-ID');
                biayaContainer.classList.remove('hidden');

                const btnSpj = document.getElementById('modal_btn_spj');
                btnSpj.href = `{{ url('sppd') }}/${sppd.id}/print-spj`;
                btnSpj.classList.remove('hidden');
            } else {
                biayaContainer.classList.add('hidden');
                document.getElementById('modal_btn_spj').classList.add('hidden');
            }

            document.getElementById('modal_btn_cetak').href = `{{ url('sppd') }}/${sppd.id}/print`;
            modal.classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('modalBackdrop').classList.remove('opacity-0');
                document.getElementById('modalPanel').classList.remove('opacity-0', 'translate-y-4');
            }, 10);
        }

        function closeDetailModal() {
            document.getElementById('modalBackdrop').classList.add('opacity-0');
            document.getElementById('modalPanel').classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => document.getElementById('detailModal').classList.add('hidden'), 300);
        }
    </script>
</x-app-layout>
