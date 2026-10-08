<x-app-layout>
    <div class="space-y-8 font-sans text-slate-100">
        
        {{-- HERO SECTION --}}
            <div class="mb-8 relative z-10">
                <x-hero-section
                    badge="ADMINISTRASI PERSURATAN"
                    badgeIcon="ph-fill ph-paper-plane-tilt"
                    showcaseIcon="ph-duotone ph-paper-plane-tilt"
                    showcaseTitle="Persuratan Keluar"
                    showcaseSubtitle="Arsip & Penertiban Surat">
                    <x-slot:title>
                        <span class="block text-slate-100">Arsip & Tata Usaha</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Surat Keluar Sekolah
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Kelola dokumen persuratan keluar. Terintegrasi langsung dengan pemprosesan draf penugasan dinas, penomoran otomatis, dan pengarsipan digital.
                    </x-slot:description>
                    <x-slot:chips>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-paper-plane text-sky-400"></i> Registrasi Surat
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-file-arrow-up text-emerald-400"></i> Upload Lampiran
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-archive text-cyan-400"></i> Arsip Digital
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        <div class="flex flex-wrap items-center gap-2.5">
                            <a href="{{ route('letters.outgoing.create') }}"
                               class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-gradient-to-r from-[#56bbf1] to-blue-600 hover:brightness-110 text-white font-bold text-sm shadow-lg shadow-[#56bbf1]/20 transition-all duration-300 hover:scale-[1.02] active:scale-[0.98]">
                                <i class="ph-bold ph-plus-circle text-lg"></i>
                                <span>Buat Surat Keluar</span>
                            </a>
                            <a href="{{ route('letters.outgoing.print-agenda', request()->all()) }}" target="_blank"
                               class="inline-flex items-center gap-1.5 px-4 py-3 rounded-2xl bg-slate-800/80 hover:bg-slate-700/80 text-sky-300 border border-sky-500/30 text-xs font-bold transition-all shadow-md hover:scale-[1.02]">
                                <i class="ph-bold ph-printer text-base"></i>
                                <span>Cetak Agenda</span>
                            </a>
                            <a href="{{ route('letters.outgoing.export-excel', request()->all()) }}"
                               class="inline-flex items-center gap-1.5 px-4 py-3 rounded-2xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold transition-all shadow-md hover:scale-[1.02]">
                                <i class="ph-bold ph-file-xls text-base"></i>
                                <span>Export Excel</span>
                            </a>
                        </div>
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        <div class="flex flex-wrap items-center gap-2">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                                <i class="ph-fill ph-paper-plane-tilt text-[#56bbf1] text-xs"></i>
                                <span class="text-[11px] font-bold text-slate-300">Tahun Ini:</span>
                                <span class="text-xs font-black text-white font-mono">{{ $stats['total_this_year'] ?? 0 }}</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                                <i class="ph-fill ph-warning-circle text-amber-400 text-xs"></i>
                                <span class="text-[11px] font-bold text-slate-300">Penting:</span>
                                <span class="text-xs font-black text-amber-300 font-mono">{{ $stats['total_penting'] ?? 0 }}</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                                <i class="ph-fill ph-briefcase text-emerald-400 text-xs"></i>
                                <span class="text-[11px] font-bold text-slate-300">SPT:</span>
                                <span class="text-xs font-black text-emerald-300 font-mono">{{ $stats['total_with_spt'] ?? 0 }}</span>
                            </div>
                        </div>
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            {{-- Toolbar Pencarian & Filter Lengkap --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden">
                
                {{-- Toolbar (Search & Filter) --}}
                <div class="p-6 border-b border-white/10 bg-white/5 flex flex-col 2xl:flex-row gap-4 justify-between items-center">
                    <div class="flex items-center gap-3">
                        <h3 class="font-black text-white text-lg flex items-center gap-2 whitespace-nowrap">
                            <i class="ph-fill ph-list-dashes text-[#56bbf1]"></i> Data Surat Keluar
                        </h3>
                        <span class="px-2.5 py-0.5 rounded-full bg-white/5 border border-white/10 text-xs font-bold text-slate-400 font-mono">
                            {{ $letters->total() }} Data
                        </span>
                    </div>
                    
                    <form action="{{ route('letters.outgoing.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 w-full 2xl:w-auto">
                        {{-- Filter Tahun --}}
                        <div class="relative w-full sm:w-32">
                            <select name="year" onchange="this.form.submit()" class="w-full pl-3 pr-8 py-2.5 rounded-xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="" class="bg-slate-900 text-white">Semua Tahun</option>
                                @foreach($availableYears as $yr)
                                    <option value="{{ $yr }}" class="bg-slate-900 text-white" {{ request('year') == $yr ? 'selected' : '' }}>Th. {{ $yr }}</option>
                                @endforeach
                            </select>
                            <i class="ph-bold ph-calendar-blank absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                        </div>

                        {{-- Filter Bulan --}}
                        <div class="relative w-full sm:w-36">
                            <select name="month" onchange="this.form.submit()" class="w-full pl-3 pr-8 py-2.5 rounded-xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="" class="bg-slate-900 text-white">Semua Bulan</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" class="bg-slate-900 text-white" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                        </div>

                        {{-- Filter Sifat --}}
                        <div class="relative w-full sm:w-36">
                            <select name="sifat_surat" onchange="this.form.submit()" class="w-full pl-3 pr-8 py-2.5 rounded-xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-xs font-bold text-white appearance-none transition-all cursor-pointer">
                                <option value="" class="bg-slate-900 text-white">Semua Sifat</option>
                                <option value="Biasa" class="bg-slate-900 text-white" {{ request('sifat_surat') == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                <option value="Penting" class="bg-slate-900 text-white" {{ request('sifat_surat') == 'Penting' ? 'selected' : '' }}>Penting</option>
                                <option value="Segera" class="bg-slate-900 text-white" {{ request('sifat_surat') == 'Segera' ? 'selected' : '' }}>Segera</option>
                            </select>
                            <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-sm"></i>
                        </div>

                        {{-- Search Input --}}
                        <div class="relative flex-1 sm:w-56 min-w-[200px]">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nomor, perihal..." class="w-full pl-9 pr-4 py-2.5 rounded-xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-xs font-medium text-white transition-all placeholder:text-slate-500">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        </div>

                        @if(request('search') || request('sifat_surat') || request('year') || request('month'))
                            <a href="{{ route('letters.outgoing.index') }}" class="px-3 py-2.5 bg-slate-800 text-slate-400 hover:bg-rose-500/20 hover:text-rose-400 rounded-xl text-xs font-bold transition-colors flex items-center justify-center gap-1.5 border border-white/10" title="Reset Semua Filter">
                                <i class="ph-bold ph-x text-sm"></i>
                                <span class="hidden sm:inline">Reset</span>
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Table Content --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-900/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-white/10">
                            <tr>
                                <th class="px-6 py-5 w-48">Agenda & Tgl</th>
                                <th class="px-6 py-5">Identitas Surat</th>
                                <th class="px-6 py-5 w-1/3">Tujuan & Perihal</th>
                                <th class="px-6 py-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($letters as $letter)
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-black text-[#56bbf1] bg-[#56bbf1]/10 px-3 py-1.5 rounded-lg border border-[#56bbf1]/20 inline-block text-sm mb-2 shadow-sm">
                                        #{{ $letter->nomor_agenda }}
                                    </div>
                                    <div class="text-xs text-slate-400 font-medium flex items-center gap-1.5 mt-1">
                                        <i class="ph-bold ph-calendar-blank"></i> {{ \Carbon\Carbon::parse($letter->tgl_surat)->translatedFormat('d M Y') }}
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-center gap-2 group/num mb-2">
                                        <span class="font-bold text-white text-sm leading-snug">{{ $letter->nomor_surat }}</span>
                                        <button type="button" 
                                                onclick="openQuickEditNomorModal({{ $letter->id }}, '{{ addslashes($letter->nomor_surat) }}')" 
                                                class="opacity-0 group-hover/num:opacity-100 transition-opacity p-1 rounded-lg hover:bg-white/10 text-slate-400 hover:text-amber-400 focus:opacity-100" 
                                                title="Edit Nomor Surat Cepat">
                                            <i class="ph-bold ph-pencil-simple text-xs"></i>
                                        </button>
                                    </div>
                                    @php
                                        $sifatColors = [
                                            'Penting' => 'bg-amber-500/15 text-amber-300 border-amber-500/30',
                                            'Segera'  => 'bg-rose-500/15 text-rose-300 border-rose-500/30',
                                            'Biasa'   => 'bg-sky-500/15 text-sky-300 border-sky-500/30',
                                        ];
                                        $sifatClass = $sifatColors[$letter->sifat_surat] ?? 'bg-slate-800/80 text-slate-300 border-white/10';
                                    @endphp
                                    <span class="inline-flex px-2.5 py-1 rounded-lg border {{ $sifatClass }} text-[11px] font-bold tracking-wider">
                                        {{ $letter->sifat_surat }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="flex items-start gap-2 mb-2">
                                        <i class="ph-fill ph-buildings text-[#56bbf1] mt-0.5"></i>
                                        <span class="font-bold text-white text-sm">{{ $letter->tujuan_surat }}</span>
                                    </div>
                                    <p class="text-sm text-slate-300 leading-relaxed line-clamp-2 font-medium">
                                        {{ $letter->perihal }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        @if($letter->file_path)
                                            <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-[#56bbf1]/10 border border-[#56bbf1]/20 text-[#56bbf1] hover:bg-[#56bbf1] hover:text-slate-950 rounded-xl text-xs font-bold transition-all shadow-sm">
                                                <i class="ph-bold ph-download-simple text-base"></i> Lampiran
                                            </a>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-slate-800/40 text-slate-500 rounded-lg text-xs font-medium border border-white/5">
                                                <i class="ph-bold ph-file-dashed"></i> No File
                                            </span>
                                        @endif

                                        {{-- Tombol Aksi (Detail, Edit, Hapus) --}}
                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button" 
                                                    data-letter="{{ json_encode($letter) }}" 
                                                    onclick="showDetailModal(JSON.parse(this.dataset.letter))" 
                                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/80 border border-white/10 text-slate-400 hover:text-sky-400 hover:border-sky-500/30 hover:bg-sky-500/10 transition-all" 
                                                    title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </button>
                                            <a href="{{ route('letters.outgoing.edit', $letter->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/80 border border-white/10 text-slate-400 hover:text-amber-400 hover:border-amber-500/30 hover:bg-amber-500/10 transition-all" title="Edit">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            <button type="button" onclick="confirmDelete('{{ $letter->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-900/80 border border-white/10 text-slate-400 hover:text-rose-400 hover:border-rose-500/30 hover:bg-rose-500/10 transition-all" title="Hapus">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>

                                        <form id="delete-form-{{ $letter->id }}" action="{{ route('letters.outgoing.destroy', $letter->id) }}" method="POST" class="hidden">
                                            @csrf @method('DELETE')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-20 text-center">
                                    <div class="w-20 h-20 bg-slate-900/60 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-500 border border-white/10">
                                        <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                                    </div>
                                    <h3 class="text-white font-bold text-lg">Data Tidak Ditemukan</h3>
                                    <p class="text-slate-400 text-sm mt-1 mb-6">Belum ada surat keluar atau hasil pencarian Anda tidak cocok.</p>
                                    <a href="{{ route('letters.outgoing.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white rounded-xl font-bold text-sm hover:brightness-110 transition-all shadow-lg shadow-[#56bbf1]/20">
                                        <i class="ph-bold ph-plus"></i> Buat Surat Baru
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

    {{-- MODAL DETAIL SURAT --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity opacity-0" id="modalBackdrop" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto custom-scrollbar">
            <div class="flex min-h-full items-start justify-center p-4 py-16 sm:p-6 sm:py-24 text-center">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2.5rem] bg-[#021124] text-left shadow-2xl transition-all w-full max-w-2xl border border-white/10 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-[#031d3d] via-[#0b2545] to-[#134074] p-6 text-white relative overflow-hidden border-b border-white/10">
                        <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                            <i class="ph-fill ph-paper-plane-tilt"></i>
                        </div>
                        <div class="flex justify-between items-center relative z-10">
                            <div>
                                <h3 class="text-xl font-black flex items-center gap-2">
                                    <i class="ph-duotone ph-info text-[#56bbf1]"></i> Detail Surat Keluar
                                </h3>
                                <p class="text-slate-300 text-sm font-medium mt-1">
                                    Agenda: <span id="modal_agenda" class="font-mono bg-[#56bbf1]/10 text-[#56bbf1] px-2 rounded font-bold"></span>
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
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Nomor Surat</span>
                                <span id="modal_nomor" class="font-bold text-white text-sm"></span>
                            </div>
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/5">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tujuan Surat</span>
                                <span id="modal_tujuan" class="font-bold text-white text-sm"></span>
                            </div>
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/5">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tanggal Surat</span>
                                <span id="modal_tanggal" class="font-bold text-white text-sm"></span>
                            </div>
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/5">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Sifat Surat</span>
                                <span id="modal_sifat" class="inline-flex px-2.5 py-1 rounded-md border border-white/10 bg-slate-800 text-xs font-bold text-slate-300"></span>
                            </div>
                        </div>
                        
                        <div class="bg-slate-900/60 p-5 rounded-2xl border border-white/5 mb-6">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Perihal / Isi Surat</span>
                            <p id="modal_perihal" class="text-sm font-medium text-slate-300 leading-relaxed"></p>
                        </div>

                        {{-- In-Modal Live Document Preview --}}
                        <div id="modal_preview_wrapper" class="mb-6 hidden">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="ph-bold ph-eye text-sky-400"></i> Pratinjau Dokumen Lampiran
                                </span>
                                <span id="modal_preview_type_badge" class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-white/5 border border-white/10 text-sky-300 uppercase"></span>
                            </div>
                            <div id="modal_preview_container" class="rounded-2xl border border-white/10 bg-slate-950/80 overflow-hidden flex items-center justify-center p-2 min-h-48 shadow-inner">
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="pt-6 border-t border-white/10 flex flex-wrap items-center justify-between gap-3">
                            <div id="modal_lampiran_container">
                                <!-- Tombol Lampiran diinject via JS -->
                            </div>
                            <button onclick="closeDetailModal()" class="px-6 py-2.5 bg-slate-800 text-slate-300 rounded-xl font-bold text-xs hover:bg-slate-700 transition-colors border border-white/10">
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
                timerProgressBar: true,
                background: '#021124',
                color: '#fff',
                customClass: { popup: 'rounded-[1.5rem] font-sans border border-white/10' }
            });
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        function openQuickEditNomorModal(id, currentNomor) {
            Swal.fire({
                title: 'Edit Nomor Surat',
                text: 'Perbarui nomor surat keluar secara manual:',
                input: 'text',
                inputValue: currentNomor,
                showCancelButton: true,
                confirmButtonText: 'Simpan Perubahan',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#021124',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    input: 'bg-slate-900 border-white/20 text-white font-bold rounded-xl text-center font-mono text-sm px-4 py-3',
                    confirmButton: 'bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white px-6 py-2.5 rounded-xl font-bold shadow-lg shadow-[#56bbf1]/20 mx-2 hover:brightness-110',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-2.5 rounded-xl font-bold border border-white/10 mx-2 hover:bg-slate-700'
                },
                buttonsStyling: false,
                preConfirm: (newNomor) => {
                    if (!newNomor || !newNomor.trim()) {
                        Swal.showValidationMessage('Nomor surat tidak boleh kosong!');
                        return false;
                    }
                    return newNomor.trim();
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/letters/outgoing/${id}/quick-update-nomor`;
                    form.innerHTML = `
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="PATCH">
                        <input type="hidden" name="nomor_surat" value="${result.value.replace(/"/g, '&quot;')}">
                    `;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Surat Keluar?', 
                text: "Data surat beserta lampirannya akan dihapus secara permanen.",
                icon: 'warning', 
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!', 
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#021124',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2 border border-white/10'
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
            const dateObj = new Date(letter.tgl_surat);
            const options = { day: 'numeric', month: 'long', year: 'numeric' };
            const formattedDate = dateObj.toLocaleDateString('id-ID', options);

            // Set Data
            document.getElementById('modal_agenda').innerText = '#' + letter.nomor_agenda;
            document.getElementById('modal_nomor').innerText = letter.nomor_surat;
            document.getElementById('modal_tujuan').innerText = letter.tujuan_surat;
            document.getElementById('modal_tanggal').innerText = formattedDate;
            document.getElementById('modal_sifat').innerText = letter.sifat_surat;
            document.getElementById('modal_perihal').innerText = letter.perihal;

            // Set Link Lampiran & Live Preview Jika Ada
            const lampiranContainer = document.getElementById('modal_lampiran_container');
            const previewWrapper = document.getElementById('modal_preview_wrapper');
            const previewContainer = document.getElementById('modal_preview_container');
            const previewBadge = document.getElementById('modal_preview_type_badge');

            if (letter.file_path) {
                const storageBase = '{{ asset("storage") }}';
                const fileUrl = `${storageBase}/${letter.file_path.replace(/^\/+/, '')}`;
                const ext = letter.file_path.split('.').pop().toLowerCase();

                previewBadge.innerText = ext;
                previewWrapper.classList.remove('hidden');

                if (ext === 'pdf') {
                    previewContainer.innerHTML = `
                        <iframe src="${fileUrl}#toolbar=0" class="w-full h-80 rounded-xl border border-white/5 bg-slate-900" title="PDF Preview"></iframe>
                    `;
                } else if (['jpg', 'jpeg', 'png', 'webp'].includes(ext)) {
                    previewContainer.innerHTML = `
                        <div class="text-center p-2">
                            <img src="${fileUrl}" alt="Lampiran" class="max-h-80 mx-auto rounded-xl object-contain shadow-2xl cursor-pointer hover:opacity-90 transition-opacity" onclick="window.open('${fileUrl}', '_blank')" title="Klik untuk membuka ukuran penuh">
                            <p class="text-[11px] text-slate-500 mt-2">Klik gambar untuk membuka resolusi penuh</p>
                        </div>
                    `;
                } else {
                    previewContainer.innerHTML = `
                        <div class="py-8 text-center text-slate-400">
                            <i class="ph-bold ph-file-text text-4xl text-[#56bbf1] mb-2 inline-block"></i>
                            <p class="text-xs font-medium">Format file (${ext}) tidak mendukung pratinjau langsung.</p>
                            <a href="${fileUrl}" target="_blank" class="text-xs text-sky-400 hover:underline font-bold mt-1 inline-block">Buka file di tab baru</a>
                        </div>
                    `;
                }

                lampiranContainer.innerHTML = `
                    <div class="flex items-center gap-2">
                        <a href="${fileUrl}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#56bbf1]/15 text-[#56bbf1] border border-[#56bbf1]/30 rounded-xl font-bold text-xs hover:bg-[#56bbf1] hover:text-slate-950 transition-all shadow-sm">
                            <i class="ph-bold ph-arrow-square-out text-sm"></i> Buka Penuh
                        </a>
                        <a href="${fileUrl}" download class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-800 text-slate-200 border border-white/10 rounded-xl font-bold text-xs hover:bg-slate-700 transition-all">
                            <i class="ph-bold ph-download-simple text-sm"></i> Unduh File
                        </a>
                    </div>
                `;
            } else {
                previewWrapper.classList.add('hidden');
                previewContainer.innerHTML = '';
                lampiranContainer.innerHTML = `
                    <span class="inline-flex items-center gap-1.5 px-4 py-2.5 bg-slate-800/40 text-slate-500 rounded-xl text-xs font-medium border border-white/5">
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
            }, 300); // Tunggu durasi transisi Tailwind (300ms)
        }
    </script>
</x-app-layout>