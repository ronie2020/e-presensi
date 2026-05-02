<x-app-layout>
    {{-- Tambahkan Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('letters.incoming.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                {{-- Card Header Microsoft Elevate --}}
                <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-pencil-simple-slash"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-pencil-simple-slash text-elevate-accent"></i> Perbarui Data Surat
                    </h2>
                    <p class="text-elevate-accent text-sm font-medium relative z-10 mt-1">
                        Edit informasi surat masuk: <span class="text-white font-mono bg-white/10 px-2 rounded font-bold">#{{ $letter->nomor_agenda }}</span>
                    </p>
                </div>

                {{-- Form Content --}}
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-bold block mb-1">Periksa kembali inputan Anda!</strong>
                                <ul class="list-disc list-inside font-medium">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    {{-- Tambahkan ID Form --}}
                    <form id="form-edit-surat" action="{{ route('letters.incoming.update', $letter->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- SECTION 1: IDENTITAS SURAT -->
                        <div class="p-6 bg-elevate-accent/5 rounded-[2rem] border border-elevate-accent/20 relative group hover:border-elevate-accent/40 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Identitas Surat
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor Agenda</label>
                                    <input type="text" name="nomor_agenda" value="{{ old('nomor_agenda', $letter->nomor_agenda) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Sifat Surat</label>
                                    <div class="relative">
                                        <select name="sifat_surat" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark appearance-none transition-all cursor-pointer">
                                            <option value="Biasa" {{ old('sifat_surat', $letter->sifat_surat) == 'Biasa' ? 'selected' : '' }}>Biasa</option>
                                            <option value="Penting" {{ old('sifat_surat', $letter->sifat_surat) == 'Penting' ? 'selected' : '' }}>Penting</option>
                                            <option value="Segera" {{ old('sifat_surat', $letter->sifat_surat) == 'Segera' ? 'selected' : '' }}>Segera</option>
                                            <option value="Rahasia" {{ old('sifat_surat', $letter->sifat_surat) == 'Rahasia' ? 'selected' : '' }}>Rahasia</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor Surat Asli</label>
                                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $letter->nomor_surat) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Asal / Pengirim Surat</label>
                                    <input type="text" name="asal_surat" value="{{ old('asal_surat', $letter->asal_surat) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL WAKTU & ISI -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                Detail Waktu & Isi
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" value="{{ old('tgl_surat', $letter->tgl_surat) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Diterima</label>
                                    <input type="date" name="tgl_diterima" value="{{ old('tgl_diterima', $letter->tgl_diterima) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Perihal / Maksud Surat</label>
                                    <textarea name="perihal" rows="3" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-medium text-elevate-dark transition-all">{{ old('perihal', $letter->perihal) }}</textarea>
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECTION 3: LAMPIRAN -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">3</span>
                                Lampiran (Opsional)
                            </h3>
                            <div class="pl-9">
                                @if($letter->file_path)
                                    <div class="mb-4 p-3 bg-white border border-slate-200 rounded-xl flex items-center justify-between shadow-sm">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-elevate-accent/10 text-elevate-primary flex items-center justify-center">
                                                <i class="ph-fill ph-file-text text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="text-xs font-bold text-elevate-dark uppercase tracking-wider mb-0.5">File Saat Ini</p>
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="text-sm font-bold text-elevate-primary hover:underline">Lihat Lampiran</a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Update File Surat</label>
                                    <div class="relative">
                                        <input type="file" name="file_surat" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-accent/10 file:text-elevate-primary hover:file:bg-elevate-accent/20 transition-all cursor-pointer border border-dashed border-slate-300 bg-white shadow-sm rounded-2xl py-3 px-4 hover:border-elevate-primary">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1 flex items-center gap-1 font-medium">
                                        <i class="ph-fill ph-info text-elevate-primary"></i> Biarkan kosong jika tidak ingin mengubah file.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="{{ route('letters.incoming.index') }}" class="px-6 py-3.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-100 hover:text-slate-700 transition-colors">
                                Batal
                            </a>
                            {{-- Ubah ke button type button untuk trigger SweetAlert --}}
                            <button type="button" onclick="confirmSubmit(event)" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center gap-2 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Update Data</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Alert Konfirmasi --}}
    <script>
        function confirmSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('form-edit-surat');
            
            // Trigger validasi bawaan HTML5 sebelum submit
            if (!form.checkValidity()) { 
                form.reportValidity(); 
                return; 
            }

            Swal.fire({
                title: 'Perbarui Data Surat?', 
                text: 'Pastikan data yang diubah sudah benar.', 
                icon: 'question',
                showCancelButton: true, 
                confirmButtonText: 'Ya, Update!', 
                cancelButtonText: 'Batal',
                reverseButtons: true, 
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                    confirmButton: 'bg-elevate-dark text-white px-6 py-3 rounded-xl font-bold hover:bg-elevate-primary mx-2 shadow-lg shadow-elevate-dark/20',
                    cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 mx-2'
                }, 
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ 
                        title: 'Memperbarui Data...', 
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false, 
                        didOpen: () => Swal.showLoading() 
                    });
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>