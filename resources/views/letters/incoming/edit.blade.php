<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Surat Masuk</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                            <strong class="font-bold">Terjadi Kesalahan!</strong>
                            <ul class="mt-2 list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('letters.incoming.update', $letter->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT') {{-- Penting: Ubah method menjadi PUT untuk update --}}
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            
                            {{-- Kolom Kiri --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Nomor Surat</label>
                                    <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $letter->nomor_surat) }}" 
                                           class="form-input rounded-md shadow-sm mt-1 block w-full @error('nomor_surat') border-red-500 @enderror" required>
                                    @error('nomor_surat')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Asal Surat / Pengirim</label>
                                    <input type="text" name="pengirim" value="{{ old('pengirim', $letter->pengirim) }}" 
                                           class="form-input rounded-md shadow-sm mt-1 block w-full @error('pengirim') border-red-500 @enderror" required>
                                    @error('pengirim')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Tanggal Surat</label>
                                    {{-- Pastikan format tanggal Y-m-d agar terbaca oleh input type="date" --}}
                                    <input type="date" name="tgl_surat" value="{{ old('tgl_surat', $letter->tgl_surat->format('Y-m-d')) }}" 
                                           class="form-input rounded-md shadow-sm mt-1 block w-full @error('tgl_surat') border-red-500 @enderror" required>
                                    @error('tgl_surat')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Kolom Kanan --}}
                            <div class="space-y-4">
                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Tanggal Diterima</label>
                                    <input type="date" name="tgl_terima" value="{{ old('tgl_terima', $letter->tgl_terima->format('Y-m-d')) }}" 
                                           class="form-input rounded-md shadow-sm mt-1 block w-full @error('tgl_terima') border-red-500 @enderror" required>
                                    @error('tgl_terima')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">Perihal / Isi Ringkas</label>
                                    <textarea name="perihal" rows="3" 
                                              class="form-input rounded-md shadow-sm mt-1 block w-full @error('perihal') border-red-500 @enderror" required>{{ old('perihal', $letter->perihal) }}</textarea>
                                    @error('perihal')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="block font-medium text-sm text-gray-700">File Surat</label>
                                    
                                    {{-- Informasi file saat ini --}}
                                    @if($letter->file_path)
                                        <div class="flex items-center text-sm text-gray-600 mb-2 bg-gray-50 p-2 rounded border border-gray-200">
                                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="truncate">File saat ini: <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Lihat File</a></span>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-500 mb-2 italic">Belum ada file diupload.</p>
                                    @endif

                                    <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png"
                                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                    <p class="text-xs text-gray-500 mt-1">*Biarkan kosong jika tidak ingin mengubah file.</p>
                                    @error('file_surat')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <a href="{{ route('letters.incoming.index') }}" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">Batal</a>
                            <button type="submit" class="px-4 py-2 bg-blue-800 text-white rounded-md hover:bg-blue-900 transition shadow-lg">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>