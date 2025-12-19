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

            // Data Dinamis dari Controller
            sptList: {{ Js::from($spt_json) }}, 
            
            // List Pegawai Reactive
            availableUsers: [],

            init() {
                // Init logic if needed
            },

            // Fungsi saat SPT dipilih
            selectSpt() {
                const data = this.sptList.find(item => item.id == this.selectedSptId);
                
                if (data) {
                    this.maksud = data.perihal;
                    this.tujuan = data.tujuan;
                    this.tanggal_berangkat = data.tgl_mulai;
                    this.tanggal_kembali = data.tgl_selesai;
                    // Update dropdown pegawai HANYA menampilkan yang ada di SPT tsb
                    this.availableUsers = data.pegawai;
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
                this.availableUsers = []; 
            }
         }">
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header Page Action -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h1 class="text-lg font-bold text-gray-900">Formulir Perjalanan Dinas</h1>
                    <p class="mt-1 text-sm text-gray-500">Silahkan isi data perjalanan dinas di bawah ini.</p>
                </div>
                <a href="{{ route('sppd.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    &larr; Kembali
                </a>
            </div>

            <!-- FORM CONTAINER -->
            <form action="{{ route('sppd.store') }}" method="POST">
                @csrf

                <!-- SECTION 1: DASAR PELAKSANAAN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4 flex items-center">
                            Dasar Pelaksanaan
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Switch Mode -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Metode Input</label>
                                <div class="flex rounded-md shadow-sm" role="group">
                                    <button type="button" 
                                            @click="mode = 'spt'; resetForm()"
                                            :class="mode === 'spt' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                            class="px-4 py-2 text-sm font-medium border rounded-l-lg flex-1">
                                        Dari Surat Tugas (SPT)
                                    </button>
                                    <button type="button" 
                                            @click="mode = 'manual'; resetForm()"
                                            :class="mode === 'manual' ? 'bg-blue-600 text-white border-blue-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
                                            class="px-4 py-2 text-sm font-medium border-t border-b border-r rounded-r-lg flex-1">
                                        Input Manual
                                    </button>
                                </div>
                                <p class="mt-2 text-xs text-gray-500" x-show="mode === 'spt'">
                                    *Data (Tujuan, Tanggal, Pegawai) diambil otomatis dari SPT.
                                </p>
                            </div>

                            <!-- Dropdown SPT -->
                            <div x-show="mode === 'spt'" x-transition>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Surat Tugas</label>
                                <select name="spt_id" 
                                        x-model="selectedSptId"
                                        @change="selectSpt()"
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="">-- Pilih Nomor Surat Tugas --</option>
                                    <template x-for="spt in sptList" :key="spt.id">
                                        <option :value="spt.id" x-text="spt.nomor + ' - ' + spt.perihal.substring(0, 30) + '...'"></option>
                                    </template>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: DETAIL PERJALANAN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                         <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4 flex items-center">
                            Detail SPPD
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            <!-- Nomor SPPD -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nomor SPPD</label>
                                <input type="text" name="nomor_sppd" value="{{ $nomor_otomatis }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-50" readonly>
                            </div>

                            <!-- Pegawai Yang Ditugaskan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Pegawai (Yang melaksanakan)</label>
                                
                                <!-- Mode Manual: List Semua User -->
                                <div x-show="mode === 'manual'">
                                    <select name="pegawai_id" :disabled="mode === 'spt'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">-- Pilih Pegawai --</option>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Mode SPT: List User Terkait SPT -->
                                <div x-show="mode === 'spt'">
                                    <select name="pegawai_id" :disabled="mode === 'manual'" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                        <option value="">-- Pilih Pegawai Terkait SPT --</option>
                                        <template x-for="user in availableUsers" :key="user.id">
                                            <option :value="user.id" x-text="user.name"></option>
                                        </template>
                                    </select>
                                </div>
                            </div>

                            <!-- Maksud Perjalanan -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Maksud Perjalanan Dinas</label>
                                <textarea name="maksud" rows="3" x-model="maksud" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" placeholder="Contoh: Menghadiri Rapat Koordinasi BOS..."></textarea>
                            </div>

                            <!-- Tujuan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tempat Tujuan</label>
                                <input type="text" name="tujuan" x-model="tujuan"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <!-- Alat Angkut -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alat Angkutan</label>
                                <input type="text" name="transportasi" placeholder="Kendaraan Dinas / Umum" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <!-- Tanggal Berangkat -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Berangkat</label>
                                <input type="date" name="tgl_berangkat" x-model="tanggal_berangkat" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                            <!-- Tanggal Kembali -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Tanggal Harus Kembali</label>
                                <input type="date" name="tgl_kembali" x-model="tanggal_kembali" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                            </div>

                        </div>
                    </div>
                </div>

                <!-- SECTION 3: PEMBEBANAN ANGGARAN -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 border border-gray-200">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">
                            Pembebanan Anggaran
                        </h3>
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

                <!-- FORM ACTIONS -->
                <div class="flex items-center justify-end gap-4 pb-12">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-800 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-blue-700 shadow-lg transition ease-in-out duration-150">
                        Simpan Data SPPD
                    </button>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>