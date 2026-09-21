<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('sppd.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#56bbf1] mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            {{-- Card Container (Alpine.js State) --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl overflow-hidden relative"
                 x-data="{ 
                    mode: 'manual',
                    
                    // PERBAIKAN: Menggabungkan data existing dengan helper old()
                    maksud: {{ Js::from(old('maksud', $sppd->maksud_perjalanan)) }},
                    tujuan: {{ Js::from(old('tujuan', $sppd->tempat_tujuan)) }},
                    tanggal_berangkat: {{ Js::from(old('tgl_berangkat', \Carbon\Carbon::parse($sppd->tgl_berangkat)->format('Y-m-d'))) }},
                    tanggal_kembali: {{ Js::from(old('tgl_kembali', \Carbon\Carbon::parse($sppd->tgl_kembali)->format('Y-m-d'))) }},
                    
                    // LIST PENGIKUT (Pre-filled dengan gabungan old data)
                    followers: {{ Js::from(old('followers', $sppd->followers)) }}, 

                    // Data User Lengkap (Untuk Dropdown)
                    allUsers: {{ Js::from($users->map(fn($u) => ['id'=>$u->id, 'name'=>$u->name, 'nip'=>$u->nip])) }},

                    addFollower() {
                        this.followers.push({ nama: '', nip: '', keterangan: '' });
                    },
                    removeFollower(index) {
                        this.followers.splice(index, 1);
                    },
                    fillFollowerName(index, event) {
                        const selectedId = event.target.value;
                        const user = this.allUsers.find(u => u.id == selectedId);
                        if (user) {
                            this.followers[index].nama = user.name;
                            this.followers[index].nip = user.nip; 
                        } else {
                            this.followers[index].nama = '';
                            this.followers[index].nip = '';
                        }
                    }
                 }">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-[#031d3d] to-[#021124] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#56bbf1]/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-pencil-simple-slash"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-pencil-simple-slash text-[#56bbf1]"></i> Edit Perjalanan Dinas
                    </h2>
                    <p class="text-[#56bbf1] text-sm font-medium relative z-10 mt-1">Perbarui data Surat Perjalanan Dinas: <span class="text-white font-mono bg-white/10 px-2 rounded">{{ $sppd->nomor_sppd }}</span></p>
                </div>

                {{-- Form Content --}}
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5 text-rose-400"></i>
                            <div>
                                <strong class="font-bold block mb-1">Periksa kembali inputan Anda!</strong>
                                <ul class="list-disc list-inside text-rose-200">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form id="form-edit-sppd" action="{{ route('sppd.update', $sppd->id) }}" method="POST" class="space-y-8">
                        @csrf
                        @method('PUT')

                        <!-- SECTION 1: DETAIL SPPD -->
                        <div class="p-6 bg-slate-900/60 rounded-[2rem] border border-white/10 relative group hover:border-white/20 transition-colors">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Detail Perjalanan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPPD</label>
                                    <input type="text" value="{{ $sppd->nomor_sppd }}" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900 text-slate-400 font-mono font-bold text-sm py-3 cursor-not-allowed" disabled>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pegawai Pelaksana</label>
                                    <div class="relative">
                                        <select name="pegawai_id" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm font-bold text-white appearance-none transition-all cursor-pointer">
                                            <option value="" class="bg-slate-900 text-slate-300">-- Pilih Pegawai --</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ old('pegawai_id', $sppd->user_id) == $user->id ? 'selected' : '' }} class="bg-slate-900 text-white">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Maksud Perjalanan Dinas</label>
                                    <textarea name="maksud" rows="2" x-model="maksud" required class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-medium text-white transition-all"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan</label>
                                    <input type="text" name="tujuan" x-model="tujuan" required class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-bold text-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Alat Angkutan</label>
                                    <input type="text" name="transportasi" value="{{ old('transportasi', $sppd->alat_angkut) }}" placeholder="Kendaraan Dinas" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-bold text-white placeholder-slate-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Berangkat</label>
                                    <input type="date" name="tgl_berangkat" x-model="tanggal_berangkat" required class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Kembali</label>
                                    <input type="date" name="tgl_kembali" x-model="tanggal_kembali" required class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-bold text-white transition-all [color-scheme:dark]">
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECTION 2: PENGIKUT -->
                        <div class="p-6 bg-slate-900/60 rounded-[2rem] border border-white/10 relative group hover:border-white/20 transition-colors">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                                    <span class="bg-[#56bbf1]/20 text-[#56bbf1] rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                    Pengikut / Peserta Tambahan
                                </h3>
                                <button type="button" @click="addFollower()" class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl text-xs font-bold hover:bg-emerald-500 hover:text-slate-950 transition-colors flex items-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Tambah
                                </button>
                            </div>

                            <div class="space-y-3 pl-9">
                                <template x-for="(follower, index) in followers" :key="index">
                                    <div class="flex flex-col md:flex-row gap-4 items-end bg-slate-900/80 p-4 rounded-2xl border border-white/10 relative group/follower transition-all hover:border-[#56bbf1]/40 shadow-sm">
                                        <div class="flex-1 w-full">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama (Pilih/Ketik)</label>
                                            <select class="block w-full text-xs border-white/10 rounded-lg mb-2 focus:border-[#56bbf1] focus:ring-[#56bbf1] bg-slate-900 text-white cursor-pointer" @change="fillFollowerName(index, $event)">
                                                <option value="" class="bg-slate-900 text-slate-400">-- Auto-fill (Opsional) --</option>
                                                <template x-for="u in allUsers" :key="u.id">
                                                    <option :value="u.id" x-text="u.name" class="bg-slate-900 text-white"></option>
                                                </template>
                                            </select>
                                            <input type="text" :name="'followers['+index+'][nama]'" x-model="follower.nama" placeholder="Nama Lengkap" class="block w-full text-sm font-bold border-white/10 bg-slate-900 rounded-xl focus:border-[#56bbf1] text-white placeholder-slate-500" required>
                                        </div>
                                        <div class="w-full md:w-1/4">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIP / NIK</label>
                                            <input type="text" :name="'followers['+index+'][nip]'" x-model="follower.nip" class="block w-full text-sm font-mono border-white/10 bg-slate-900 rounded-xl focus:border-[#56bbf1] text-slate-300" placeholder="-">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Keterangan</label>
                                            <input type="text" :name="'followers['+index+'][keterangan]'" x-model="follower.keterangan" placeholder="Contoh: Guru Pendamping" class="block w-full text-sm border-white/10 bg-slate-900 rounded-xl focus:border-[#56bbf1] text-slate-300 placeholder-slate-500">
                                        </div>
                                        <div class="absolute top-2 right-2 md:static">
                                            <button type="button" @click="removeFollower(index)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-slate-900 border border-white/10 text-slate-400 hover:text-rose-400 hover:border-rose-500/40 hover:bg-slate-800 transition-all">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="followers.length === 0" class="text-center py-8 border-2 border-dashed border-white/10 rounded-2xl text-slate-400 italic text-sm bg-slate-900/40">
                                    Tidak ada pengikut tambahan.
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: ANGGARAN -->
                        <div class="p-6 bg-slate-900/60 rounded-[2rem] border border-white/10 relative group hover:border-white/20 transition-colors">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] rounded-full w-7 h-7 flex items-center justify-center text-xs">3</span>
                                Pembebanan Anggaran
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Instansi Penanggung Biaya</label>
                                    <input type="text" name="instansi_biaya" value="{{ old('instansi_biaya', $sppd->instansi_pembayar) }}" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] text-sm py-3 font-bold text-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mata Anggaran / Kode Rekening</label>
                                    <input type="text" name="kode_rekening" value="{{ old('kode_rekening', $sppd->mata_anggaran) }}" placeholder="Misal: 5.2.2.15.01" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] text-sm py-3 font-mono font-bold text-white placeholder-slate-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: REKAP BIAYA -->
                        <div class="p-6 bg-emerald-500/10 rounded-[2rem] border border-emerald-500/20 hover:border-emerald-500/40 transition-colors">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-1 flex items-center gap-2">
                                <span class="bg-emerald-500/20 text-emerald-400 rounded-full w-7 h-7 flex items-center justify-center text-xs">4</span>
                                Rekap Biaya Perjalanan
                                <span class="text-[10px] font-medium text-slate-400 normal-case tracking-normal ml-1">(opsional)</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pl-9 mt-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Biaya Transport (Rp)</label>
                                    <input type="number" name="biaya_transport" id="biaya_transport" value="{{ old('biaya_transport', $sppd->biaya_transport) }}" min="0" step="1000" onchange="hitungTotal()" placeholder="0" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-mono font-bold text-emerald-300 placeholder-slate-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Biaya Penginapan (Rp)</label>
                                    <input type="number" name="biaya_penginapan" id="biaya_penginapan" value="{{ old('biaya_penginapan', $sppd->biaya_penginapan) }}" min="0" step="1000" onchange="hitungTotal()" placeholder="0" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-mono font-bold text-emerald-300 placeholder-slate-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Uang Harian (Rp)</label>
                                    <input type="number" name="uang_harian" id="uang_harian" value="{{ old('uang_harian', $sppd->uang_harian) }}" min="0" step="1000" onchange="hitungTotal()" placeholder="0" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-mono font-bold text-emerald-300 placeholder-slate-500 transition-all">
                                </div>
                            </div>
                            <div class="ml-9 mt-4 p-4 bg-slate-900/80 rounded-2xl border border-emerald-500/20 flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-300">Total Biaya:</span>
                                <span id="total_biaya_display" class="text-xl font-black text-emerald-400">Rp {{ number_format($sppd->total_biaya, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        @if($sppd->catatan_kepala)
                        <!-- Catatan Kepala (readonly) -->
                        <div class="p-5 bg-sky-500/10 rounded-[2rem] border border-sky-500/20">
                            <h3 class="text-sm font-black text-[#56bbf1] mb-2 flex items-center gap-2">
                                <i class="ph-fill ph-chats-circle text-[#56bbf1]"></i> Catatan Kepala Sekolah
                            </h3>
                            <p class="text-sm text-sky-200 pl-7 font-medium">{{ $sppd->catatan_kepala }}</p>
                        </div>
                        @endif

                        {{-- Action Footer --}}
                        <div class="flex items-center justify-end gap-4 pt-6 mt-4 border-t border-white/10">
                            <a href="{{ route('sppd.index') }}" class="px-6 py-3.5 rounded-xl text-slate-400 font-bold text-sm hover:bg-slate-800/60 hover:text-white transition-colors">Batal</a>
                            <button type="button" onclick="confirmSubmit(event)" class="px-8 py-3.5 bg-[#56bbf1] text-slate-950 font-bold rounded-xl hover:bg-sky-400 shadow-lg shadow-[#56bbf1]/20 transition-all transform active:scale-95 flex items-center gap-2 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Perbarui SPPD</span>
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
            const form = document.getElementById('form-edit-sppd');
            if (!form.checkValidity()) { form.reportValidity(); return; }
            Swal.fire({
                title: 'Simpan Perubahan?', text: 'Pastikan data perjalanan dinas sudah sesuai.', icon: 'question',
                showCancelButton: true, confirmButtonText: 'Ya, Update!', cancelButtonText: 'Periksa Lagi', reverseButtons: true,
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl bg-[#021124]', confirmButton: 'bg-[#56bbf1] text-slate-950 px-6 py-3 rounded-xl font-bold hover:bg-sky-400 mx-2 shadow-lg shadow-[#56bbf1]/20', cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 mx-2' },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Memperbarui Data...', allowOutsideClick: false, showConfirmButton: false, background: '#021124', color: '#fff', didOpen: () => Swal.showLoading() });
                    form.submit();
                }
            });
        }

        function hitungTotal() {
            const t = parseFloat(document.getElementById('biaya_transport').value) || 0;
            const p = parseFloat(document.getElementById('biaya_penginapan').value) || 0;
            const h = parseFloat(document.getElementById('uang_harian').value) || 0;
            document.getElementById('total_biaya_display').innerText = 'Rp ' + (t+p+h).toLocaleString('id-ID');
        }
    </script>
</x-app-layout>