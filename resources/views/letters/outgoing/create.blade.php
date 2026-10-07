<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6 font-sans text-slate-100 pb-10">
        
        <a href="{{ route('letters.outgoing.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#56bbf1] transition-colors group">
            <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar Surat Keluar
        </a>

            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                
                {{-- Header --}}
                <div class="bg-gradient-to-r from-[#031d3d] via-[#0b2545] to-[#134074] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#56bbf1]/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-right"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-paper-plane-tilt text-[#56bbf1]"></i> 
                        Registrasi Surat Keluar
                    </h2>
                    <p class="text-slate-300 text-sm font-medium relative z-10 mt-1">Buat surat dinas keluar dan integrasikan dengan SPT/SPPD jika diperlukan.</p>
                </div>

                <div class="p-6 sm:p-8">
                    <form id="form-create-keluar" action="{{ route('letters.outgoing.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- SECTION 1: IDENTITAS -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">1</span>
                                Identitas Surat Keluar
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <div class="flex items-center justify-between mb-2 ml-1">
                                        <label class="block text-xs font-bold text-slate-300 uppercase">Nomor Agenda (Tahun Ini)</label>
                                        <span class="text-[10px] text-slate-400 font-mono">Reset per tahun</span>
                                    </div>
                                    <input type="text" id="nomor_agenda" name="nomor_agenda" value="{{ old('nomor_agenda', $nextAgendaKeluar) }}" readonly class="w-full px-4 py-3 rounded-2xl border border-white/5 bg-slate-900/40 text-slate-400 text-sm font-bold cursor-not-allowed">
                                    <p class="text-[11px] text-slate-500 mt-1.5 ml-1">Urutan agenda dinas pada tahun berjalan.</p>
                                </div>
                                <div>
                                    <div class="flex items-center justify-between mb-2 ml-1">
                                        <label class="block text-xs font-bold text-slate-300 uppercase">Nomor Surat</label>
                                        <div class="flex items-center gap-2">
                                            <span id="badge-mode-nomor" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/15 border border-sky-500/30 text-sky-300">
                                                <i class="ph-bold ph-magic-wand"></i> Otomatis
                                            </span>
                                            <button type="button" id="btn-toggle-nomor-manual" onclick="toggleNomorSuratManual()" class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[11px] font-bold bg-white/10 hover:bg-white/20 text-slate-200 border border-white/10 transition-all hover:scale-105 active:scale-95" title="Klik untuk mengedit nomor surat secara manual">
                                                <i class="ph-bold ph-pencil-simple text-amber-400"></i> Edit Manual
                                            </button>
                                        </div>
                                    </div>
                                    <div class="relative">
                                        <input type="text" id="nomor_surat" name="nomor_surat" value="{{ old('nomor_surat', $defaultNomorSurat) }}" required readonly class="w-full pl-4 pr-11 py-3 rounded-2xl border border-sky-500/40 bg-slate-900/80 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-sky-200 transition-all cursor-not-allowed">
                                        <div id="icon-lock-nomor" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-sky-400 pointer-events-none" title="Nomor Terkunci Otomatis">
                                            <i class="ph-bold ph-lock-simple text-base"></i>
                                        </div>
                                    </div>
                                    
                                    {{-- Quick format chips & helper info --}}
                                    <div class="mt-2.5 space-y-1.5">
                                        <p id="hint-nomor-surat" class="text-[11px] text-slate-400 leading-relaxed">
                                            <i class="ph-bold ph-info text-[#56bbf1]"></i> Otomatis: urut dari <span class="text-sky-300 font-bold">01</span> sampai akhir tahun. Reset kembali ke <span class="text-sky-300 font-bold">01</span> di tahun baru.
                                        </p>
                                        <div class="flex flex-wrap items-center gap-1.5 pt-1">
                                            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Format Cepat:</span>
                                            <button type="button" onclick="setQuickFormat('421.3')" class="px-2 py-0.5 rounded-md bg-slate-800 hover:bg-sky-500/20 text-slate-300 hover:text-sky-300 border border-white/10 text-[10px] font-bold transition-all">
                                                421.3 (Dinas)
                                            </button>
                                            <button type="button" onclick="setQuickFormat('094')" class="px-2 py-0.5 rounded-md bg-slate-800 hover:bg-sky-500/20 text-slate-300 hover:text-sky-300 border border-white/10 text-[10px] font-bold transition-all">
                                                094 (SPT)
                                            </button>
                                            <button type="button" onclick="setQuickFormatOnlyNumber()" class="px-2 py-0.5 rounded-md bg-slate-800 hover:bg-sky-500/20 text-slate-300 hover:text-sky-300 border border-white/10 text-[10px] font-bold transition-all">
                                                Nomor Saja ({{ $nextSequence }})
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tujuan / Penerima Surat</label>
                                    <input type="text" name="tujuan_surat" value="{{ old('tujuan_surat') }}" required placeholder="Contoh: Kepala Dinas Pendidikan / Wali Murid" class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all placeholder:text-slate-500">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: WAKTU & PERIHAL -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">2</span>
                                Waktu & Perihal
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" value="{{ old('tgl_surat', date('Y-m-d')) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Sifat Surat</label>
                                    <select name="sifat_surat" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all cursor-pointer">
                                        <option value="Biasa" class="bg-slate-900 text-white">Biasa</option>
                                        <option value="Penting" class="bg-slate-900 text-white">Penting</option>
                                        <option value="Segera" class="bg-slate-900 text-white">Segera</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Perihal</label>
                                    <textarea name="perihal" rows="3" required placeholder="Undangan Rapat, Permohonan Bantuan, dll..." class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-medium text-white transition-all placeholder:text-slate-500">{{ old('perihal') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: INTEGRASI SPT (MAGIC FEATURE) -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-[#56bbf1]/30 relative overflow-hidden group hover:border-[#56bbf1]/50 transition-colors">
                            <div class="flex items-start justify-between gap-4 relative z-10">
                                <div>
                                    <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-1 flex items-center gap-2">
                                        <i class="ph-fill ph-briefcase text-[#56bbf1] text-lg"></i>
                                        Integrasi Penugasan (SPT/SPPD)
                                    </h3>
                                    <p class="text-xs text-slate-300 font-medium">Aktifkan jika surat ini menugaskan Guru/Staf untuk dinas luar.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer mt-1">
                                    <input type="checkbox" name="is_penugasan" id="toggle_penugasan" class="sr-only peer" onchange="toggleSPTFields()">
                                    <div class="w-14 h-7 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#56bbf1] shadow-inner"></div>
                                </label>
                            </div>

                            <div id="spt_fields" class="mt-6 hidden relative z-10 border-t border-white/10 pt-5">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Pilih Pegawai (Tahan CTRL untuk pilih > 1)</label>
                                        <select name="guru_ditugaskan[]" multiple class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-medium h-36 transition-all">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" class="py-1 px-2 hover:bg-[#56bbf1]/20">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tgl Keberangkatan</label>
                                            <input type="date" name="tgl_berangkat" class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white [color-scheme:dark]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tgl Kembali</label>
                                            <input type="date" name="tgl_kembali" class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white [color-scheme:dark]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: LAMPIRAN -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">4</span>
                                Lampiran Digital
                            </h3>
                            <div>
                                <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#56bbf1]/20 file:text-[#56bbf1] hover:file:bg-[#56bbf1]/30 transition-all cursor-pointer border border-dashed border-white/20 bg-slate-900/60 rounded-2xl py-3 px-4 hover:border-[#56bbf1]">
                            </div>
                        </div>

                        <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-4">
                            <a href="{{ route('letters.outgoing.index') }}" class="px-6 py-3.5 rounded-xl text-slate-400 font-bold text-sm bg-slate-800/80 hover:bg-slate-700 hover:text-white transition-colors border border-white/10">Batal</a>
                            <button type="button" onclick="confirmSubmit(event)" class="px-8 py-3.5 bg-gradient-to-r from-[#56bbf1] to-blue-600 hover:brightness-110 text-white font-bold rounded-xl shadow-lg shadow-[#56bbf1]/20 transition-all transform active:scale-95 flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Surat Keluar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
    </div>

    <script>
        let isManualNomor = {{ old('nomor_surat') ? 'true' : 'false' }};
        let currentSequence = '{{ $nextSequence }}';
        let currentKode = '421.3';

        document.addEventListener('DOMContentLoaded', function() {
            if (isManualNomor) {
                enableManualMode(false);
            }

            // Listener saat tanggal surat diubah
            const tglInput = document.querySelector('input[name="tgl_surat"]');
            if (tglInput) {
                tglInput.addEventListener('change', function() {
                    if (!isManualNomor) {
                        fetchGeneratedNumber();
                    }
                });
            }
        });

        function toggleNomorSuratManual() {
            if (!isManualNomor) {
                enableManualMode(true);
            } else {
                disableManualMode();
            }
        }

        function enableManualMode(focus = true) {
            isManualNomor = true;
            const input = document.getElementById('nomor_surat');
            const badge = document.getElementById('badge-mode-nomor');
            const btn = document.getElementById('btn-toggle-nomor-manual');
            const iconLock = document.getElementById('icon-lock-nomor');
            const hint = document.getElementById('hint-nomor-surat');

            input.readOnly = false;
            input.classList.remove('cursor-not-allowed', 'text-sky-200', 'border-sky-500/40');
            input.classList.add('text-white', 'border-amber-500/60', 'bg-slate-900');

            badge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-500/15 border border-amber-500/30 text-amber-300';
            badge.innerHTML = '<i class="ph-bold ph-pencil-simple"></i> Mode Manual';

            btn.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[11px] font-bold bg-sky-500/20 hover:bg-sky-500/30 text-sky-300 border border-sky-500/30 transition-all hover:scale-105 active:scale-95';
            btn.innerHTML = '<i class="ph-bold ph-arrows-clockwise text-sky-400"></i> Reset Otomatis';

            iconLock.innerHTML = '<i class="ph-bold ph-lock-simple-open text-base text-amber-400"></i>';
            hint.innerHTML = '<i class="ph-bold ph-pencil-simple text-amber-400"></i> <strong>Mode manual aktif</strong>: Anda bebas mengedit atau menyesuaikan nomor surat sesuai kebutuhan.';

            if (focus) {
                input.focus();
                input.select();
            }
        }

        function disableManualMode() {
            isManualNomor = false;
            const input = document.getElementById('nomor_surat');
            const badge = document.getElementById('badge-mode-nomor');
            const btn = document.getElementById('btn-toggle-nomor-manual');
            const iconLock = document.getElementById('icon-lock-nomor');
            const hint = document.getElementById('hint-nomor-surat');

            input.readOnly = true;
            input.classList.add('cursor-not-allowed', 'text-sky-200', 'border-sky-500/40');
            input.classList.remove('text-white', 'border-amber-500/60');

            badge.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-500/15 border border-sky-500/30 text-sky-300';
            badge.innerHTML = '<i class="ph-bold ph-magic-wand"></i> Otomatis';

            btn.className = 'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-lg text-[11px] font-bold bg-white/10 hover:bg-white/20 text-slate-200 border border-white/10 transition-all hover:scale-105 active:scale-95';
            btn.innerHTML = '<i class="ph-bold ph-pencil-simple text-amber-400"></i> Edit Manual';

            iconLock.innerHTML = '<i class="ph-bold ph-lock-simple text-base text-sky-400"></i>';
            hint.innerHTML = '<i class="ph-bold ph-info text-[#56bbf1]"></i> Otomatis: urut dari <span class="text-sky-300 font-bold">01</span> sampai akhir tahun. Reset kembali ke <span class="text-sky-300 font-bold">01</span> di tahun baru.';

            fetchGeneratedNumber();
        }

        function fetchGeneratedNumber() {
            const tglInput = document.querySelector('input[name="tgl_surat"]');
            const isPenugasan = document.getElementById('toggle_penugasan')?.checked ? true : false;
            const tgl = tglInput ? tglInput.value : '';

            fetch(`{{ route('letters.outgoing.generate-number') }}?date=${tgl}&is_penugasan=${isPenugasan}&kode=${currentKode}`)
                .then(res => res.json())
                .then(data => {
                    if (data) {
                        currentSequence = data.formatted_sequence;
                        document.getElementById('nomor_agenda').value = data.nomor_agenda;
                        if (!isManualNomor) {
                            document.getElementById('nomor_surat').value = data.nomor_surat;
                        }
                    }
                })
                .catch(err => console.error('Gagal generate nomor surat otomatis:', err));
        }

        function setQuickFormat(kode) {
            currentKode = kode;
            if (isManualNomor) {
                disableManualMode();
            } else {
                fetchGeneratedNumber();
            }
        }

        function setQuickFormatOnlyNumber() {
            enableManualMode(true);
            document.getElementById('nomor_surat').value = currentSequence;
        }

        function toggleSPTFields() {
            const checkBox = document.getElementById('toggle_penugasan');
            const sptFields = document.getElementById('spt_fields');
            const inputs = sptFields.querySelectorAll('select, input[type="date"]');
            
            if (checkBox.checked) {
                sptFields.style.display = 'block';
                sptFields.classList.remove('hidden');
                inputs.forEach(input => input.required = true);
                if (!isManualNomor) {
                    currentKode = '094';
                    fetchGeneratedNumber();
                }
            } else {
                sptFields.style.display = 'none';
                sptFields.classList.add('hidden');
                inputs.forEach(input => {
                    input.required = false;
                    input.value = '';
                });
                if (!isManualNomor) {
                    currentKode = '421.3';
                    fetchGeneratedNumber();
                }
            }
        }

        @if ($errors->any())
            Swal.fire({
                icon: 'error', title: 'Validasi Gagal!', 
                text: 'Silakan periksa kembali inputan Anda.',
                confirmButtonColor: '#e11d48', confirmButtonText: 'Baik',
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl' }
            });
        @endif

        function confirmSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('form-create-keluar');
            const isPenugasan = document.getElementById('toggle_penugasan').checked;
            
            if (!form.checkValidity()) { form.reportValidity(); return; }

            let textInfo = isPenugasan ? "Sistem juga otomatis membuat Draf SPT dan SPPD untuk pegawai yang dipilih." : "Menyimpan data Surat Keluar ini ke dalam arsip.";

            Swal.fire({
                title: 'Simpan Surat Keluar?', text: textInfo, icon: 'info',
                showCancelButton: true, confirmButtonText: 'Ya, Simpan!', cancelButtonText: 'Batal',
                reverseButtons: true, background: '#021124', color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    confirmButton: 'bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:brightness-110 mx-2 shadow-lg shadow-[#56bbf1]/20',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 mx-2 border border-white/10'
                }, buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', allowOutsideClick: false, showConfirmButton: false, background: '#021124', color: '#fff', didOpen: () => Swal.showLoading() });
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>