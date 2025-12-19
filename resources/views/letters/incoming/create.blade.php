<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Input Surat Masuk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('letters.incoming.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Kolom Kiri --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Nomor Surat</label>
                                    <input type="text" name="nomor_surat" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Asal Surat / Pengirim</label>
                                    <input type="text" name="pengirim" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Tanggal Surat</label>
                                    <input type="date" name="tgl_surat" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Tanggal Diterima</label>
                                    <input type="date" name="tgl_terima" value="{{ date('Y-m-d') }}" class="form-input rounded-md shadow-sm mt-1 block w-full" required>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Perihal / Isi Ringkas</label>
                                    <textarea name="perihal" rows="3" class="form-input rounded-md shadow-sm mt-1 block w-full" required></textarea>
                                </div>
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Upload File (PDF/Gambar)</label>
                                    <input type="file" name="file_surat" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('letters.incoming.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded-md">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>