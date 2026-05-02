<x-app-layout>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-700 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <a href="{{ route('letters.outgoing.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar Surat Keluar
            </a>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                {{-- Header Tema Elevate --}}
                <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-right"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-paper-plane-tilt text-elevate-accent"></i> 
                        Registrasi Surat Keluar
                    </h2>
                    <p class="text-elevate-accent text-sm font-medium relative z-10 mt-1">Buat surat dinas keluar dan integrasikan dengan SPT/SPPD jika diperlukan.</p>
                </div>

                <div class="p-8">
                    <form id="form-create-keluar" action="{{ route('letters.outgoing.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf

                        <!-- SECTION 1: IDENTITAS -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Identitas Surat Keluar
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor Agenda</label>
                                    <input type="text" name="nomor_agenda" value="{{ old('nomor_agenda', $nextAgendaKeluar) }}" readonly class="w-full px-4 rounded-2xl border-slate-200 bg-slate-100 shadow-inner focus:outline-none text-sm py-3 font-bold text-slate-500 cursor-not-allowed transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor Surat</label>
                                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat') }}" required placeholder="005/SMA/2026..." class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tujuan / Penerima Surat</label>
                                    <input type="text" name="tujuan_surat" value="{{ old('tujuan_surat') }}" required placeholder="Contoh: Kepala Dinas Pendidikan / Wali Murid" class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: WAKTU & PERIHAL -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                Waktu & Perihal
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" value="{{ old('tgl_surat', date('Y-m-d')) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Sifat Surat</label>
                                    <select name="sifat_surat" required class="w-full px-4 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary text-sm font-bold text-elevate-dark transition-all cursor-pointer">
                                        <option value="Biasa">Biasa</option>
                                        <option value="Penting">Penting</option>
                                        <option value="Segera">Segera</option>
                                    </select>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Perihal</label>
                                    <textarea name="perihal" rows="3" required placeholder="Undangan Rapat, Permohonan Bantuan, dll..." class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary text-sm py-3 font-medium text-elevate-dark transition-all">{{ old('perihal') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: INTEGRASI SPT (MAGIC FEATURE) -->
                        <div class="p-6 bg-elevate-soft/50 rounded-[2rem] border border-elevate-accent/20 relative overflow-hidden group hover:border-elevate-accent/50 transition-colors">
                            <div class="flex items-start justify-between gap-4 relative z-10">
                                <div>
                                    <h3 class="text-sm font-black text-elevate-primary uppercase tracking-wider mb-1 flex items-center gap-2">
                                        <i class="ph-fill ph-briefcase text-elevate-primary text-lg"></i>
                                        Integrasi Penugasan (SPT/SPPD)
                                    </h3>
                                    <p class="text-xs text-elevate-dark/70 font-medium pl-6">Aktifkan jika surat ini menugaskan Guru/Staf untuk dinas luar.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer mt-1">
                                    <input type="checkbox" name="is_penugasan" id="toggle_penugasan" class="sr-only peer" onchange="toggleSPTFields()">
                                    <div class="w-14 h-7 bg-slate-300 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-elevate-primary shadow-inner"></div>
                                </label>
                            </div>

                            <div id="spt_fields" class="mt-6 pl-6 pr-2 hidden relative z-10 border-t border-elevate-accent/20 pt-5">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-elevate-dark uppercase mb-2 ml-1">Pilih Pegawai (Tahan CTRL untuk pilih > 1)</label>
                                        <select name="guru_ditugaskan[]" multiple class="w-full px-4 rounded-2xl border-elevate-accent/30 bg-white shadow-sm focus:border-elevate-primary text-sm py-3 font-medium text-elevate-dark h-32 transition-all">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-dark uppercase mb-2 ml-1">Tgl Keberangkatan</label>
                                            <input type="date" name="tgl_berangkat" class="w-full px-4 rounded-2xl border-elevate-accent/30 bg-white shadow-sm focus:border-elevate-primary text-sm py-3 font-bold text-elevate-dark">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-elevate-dark uppercase mb-2 ml-1">Tgl Kembali</label>
                                            <input type="date" name="tgl_kembali" class="w-full px-4 rounded-2xl border-elevate-accent/30 bg-white shadow-sm focus:border-elevate-primary text-sm py-3 font-bold text-elevate-dark">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: LAMPIRAN -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">4</span>
                                Lampiran Digital
                            </h3>
                            <div class="pl-9">
                                <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-accent/10 file:text-elevate-primary hover:file:bg-elevate-accent/20 transition-all cursor-pointer border border-dashed border-slate-300 bg-white shadow-sm rounded-2xl py-3 px-4 focus:border-elevate-primary focus:ring-elevate-primary">
                            </div>
                        </div>

                        <div class="pt-6 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <button type="button" onclick="confirmSubmit(event)" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Surat Keluar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleSPTFields() {
            const checkBox = document.getElementById('toggle_penugasan');
            const sptFields = document.getElementById('spt_fields');
            const inputs = sptFields.querySelectorAll('select, input[type="date"]');
            
            if (checkBox.checked) {
                sptFields.style.display = 'block';
                sptFields.classList.remove('hidden');
                inputs.forEach(input => input.required = true);
            } else {
                sptFields.style.display = 'none';
                sptFields.classList.add('hidden');
                inputs.forEach(input => {
                    input.required = false;
                    input.value = '';
                });
            }
        }

        @if ($errors->any())
            Swal.fire({
                icon: 'error', title: 'Validasi Gagal!', 
                text: 'Silakan periksa kembali inputan Anda.',
                confirmButtonColor: '#e11d48', confirmButtonText: 'Baik'
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
                reverseButtons: true, customClass: {
                    confirmButton: 'bg-elevate-dark text-white px-6 py-3 rounded-xl font-bold hover:bg-elevate-primary mx-2 shadow-lg shadow-elevate-dark/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 mx-2'
                }, buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan...', showConfirmButton: false, didOpen: () => Swal.showLoading() });
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>