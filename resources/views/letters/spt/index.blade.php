<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <div class="flex items-center justify-center md:justify-start gap-2 mb-2">
                             <a href="{{ route('dashboard') }}" class="text-xs font-bold text-elevate-primary hover:text-elevate-dark transition flex items-center gap-1 bg-white/60 px-3 py-1 rounded-full border border-white backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Dashboard
                            </a>
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider bg-white/50 px-3 py-1 rounded-full border border-white/60 backdrop-blur-sm shadow-sm">Surat Dinas</span>
                        </div>
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <div class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-paper-plane-tilt text-xl"></i>
                            </div>
                            Surat Perintah Tugas (SPT)
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium leading-relaxed max-w-lg ml-0 md:ml-12">
                            Kelola SPT, cetak dokumen penugasan dinas untuk pegawai, dan lihat arsip penugasan.
                        </p>
                        
                        <div class="mt-6 flex flex-wrap justify-center md:justify-start gap-3 ml-0 md:ml-12">
                            <a href="{{ route('letters.spt.create') }}" class="group bg-white text-elevate-dark px-5 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-slate-50 flex items-center gap-2 shadow-lg shadow-elevate-dark/5 border border-white active:scale-95">
                                <div class="w-7 h-7 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                    <i class="ph-bold ph-plus text-sm"></i>
                                </div>
                                <span>Buat SPT Baru</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Statistik Ringkas --}}
                    <div class="flex gap-3">
                        <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white shadow-sm text-center min-w-[140px]">
                            <span class="block text-4xl font-black text-elevate-dark mb-1">{{ $spts->total() }}</span>
                            <span class="text-[10px] uppercase font-bold text-elevate-primary tracking-wider">Total SPT</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Toolbar & Table --}}
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                
                {{-- Toolbar (Search) --}}
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                        <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Data Surat Perintah Tugas
                    </h3>

                    <form action="{{ route('letters.spt.index') }}" method="GET" class="flex items-center gap-3 w-full sm:w-auto">
                        <div class="relative w-full sm:w-80 group">
                            <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                            <input type="text" name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Cari No SPT / Pegawai / Perihal..." 
                                   class="w-full pl-11 pr-4 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark transition-all">
                        </div>
                        @if(request('search'))
                            <a href="{{ route('letters.spt.index') }}" class="w-12 h-12 flex items-center justify-center shrink-0 bg-slate-100 text-slate-500 hover:bg-rose-50 hover:text-rose-600 rounded-2xl text-lg font-bold transition-colors" title="Reset Pencarian">
                                <i class="ph-bold ph-x"></i>
                            </a>
                        @endif
                    </form>
                </div>

                {{-- Tabel Data --}}
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50/80 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-5 w-[28%]">Identitas SPT & Pegawai</th>
                                <th class="px-6 py-5">Tempat & Waktu</th>
                                <th class="px-6 py-5 w-[30%]">Perihal Penugasan</th>
                                <th class="px-6 py-5 text-right w-[15%]">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($spts as $spt)
                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                <td class="px-6 py-5 align-top">
                                    <div class="font-mono font-bold text-elevate-primary bg-elevate-accent/10 px-3 py-1.5 rounded-lg border border-elevate-accent/20 inline-block text-xs mb-3">
                                        {{ $spt->nomor_spt }}
                                    </div>
                                    <div class="flex items-center gap-3 mb-2">
                                        {{-- Inisial Nama Pegawai Pertama --}}
                                        <div class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 border border-slate-200 flex items-center justify-center font-bold text-xs shrink-0 group-hover:bg-elevate-primary group-hover:text-white transition-colors shadow-sm">
                                            {{ $spt->users->count() > 0 ? substr($spt->users->first()->name, 0, 1) : '?' }}
                                        </div>
                                        <div>
                                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-tighter leading-none mb-1">Pegawai Ditugaskan</div>
                                            <div class="font-bold text-elevate-dark text-sm group-hover:text-elevate-primary transition-colors leading-tight">
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
                                        <div class="text-[10px] font-bold text-slate-500 flex items-center gap-1 mt-2 pl-11">
                                            <i class="ph-fill ph-users"></i> +{{ count($spt->pengikut) }} Pengikut Luar
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="font-bold text-elevate-dark text-sm mb-2 flex items-center gap-1.5">
                                        <i class="ph-fill ph-map-pin text-rose-500"></i> {{ $spt->tempat_tujuan }}
                                    </div>
                                    <div class="text-xs text-slate-500 flex flex-col gap-1 pl-5 font-medium">
                                        <span class="flex items-center gap-1.5"><i class="ph-bold ph-calendar text-[10px]"></i> {{ \Carbon\Carbon::parse($spt->tgl_berangkat)->isoFormat('D MMM YYYY') }}</span>
                                        @if($spt->lama_hari > 1)
                                            <span class="text-[10px] text-slate-400 uppercase font-black tracking-widest pl-4">s.d</span>
                                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-calendar-check text-[10px]"></i> {{ \Carbon\Carbon::parse($spt->tgl_kembali)->isoFormat('D MMM YYYY') }}</span>
                                        @endif
                                        <span class="inline-block mt-2 px-2.5 py-1 bg-white border border-slate-200 rounded-lg text-[10px] text-elevate-primary font-bold w-fit shadow-sm">
                                            {{ $spt->lama_hari }} Hari Tugas
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 align-top">
                                    <div class="text-[10px] uppercase font-bold text-slate-400 mb-1">Maksud Penugasan:</div>
                                    <p class="text-sm text-slate-600 leading-relaxed line-clamp-3 font-medium">
                                        {{ $spt->untuk }}
                                    </p>
                                </td>
                                <td class="px-6 py-5 align-top text-right">
                                    <div class="flex flex-col items-end gap-2">
                                        <a href="{{ route('letters.spt.print', $spt->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary hover:bg-elevate-primary hover:text-white rounded-xl text-xs font-bold transition-all shadow-sm group/btn">
                                            <i class="ph-bold ph-printer text-base group-hover/btn:scale-110 transition-transform"></i> Cetak SPT
                                        </a>
                                        
                                        {{-- Tombol Aksi (Detail, Edit, Hapus) --}}
                                        <div class="flex items-center gap-2 mt-1">
                                            <button type="button" onclick="showDetailModal({{ json_encode($spt) }})" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-sky-600 hover:border-sky-200 hover:bg-sky-50 hover:shadow-sm transition-all" title="Lihat Detail">
                                                <i class="ph-bold ph-eye text-lg"></i>
                                            </button>
                                            <a href="{{ route('letters.spt.edit', $spt->id) }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-amber-600 hover:border-amber-200 hover:bg-amber-50 hover:shadow-sm transition-all" title="Edit">
                                                <i class="ph-bold ph-pencil-simple text-lg"></i>
                                            </a>
                                            <button type="button" onclick="confirmDelete('{{ $spt->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-200 text-slate-400 hover:text-rose-600 hover:border-rose-200 hover:bg-rose-50 hover:shadow-sm transition-all" title="Hapus">
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
                                        <i class="ph-duotone ph-paper-plane-tilt text-4xl"></i>
                                    </div>
                                    <h3 class="text-elevate-dark font-bold text-lg">Data Tidak Ditemukan</h3>
                                    <p class="text-slate-500 text-sm mt-1 mb-6">Belum ada SPT atau hasil pencarian Anda tidak cocok.</p>
                                    <a href="{{ route('letters.spt.create') }}" class="inline-flex items-center gap-2 px-6 py-3.5 bg-elevate-dark text-white rounded-xl font-bold text-sm hover:bg-elevate-primary transition-colors shadow-lg shadow-elevate-dark/20">
                                        <i class="ph-bold ph-plus"></i> Buat SPT Baru
                                    </a>
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

    {{-- MODAL DETAIL SPT --}}
    <div id="detailModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity opacity-0" id="modalBackdrop" onclick="closeDetailModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto custom-scrollbar">
            {{-- Menggunakan items-start dan padding besar (py-16) agar modal tidak terpotong --}}
            <div class="flex min-h-full items-start justify-center p-4 py-16 sm:p-6 sm:py-24 text-center">
                <div id="modalPanel" class="relative transform overflow-hidden rounded-[2.5rem] bg-white text-left shadow-2xl transition-all w-full max-w-2xl border border-slate-100 opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95 duration-300">
                    
                    {{-- Modal Header --}}
                    <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-6 text-white relative overflow-hidden">
                        <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                            <i class="ph-fill ph-briefcase"></i>
                        </div>
                        <div class="flex justify-between items-center relative z-10">
                            <div>
                                <h3 class="text-xl font-black flex items-center gap-2">
                                    <i class="ph-duotone ph-info text-elevate-accent"></i> Detail Surat Perintah Tugas
                                </h3>
                                <p class="text-elevate-accent text-sm font-medium mt-1 flex items-center gap-2">
                                    Nomor: <span id="modal_nomor" class="font-mono bg-white/10 px-2 rounded font-bold"></span>
                                </p>
                            </div>
                            <button onclick="closeDetailModal()" class="w-10 h-10 rounded-full bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                                <i class="ph-bold ph-x text-lg"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Tempat Tujuan</span>
                                <span id="modal_tempat" class="font-bold text-elevate-dark text-sm flex items-center gap-1.5"><i class="ph-fill ph-map-pin text-rose-500"></i> <span id="modal_tempat_text"></span></span>
                            </div>
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Waktu Penugasan (<span id="modal_lama" class="text-elevate-primary"></span>)</span>
                                <span id="modal_waktu" class="font-bold text-elevate-dark text-sm"></span>
                            </div>
                        </div>

                        {{-- Section Pegawai Ditugaskan --}}
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 mb-6">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-3"><i class="ph-bold ph-users"></i> Pegawai Yang Ditugaskan</span>
                            <div id="modal_pegawai" class="flex flex-wrap gap-2">
                                <!-- Data pegawai masuk via JS -->
                            </div>
                        </div>
                        
                        {{-- Section Perihal/Maksud --}}
                        <div class="bg-slate-50 p-5 rounded-2xl border border-slate-100 mb-6">
                            <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Maksud Penugasan / Untuk</span>
                            <p id="modal_untuk" class="text-sm font-medium text-slate-700 leading-relaxed"></p>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="pt-6 border-t border-slate-100 flex items-center justify-between">
                            <a href="#" id="modal_btn_cetak" target="_blank" class="inline-flex items-center gap-2 px-6 py-3 bg-elevate-dark text-white rounded-xl font-bold text-sm hover:bg-elevate-primary transition-colors shadow-lg shadow-elevate-dark/20">
                                <i class="ph-bold ph-printer"></i> Cetak Dokumen SPT
                            </a>
                            <button onclick="closeDetailModal()" class="px-6 py-3 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition-colors">
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
                timerProgressBar: true, customClass: { popup: 'rounded-[1.5rem]' }
            });
            Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
        @endif

        // Konfirmasi Hapus
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus SPT?', text: "Data penugasan ini akan dihapus permanen.",
                icon: 'warning', showCancelButton: true,
                confirmButtonColor: '#e11d48', cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!', cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
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
                        <span class="inline-flex items-center gap-1.5 bg-white border border-slate-200 shadow-sm rounded-lg px-3 py-1.5 text-xs font-bold text-slate-700">
                            <i class="ph-fill ph-user text-elevate-primary"></i> ${user.name}
                        </span>
                    `;
                });
            } else {
                pegawaiHtml = '<span class="text-rose-500 italic text-xs font-medium">Pegawai belum ditugaskan.</span>';
            }
            
            // Cek jika ada pengikut luar
            if (spt.pengikut && Array.isArray(spt.pengikut) && spt.pengikut.length > 0) {
                pegawaiHtml += `
                    <span class="inline-flex items-center gap-1.5 bg-slate-100 border border-slate-200 rounded-lg px-3 py-1.5 text-xs font-bold text-slate-500">
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