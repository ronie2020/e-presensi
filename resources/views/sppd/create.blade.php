<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat SPPD Baru') }}
        </h2>
    </x-slot>

    <div class="py-12"
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
            
            // LIST PENGIKUT (Array of Objects)
            followers: [], // Format: { nama: '', nip: '', keterangan: '' }

            // Data User Lengkap (untuk dropdown select pengikut)
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

            // LOGIC PENGIKUT
            addFollower() {
                this.followers.push({ nama: '', nip: '', keterangan: '' });
            },
            removeFollower(index) {
                this.followers.splice(index, 1);
            },
            // Helper saat dropdown pengikut dipilih, otomatis isi nama & NIP
            fillFollowerName(index, event) {
                const selectedId = event.target.value;
                const user = this.allUsers.find(u => u.id == selectedId);
                
                if (user) {
                    this.followers[index].nama = user.name;
                    this.followers[index].nip = user.nip; // Auto-fill NIP
                } else {
                    this.followers[index].nama = '';
                    this.followers[index].nip = '';
                }
            }
         }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Page Action -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Formulir Perjalanan Dinas</h1>
                    <p class="mt-1 text-sm text-gray-500">Silahkan isi data perjalanan dinas di bawah ini.</p>
                </div>
                <a href="{{ route('sppd.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 transition">
                    &larr; Kembali
                </a>
            </div>

            <form action="{{ route('sppd.store') }}" method="POST">
                @csrf

                <!-- SECTION 1: DASAR & MODE INPUT -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Dasar Pelaksanaan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Input</label>
                                <div class="flex rounded-md shadow-sm" role="group">
                                    <button type="button" @click="mode = 'spt'; resetForm()" :class="mode === 'spt' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 text-sm font-medium border rounded-l-lg flex-1 border-gray-300">Dari SPT</button>
                                    <button type="button" @click="mode = 'manual'; resetForm()" :class="mode === 'manual' ? 'bg-blue-600 text-white' : 'bg-white text-gray-700'" class="px-4 py-2 text-sm font-medium border-t border-b border-r rounded-r-lg flex-1 border-gray-300">Input Manual</button>
                                </div>
                            </div>
                            <div x-show="mode === 'spt'" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Surat Tugas</label>
                                <select name="spt_id" x-model="selectedSptId" @change="selectSpt()" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">-- Pilih Nomor Surat Tugas --</option>
                                    <template x-for="spt in sptList" :key="spt.id">
                                        <option :value="spt.id" x-text="spt.nomor + ' - ' + spt.perihal.substring(0, 30) + '...'"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: DETAIL SPPD -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                         <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Detail Perjalanan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor SPPD</label>
                                <input type="text" name="nomor_sppd" value="{{ $nomor_otomatis }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pegawai Utama (Pelaksana)</label>
                                <div x-show="mode === 'manual'">
                                    <select name="pegawai_id" :disabled="mode === 'spt'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div x-show="mode === 'spt'">
                                    <select name="pegawai_id" :disabled="mode === 'manual'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">-- Pilih Pegawai Terkait SPT --</option>
                                        <template x-for="user in availableUsers" :key="user.id">
                                            <option :value="user.id" x-text="user.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Maksud Perjalanan</label>
                                <textarea name="maksud" rows="2" x-model="maksud" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat Tujuan</label>
                                <input type="text" name="tujuan" x-model="tujuan" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alat Angkutan</label>
                                <input type="text" name="transportasi" placeholder="Kendaraan Dinas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tgl Berangkat</label>
                                <input type="date" name="tgl_berangkat" x-model="tanggal_berangkat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tgl Kembali</label>
                                <input type="date" name="tgl_kembali" x-model="tanggal_kembali" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- SECTION 3: PENGIKUT (ARRAY DINAMIS) -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Pengikut / Peserta Tambahan</h3>
                            <button type="button" @click="addFollower()" class="px-3 py-1 bg-green-600 text-white text-sm font-bold rounded hover:bg-green-700 transition">
                                + Tambah Pengikut
                            </button>
                        </div>

                        <!-- LOOPING INPUT PENGIKUT -->
                        <div class="space-y-3">
                            <template x-for="(follower, index) in followers" :key="index">
                                <div class="flex flex-col md:flex-row gap-3 items-end bg-gray-50 p-3 rounded border border-gray-200">
                                    <div class="flex-1">
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Nama Pengikut (Pilih/Ketik)</label>
                                        <!-- Opsi Pilih dari Pegawai -->
                                        <select class="block w-full text-xs border-gray-300 rounded mb-1" @change="fillFollowerName(index, $event)">
                                            <option value="">-- Pilih dari Pegawai (Opsional) --</option>
                                            <template x-for="u in allUsers" :key="u.id">
                                                <option :value="u.id" x-text="u.name"></option>
                                            </template>
                                        </select>
                                        <!-- Input Text (Bisa diedit atau isi manual) -->
                                        <input type="text" :name="'followers['+index+'][nama]'" x-model="follower.nama" placeholder="Nama Lengkap" class="block w-full text-sm border-gray-300 rounded" required>
                                    </div>
                                    <div class="w-full md:w-40">
                                        <label class="block text-xs font-bold text-gray-500 mb-1">NIP / NIK</label>
                                        <input type="text" :name="'followers['+index+'][nip]'" x-model="follower.nip" class="block w-full text-sm border-gray-300 rounded" placeholder="NIP/NIK">
                                    </div>
                                    <div class="w-full md:w-48">
                                        <label class="block text-xs font-bold text-gray-500 mb-1">Keterangan</label>
                                        <input type="text" :name="'followers['+index+'][keterangan]'" x-model="follower.keterangan" placeholder="Ex: Guru / Penjaga" class="block w-full text-sm border-gray-300 rounded">
                                    </div>
                                    <div>
                                        <button type="button" @click="removeFollower(index)" class="text-red-500 hover:text-red-700 font-bold px-2 py-1">
                                            &times; Hapus
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <div x-show="followers.length === 0" class="text-center py-4 text-gray-400 italic text-sm">
                                Tidak ada pengikut. Klik tombol tambah jika ada pegawai lain yang ikut.
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: ANGGARAN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Pembebanan Anggaran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Instansi Penanggung Biaya</label>
                                <input type="text" name="instansi_biaya" value="SMP Negeri 3 Lakbok" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Mata Anggaran / Kode Rekening</label>
                                <input type="text" name="kode_rekening" placeholder="Misal: 5.2.2.15.01" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pb-12">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-800 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-lg">
                        Simpan Data SPPD
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>