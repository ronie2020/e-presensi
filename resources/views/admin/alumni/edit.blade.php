<x-app-layout>
    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER --}}
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-black text-slate-800">Edit Data Alumni</h1>
                    <p class="text-sm text-slate-500 font-medium">Input manual tracer study (Sekolah Lanjutan).</p>
                </div>
                <a href="{{ route('admin.alumni.index') }}" class="px-4 py-2 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-600 hover:bg-slate-50">
                    <i class="ph-bold ph-arrow-left"></i> Kembali
                </a>
            </div>

            {{-- FORM CARD --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                <div class="h-1.5 bg-gradient-to-r from-amber-400 to-orange-500"></div>
                
                <form action="{{ route('admin.alumni.update', $student->id) }}" method="POST" x-data="{ status: '{{ $student->alumniProfile->activity_status ?? 'SMA' }}' }">
                    @csrf
                    @method('PUT')

                    <div class="p-8 space-y-8">
                        
                        {{-- IDENTITAS SISWA (READ ONLY) --}}
                        <div class="bg-slate-50 p-6 rounded-2xl flex items-center gap-4 border border-slate-200">
                            <div class="w-16 h-16 rounded-full bg-white border border-slate-200 flex items-center justify-center text-xl font-black text-slate-400">
                                {{ substr($student->name, 0, 2) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-800">{{ $student->name }}</h3>
                                <p class="text-sm text-slate-500">{{ $student->student_id }} | Lulusan {{ $student->graduated_date ? \Carbon\Carbon::parse($student->graduated_date)->year : '-' }}</p>
                            </div>
                        </div>

                        {{-- 1. KONTAK --}}
                        <div>
                            <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-fill ph-address-book text-amber-500"></i> Kontak Terkini
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">No. HP / WA</label>
                                    <input type="text" name="phone_number" value="{{ old('phone_number', $student->alumniProfile->phone_number ?? $student->phone) }}" 
                                           class="w-full rounded-xl border-slate-200 font-bold text-slate-800 focus:ring-amber-500">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Email</label>
                                    <input type="email" name="email" value="{{ old('email', $student->alumniProfile->email ?? '') }}" 
                                           class="w-full rounded-xl border-slate-200 font-bold text-slate-800 focus:ring-amber-500">
                                </div>
                            </div>
                        </div>

                        {{-- 2. AKTIVITAS --}}
                        <div>
                            <h4 class="font-bold text-slate-800 mb-4 flex items-center gap-2 text-sm uppercase tracking-wider">
                                <i class="ph-fill ph-briefcase text-amber-500"></i> Melanjutkan Ke
                            </h4>
                            
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-6">
                                @foreach(['SMA', 'SMK', 'MA', 'Pesantren', 'Tidak Lanjut'] as $opt)
                                <label class="cursor-pointer">
                                    <input type="radio" name="activity_status" value="{{ $opt }}" x-model="status" class="peer sr-only">
                                    <div class="px-2 py-3 rounded-xl border border-slate-200 text-center font-bold text-slate-500 peer-checked:bg-amber-500 peer-checked:text-white peer-checked:border-amber-500 transition-all text-sm">
                                        {{ $opt }}
                                    </div>
                                </label>
                                @endforeach
                            </div>

                            {{-- DINAMIS: SEKOLAH --}}
                            <div x-show="['SMA', 'SMK', 'MA', 'Pesantren'].includes(status)" class="bg-blue-50/50 p-6 rounded-2xl border border-blue-100">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="col-span-2">
                                        <label class="text-xs font-bold text-blue-600 mb-1 block">Nama Sekolah / Pesantren</label>
                                        <input type="text" name="campus_name" value="{{ old('campus_name', $student->alumniProfile->campus_name ?? '') }}" 
                                               class="w-full rounded-xl border-blue-200 focus:ring-blue-500 h-10 text-sm" placeholder="Nama Sekolah Tujuan">
                                    </div>
                                    <div x-show="status !== 'Pesantren'">
                                        <label class="text-xs font-bold text-blue-600 mb-1 block">Jurusan</label>
                                        <input type="text" name="campus_major" value="{{ old('campus_major', $student->alumniProfile->campus_major ?? '') }}" 
                                               class="w-full rounded-xl border-blue-200 focus:ring-blue-500 h-10 text-sm" placeholder="IPA/IPS/TKJ/dll">
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-blue-600 mb-1 block">Tahun Masuk</label>
                                        <input type="number" name="campus_entry_year" value="{{ old('campus_entry_year', $student->alumniProfile->campus_entry_year ?? '') }}" 
                                               class="w-full rounded-xl border-blue-200 focus:ring-blue-500 h-10 text-sm">
                                    </div>
                                </div>
                            </div>

                            {{-- DINAMIS: LAINNYA --}}
                            <div x-show="status === 'Tidak Lanjut'" class="bg-slate-100 p-6 rounded-2xl border border-slate-200" style="display: none;">
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="col-span-2">
                                        <label class="text-xs font-bold text-slate-500 mb-1 block">Keterangan Kegiatan</label>
                                        <input type="text" name="company_name" value="{{ old('company_name', $student->alumniProfile->company_name ?? '') }}" 
                                               class="w-full rounded-xl border-slate-200 focus:ring-slate-500 h-10 text-sm" placeholder="Bekerja / Istirahat / dll">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- 3. TESTIMONI --}}
                        <div>
                            <label class="block text-xs font-bold text-slate-400 mb-2 ml-1">Catatan / Testimoni Alumni</label>
                            <textarea name="testimony" rows="3" class="w-full rounded-xl border-slate-200 focus:ring-amber-500 text-sm">{{ old('testimony', $student->alumniProfile->testimony ?? '') }}</textarea>
                        </div>

                    </div>

                    <div class="px-8 py-6 bg-slate-50 border-t border-slate-100 flex justify-end">
                        <button type="submit" class="px-6 py-3 bg-slate-900 text-white font-bold rounded-xl shadow-lg hover:bg-slate-800 transition flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i> Simpan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>