<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('letters.incoming.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#56bbf1] mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-[#031d3d] via-[#0b2545] to-[#134074] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#56bbf1]/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-pencil-simple-slash"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-pencil-simple-slash text-[#56bbf1]"></i> Perbarui Data Surat
                    </h2>
                    <p class="text-slate-300 text-sm font-medium relative z-10 mt-1">
                        Edit informasi surat masuk: <span class="text-[#56bbf1] font-mono bg-[#56bbf1]/10 px-2 rounded font-bold">#{{ $letter->nomor_agenda }}</span>
                    </p>
                </div>

                {{-- Form Content --}}
                <div class="p-6 sm:p-8">
                    <form id="form-edit-surat" action="{{ route('letters.incoming.update', $letter->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- SECTION 1: IDENTITAS SURAT -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">1</span>
                                Identitas Surat
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Nomor Agenda</label>
                                    <input type="text" name="nomor_agenda" value="{{ old('nomor_agenda', $letter->nomor_agenda) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Sifat Surat</label>
                                    <div class="relative">
                                        <select name="sifat_surat" required class="w-full pl-4 pr-10 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white appearance-none transition-all cursor-pointer">
                                            <option value="Biasa" class="bg-slate-900 text-white" {{ old('sifat_surat', $letter->sifat_surat) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                            <option value="Penting" class="bg-slate-900 text-white" {{ old('sifat_surat', $letter->sifat_surat) == 'Penting' ? 'selected' : '' }}>Penting</option>
                                            <option value="Segera" class="bg-slate-900 text-white" {{ old('sifat_surat', $letter->sifat_surat) == 'Segera' ? 'selected' : '' }}>Segera</option>
                                            <option value="Rahasia" class="bg-slate-900 text-white" {{ old('sifat_surat', $letter->sifat_surat) == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Nomor Surat Asli</label>
                                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $letter->nomor_surat) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Asal / Pengirim Surat</label>
                                    <input type="text" name="asal_surat" value="{{ old('asal_surat', $letter->asal_surat) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL WAKTU & ISI -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">2</span>
                                Detail Waktu & Isi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" value="{{ old('tgl_surat', \Carbon\Carbon::parse($letter->tgl_surat)->format('Y-m-d')) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tanggal Diterima</label>
                                    <input type="date" name="tgl_diterima" value="{{ old('tgl_diterima', \Carbon\Carbon::parse($letter->tgl_diterima)->format('Y-m-d')) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Perihal / Maksud Surat</label>
                                    <textarea name="perihal" rows="3" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-medium text-white transition-all">{{ old('perihal', $letter->perihal) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: INTEGRASI SPT (MAGIC FEATURE) -->
                        @php
                            $hasSpt = $letter->spt ? true : false;
                            $sptUsers = $hasSpt ? $letter->spt->users->pluck('id')->toArray() : [];
                        @endphp
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-[#56bbf1]/30 relative overflow-hidden group hover:border-[#56bbf1]/50 transition-colors">
                            <div class="flex items-start justify-between gap-4 relative z-10">
                                <div>
                                    <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-1 flex items-center gap-2">
                                        <i class="ph-fill ph-briefcase text-[#56bbf1] text-lg"></i>
                                        Integrasi Penugasan (SPT/SPPD)
                                    </h3>
                                    <p class="text-xs text-slate-300 font-medium">Aktifkan untuk memperbarui atau menambahkan penugasan dinas luar dari surat ini.</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer mt-1">
                                    <input type="checkbox" name="is_penugasan" id="toggle_penugasan" class="sr-only peer" onchange="toggleSPTFields()" {{ (old('is_penugasan') == 'on' || $hasSpt) ? 'checked' : '' }}>
                                    <div class="w-14 h-7 bg-slate-700 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-[#56bbf1] shadow-inner"></div>
                                </label>
                            </div>

                            <div id="spt_fields" class="mt-6 {{ (old('is_penugasan') == 'on' || $hasSpt) ? '' : 'hidden' }} relative z-10 border-t border-white/10 pt-5">
                                <div class="grid grid-cols-1 gap-6">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Pilih Pegawai (Tahan CTRL untuk pilih > 1)</label>
                                        <select name="guru_ditugaskan[]" multiple class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/80 text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-medium h-36 transition-all">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" class="py-1 px-2 hover:bg-[#56bbf1]/20" {{ in_array($user->id, old('guru_ditugaskan', $sptUsers)) ? 'selected' : '' }}>{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tgl Keberangkatan</label>
                                            <input type="date" name="tgl_berangkat" value="{{ old('tgl_berangkat', $letter->spt->tgl_berangkat ?? '') }}" class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white [color-scheme:dark]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tgl Kembali</label>
                                            <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali', $letter->spt->tgl_kembali ?? '') }}" class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white [color-scheme:dark]">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECTION 4: LAMPIRAN -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">4</span>
                                Lampiran (Opsional)
                            </h3>
                            <div>
                                @if($letter->file_path)
                                    <div class="mb-4 p-3.5 bg-slate-900/80 border border-white/10 rounded-xl flex items-center justify-between shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-[#56bbf1]/20 text-[#56bbf1] flex items-center justify-center">
                                                <i class="ph-fill ph-file-text text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">File Saat Ini</p>
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="text-xs font-bold text-[#56bbf1] hover:underline flex items-center gap-1">
                                                    <span>Lihat Lampiran</span>
                                                    <i class="ph-bold ph-arrow-square-out"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Update File Surat</label>
                                    <input type="file" name="file_surat" class="w-full text-sm text-slate-300 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#56bbf1]/20 file:text-[#56bbf1] hover:file:bg-[#56bbf1]/30 transition-all cursor-pointer border border-dashed border-white/20 bg-slate-900/60 rounded-2xl py-3 px-4 hover:border-[#56bbf1]">
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1 flex items-center gap-1 font-medium">
                                        <i class="ph-fill ph-info text-[#56bbf1]"></i> PDF / JPG / PNG (Max 2MB). Biarkan kosong jika tidak ingin mengubah file.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-4">
                            <a href="{{ route('letters.incoming.index') }}" class="px-6 py-3.5 rounded-xl text-slate-400 font-bold text-sm bg-slate-800/80 hover:bg-slate-700 hover:text-white transition-colors border border-white/10">
                                Batal
                            </a>
                            <button type="button" onclick="confirmSubmit(event)" class="px-8 py-3.5 bg-gradient-to-r from-[#56bbf1] to-blue-600 hover:brightness-110 text-white font-bold rounded-xl shadow-lg shadow-[#56bbf1]/20 transition-all transform active:scale-95 flex items-center gap-2 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Update Data</span>
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
                });
            }
        }

        // Tampilkan error jika validasi controller gagal
        @if ($errors->any())
            let errorMessages = '<div class="text-left"><ul class="list-disc list-inside text-sm font-medium space-y-1 text-slate-300">';
            @foreach ($errors->all() as $error)
                errorMessages += '<li>{{ $error }}</li>';
            @endforeach
            errorMessages += '</ul></div>';

            Swal.fire({
                icon: 'error',
                title: 'Periksa Kembali Inputan Anda!',
                html: errorMessages,
                confirmButtonColor: '#e11d48',
                confirmButtonText: 'Baik, Saya Perbaiki',
                background: '#021124',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors shadow-lg shadow-rose-900/20'
                },
                buttonsStyling: false
            });
        @endif

        function confirmSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('form-edit-surat');
            const isPenugasan = document.getElementById('toggle_penugasan').checked;
            
            if (!form.checkValidity()) { 
                form.reportValidity(); 
                return; 
            }

            let textInfo = isPenugasan ? "Pembaruan ini juga akan menyinkronkan data SPT dan SPPD terkait." : "Pastikan data yang diubah sudah benar.";

            Swal.fire({
                title: 'Perbarui Data Surat?', 
                text: textInfo, 
                icon: 'question',
                showCancelButton: true, 
                confirmButtonText: 'Ya, Update!', 
                cancelButtonText: 'Batal',
                reverseButtons: true, 
                background: '#021124',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    confirmButton: 'bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:brightness-110 mx-2 shadow-lg shadow-[#56bbf1]/20',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 mx-2 border border-white/10'
                }, 
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ 
                        title: 'Memperbarui Data...', 
                        allowOutsideClick: false,
                        showConfirmButton: false, 
                        background: '#021124',
                        color: '#fff',
                        didOpen: () => Swal.showLoading() 
                    });
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>