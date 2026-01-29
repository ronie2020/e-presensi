<div class="space-y-6 animate-in fade-in duration-500">
    <div class="bg-gradient-to-r from-emerald-800 to-teal-600 rounded-[2rem] p-8 text-white shadow-xl relative overflow-hidden">
        <div class="absolute top-0 right-0 p-4 opacity-10">
            <i class="ph-fill ph-moon text-[100px]"></i>
        </div>
        <div class="relative z-10">
            <h2 class="text-2xl font-black mb-2">Jurnal Ibadah Ramadhan</h2>
            <p class="text-emerald-50/80 text-sm italic">"Fastabiqul Khairat - Berlomba-lombalah dalam kebaikan."</p>
        </div>
    </div>

    <form action="{{ route('student.ramadan.save') }}" method="POST" class="grid grid-cols-1 md:grid-cols-12 gap-6">
        @csrf
        <input type="hidden" name="date" value="{{ $today }}">

        {{-- Checklist Utama --}}
        <div class="md:col-span-8 space-y-6">
            {{-- PUASA --}}
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="ph-bold ph-check-circle text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-800">Ibadah Puasa</h3>
                        <p class="text-xs text-slate-400">Centang jika kamu berpuasa hari ini</p>
                    </div>
                </div>
                <input type="checkbox" name="is_fasting" class="w-6 h-6 rounded-lg text-emerald-600 focus:ring-emerald-500 border-slate-300" {{ ($todayRamadanLog->is_fasting ?? true) ? 'checked' : '' }}>
            </div>

            {{-- SHALAT WAJIB --}}
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-clock text-emerald-500"></i> Shalat Wajib 5 Waktu
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                    @foreach(['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $p)
                    @php $checked = $todayRamadanLog->prayers[$p] ?? false; @endphp
                    <label class="cursor-pointer group">
                        <input type="checkbox" name="prayer_{{ $p }}" class="hidden peer" {{ $checked ? 'checked' : '' }}>
                        <div class="p-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-slate-400 transition-all peer-checked:bg-emerald-50 peer-checked:border-emerald-200 peer-checked:text-emerald-700 flex flex-col items-center gap-2">
                            <span class="text-[10px] font-bold uppercase tracking-widest">{{ $p }}</span>
                            <i class="ph-bold ph-check-circle text-xl"></i>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            {{-- TILAWAH --}}
            <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-book-open text-blue-500"></i> Tadarus & Murojaah
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Surah Terakhir</label>
                        <div class="flex gap-2">
                            <input type="text" name="tadarus_surah" value="{{ $todayRamadanLog->tadarus_surah ?? '' }}" class="flex-1 bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-emerald-500" placeholder="Nama Surah">
                            <input type="number" name="tadarus_ayah" value="{{ $todayRamadanLog->tadarus_ayah ?? '' }}" class="w-20 bg-slate-50 border-none rounded-xl text-sm font-bold text-center focus:ring-emerald-500" placeholder="Ayat">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Target Murojaah</label>
                        <input type="text" name="murojaah_surah" value="{{ $todayRamadanLog->murojaah_surah ?? '' }}" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-emerald-500" placeholder="Contoh: An-Naba">
                    </div>
                </div>
            </div>
        </div>

        {{-- Amalan Sunnah & Simpan --}}
        <div class="md:col-span-4 space-y-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm h-full flex flex-col">
                <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                    <i class="ph-fill ph-star text-amber-500"></i> Amalan Sunnah
                </h3>
                <div class="space-y-3 flex-1">
                    @foreach(['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah'] as $s)
                    @php $checked = $todayRamadanLog->sunnah_deeds[$s] ?? false; @endphp
                    <label class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-50 cursor-pointer hover:border-emerald-200 transition-all group">
                        <span class="text-xs font-bold text-slate-600 capitalize group-hover:text-emerald-700">{{ $s }}</span>
                        <input type="checkbox" name="sunnah_{{ $s }}" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300" {{ $checked ? 'checked' : '' }}>
                    </label>
                    @endforeach
                </div>

                <button type="submit" class="w-full mt-8 bg-emerald-600 hover:bg-emerald-700 text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-100 transition-all flex items-center justify-center gap-2">
                    <i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal
                </button>
            </div>
        </div>
    </form>
</div>