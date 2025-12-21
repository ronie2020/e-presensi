<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('sppd.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative"
                 x-data="{ 
                    mode: 'manual', 
                    selectedSptId: '',
                    
                    // Data Form
                    pegawaiId: '',
                    maksud: '',
                    tujuan: '',
                    tanggal_berangkat: '',
                    tanggal_kembali: '',

                    // Data Dinamis
                    sptList: {{ Js::from($spt_json) }}, 
                    availableUsers: [],
                    
                    // LIST PENGIKUT
                    followers: [], 

                    // Data User Lengkap
                    allUsers: {{ Js::from($users->map(fn($u) => ['id'=>$u->id, 'name'=>$u->name, 'nip'=>$u->nip])) }},

                    init() {},

                    selectSpt() {
                        const data = this.sptList.find(item => item.id == this.selectedSptId);
                        if (data) {
                            this.maksud = data.perihal;
                            this.tujuan = data.tujuan;
                            this.tanggal_berangkat = data.tgl_mulai;
                            this.tanggal_kembali = data.tgl_selesai;
                            this.availableUsers = data.pegawai;
                        } else {
                            this.resetForm();
                        }
                    },

                    resetForm() {
                        this.maksud = ''; this.tujuan = ''; 
                        this.tanggal_berangkat = ''; this.tanggal_kembali = ''; 
                        this.selectedSptId = ''; this.availableUsers = []; 
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
                            this.followers[index].nip = user.nip; 
                        } else {
                            this.followers[index].nama = '';
                            this.followers[index].nip = '';
                        }
                    }
                 }">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-car-profile"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10">Formulir Perjalanan Dinas</h2>
                    <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Lengkapi data SPPD di bawah ini.</p>
                </div>

                {{-- Form Content --}}
                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl text-sm flex items-start gap-3">
                            <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5"></i>
                            <div>
                                <strong class="font-bold block mb-1">Periksa kembali inputan Anda!</strong>
                                <ul class="list-disc list-inside">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('sppd.store') }}" method="POST" class="space-y-8">
                        @csrf

                        <!-- SECTION 1: DASAR & MODE INPUT -->
                        <div class="p-6 bg-blue-50/50 rounded-[2rem] border border-blue-100">
                            <h3 class="text-sm font-black text-blue-900 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-blue-200 text-blue-800 rounded-full w-6 h-6 flex items-center justify-center text-xs">1</span>
                                Dasar Pelaksanaan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-8">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Metode Input Data</label>
                                    <div class="flex p-1 bg-white rounded-xl border border-slate-200 shadow-sm">
                                        <button type="button" @click="mode = 'spt'; resetForm()" 
                                            :class="mode === 'spt' ? 'bg-blue-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-700'" 
                                            class="px-4 py-2.5 text-sm font-bold rounded-lg flex-1 transition-all">
                                            Dari Surat Tugas (SPT)
                                        </button>
                                        <button type="button" @click="mode = 'manual'; resetForm()" 
                                            :class="mode === 'manual' ? 'bg-blue-900 text-white shadow-md' : 'text-slate-500 hover:text-slate-700'" 
                                            class="px-4 py-2.5 text-sm font-bold rounded-lg flex-1 transition-all">
                                            Input Manual
                                        </button>
                                    </div>
                                </div>
                                <div x-show="mode === 'spt'" x-transition>
                                    <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Pilih Nomor Surat Tugas</label>
                                    <div class="relative">
                                        <select name="spt_id" x-model="selectedSptId" @change="selectSpt()" class="w-full pl-4 pr-10 py-3 rounded-xl border-slate-300 bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 appearance-none transition-all shadow-sm">
                                            <option value="">-- Pilih Surat Tugas --</option>
                                            <template x-for="spt in sptList" :key="spt.id">
                                                <option :value="spt.id" x-text="spt.nomor + ' - ' + spt.perihal.substring(0, 30) + '...'"></option>
                                            </template>
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: DETAIL SPPD -->
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-slate-200 text-slate-600 rounded-full w-6 h-6 flex items-center justify-center text-xs">2</span>
                                Detail Perjalanan
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-8">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPPD (Otomatis)</label>
                                    <input type="text" name="nomor_sppd" value="{{ $nomor_otomatis }}" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 text-slate-500 font-mono font-bold text-sm py-3" readonly>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Pegawai Pelaksana</label>
                                    <div class="relative">
                                        <!-- Select Manual -->
                                        <div x-show="mode === 'manual'">
                                            <select name="pegawai_id" :disabled="mode === 'spt'" class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 appearance-none transition-all">
                                                <option value="">-- Pilih Pegawai --</option>
                                                @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Select dari SPT -->
                                        <div x-show="mode === 'spt'">
                                            <select name="pegawai_id" :disabled="mode === 'manual'" class="w-full pl-4 pr-10 py-3 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm font-bold text-slate-700 appearance-none transition-all">
                                                <option value="">-- Pilih Pegawai di SPT --</option>
                                                <template x-for="user in availableUsers" :key="user.id">
                                                    <option :value="user.id" x-text="user.name"></option>
                                                </template>
                                            </select>
                                        </div>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Maksud Perjalanan Dinas</label>
                                    <textarea name="maksud" rows="2" x-model="maksud" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-medium text-slate-700 transition-all"></textarea>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan</label>
                                    <input type="text" name="tujuan" x-model="tujuan" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Alat Angkutan</label>
                                    <input type="text" name="transportasi" placeholder="Kendaraan Dinas" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Berangkat</label>
                                    <input type="date" name="tgl_berangkat" x-model="tanggal_berangkat" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Kembali</label>
                                    <input type="date" name="tgl_kembali" x-model="tanggal_kembali" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                </div>
                            </div>
                        </div>
                        
                        <!-- SECTION 3: PENGIKUT -->
                        <div>
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider flex items-center gap-2">
                                    <span class="bg-slate-200 text-slate-600 rounded-full w-6 h-6 flex items-center justify-center text-xs">3</span>
                                    Pengikut / Peserta Tambahan
                                </h3>
                                <button type="button" @click="addFollower()" class="px-4 py-2 bg-emerald-50 text-emerald-600 border border-emerald-200 rounded-xl text-xs font-bold hover:bg-emerald-100 transition-colors flex items-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Tambah
                                </button>
                            </div>

                            <div class="space-y-3 pl-8">
                                <template x-for="(follower, index) in followers" :key="index">
                                    <div class="flex flex-col md:flex-row gap-4 items-end bg-slate-50 p-4 rounded-2xl border border-slate-200 relative group transition-all hover:border-blue-300">
                                        <div class="flex-1 w-full">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Nama (Pilih/Ketik)</label>
                                            <select class="block w-full text-xs border-slate-300 rounded-lg mb-2 focus:ring-blue-500 bg-white" @change="fillFollowerName(index, $event)">
                                                <option value="">-- Auto-fill (Opsional) --</option>
                                                <template x-for="u in allUsers" :key="u.id">
                                                    <option :value="u.id" x-text="u.name"></option>
                                                </template>
                                            </select>
                                            <input type="text" :name="'followers['+index+'][nama]'" x-model="follower.nama" placeholder="Nama Lengkap" class="block w-full text-sm font-bold border-slate-300 rounded-xl focus:ring-blue-500 text-slate-700" required>
                                        </div>
                                        <div class="w-full md:w-1/4">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">NIP / NIK</label>
                                            <input type="text" :name="'followers['+index+'][nip]'" x-model="follower.nip" class="block w-full text-sm font-mono border-slate-300 rounded-xl focus:ring-blue-500 text-slate-600" placeholder="-">
                                        </div>
                                        <div class="w-full md:w-1/3">
                                            <label class="block text-[10px] font-bold text-slate-400 uppercase mb-1">Keterangan</label>
                                            <input type="text" :name="'followers['+index+'][keterangan]'" x-model="follower.keterangan" placeholder="Contoh: Guru Pendamping" class="block w-full text-sm border-slate-300 rounded-xl focus:ring-blue-500 text-slate-600">
                                        </div>
                                        <div class="absolute top-2 right-2 md:static">
                                            <button type="button" @click="removeFollower(index)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:text-rose-500 hover:border-rose-200 hover:shadow-sm transition-all">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                
                                <div x-show="followers.length === 0" class="text-center py-8 border-2 border-dashed border-slate-200 rounded-2xl text-slate-400 italic text-sm bg-slate-50/50">
                                    Tidak ada pengikut tambahan.
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: ANGGARAN -->
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-wider mb-4 flex items-center gap-2">
                                <span class="bg-slate-200 text-slate-600 rounded-full w-6 h-6 flex items-center justify-center text-xs">4</span>
                                Pembebanan Anggaran
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pl-8">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Instansi Penanggung Biaya</label>
                                    <input type="text" name="instansi_biaya" value="SMP Negeri 3 Lakbok" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Mata Anggaran / Kode Rekening</label>
                                    <input type="text" name="kode_rekening" placeholder="Misal: 5.2.2.15.01" class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-mono font-bold text-slate-700 transition-all">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 pt-8 mt-4 border-t border-slate-100">
                            <a href="{{ route('sppd.index') }}" class="px-6 py-3 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-50 hover:text-slate-700 transition-colors">Batal</a>
                            <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all transform active:scale-95 flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Data SPPD
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>