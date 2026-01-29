@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-slate-50 pb-20 pt-10 px-4" x-data="{ isSaving: false }">
    <div class="max-w-4xl mx-auto">
        
        <!-- HEADER -->
        <div class="bg-gradient-to-r from-emerald-800 to-teal-600 rounded-[2.5rem] p-8 text-white shadow-xl mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-10">
                <i class="ph-fill ph-moon text-[120px]"></i>
            </div>
            <div class="relative z-10">
                <h1 class="text-3xl font-black mb-2">Jurnal Ramadhan Digital</h1>
                <p class="text-emerald-50/80">Pantau progres ibadahmu setiap hari untuk membentuk karakter mulia.</p>
            </div>
        </div>

        <form action="{{ route('student.ramadan.save') }}" method="POST" @submit="isSaving = true">
            @csrf
            <input type="hidden" name="date" value="{{ $today }}">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- KOLOM KIRI: WAJIB -->
                <div class="md:col-span-2 space-y-6">
                    
                    <!-- STATUS PUASA -->
                    <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center">
                                <i class="ph-bold ph-check-circle text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="font-bold text-slate-800">Status Puasa</h3>
                                <p class="text-xs text-slate-400">Apakah kamu berpuasa hari ini?</p>
                            </div>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_fasting" class="sr-only peer" {{ ($todayLog->is_fasting ?? true) ? 'checked' : '' }}>
                            <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <!-- SHALAT 5 WAKTU -->
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-clock text-emerald-500"></i> Shalat Wajib 5 Waktu
                        </h3>
                        <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                            @foreach(['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $p)
                            @php $checked = $todayLog->prayers[$p] ?? false; @endphp
                            <label class="cursor-pointer group">
                                <input type="checkbox" name="prayer_{{ $p }}" class="hidden peer" {{ $checked ? 'checked' : '' }}>
                                <div class="p-3 rounded-2xl border-2 border-slate-50 bg-slate-50 text-slate-400 transition-all peer-checked:bg-emerald-50 peer-checked:border-emerald-200 peer-checked:text-emerald-700 flex flex-col items-center gap-2">
                                    <span class="text-[10px] font-bold uppercase">{{ $p }}</span>
                                    <i class="ph-bold ph-check-circle text-xl"></i>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- TILAWAH -->
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-book-open text-blue-500"></i> Tilawah & Murojaah
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase">Surah Tadarus</label>
                                <div class="flex gap-2">
                                    <input type="text" name="tadarus_surah" value="{{ $todayLog->tadarus_surah ?? '' }}" class="flex-1 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Surah">
                                    <input type="number" name="tadarus_ayah" value="{{ $todayLog->tadarus_ayah ?? '' }}" class="w-20 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Ayat">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-xs font-bold text-slate-400 uppercase">Surah Murojaah</label>
                                <input type="text" name="murojaah_surah" value="{{ $todayLog->murojaah_surah ?? '' }}" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Contoh: An-Naba">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- KOLOM KANAN: SUNNAH -->
                <div class="space-y-6">
                    <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                        <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2">
                            <i class="ph-fill ph-star text-amber-500"></i> Amalan Sunnah
                        </h3>
                        <div class="space-y-3">
                            @foreach(['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah'] as $s)
                            @php $checked = $todayLog->sunnah_deeds[$s] ?? false; @endphp
                            <label class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-50 cursor-pointer hover:border-emerald-200 transition-all">
                                <span class="text-sm font-bold text-slate-600 capitalize">{{ $s }}</span>
                                <input type="checkbox" name="sunnah_{{ $s }}" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" {{ $checked ? 'checked' : '' }}>
                            </label>
                            @endforeach
                        </div>

                        <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2" :disabled="isSaving">
                            <template x-if="!isSaving">
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal
                                </div>
                            </template>
                            <template x-if="isSaving">
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-spinner animate-spin"></i> Memproses...
                                </div>
                            </template>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection