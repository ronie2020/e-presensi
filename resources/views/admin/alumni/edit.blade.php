<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-2xl font-black text-white tracking-tight">Edit Data Alumni</h1>
                    <p class="text-sm text-slate-400 font-medium mt-1">Input manual tracer study (Sekolah Lanjutan / Karir).</p>
                </div>
                <a href="{{ route('admin.alumni.index') }}" class="px-5 py-2.5 bg-[#021124]/80 border border-white/15 rounded-xl text-sm font-bold text-slate-300 hover:bg-[#031d3d] hover:text-white hover:border-[#56bbf1]/40 transition-all flex items-center gap-2 shadow-sm group">
                    <i class="ph-bold ph-arrow-left group-hover:-translate-x-1 transition-transform"></i> Kembali
                </a>
            </div>

            {{-- FORM CARD --}}
            <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden relative group transition-colors">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] via-sky-500 to-[#56bbf1]"></div>
                
                {{-- 
                    LOGIKA STATUS LEGACY:
                    Jika status lama adalah "Tidak Lanjut" (dari versi web sebelumnya), 
                    otomatis arahkan/konversi visualnya ke "Lainnya" agar UI tidak blank.
                --}}
                @php
                    $currentStatus = $student->alumniProfile->activity_status ?? 'SMA';
                    if ($currentStatus == 'Tidak Lanjut') {
                        $currentStatus = 'Lainnya';
                    }
                @endphp

                <form action="{{ route('admin.alumni.update', $student->id) }}" method="POST" x-data="{ status: '{{ $currentStatus }}' }">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-8 mt-2">
                        
                        {{-- IDENTITAS SISWA (READ ONLY) --}}
                        <div class="bg-[#021124]/80 p-6 rounded-2xl flex flex-col md:flex-row md:items-center gap-4 border border-white/10">
                            <div class="w-16 h-16 rounded-[1rem] bg-white/5 border border-white/10 flex items-center justify-center text-xl font-black text-[#56bbf1] shrink-0 shadow-sm overflow-hidden">
                                @if($student->photo_path)
                                    <img src="{{ asset('storage/' . $student->photo_path) }}" class="w-full h-full object-cover">
                                @else
                                    {{ substr($student->name, 0, 2) }}
                                @endif
                             </div>
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-white leading-tight">{{ $student->name }}</h3>
                                <p class="text-sm font-medium text-slate-400 mt-1">{{ $student->nisn ?? $student->student_id }} | <span class="font-bold text-slate-300">Lulusan {{ $student->graduation_year ?? ($student->graduated_date ? \Carbon\Carbon::parse($student->graduated_date)->year : '-') }}</span></p>
                            </div>
                            <div class="shrink-0 text-right md:block hidden">
                                <span class="px-4 py-2 bg-[#56bbf1]/10 text-[#56bbf1] text-xs font-bold rounded-xl border border-[#56bbf1]/20">Status: Alumni</span>
                            </div>
                        </div>

                        {{-- 1. KONTAK --}}
                        <div>
                            <h4 class="font-black text-white mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-fill ph-address-book text-[#56bbf1]"></i> Kontak Terkini
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">No. HP / WA</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $student->alumniProfile->phone_number ?? $student->phone) }}" 
                                           class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all py-3 px-4 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $student->alumniProfile->email ?? '') }}" 
                                           class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all py-3 px-4 text-sm">
                                </div>
                            </div>
                        </div>

                        {{-- 2. AKTIVITAS --}}
                        <div>
                            <h4 class="font-black text-white mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-fill ph-briefcase text-[#56bbf1]"></i> Aktivitas Saat Ini
                            </h4>
                            
                            {{-- PENYELARASAN OPSI --}}
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3 mb-6 bg-[#021124]/80 p-2 rounded-2xl border border-white/10">
                                @foreach(['SMA', 'SMK', 'MA', 'Pesantren', 'Bekerja', 'Lainnya'] as $opt)
                                <label class="cursor-pointer h-full">
                                    <input type="radio" name="activity_status" value="{{ $opt }}" x-model="status" class="peer sr-only">
                                    <div class="px-2 py-3 rounded-xl border border-transparent text-center font-bold text-slate-400 peer-checked:bg-gradient-to-r peer-checked:from-[#0d52a1] peer-checked:to-[#56bbf1] peer-checked:text-white peer-checked:border-white/20 peer-checked:shadow-sm transition-all text-xs hover:text-white h-full flex items-center justify-center">
                                        {{ $opt }}
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            {{-- DINAMIS: SEKOLAH --}}
                            <div x-show="['SMA', 'SMK', 'MA', 'Pesantren'].includes(status)" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 class="bg-[#56bbf1]/5 p-6 rounded-[2rem] border border-[#56bbf1]/20" x-cloak>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <div class="col-span-2">
                                        <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Nama Sekolah / Pesantren</label>
                                        <input type="text" name="campus_name" value="{{ old('campus_name', $student->alumniProfile->campus_name ?? '') }}" 
                                               class="w-full rounded-2xl border-white/15 bg-[#021124]/90 font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all py-3 px-4 text-sm shadow-sm placeholder:text-slate-500" placeholder="Nama Sekolah Tujuan">
                                    </div>
                                    <div x-show="status !== 'Pesantren'">
                                        <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Jurusan / Peminatan</label>
                                        <input type="text" name="campus_major" value="{{ old('campus_major', $student->alumniProfile->campus_major ?? '') }}" 
                                               class="w-full rounded-2xl border-white/15 bg-[#021124]/90 font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all py-3 px-4 text-sm shadow-sm placeholder:text-slate-500" placeholder="IPA/IPS/TKJ/dll">
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-[#56bbf1] uppercase tracking-wider mb-2 ml-1">Tahun Masuk</label>
                                        <input type="number" name="campus_entry_year" value="{{ old('campus_entry_year', $student->alumniProfile->campus_entry_year ?? date('Y')) }}" 
                                               class="w-full rounded-2xl border-white/15 bg-[#021124]/90 font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all py-3 px-4 text-sm shadow-sm">
                                    </div>
                                </div>
                            </div>

                            {{-- DINAMIS: BEKERJA / LAINNYA --}}
                            <div x-show="['Bekerja', 'Lainnya'].includes(status)" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 -translate-y-2"
                                 class="bg-[#021124]/80 p-6 rounded-[2rem] border border-white/10" x-cloak>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    <template x-if="status === 'Bekerja'">
                                        <div class="col-span-2 md:col-span-1">
                                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nama Tempat Kerja</label>
                                            <input type="text" name="company_name" value="{{ old('company_name', $student->alumniProfile->company_name ?? '') }}" 
                                                   class="w-full rounded-2xl border-white/15 bg-[#021124]/90 font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all py-3 px-4 text-sm shadow-sm placeholder:text-slate-500" placeholder="PT... / CV... / Toko...">
                                        </div>
                                    </template>

                                    <div class="col-span-2" :class="status === 'Bekerja' ? 'md:col-span-1' : ''">
                                        <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">
                                            <span x-text="status === 'Bekerja' ? 'Posisi / Jabatan' : 'Keterangan Kegiatan / Kesibukan'"></span>
                                        </label>
                                        <input type="text" name="position" value="{{ old('position', $student->alumniProfile->position ?? '') }}" 
                                               class="w-full rounded-2xl border-white/15 bg-[#021124]/90 font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all py-3 px-4 text-sm shadow-sm placeholder:text-slate-500" 
                                               x-bind:placeholder="status === 'Bekerja' ? 'Staff / Admin / Kasir' : 'Gap Year / Membantu Orang Tua'">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. TESTIMONI --}}
                        <div>
                            <h4 class="font-black text-white mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-fill ph-chat-centered-text text-[#56bbf1]"></i> Catatan Testimoni
                            </h4>
                            <textarea name="testimony" rows="3" placeholder="Pesan dan kesan dari alumni..."
                                      class="w-full rounded-2xl border-white/15 bg-[#021124]/90 focus:bg-[#031d3d] font-medium text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] transition-all p-4 text-sm placeholder:text-slate-500">{{ old('testimony', $student->alumniProfile->testimony ?? '') }}</textarea>
                            
                            {{-- Input rating tersembunyi agar tidak hilang saat admin save --}}
                            <input type="hidden" name="rating" value="{{ old('rating', $student->alumniProfile->rating ?? 5) }}">
                        </div>

                    </div>

                    <div class="px-6 md:px-8 py-6 bg-white/[0.02] border-t border-white/10 flex justify-end gap-3">
                        <a href="{{ route('admin.alumni.index') }}" class="px-6 py-3.5 bg-white/5 border border-white/10 text-slate-300 font-bold rounded-2xl hover:bg-white/10 hover:text-white transition-all text-sm flex items-center justify-center">
                            Batal
                        </a>
                        <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white font-bold rounded-2xl shadow-lg shadow-sky-950/50 hover:brightness-110 transition-all transform active:scale-95 flex items-center gap-2 group/btn text-sm">
                            <i class="ph-bold ph-floppy-disk text-lg group-hover/btn:scale-110 transition-transform"></i>
                            <span>Simpan Perubahan</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>