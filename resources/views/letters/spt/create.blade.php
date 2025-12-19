<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Buat Surat Perintah Tugas</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('letters.spt.store') }}" method="POST">
                        @csrf
                        
                        <!-- BAGIAN 1: DASAR SURAT -->
                        <div class="mb-6 bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <label class="block font-bold text-sm text-blue-800 mb-2">Dasar Surat (Surat Masuk)</label>
                            <select name="letter_incoming_id" class="form-select w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                <option value="">-- Tanpa Dasar Surat (Langsung) --</option>
                                @foreach($incoming_letters as $letter)
                                    <option value="{{ $letter->id }}" {{ (isset($selected_letter_id) && $selected_letter_id == $letter->id) ? 'selected' : '' }}>
                                        {{ $letter->nomor_surat }} - {{ Str::limit($letter->perihal, 50) }}
                                    </option>
                                @endforeach
                            </select>
                            <p class="text-xs text-blue-600 mt-1">*Pilih surat masuk yang menjadi dasar penugasan ini (Opsional).</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kolom Kiri -->
                            <div class="space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Nomor SPT</label>
                                    <input type="text" name="nomor_spt" value="{{ $nomor_otomatis ?? '' }}" class="form-input rounded-md shadow-sm mt-1 block w-full bg-gray-50" required>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Untuk (Maksud Tugas)</label>
                                    <textarea name="untuk" rows="4" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Contoh: Menghadiri kegiatan Workshop Kurikulum Merdeka..." required></textarea>
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Tempat Tujuan</label>
                                    <input type="text" name="tempat" class="form-input rounded-md shadow-sm mt-1 block w-full" placeholder="Contoh: Aula Dinas Pendidikan Ciamis" required>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Tgl Berangkat</label>
                                        <input type="date" name="tgl_berangkat" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                                    </div>
                                    <div>
                                        <label class="block font-medium text-sm text-gray-700">Tgl Kembali</label>
                                        <input type="date" name="tgl_kembali" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                                    </div>
                                </div>
                            </div>

                            <!-- Kolom Kanan: PILIH PEGAWAI (CHECKBOX MULTI SELECT) -->
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <label class="block font-bold text-sm text-gray-800 mb-2">Pilih Pegawai yang Ditugaskan</label>
                                <p class="text-xs text-gray-500 mb-3">Centang pegawai yang akan berangkat (Bisa lebih dari satu).</p>
                                
                                <div class="max-h-96 overflow-y-auto border rounded-md bg-white p-2">
                                    {{-- Menggunakan Data Real dari Controller --}}
                                    @forelse($users as $user)
                                    <label class="flex items-center p-2 hover:bg-gray-50 cursor-pointer border-b last:border-0">
                                        <input type="checkbox" name="pegawai_ids[]" value="{{ $user->id }}" class="rounded text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 h-5 w-5">
                                        <div class="ml-3">
                                            <span class="block text-sm font-medium text-gray-900">{{ $user->name }}</span>
                                            <span class="block text-xs text-gray-500">{{ $user->pangkat ?? 'NIP. ' . $user->nip }}</span>
                                        </div>
                                    </label>
                                    @empty
                                        <div class="p-4 text-center text-sm text-red-500">
                                            Data Pegawai Kosong. Silahkan jalankan Seeder.
                                        </div>
                                    @endforelse
                                </div>
                                @error('pegawai_ids')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-8 flex justify-end gap-3 border-t pt-4">
                            <a href="{{ route('letters.spt.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md font-semibold hover:bg-gray-300">Batal</a>
                            <button type="submit" class="px-6 py-2 bg-blue-800 text-white rounded-md font-bold hover:bg-blue-900 shadow-lg">
                                Simpan SPT & Tugaskan Pegawai
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>