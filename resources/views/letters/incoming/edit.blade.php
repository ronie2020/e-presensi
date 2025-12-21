<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('letters.incoming.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-pencil-simple-slash"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10">Perbarui Data Surat</h2>
                    <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">
                        Edit informasi surat masuk: <span class="text-white font-mono bg-white/10 px-2 rounded font-bold">{{ $letter->nomor_surat }}</span>
                    </p>
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

                    <form action="{{ route('letters.incoming.update', $letter->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI --}}
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor Surat <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-hash absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="nomor_surat" value="{{ old('nomor_surat', $letter->nomor_surat) }}" required
                                               class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all font-mono">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Asal Surat / Pengirim <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-buildings absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="pengirim" value="{{ old('pengirim', $letter->pengirim) }}" required
                                               class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Surat <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <input type="date" name="tgl_surat" value="{{ old('tgl_surat', $letter->tgl_surat->format('Y-m-d')) }}" required
                                               class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN --}}
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tanggal Diterima</label>
                                    <div class="relative">
                                        <input type="date" name="tgl_terima" value="{{ old('tgl_terima', $letter->tgl_terima->format('Y-m-d')) }}" required
                                               class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Perihal / Ringkasan <span class="text-rose-500">*</span></label>
                                    <textarea name="perihal" rows="4" required
                                              class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-medium text-slate-700 transition-all">{{ old('perihal', $letter->perihal) }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Update File</label>
                                    
                                    {{-- File Preview --}}
                                    @if($letter->file_path)
                                        <div class="mb-3 flex items-center gap-3 p-3 bg-blue-50 border border-blue-100 rounded-2xl">
                                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-blue-600 shadow-sm border border-blue-50">
                                                <i class="ph-fill ph-file-pdf text-xl text-red-500"></i>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-blue-900 truncate">File Tersimpan</p>
                                                <a href="{{ asset('storage/' . $letter->file_path) }}" target="_blank" class="text-[10px] text-blue-600 hover:underline font-bold">
                                                    Klik untuk melihat
                                                </a>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="relative group">
                                        <input type="file" name="file_surat" accept=".pdf,.jpg,.jpeg,.png"
                                               class="block w-full text-xs text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer border border-dashed border-slate-300 rounded-2xl py-3 px-4 hover:border-blue-400 hover:bg-slate-50">
                                    </div>
                                    <p class="text-[10px] text-slate-400 mt-2 ml-1 flex items-center gap-1">
                                        <i class="ph-fill ph-info"></i> Biarkan kosong jika tidak ingin mengubah file.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-8 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="{{ route('letters.incoming.index') }}" class="px-6 py-3 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-50 hover:text-slate-700 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all transform active:scale-95 flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Update Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>