@extends('layouts.public')

@section('content')
<div class="min-h-screen bg-slate-50 pb-20 pt-10 px-4" x-data="{ isSaving: false }">
    <div class="max-w-4xl mx-auto">
        
        <!-- HEADER & NAVIGASI KEMBALI -->
        <div class="mb-6 flex items-center gap-3">
            <a href="{{ route('portal.show', Auth::guard('student')->id()) }}"class="w-10 h-10 rounded-xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-200 transition shadow-sm">
                <i class="ph-bold ph-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-lg font-black text-slate-800">Kembali ke Portal</h2>
                <p class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::parse($today)->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
        </div>

        {{-- 1. KALENDER RAMADHAN (NEW FEATURE) --}}
        <div class="bg-white rounded-[2.5rem] p-6 shadow-sm border border-slate-100 mb-8">
            <div class="flex items-center justify-between mb-4 px-2">
                <h3 class="font-black text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-calendar-check text-emerald-500"></i> Kalender Ramadhan
                </h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Mulai: {{ \Carbon\Carbon::parse($startDate)->format('d M') }}</span>
            </div>

            <div class="grid grid-cols-7 sm:grid-cols-8 md:grid-cols-10 gap-2 sm:gap-3">
                @for ($i = 0; $i < 30; $i++)
                    @php
                        $dateCheck = \Carbon\Carbon::parse($startDate)->addDays($i);
                        $dateString = $dateCheck->format('Y-m-d');
                        $isToday = $dateString === $today;
                        $isPast = $dateCheck->lt(\Carbon\Carbon::parse($today));
                        $isFuture = $dateCheck->gt(\Carbon\Carbon::parse($today));
                        
                        // Cek apakah ada log di tanggal ini
                        $logExists = isset($calendarLogs[$dateString]);
                        
                        // Tentukan Style
                        $bgClass = 'bg-slate-50 border-slate-100 text-slate-400'; // Default Future
                        if ($isToday) {
                            $bgClass = 'bg-white border-emerald-500 text-emerald-600 ring-2 ring-emerald-100 ring-offset-2';
                        } elseif ($logExists) {
                            $bgClass = 'bg-emerald-500 border-emerald-600 text-white'; // Filled
                        } elseif ($isPast) {
                            $bgClass = 'bg-slate-200 border-slate-300 text-slate-400 opacity-60'; // Missed
                        }
                    @endphp
                    
                    <div class="aspect-square rounded-2xl border flex flex-col items-center justify-center relative group {{ $bgClass }}">
                        <span class="text-[10px] font-black uppercase mb-0.5">H-{{ $i + 1 }}</span>
                        <span class="text-xs font-bold">{{ $dateCheck->format('d') }}</span>
                        
                        {{-- Indikator Status --}}
                        @if($logExists)
                            <div class="absolute -top-1 -right-1 bg-white text-emerald-600 rounded-full p-0.5 shadow-sm border border-emerald-100">
                                <i class="ph-fill ph-check-circle text-[10px]"></i>
                            </div>
                        @elseif($isPast)
                            <div class="absolute -top-1 -right-1 bg-white text-rose-400 rounded-full p-0.5 shadow-sm border border-rose-100">
                                <i class="ph-bold ph-x text-[10px]"></i>
                            </div>
                        @endif
                    </div>
                @endfor
            </div>
        </div>

        {{-- 2. ALERT JIKA SUDAH LEWAT HARI (LOCKING SYSTEM) --}}
        @if(!$canFill)
        <div class="bg-amber-50 border border-amber-200 p-6 rounded-[2rem] text-center mb-8">
            <div class="w-12 h-12 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="ph-bold ph-lock-key text-2xl"></i>
            </div>
            <h3 class="font-bold text-amber-800">Waktu Pengisian Ditutup</h3>
            <p class="text-sm text-amber-600 mt-1 max-w-md mx-auto">
                Formulir ini hanya terbuka selama <b>1x24 jam</b> pada tanggal {{ \Carbon\Carbon::parse($today)->format('d F Y') }}. Anda tidak dapat mengisi untuk tanggal yang lalu atau akan datang.
            </p>
        </div>
        @endif
        
        @if($todayRamadanLog)
        <div class="bg-emerald-50 border border-emerald-200 p-6 rounded-[2rem] text-center mb-8">
            <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="ph-fill ph-check-fat text-2xl"></i>
            </div>
            <h3 class="font-bold text-emerald-800">Alhamdulillah!</h3>
            <p class="text-sm text-emerald-600 mt-1">
                Kamu sudah mengisi jurnal hari ini. Data tersimpan aman.
            </p>
        </div>
        @endif

        <form action="{{ route('student.ramadan.save') }}" method="POST" @submit="isSaving = true">
            @csrf
            {{-- Tanggal otomatis dari server (locked) --}}
            <input type="hidden" name="date" value="{{ $today }}">

            <fieldset {{ !$canFill ? 'disabled' : '' }} class="contents group-disabled:opacity-50 group-disabled:pointer-events-none">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    {{-- KOLOM KIRI --}}
                    <div class="md:col-span-2 space-y-6">
                        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center"><i class="ph-bold ph-check-circle text-2xl"></i></div>
                                <div><h3 class="font-bold text-slate-800">Status Puasa</h3><p class="text-xs text-slate-400">Apakah kamu berpuasa hari ini?</p></div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="is_fasting" class="sr-only peer" {{ ($todayRamadanLog->is_fasting ?? true) ? 'checked' : '' }}>
                                <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-600"></div>
                            </label>
                        </div>

                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-clock text-emerald-500"></i> Shalat Wajib 5 Waktu</h3>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                @foreach(['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $p)
                                @php $checked = $todayRamadanLog->prayers[$p] ?? false; @endphp
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

                        @if(\Carbon\Carbon::parse($today)->isFriday())
                        <div class="bg-white p-8 rounded-[2rem] border border-emerald-100 shadow-sm relative overflow-hidden ring-1 ring-emerald-50">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2 relative z-10"><i class="ph-fill ph-mosque text-emerald-600"></i> Laporan Shalat Jumat</h3>
                            <div class="grid grid-cols-1 gap-5 relative z-10">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Nama Khotib</label>
                                    <input type="text" name="friday_khotib" value="{{ $todayRamadanLog->friday_khotib ?? '' }}" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold focus:ring-emerald-500 pl-4" placeholder="Nama Ustadz..." {{ ($todayRamadanLog->teacher_verified_at ?? false) ? 'readonly' : '' }}>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Ringkasan</label>
                                    <textarea name="friday_summary" rows="4" class="w-full bg-slate-50 border-none rounded-xl text-sm font-medium focus:ring-emerald-500" placeholder="Ringkasan khutbah..." {{ ($todayRamadanLog->teacher_verified_at ?? false) ? 'readonly' : '' }}>{{ $todayRamadanLog->friday_summary ?? '' }}</textarea>
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-book-open text-blue-500"></i> Tilawah & Murojaah</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Surah Tadarus</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="tadarus_surah" value="{{ $todayRamadanLog->tadarus_surah ?? '' }}" class="flex-1 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Surah">
                                        <input type="number" name="tadarus_ayah" value="{{ $todayRamadanLog->tadarus_ayah ?? '' }}" class="w-20 bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Ayat">
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-slate-400 uppercase">Murojaah</label>
                                    <input type="text" name="murojaah_surah" value="{{ $todayRamadanLog->murojaah_surah ?? '' }}" class="w-full bg-slate-50 border-none rounded-xl text-sm font-bold" placeholder="Contoh: An-Naba">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN --}}
                    <div class="space-y-6">
                        <div class="bg-white p-8 rounded-[2rem] shadow-sm border border-slate-100 h-full flex flex-col">
                            <h3 class="font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="ph-fill ph-star text-amber-500"></i> Amalan Sunnah</h3>
                            <div class="space-y-3 flex-1">
                                @foreach(['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah'] as $s)
                                @php $checked = $todayRamadanLog->sunnah_deeds[$s] ?? false; @endphp
                                <label class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-50 cursor-pointer hover:border-emerald-200 transition-all">
                                    <span class="text-sm font-bold text-slate-600 capitalize">{{ $s }}</span>
                                    <input type="checkbox" name="sunnah_{{ $s }}" class="w-5 h-5 rounded text-emerald-600 focus:ring-emerald-500" {{ $checked ? 'checked' : '' }}>
                                </label>
                                @endforeach
                            </div>

                            @if($canFill)
                            <button type="submit" class="w-full mt-10 bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-4 rounded-2xl shadow-lg transition-all flex items-center justify-center gap-2 group" :disabled="isSaving">
                                <template x-if="!isSaving"><div class="flex items-center gap-2 group-hover:scale-105 transition-transform"><i class="ph-bold ph-floppy-disk"></i> Simpan Jurnal</div></template>
                                <template x-if="isSaving"><div class="flex items-center gap-2"><i class="ph-bold ph-spinner animate-spin"></i> Memproses...</div></template>
                            </button>
                            @else
                             <div class="w-full mt-10 bg-slate-200 text-slate-400 font-bold py-4 rounded-2xl text-center cursor-not-allowed">
                                <i class="ph-bold ph-lock-key"></i> Form Terkunci
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
@endsection