<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('letters.spt.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-elevate-primary mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                {{-- Card Header Microsoft Elevate Style --}}
                <div class="bg-gradient-to-r from-elevate-dark to-elevate-primary p-8 text-white relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-elevate-accent/20 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-pencil-simple text-elevate-accent"></i> Perbarui SPT
                    </h2>
                    <p class="text-elevate-accent text-sm font-medium relative z-10 mt-1">
                        Edit rincian penugasan: <span class="text-white font-mono bg-white/10 px-2 rounded font-bold">{{ $spt->nomor_spt }}</span>
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

                    <form id="form-edit-spt" action="{{ route('letters.spt.update', $spt->id) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- SECTION 1: IDENTITAS & DASAR HUKUM -->
                        <div class="p-6 bg-elevate-accent/5 rounded-[2rem] border border-elevate-accent/20 relative group hover:border-elevate-accent/40 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Identitas & Dasar SPT
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPT</label>
                                    <input type="text" name="nomor_spt" value="{{ old('nomor_spt', $spt->nomor_spt) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Dasar Surat (Surat Masuk)</label>
                                    <div class="relative">
                                        <select name="letter_incoming_id" class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark appearance-none transition-all cursor-pointer">
                                            <option value="">-- Tanpa Dasar Surat --</option>
                                            @foreach($incoming_letters as $letter)
                                                <option value="{{ $letter->id }}" {{ old('letter_incoming_id', $spt->letter_incoming_id) == $letter->id ? 'selected' : '' }}>
                                                    {{ $letter->nomor_surat }} - {{ $letter->perihal }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: LOKASI & WAKTU -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                Lokasi & Waktu Penugasan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan</label>
                                    <input type="text" name="tempat" value="{{ old('tempat', $spt->tempat_tujuan) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all" placeholder="Contoh: Kantor Dinas Pendidikan...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Berangkat</label>
                                    <input type="date" name="tgl_berangkat" value="{{ old('tgl_berangkat', $spt->tgl_berangkat) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Kembali</label>
                                    <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali', $spt->tgl_kembali) }}" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-bold text-elevate-dark transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: PERSONIL -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">3</span>
                                Pegawai yang Ditugaskan
                            </h3>
                            <div class="pl-9">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-4 bg-white rounded-2xl border border-slate-200 custom-scrollbar shadow-inner">
                                    @php $selectedUsers = $spt->users->pluck('id')->toArray(); @endphp
                                    @foreach($users as $user)
                                        <label class="flex items-center gap-3 p-3 rounded-xl border border-transparent hover:border-elevate-accent/30 hover:bg-elevate-accent/5 transition-all cursor-pointer group/item">
                                            <input type="checkbox" name="pegawai_ids[]" value="{{ $user->id }}" 
                                                class="rounded-md border-slate-300 text-elevate-primary focus:ring-elevate-primary w-5 h-5 transition-all"
                                                {{ in_array($user->id, old('pegawai_ids', $selectedUsers)) ? 'checked' : '' }}>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-elevate-dark group-hover/item:text-elevate-primary">{{ $user->name }}</span>
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $user->nip ?? 'NIP -' }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-[10px] text-slate-400 mt-3 ml-1 flex items-center gap-1 font-medium italic">
                                    <i class="ph-fill ph-info text-elevate-primary"></i> Pilih satu atau lebih pegawai yang akan menjalankan tugas.
                                </p>
                            </div>
                        </div>

                        <!-- SECTION 4: PERIHAL -->
                        <div class="p-6 bg-slate-50/50 rounded-[2rem] border border-slate-100 relative group hover:border-slate-200 transition-colors">
                            <h3 class="text-sm font-black text-elevate-dark uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-elevate-accent/20 text-elevate-primary rounded-full w-7 h-7 flex items-center justify-center text-xs">4</span>
                                Maksud & Tujuan (Untuk)
                            </h3>
                            <div class="pl-9">
                                <textarea name="untuk" rows="4" required class="w-full px-4 rounded-2xl border-slate-200 bg-white shadow-sm focus:border-elevate-primary focus:ring-elevate-primary text-sm py-3 font-medium text-elevate-dark transition-all" placeholder="Tuliskan detail perintah tugas di sini...">{{ old('untuk', $spt) ? $spt->untuk : '' }}</textarea>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="{{ route('letters.spt.index') }}" class="px-6 py-3.5 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-100 transition-colors">
                                Batal
                            </a>
                            <button type="button" onclick="confirmSubmit(event)" class="px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-xl hover:bg-elevate-primary shadow-lg shadow-elevate-dark/20 transition-all transform active:scale-95 flex items-center gap-2 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Update SPT</span>
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
            const form = document.getElementById('form-edit-spt');
            
            if (!form.checkValidity()) { 
                form.reportValidity(); 
                return; 
            }

            Swal.fire({
                title: 'Simpan Perubahan SPT?', 
                text: 'Pastikan data penugasan sudah sesuai.', 
                icon: 'question',
                showCancelButton: true, 
                confirmButtonText: 'Ya, Simpan!', 
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
                        title: 'Memproses Data...', 
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