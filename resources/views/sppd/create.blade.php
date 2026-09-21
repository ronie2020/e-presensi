<x-app-layout>
    {{-- Load SweetAlert --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans bg-[#020b18] text-slate-100 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('sppd.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-400 hover:text-[#56bbf1] mb-6 transition-colors group">
                <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl backdrop-blur-xl overflow-hidden relative"
                 x-data="{ 
                    mode: 'spt', 
                    // Mengambil nilai 'old' jika validasi gagal
                    selectedSptId: {{ Js::from(old('spt_id', '')) }},
                    pegawaiId: {{ Js::from(old('pegawai_id', '')) }},
                    maksud: {{ Js::from(old('maksud', '')) }},
                    tujuan: {{ Js::from(old('tujuan', '')) }},
                    tanggal_berangkat: {{ Js::from(old('tgl_berangkat', '')) }},
                    tanggal_kembali: {{ Js::from(old('tgl_kembali', '')) }},
                    followers: {{ Js::from(old('followers', [])) }}, 

                    // Data Dinamis
                    sptList: {{ Js::from($spt_json ?? []) }}, 
                    availableUsers: [],
                    allUsers: {{ Js::from($users->map(fn($u) => ['id'=>$u->id, 'name'=>$u->name, 'nip'=>$u->nip])) }},

                    init() {
                        // Menentukan mode saat pertama kali load berdasarkan data 'old'
                        if(this.selectedSptId) {
                            this.mode = 'spt';
                            const data = this.sptList.find(item => item.id == this.selectedSptId);
                            if(data) this.availableUsers = data.pegawai;
                        } else if (this.pegawaiId) {
                            this.mode = 'manual';
                        } else {
                            if(this.sptList.length > 0) this.mode = 'spt';
                        }
                    },

                    selectSpt() {
                        const data = this.sptList.find(item => item.id == this.selectedSptId);
                        if (data) {
                            this.maksud = data.perihal;
                            this.tujuan = data.tujuan;
                            this.tanggal_berangkat = data.tgl_mulai;
                            this.tanggal_kembali = data.tgl_selesai;
                            this.availableUsers = data.pegawai;
                            
                            // Otomatis pilih pegawai jika hanya ada satu di SPT tersebut
                            if(this.availableUsers.length === 1) {
                                this.pegawaiId = this.availableUsers[0].id;
                            } else {
                                this.pegawaiId = '';
                            }
                        } else {
                            this.resetForm();
                        }
                    },

                    resetForm() {
                        this.maksud = ''; 
                        this.tujuan = ''; 
                        this.tanggal_berangkat = ''; 
                        this.tanggal_kembali = ''; 
                        this.selectedSptId = ''; 
                        this.pegawaiId = '';
                        this.availableUsers = []; 
                    },

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
                            this.followers[index].nip = user.nip || '-'; 
                        }
                    }
                 }">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-[#031d3d] to-[#021124] p-8 text-white relative overflow-hidden border-b border-white/10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-[#56bbf1]/10 rounded-full blur-3xl pointer-events-none -translate-y-1/2 translate-x-1/2"></div>
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-car-profile"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10 flex items-center gap-3">
                        <i class="ph-duotone ph-file-text text-[#56bbf1]"></i> Formulir Perjalanan Dinas
                    </h2>
                    <p class="text-[#56bbf1] text-sm font-medium relative z-10 mt-1">Lengkapi data SPPD berdasarkan Surat Perintah Tugas (SPT).</p>
                </div>

                {{-- Form Content --}}
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-300 rounded-2xl text-sm flex items-start gap-3 shadow-sm">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5 text-rose-400"></i>
                            <div>
                                <strong class="font-bold block mb-1">Periksa kembali inputan Anda!</strong>
                                <ul class="list-disc list-inside font-medium text-rose-200">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form id="form-create-sppd" action="{{ route('sppd.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- SECTION 1: DASAR & MODE INPUT -->
                        <div class="p-6 bg-slate-900/60 rounded-[2rem] border border-white/10 relative group hover:border-[#56bbf1]/40 transition-colors">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] rounded-full w-7 h-7 flex items-center justify-center text-xs">1</span>
                                Dasar Pelaksanaan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Metode Input Data</label>
                                    <div class="flex p-1 bg-slate-900 rounded-2xl border border-white/10 shadow-sm">
                                        <button type="button" @click="mode = 'spt'; resetForm()" 
                                            :class="mode === 'spt' ? 'bg-[#56bbf1] text-slate-950 font-black shadow-md shadow-[#56bbf1]/20' : 'text-slate-400 hover:text-white'" 
                                            class="px-4 py-2.5 text-xs font-bold rounded-xl flex-1 transition-all uppercase tracking-wider">
                                            Berdasarkan SPT
                                        </button>
                                        <button type="button" @click="mode = 'manual'; resetForm()" 
                                            :class="mode === 'manual' ? 'bg-[#56bbf1] text-slate-950 font-black shadow-md shadow-[#56bbf1]/20' : 'text-slate-400 hover:text-white'" 
                                            class="px-4 py-2.5 text-xs font-bold rounded-xl flex-1 transition-all uppercase tracking-wider">
                                            Input Manual
                                        </button>
                                    </div>
                                </div>
                                <div x-show="mode === 'spt'" x-transition class="animate-in fade-in slide-in-from-left-2">
                                    <label class="block text-xs font-bold text-[#56bbf1] uppercase mb-2 ml-1">Pilih Nomor Surat Tugas (SPT)</label>
                                    <div class="relative">
                                        <select name="spt_id" x-model="selectedSptId" @change="selectSpt()" class="w-full pl-4 pr-10 py-3 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm font-bold text-white appearance-none transition-all cursor-pointer">
                                            <option value="" class="bg-slate-900 text-slate-300">-- Cari Nomor SPT --</option>
                                            <template x-for="spt in sptList" :key="spt.id">
                                                <option :value="spt.id" x-text="spt.nomor + ' (' + spt.tujuan + ')'" class="bg-slate-900 text-white"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL SPPD -->
                        <div class="p-6 bg-slate-900/60 rounded-[2rem] border border-white/10 relative group hover:border-white/20 transition-colors">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] rounded-full w-7 h-7 flex items-center justify-center text-xs">2</span>
                                Detail Perjalanan & Pelaksana
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPPD (Otomatis)</label>
                                    <input type="text" name="nomor_sppd" value="{{ old('nomor_sppd', $nomor_otomatis ?? '') }}" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900 text-slate-400 font-mono font-bold text-sm py-3 shadow-inner cursor-not-allowed" readonly>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pegawai Pelaksana <span class="text-rose-400">*</span></label>
                                    <div class="relative">
                                        <select name="pegawai_id" x-model="pegawaiId" required class="w-full pl-4 pr-10 py-3 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm font-bold text-white appearance-none transition-all cursor-pointer">
                                            <option value="" class="bg-slate-900 text-slate-300">-- Pilih Pegawai --</option>
                                            <template x-for="user in (mode === 'manual' ? allUsers : availableUsers)" :key="user.id">
                                                <option :value="user.id" x-text="user.name + (mode === 'manual' && user.nip && user.nip !== '-' ? ' (' + user.nip + ')' : '')" class="bg-slate-900 text-white"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1 italic" x-show="mode === 'spt' && availableUsers.length > 0">
                                        *Hanya menampilkan pegawai yang terdaftar di SPT terpilih.
                                    </p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Maksud Perjalanan Dinas</label>
                                    <textarea name="maksud" rows="2" x-model="maksud" required class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-medium text-white placeholder-slate-500 transition-all" placeholder="Tujuan atau perihal keberangkatan..."></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan</label>
                                    <input type="text" name="tujuan" x-model="tujuan" required class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-bold text-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Alat Angkutan</label>
                                    <input type="text" name="transportasi" value="{{ old('transportasi') }}" placeholder="Contoh: Kendaraan Dinas / Mobil Pribadi" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-bold text-white placeholder-slate-500 transition-all">
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
                        
                        <!-- SECTION 3: PENGIKUT -->
                        <div class="p-6 bg-slate-900/60 rounded-[2rem] border border-white/10 relative group hover:border-white/20 transition-colors">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                                    <span class="bg-[#56bbf1]/20 text-[#56bbf1] rounded-full w-7 h-7 flex items-center justify-center text-xs">3</span>
                                    Pengikut / Peserta Tambahan
                                </h3>
                                <button type="button" @click="addFollower()" class="px-4 py-2 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl text-xs font-bold hover:bg-emerald-500 hover:text-slate-950 transition-colors flex items-center gap-2 shadow-sm">
                                    <i class="ph-bold ph-plus"></i> Tambah Pengikut
                                </button>
                            </div>

                            <div class="space-y-3 pl-9">
                                <template x-for="(follower, index) in followers" :key="index">
                                    <div class="flex flex-col md:flex-row gap-4 items-end bg-slate-900/80 p-5 rounded-[1.5rem] border border-white/10 relative group/follower transition-all hover:border-[#56bbf1]/40 shadow-sm animate-in zoom-in-95 duration-200">
                                        <div class="flex-1 w-full">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Nama (Pilih/Ketik)</label>
                                            <select class="block w-full text-xs border-white/10 rounded-xl mb-2 focus:border-[#56bbf1] focus:ring-[#56bbf1] bg-slate-900 text-white cursor-pointer" @change="fillFollowerName(index, $event)">
                                                <option value="" class="bg-slate-900 text-slate-400">-- Pilih Cepat dari Database --</option>
                                                <template x-for="u in allUsers" :key="u.id">
                                                    <option :value="u.id" x-text="u.name" class="bg-slate-900 text-white"></option>
                                                </template>
                                            </select>
                                            <input type="text" :name="'followers['+index+'][nama]'" x-model="follower.nama" placeholder="Nama Lengkap" class="block w-full text-sm font-bold border-white/10 bg-slate-900 rounded-xl focus:border-[#56bbf1] text-white placeholder-slate-500" required>
                                        </div>
                                        <div class="w-full md:w-1/4">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">NIP / NIK</label>
                                            <input type="text" :name="'followers['+index+'][nip]'" x-model="follower.nip" class="block w-full text-sm font-mono border-white/10 bg-slate-900 rounded-xl focus:border-[#56bbf1] text-slate-300" placeholder="-">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1 tracking-wider">Keterangan</label>
                                            <input type="text" :name="'followers['+index+'][keterangan]'" x-model="follower.keterangan" placeholder="Contoh: Pendamping" class="block w-full text-sm border-white/10 bg-slate-900 rounded-xl focus:border-[#56bbf1] text-slate-300 placeholder-slate-500">
                                        </div>
                                        <div class="absolute top-2 right-2 md:static">
                                            <button type="button" @click="removeFollower(index)" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-900 border border-white/10 text-slate-400 hover:text-rose-400 hover:border-rose-500/40 hover:bg-slate-800 transition-all shadow-sm">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="followers.length === 0" class="text-center py-10 border-2 border-dashed border-white/10 rounded-[2rem] text-slate-400 italic text-sm bg-slate-900/40">
                                    <i class="ph-bold ph-users-three text-3xl block mb-2 opacity-50"></i>
                                    Belum ada pengikut tambahan yang ditambahkan.
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: ANGGARAN -->
                        <div class="p-6 bg-slate-900/60 rounded-[2rem] border border-white/10 relative group hover:border-white/20 transition-colors">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-[#56bbf1]/20 text-[#56bbf1] rounded-full w-7 h-7 flex items-center justify-center text-xs">4</span>
                                Pembebanan Anggaran
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-9">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Instansi Penanggung Biaya</label>
                                    <input type="text" name="instansi_biaya" value="{{ old('instansi_biaya', 'SMP Negeri 3 Lakbok') }}" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-bold text-white transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mata Anggaran / Kode Rekening</label>
                                    <input type="text" name="kode_rekening" value="{{ old('kode_rekening') }}" placeholder="Misal: 5.2.2.15.01 (Dana BOS)" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-[#56bbf1] focus:ring-[#56bbf1] text-sm py-3 font-mono font-bold text-white placeholder-slate-500 transition-all">
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 5: REKAP BIAYA (OPSIONAL) -->
                        <div class="p-6 bg-emerald-500/10 rounded-[2rem] border border-emerald-500/20 relative group hover:border-emerald-500/40 transition-colors">
                            <h3 class="text-sm font-black text-white uppercase tracking-wider mb-1 flex items-center gap-2">
                                <span class="bg-emerald-500/20 text-emerald-400 rounded-full w-7 h-7 flex items-center justify-center text-xs">5</span>
                                Rekap Biaya Perjalanan
                                <span class="text-[10px] font-medium text-slate-400 normal-case tracking-normal ml-1">(opsional, dapat diisi setelah perjalanan)</span>
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pl-9 mt-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Biaya Transport (Rp)</label>
                                    <input type="number" name="biaya_transport" id="biaya_transport" value="{{ old('biaya_transport') }}" min="0" step="1000" onchange="hitungTotal()" placeholder="0" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-mono font-bold text-emerald-300 placeholder-slate-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Biaya Penginapan (Rp)</label>
                                    <input type="number" name="biaya_penginapan" id="biaya_penginapan" value="{{ old('biaya_penginapan') }}" min="0" step="1000" onchange="hitungTotal()" placeholder="0" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-mono font-bold text-emerald-300 placeholder-slate-500 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Uang Harian (Rp)</label>
                                    <input type="number" name="uang_harian" id="uang_harian" value="{{ old('uang_harian') }}" min="0" step="1000" onchange="hitungTotal()" placeholder="0" class="w-full px-4 rounded-2xl border-white/10 bg-slate-900/80 shadow-sm focus:border-emerald-400 focus:ring-emerald-400 text-sm py-3 font-mono font-bold text-emerald-300 placeholder-slate-500 transition-all">
                                </div>
                            </div>
                            <div class="ml-9 mt-4 p-4 bg-slate-900/80 rounded-2xl border border-emerald-500/20 flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-300">Total Biaya:</span>
                                <span id="total_biaya_display" class="text-xl font-black text-emerald-400">Rp 0</span>
                            </div>
                        </div>

                        {{-- Action Footer --}}
                        <div class="flex items-center justify-end gap-4 pt-8 mt-4 border-t border-white/10">
                            <a href="{{ route('sppd.index') }}" class="px-8 py-4 rounded-2xl text-slate-400 font-bold text-sm hover:bg-slate-800/60 hover:text-white transition-colors">Batalkan</a>
                            
                            <button type="button" @click="confirmSubmit($event)" class="px-10 py-4 bg-[#56bbf1] text-slate-950 font-black text-xs uppercase tracking-widest rounded-2xl hover:bg-sky-400 shadow-xl shadow-[#56bbf1]/20 transition-all transform active:scale-95 flex items-center gap-3 group">
                                <i class="ph-bold ph-floppy-disk text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Simpan & Terbitkan</span>
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
            const form = document.getElementById('form-create-sppd');
            const pegawai = document.getElementsByName('pegawai_id')[0].value;
            if(!pegawai) {
                 Swal.fire({ icon: 'warning', title: 'Data Belum Lengkap', text: 'Mohon pilih pegawai pelaksana perjalanan dinas.', background: '#021124', color: '#fff', customClass: { popup: 'rounded-[2rem] border border-white/10 bg-[#021124]' } });
                return;
            }
            if (!form.checkValidity()) { form.reportValidity(); return; }
            Swal.fire({
                title: 'Simpan Data SPPD?', text: 'Pastikan rincian tugas dan anggaran sudah sesuai.', icon: 'question',
                showCancelButton: true, confirmButtonText: 'Ya, Simpan!', cancelButtonText: 'Periksa Lagi', reverseButtons: true,
                background: '#021124', color: '#fff',
                customClass: { popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl bg-[#021124]', confirmButton: 'bg-[#56bbf1] text-slate-950 px-8 py-3.5 rounded-2xl font-bold hover:bg-sky-400 mx-2 shadow-lg shadow-[#56bbf1]/20', cancelButton: 'bg-slate-800 text-slate-300 px-8 py-3.5 rounded-2xl font-bold hover:bg-slate-700 mx-2' },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({ title: 'Menyimpan Data...', text: 'Mohon tunggu sebentar', allowOutsideClick: false, showConfirmButton: false, background: '#021124', color: '#fff', didOpen: () => Swal.showLoading() });
                    form.submit();
                }
            });
        }

        function hitungTotal() {
            const t = parseFloat(document.getElementById('biaya_transport').value) || 0;
            const p = parseFloat(document.getElementById('biaya_penginapan').value) || 0;
            const h = parseFloat(document.getElementById('uang_harian').value) || 0;
            const total = t + p + h;
            document.getElementById('total_biaya_display').innerText = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
</x-app-layout>