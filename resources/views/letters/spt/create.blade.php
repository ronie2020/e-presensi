<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Tombol Kembali --}}
            <a href="{{ route('letters.spt.index') }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-blue-600 mb-6 transition-colors">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Daftar
            </a>

            {{-- Card Container --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                {{-- Card Header --}}
                <div class="bg-gradient-to-r from-blue-900 to-blue-800 p-8 text-white relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 text-white/5 text-9xl pointer-events-none">
                        <i class="ph-fill ph-paper-plane-tilt"></i>
                    </div>
                    <h2 class="text-2xl font-black relative z-10">Buat Surat Perintah Tugas</h2>
                    <p class="text-blue-200 text-sm font-medium relative z-10 mt-1">Isi formulir penugasan dinas pegawai.</p>
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

                    <form action="{{ route('letters.spt.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        {{-- Dasar Surat Section --}}
                        <div class="bg-blue-50/50 p-5 rounded-2xl border border-blue-100 mb-6">
                            <label class="block text-xs font-bold text-blue-800 uppercase mb-2 ml-1">Dasar Surat (Referensi)</label>
                            <div class="relative">
                                <i class="ph-bold ph-link absolute left-4 top-1/2 -translate-y-1/2 text-blue-400"></i>
                                <select name="letter_incoming_id" class="w-full pl-11 pr-10 rounded-xl border-blue-200 bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all appearance-none">
                                    <option value="">-- Tanpa Dasar Surat (Langsung) --</option>
                                    @foreach($incoming_letters as $letter)
                                        <option value="{{ $letter->id }}" {{ (old('letter_incoming_id', $selected_letter_id) == $letter->id) ? 'selected' : '' }}>
                                            No: {{ $letter->nomor_surat }} — {{ Str::limit($letter->perihal, 60) }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-blue-400"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                            <p class="text-[10px] text-blue-500 mt-2 ml-1 font-medium">* Opsional: Pilih surat masuk yang mendasari tugas ini.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            
                            {{-- KOLOM KIRI (Detail Tugas) --}}
                            <div class="space-y-5">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Nomor SPT <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-hash absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="nomor_spt" value="{{ old('nomor_spt', $nomor_otomatis) }}" required
                                               class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-mono font-bold text-slate-700 transition-all">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Untuk (Maksud Tugas) <span class="text-rose-500">*</span></label>
                                    <textarea name="untuk" rows="4" required placeholder="Contoh: Menghadiri kegiatan Workshop Kurikulum Merdeka..."
                                              class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-medium text-slate-700 transition-all">{{ old('untuk') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tempat Tujuan <span class="text-rose-500">*</span></label>
                                    <div class="relative">
                                        <i class="ph-bold ph-map-pin absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                        <input type="text" name="tempat" value="{{ old('tempat') }}" required placeholder="Contoh: Aula Dinas Pendidikan"
                                               class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Berangkat</label>
                                        <input type="date" name="tgl_berangkat" value="{{ old('tgl_berangkat') }}" required
                                               class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2 ml-1">Tgl Kembali</label>
                                        <input type="date" name="tgl_kembali" value="{{ old('tgl_kembali') }}" required
                                               class="w-full px-4 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-600 focus:ring-blue-600 text-sm py-3 font-bold text-slate-700 transition-all">
                                    </div>
                                </div>
                            </div>

                            {{-- KOLOM KANAN (Pilih Pegawai) --}}
                            <div class="bg-slate-50 p-5 rounded-[2rem] border border-slate-200 h-full flex flex-col">
                                <label class="block text-xs font-bold text-slate-500 uppercase mb-3 ml-1 flex items-center justify-between">
                                    <span>Pilih Pegawai</span>
                                    <span class="text-[10px] bg-slate-200 px-2 py-0.5 rounded text-slate-500">Multi-select</span>
                                </label>
                                
                                <div class="flex-1 overflow-y-auto max-h-[400px] custom-scrollbar pr-2 space-y-2">
                                    @forelse($users as $user)
                                        <label class="flex items-center p-3 bg-white rounded-2xl border border-slate-200 cursor-pointer hover:border-blue-400 hover:shadow-sm transition-all group">
                                            <div class="relative flex items-center">
                                                <input type="checkbox" name="pegawai_ids[]" value="{{ $user->id }}" 
                                                    class="peer h-5 w-5 cursor-pointer appearance-none rounded-lg border-2 border-slate-300 bg-white transition-all checked:border-blue-600 checked:bg-blue-600 hover:border-blue-400"
                                                    {{ (is_array(old('pegawai_ids')) && in_array($user->id, old('pegawai_ids'))) ? 'checked' : '' }}>
                                                <div class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 text-white opacity-0 transition-opacity peer-checked:opacity-100">
                                                    <i class="ph-bold ph-check text-xs"></i>
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <span class="block text-sm font-bold text-slate-700 group-hover:text-blue-700 transition-colors">{{ $user->name }}</span>
                                                <span class="block text-[10px] text-slate-400 font-mono">{{ $user->pangkat ?? 'NIP. ' . ($user->nip ?? '-') }}</span>
                                            </div>
                                        </label>
                                    @empty
                                        <div class="p-6 text-center text-slate-400 italic text-sm border-2 border-dashed border-slate-200 rounded-2xl">
                                            Data pegawai tidak ditemukan.
                                        </div>
                                    @endforelse
                                </div>
                                @error('pegawai_ids')
                                    <p class="text-rose-500 text-xs mt-2 font-bold">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="pt-8 mt-4 border-t border-slate-100 flex items-center justify-end gap-4">
                            <a href="{{ route('letters.spt.index') }}" class="px-6 py-3 rounded-xl text-slate-500 font-bold text-sm hover:bg-slate-50 hover:text-slate-700 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow-lg shadow-blue-900/20 transition-all transform active:scale-95 flex items-center gap-2">
                                <i class="ph-bold ph-floppy-disk text-lg"></i> Simpan SPT
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>