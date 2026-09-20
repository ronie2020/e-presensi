<x-app-layout>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider bg-white/50 px-3 py-1 rounded-full border border-white/60 backdrop-blur-sm shadow-sm">Administrasi Sekolah</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-car-profile text-xl"></i>
                            </div>
                            Surat Perjalanan Dinas
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium leading-relaxed max-w-lg ml-0 md:ml-12">
                            Kelola SPPD, tracking status, cetak dokumen, dan pantau rekapitulasi biaya perjalanan dinas.
                        </p>
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3 ml-0 md:ml-12">
                            <a href="{{ route('sppd.create') }}" class="group bg-white text-elevate-dark px-5 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-slate-50 flex items-center gap-2 shadow-lg shadow-elevate-dark/5 border border-white active:scale-95">
                                <div class="w-7 h-7 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-plus text-sm"></i>
                                </div>
                                <span>Input SPPD Baru</span>
                            </a>
                            <a href="{{ route('sppd.dashboard') }}" class="group bg-elevate-primary/20 text-elevate-dark px-5 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-elevate-primary hover:text-white flex items-center gap-2 border border-white/60 active:scale-95">
                                <i class="ph-bold ph-chart-bar text-sm"></i>
                                <span>Dashboard Rekap</span>
                            </a>
                        </div>
                    </div>

                    {{-- Statistik Status --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @foreach(['draft' => ['Draft','slate'], 'submitted' => ['Diajukan','amber'], 'approved' => ['Disetujui','sky'], 'selesai' => ['Selesai','emerald']] as $key => $info)
                        <a href="{{ route('sppd.index', ['status' => $key]) }}" class="bg-white/60 backdrop-blur-md px-4 py-4 rounded-[1.5rem] border border-white shadow-sm text-center min-w-[100px] hover:bg-white transition-colors group">
                            <span class="block text-3xl font-black text-elevate-dark mb-1 group-hover:scale-110 transition-transform inline-block">{{ $stats[$key] }}</span>
                            <span class="text-[10px] uppercase font-bold text-elevate-primary tracking-wider">{{ $info[0] }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Toolbar & Table --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">

                {{-- Toolbar --}}
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="flex items-center gap-3">
                        <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                            <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Riwayat SPPD
                        </h3>
                        {{-- Filter Status --}}
                        <div class="flex gap-1 flex-wrap">
                            <a href="{{ route('sppd.index') }}" class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider transition-all {{ !request('status') ? 'bg-elevate-dark text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">Semua</a>
                            @foreach(['draft' => 'Draft', 'submitted' => 'Diajukan', 'approved' => 'Disetujui', 'selesai' => 'Selesai'] as $key => $lbl)
                            <a href="{{ route('sppd.index', ['status' => $key, 'search' => request('search')]) }}" class="px-3 py-1 text-[10px] font-bold rounded-full uppercase tracking-wider transition-all {{ request('status') === $key ? 'bg-elevate-dark text-white' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">{{ $lbl }}</a>
                            @endforeach
                        </div>
                    </div>

                    <form action="{{ route('sppd.index') }}" method="GET" class="relative w-full sm:w-80 group">
                        @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No SPPD / Tujuan / Pegawai..."
                               class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark transition-all">
                    </form>
                </div>

                {{-- Tabel --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5">No. SPPD & Pegawai</th>
                                <th class="px-6 py-5">Tujuan & Waktu</th>
                                <th class="px-6 py-5">Status</th>
                                <th class="px-6 py-5">Total Biaya</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($sppds as $sppd)
                            @php
                                $statusColors = [
                                    'draft'     => 'bg-slate-100 text-slate-600',
                                    'submitted' => 'bg-amber-50 text-amber-700 border border-amber-200',
                                    'approved'  => 'bg-sky-50 text-sky-700 border border-sky-200',
                                    'selesai'   => 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                                ];
                                $statusIcons = ['draft'=>'ph-pencil-simple','submitted'=>'ph-paper-plane-tilt','approved'=>'ph-check-circle','selesai'=>'ph-seal-check'];
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-bold text-elevate-primary bg-elevate-accent/10 px-3 py-1.5 rounded-lg border border-elevate-accent/20 inline-block text-xs mb-3">
                                        {{ $sppd->nomor_sppd }}
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-elevate-primary group-hover:text-white transition-colors">
                                            {{ substr($sppd->user?->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-elevate-dark text-sm">{{ $sppd->user?->name ?? 'Pegawai Terhapus' }}</div>
                                            <div class="text-[10px] text-slate-500 font-mono">NIP. {{ $sppd->user?->nip ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-elevate-dark text-sm mb-2 flex items-center gap-1.5">
                                        <i class="ph-fill ph-map-pin text-rose-500"></i> {{ $sppd->tempat_tujuan }}
                                    </div>
                                    <div class="text-xs text-slate-500 flex flex-col gap-1 pl-5 font-medium">
                                        <span>{{ \Carbon\Carbon::parse($sppd->tgl_berangkat)->format('d M Y') }}</span>
                                        <span class="text-[10px] text-slate-400 uppercase font-bold tracking-widest">s/d</span>
                                        <span>{{ \Carbon\Carbon::parse($sppd->tgl_kembali)->format('d M Y') }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-bold {{ $statusColors[$sppd->status] ?? 'bg-slate-100 text-slate-500' }}">
                                        <i class="ph-bold {{ $statusIcons[$sppd->status] ?? 'ph-question' }} text-sm"></i>
                                        {{ $sppd->status_label }}
                                    </span>

                                    {{-- Tombol Workflow --}}
                                    <div class="mt-2 flex flex-col gap-1">
                                        @if($sppd->status === 'draft')
                                            <button onclick="updateStatus('{{ $sppd->id }}', 'submitted', '{{ $sppd->nomor_sppd }}')"
                                                class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-200 hover:bg-amber-100 px-2 py-1 rounded-lg transition-colors flex items-center gap-1 w-full justify-center">
                                                <i class="ph-bold ph-paper-plane-tilt"></i> Ajukan
                                            </button>
                                        @elseif($sppd->status === 'submitted')
                                            <button onclick="updateStatus('{{ $sppd->id }}', 'approved', '{{ $sppd->nomor_sppd }}')"
                                                class="text-[10px] font-bold text-sky-600 bg-sky-50 border border-sky-200 hover:bg-sky-100 px-2 py-1 rounded-lg transition-colors flex items-center gap-1 w-full justify-center">
                                                <i class="ph-bold ph-check-circle"></i> Setujui
                                            </button>
                                        @elseif($sppd->status === 'approved')
                                            <button onclick="updateStatus('{{ $sppd->id }}', 'selesai', '{{ $sppd->nomor_sppd }}')"
                                                class="text-[10px] font-bold text-emerald-600 bg-emerald-50 border border-emerald-200 hover:bg-emerald-100 px-2 py-1 rounded-lg transition-colors flex items-center gap-1 w-full justify-center">
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
                                        <div class="font-bold text-elevate-dark text-sm">
                                            Rp {{ number_format($sppd->total_biaya, 0, ',', '.') }}
                                        </div>
                                        <div class="text-[10px] text-slate-400 mt-1 space-y-0.5">
                                            @if($sppd->biaya_transport) <div>Transport: Rp {{ number_format($sppd->biaya_transport, 0, ',', '.') }}</div>@endif
                                            @if($sppd->biaya_penginapan)<div>Penginapan: Rp {{ number_format($sppd->biaya_penginapan, 0, ',', '.') }}</div>@endif
                                            @if($sppd->uang_harian)    <div>Harian: Rp {{ number_format($sppd->uang_harian, 0, ',', '.') }}</div>@endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum diisi</span>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('sppd.print', $sppd->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary hover:bg-elevate-primary hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="ph-bold ph-printer text-base"></i> Cetak SPPD
                                        </a>
                                        @if($sppd->total_biaya > 0)
                                        <a href="{{ route('sppd.print-spj', $sppd->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-600 hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm">
                                            <i class="ph-bold ph-receipt text-base"></i> Cetak SPJ
                                        </a>
                                        @endif

                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button" onclick="showDetailModal({{ json_encode($sppd->load('user','followers')) }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sky-600 hover:border-sky-200 hover:bg-sky-50 hover:shadow-sm transition-all" title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </button>
                                            <a href="{{ route('sppd.edit', $sppd->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-elevate-primary hover:border-elevate-accent hover:bg-elevate-soft hover:shadow-sm transition-all" title="Edit Data">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            <button type="button" onclick="confirmDelete('{{ $sppd->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 hover:shadow-sm transition-all" title="Hapus">
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
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                                        <i class="ph-duotone ph-car-profile text-4xl"></i>
                                    </div>
                                    <h3 class="text-elevate-dark font-bold text-lg">Belum ada data SPPD</h3>
                                    <p class="text-slate-500 text-sm mt-1">Silakan input SPPD baru melalui tombol di atas.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100 bg-slate-50/50">
                    {{ $sppds->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL DETAIL (sama seperti sebelumnya) --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto custom-scrollbar">
            <div class="flex min-h-full items-start justify-center p-4 py-16 sm:p-6 sm:py-24 text-center">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all w-full max-w-2xl border border-slate-100 opacity-0 translate-y-4 duration-300">
                    <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-6 text-white">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-xl font-black flex items-center gap-2"><i class="ph-duotone ph-info text-elevate-accent"></i> Detail SPPD</h3>
                                <p class="text-elevate-accent text-sm font-medium mt-1">No: <span id="modal_nomor" class="font-mono bg-white/10 px-2 rounded font-bold"></span></p>
                            </div>
                            <button onclick="closeDetailModal()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center"><i class="ph-bold ph-x text-lg"></i></button>
                        </div>
                    </div>
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-user text-elevate-primary"></i> Pegawai</span><span id="modal_pegawai" class="font-bold text-elevate-dark text-sm flex flex-col gap-0.5 pl-4"></span></div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-map-pin text-rose-500"></i> Rute</span><span id="modal_rute" class="font-bold text-elevate-dark text-sm pl-4"></span></div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-car text-elevate-primary"></i> Transportasi</span><span id="modal_angkutan" class="font-bold text-elevate-dark text-sm pl-4"></span></div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2"><i class="ph-fill ph-calendar-blank text-elevate-primary"></i> Waktu (<span id="modal_lama"></span>)</span><span id="modal_waktu" class="font-bold text-elevate-dark text-sm pl-4"></span></div>
                        </div>
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 mb-4"><span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Maksud Penugasan</span><p id="modal_maksud" class="text-sm font-medium text-slate-700 leading-relaxed"></p></div>
                        <div class="bg-emerald-50 p-5 rounded-2xl border border-emerald-100 mb-6 hidden" id="modal_biaya_container">
                            <span class="block text-[10px] font-bold text-emerald-600 uppercase tracking-wider mb-3"><i class="ph-bold ph-money text-emerald-600"></i> Rekap Biaya</span>
                            <div id="modal_biaya_detail" class="space-y-1 text-sm pl-4"></div>
                            <div class="border-t border-emerald-200 mt-3 pt-3 font-black text-emerald-700 pl-4">Total: <span id="modal_total_biaya"></span></div>
                        </div>
                        <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                            <div class="flex gap-2">
                                <a href="#" id="modal_btn_cetak" target="_blank" class="inline-flex items-center gap-2 px-5 py-2.5 bg-elevate-dark text-white rounded-xl font-bold text-sm hover:bg-elevate-primary transition-colors shadow-lg">
                                    <i class="ph-bold ph-printer"></i> Cetak SPPD
                                </a>
                                <a href="#" id="modal_btn_spj" target="_blank" class="hidden inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl font-bold text-sm hover:bg-emerald-700 transition-colors shadow-lg">
                                    <i class="ph-bold ph-receipt"></i> Cetak SPJ
                                </a>
                            </div>
                            <button onclick="closeDetailModal()" class="px-5 py-2.5 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200">Tutup</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        @if(session('success'))
        Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true })
            .fire({ icon: 'success', title: '{{ session("success") }}' });
        @endif

        // ===== WORKFLOW STATUS =====
        function updateStatus(id, newStatus, nomor) {
            const labels = { submitted: 'Ajukan', approved: 'Setujui', selesai: 'Tandai Selesai' };
            const icons  = { submitted: 'question', approved: 'question', selesai: 'success' };

            Swal.fire({
                title: `${labels[newStatus]} SPPD?`,
                html: `<p class="text-slate-500 text-sm">No. SPPD: <strong>${nomor}</strong></p>`,
                icon: icons[newStatus],
                showCancelButton: true,
                confirmButtonText: `Ya, ${labels[newStatus]}!`,
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans',
                    confirmButton: 'bg-elevate-dark text-white px-6 py-2.5 rounded-xl font-bold mx-2',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-2.5 rounded-xl font-bold mx-2'
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
                customClass: { popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl', confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold mx-2', cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold mx-2' },
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
                ? `<span>${sppd.user.name}</span><span class="text-[10px] text-slate-500 font-mono">NIP. ${sppd.user.nip || '-'}</span>`
                : '<span class="text-rose-500 italic text-xs">Data Pegawai Terhapus</span>';

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
                if (sppd.biaya_transport)  html += `<div class="flex justify-between"><span class="text-slate-500">Transport</span><span class="font-bold">Rp ${parseInt(sppd.biaya_transport).toLocaleString('id-ID')}</span></div>`;
                if (sppd.biaya_penginapan) html += `<div class="flex justify-between"><span class="text-slate-500">Penginapan</span><span class="font-bold">Rp ${parseInt(sppd.biaya_penginapan).toLocaleString('id-ID')}</span></div>`;
                if (sppd.uang_harian)      html += `<div class="flex justify-between"><span class="text-slate-500">Uang Harian</span><span class="font-bold">Rp ${parseInt(sppd.uang_harian).toLocaleString('id-ID')}</span></div>`;
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
