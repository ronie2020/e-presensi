<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('letters.spt.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#56bbf1] mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-[#031d3d] via-[#0b2545] to-[#134074] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#56bbf1]/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-pencil-simple text-[#56bbf1]"></i> Perbarui SPT
                    </h2>
                    <p class="text-slate-300 text-sm font-medium relative z-10 mt-1">
                        Edit rincian penugasan: <span class="text-[#56bbf1] font-mono bg-[#56bbf1]/10 px-2 rounded font-bold">{{ $spt->nomor_spt }}</span>
                    </p>
                </div>

                {{-- Form Content --}}
                <div class="p-6 sm:p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5 text-rose-400"></i>
                            <div>
                                <strong class="font-bold block mb-1 text-rose-300">Periksa kembali inputan Anda!</strong>
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
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">1</span>
                                Identitas & Dasar SPT
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Nomor SPT</label>
                                    <input type="text" name="nomor_spt" value="{{ old('nomor_spt', $spt->nomor_spt) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Dasar Surat (Surat Masuk)</label>
                                    <div class="relative">
                                        <select name="letter_incoming_id" class="w-full pl-4 pr-10 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white appearance-none transition-all cursor-pointer">
                                            <option value="" class="bg-slate-900 text-white">-- Tanpa Dasar Surat --</option>
                                            @foreach($incoming_letters as $letter)
                                                <option value="{{ $letter->id }}" class="bg-slate-900 text-white" {{ old('letter_incoming_id', $spt->letter_incoming_id) == $letter->id ? 'selected' : '' }}>
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
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">2</span>
                                Lokasi & Waktu Penugasan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tempat Tujuan</label>
                                    <input type="text" name="tempat" value="{{ old('tempat', $spt->tempat_tujuan) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all" placeholder="Contoh: Kantor Dinas Pendidikan...">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tanggal Berangkat</label>
                                    <input type="date" name="tgl_berangkat" value="{{ old('tgl_berangkat', $spt->tgl_berangkat) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-300 uppercase mb-2 ml-1">Tanggal Kembali</label>
                                    <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali', $spt->tgl_kembali) }}" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: PERSONIL -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">3</span>
                                Pegawai yang Ditugaskan
                            </h3>
                            <div>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 max-h-60 overflow-y-auto p-4 bg-slate-900/80 rounded-2xl border border-white/10 custom-scrollbar shadow-inner">
                                    @php $selectedUsers = $spt->users->pluck('id')->toArray(); @endphp
                                    @foreach($users as $user)
                                        <label class="flex items-center gap-3 p-3 rounded-xl border border-white/5 hover:border-[#56bbf1]/30 hover:bg-[#56bbf1]/10 transition-all cursor-pointer group/item">
                                            <input type="checkbox" name="pegawai_ids[]" value="{{ $user->id }}" 
                                                class="rounded-md border-white/20 bg-slate-800 text-[#56bbf1] focus:ring-[#56bbf1] w-5 h-5 transition-all"
                                                {{ in_array($user->id, old('pegawai_ids', $selectedUsers)) ? 'checked' : '' }}>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-bold text-white group-hover/item:text-[#56bbf1]">{{ $user->name }}</span>
                                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">{{ $user->nip ?? 'NIP -' }}</span>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                <p class="text-[10px] text-slate-400 mt-3 ml-1 flex items-center gap-1 font-medium italic">
                                    <i class="ph-fill ph-info text-[#56bbf1]"></i> Pilih satu atau lebih pegawai yang akan menjalankan tugas.
                                </p>
                            </div>
                        </div>

                        <!-- SECTION 4: PERIHAL -->
                        <div class="p-6 bg-white/[0.03] rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/30 transition-colors">
                            <h3 class="text-sm font-black text-[#56bbf1] uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] border border-[#56bbf1]/30 rounded-full w-7 h-7 flex items-center justify-center text-xs font-bold">4</span>
                                Maksud & Tujuan (Untuk)
                            </h3>
                            <div>
                                <textarea name="untuk" rows="4" required class="w-full px-4 py-3 rounded-2xl border border-white/10 bg-slate-900/60 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-medium text-white transition-all" placeholder="Tuliskan detail perintah tugas di sini...">{{ old('untuk', $spt) ? $spt->untuk : '' }}</textarea>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-6 border-t border-white/10 flex items-center justify-end gap-4">
                            <a href="{{ route('letters.spt.index') }}" class="px-6 py-3.5 rounded-xl text-slate-400 font-bold text-sm bg-slate-800/80 hover:bg-slate-700 hover:text-white transition-colors border border-white/10">
                                Batal
                            </a>
                            <button type="button" onclick="confirmSubmit(event)" class="px-8 py-3.5 bg-gradient-to-r from-[#56bbf1] to-blue-600 hover:brightness-110 text-white font-bold rounded-xl shadow-lg shadow-[#56bbf1]/20 transition-all transform active:scale-95 flex items-center gap-2 group">
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
                background: '#021124', color: '#fff',
                customClass: {
                    popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                    confirmButton: 'bg-gradient-to-r from-[#56bbf1] to-blue-600 text-white px-6 py-3 rounded-xl font-bold hover:brightness-110 mx-2 shadow-lg shadow-[#56bbf1]/20',
                    cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 mx-2 border border-white/10'
                }, 
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ 
                        title: 'Memproses Data...', 
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        showConfirmButton: false, 
                        background: '#021124', color: '#fff',
                        didOpen: () => Swal.showLoading() 
                    });
                    form.submit();
                }
            });
        }
    </script>
</x-app-layout>