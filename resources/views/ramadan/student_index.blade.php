@extends('layouts.public')

@section('content')
@php
    use Carbon\Carbon;
    $startDate = $startDate ?? Carbon::now()->startOfMonth()->format('Y-m-d'); 
    $today = $today ?? Carbon::now()->format('Y-m-d');
    
    // Gunakan parameter dari controller, default false jika tidak ada
    $isRamadanEnded = $isRamadanEnded ?? false; 
    $canFill = $isRamadanEnded ? false : ($canFill ?? false);

    $todayRamadanLog = $todayRamadanLog ?? null;
    $calendarLogs = $calendarLogs ?? [];
    $totalRamadanDays = $totalRamadanDays ?? 30; 
    
    $userCity = Auth::guard('student')->check() ? (Auth::guard('student')->user()->city ?? 'Jakarta') : 'Jakarta';
@endphp

{{-- 1. CUSTOM STYLES & MICROSOFT FLUENT ELEVATION --}}
<style>
    /* Microsoft Fluent Elevation Shadows */
    .fluent-card {
        box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108);
        border: 1px solid rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }
    .fluent-card:hover {
        box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108);
        transform: translateY(-2px);
    }
    .scrollbar-hide::-webkit-scrollbar { display: none; }
    .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<div class="min-h-screen bg-[#f8fafc] pb-20 pt-10 px-4 font-sans text-[#2A3B52]" x-data="{ isSaving: false }">
    <div class="max-w-4xl mx-auto">
        
        <!-- HEADER -->
        <div class="mb-6 flex items-center gap-4">
            <a href="{{ route('portal.show', Auth::guard('student')->id() ?? 0) }}?tab=ramadan_jurnal" class="w-11 h-11 rounded-xl bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-[#F3F9FD] hover:text-[#5295FF] hover:border-[#D0E7F8] transition-all shadow-sm fluent-card">
                <i class="ph-bold ph-arrow-left"></i>
            </a>
            <div>
                <h2 class="text-xl font-black text-[#2A3B52] tracking-tight">Kembali ke Portal</h2>
                <p class="text-xs text-slate-500 font-bold tracking-wide mt-0.5">{{ \Carbon\Carbon::parse($today)->translatedFormat('l, d F Y') }}</p>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- PENGUMUMAN RAMADHAN SELESAI & LEADERBOARD  -->
        <!-- ========================================== -->
        @if($isRamadanEnded)
        <div class="bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] rounded-[2.5rem] p-8 md:p-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] mb-8 relative overflow-hidden border border-white/40">
            {{-- Ornamen Latar Microsoft Elevate --}}
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-[0.03] pointer-events-none z-0"></div>
            <div class="absolute top-0 right-0 -mt-10 -mr-10 w-[300px] h-[300px] bg-white/30 rounded-full blur-[80px] z-0"></div>
            <div class="absolute -right-10 -top-10 opacity-10 rotate-12 pointer-events-none z-0">
                <i class="ph-fill ph-check-circle text-[200px] text-[#2A3B52]"></i>
            </div>
            
            <div class="relative z-10">
                {{-- Pesan Utama --}}
                <div class="mb-8 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-md shadow-sm">
                        <i class="ph-fill ph-info text-[#5295FF]"></i> Informasi Penting
                    </div>
                    <h3 class="text-3xl md:text-4xl font-black mb-4 tracking-tight">Alhamdulillah, Pengisian Jurnal Selesai! 🎉</h3>
                    <p class="text-[#2A3B52]/80 text-sm md:text-base leading-relaxed max-w-2xl mx-auto md:mx-0 font-medium">
                        Waktu pengisian Jurnal Ramadhan tahun ini telah resmi ditutup. Terima kasih atas semangat dan antusiasme kalian dalam beribadah selama sebulan penuh. Insya Allah kita akan berjumpa dan mulai mengisi jurnal kembali di Ramadhan tahun depan. Tetap istiqomah!
                    </p>
                </div>
                
                {{-- Daftar Siswa Terajin --}}
                @if(isset($topStudents) && $topStudents->isNotEmpty())
                <div class="bg-white/40 backdrop-blur-md rounded-[2rem] p-6 md:p-8 border border-white/50 mt-6 shadow-sm">
                    <h4 class="font-bold text-[#2A3B52] mb-5 flex items-center justify-center md:justify-start gap-2">
                        <i class="ph-fill ph-medal text-[#D83B01] text-2xl"></i> Pahlawan Kebaikan Tahun Ini:
                    </h4>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($topStudents as $index => $student)
                            @php
                                $medalColor = $index == 0 ? 'bg-[#D83B01] border-[#FFD8A8]' : ($index == 1 ? 'bg-[#5295FF] border-[#D0E7F8]' : 'bg-[#107C10] border-[#B7DFB9]');
                            @endphp
                            <div class="bg-white rounded-[1.5rem] p-4 flex items-center gap-4 text-[#2A3B52] fluent-card">
                                {{-- Peringkat / Medali --}}
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center font-black text-lg text-white shadow-sm border {{ $medalColor }}">
                                    #{{ $index + 1 }}
                                </div>
                                {{-- Info Siswa --}}
                                <div class="flex-1 overflow-hidden">
                                    <h5 class="font-bold text-sm truncate capitalize">{{ strtolower($student->name) }}</h5>
                                    <p class="text-[10px] text-slate-500 font-bold uppercase tracking-widest mt-1 flex items-center gap-1">
                                        <i class="ph-fill ph-star text-[#D83B01]"></i> {{ $student->ramadan_points ?? 0 }} Pts
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8 flex justify-center md:justify-end">
                        <a href="{{ route('portal.show', Auth::guard('student')->id() ?? 0) }}?tab=ramadan_rank" class="inline-flex items-center gap-2 bg-[#2A3B52] text-white font-bold px-6 py-3 rounded-xl text-xs uppercase tracking-widest hover:bg-[#182436] shadow-md transition-all active:scale-95">
                            Papan Peringkat Lengkap <i class="ph-bold ph-arrow-right text-lg"></i>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif
        <!-- ========================================== -->

        {{-- JIKA BELUM SELESAI, TAMPILKAN FORM DAN WIDGET --}}
        @if(!$isRamadanEnded)
        
            {{-- WIDGET JADWAL SHALAT (Microsoft Elevate Clean Glass) --}}
            <div x-data="prayerWidgetIndex()" x-init="init()" class="relative mb-8">
                <div x-show="!isLoading" 
                     class="bg-white rounded-[2rem] p-6 md:p-8 text-[#2A3B52] fluent-card relative overflow-hidden group border-t-4 border-[#5295FF]">
                    <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                        <div class="text-center md:text-left">
                            <h3 class="text-2xl md:text-3xl font-black text-[#2A3B52] tracking-tight mb-2" x-text="nextEventName">...</h3>
                            <p class="text-slate-500 text-sm font-bold font-mono tracking-wider bg-slate-50 border border-slate-200 px-4 py-1.5 rounded-lg inline-block">
                                <i class="ph-bold ph-hourglass-medium text-[#5295FF] mr-1"></i> <span x-text="countdown">00:00:00</span>
                            </p>
                        </div>
                        <div class="w-full md:w-auto overflow-x-auto pb-2 md:pb-0 scrollbar-hide">
                            <div class="flex md:grid md:grid-cols-6 gap-3 min-w-max px-2">
                                <template x-for="(time, name) in schedule" :key="name">
                                    <div class="flex flex-col items-center group/item">
                                        <div class="w-16 h-20 rounded-2xl flex flex-col items-center justify-center p-2 transition-all duration-300 relative overflow-hidden border shadow-sm"
                                             :class="currentEvent === name ? 'bg-[#F3F9FD] border-[#5295FF] scale-105' : 'bg-white border-slate-200 hover:bg-slate-50'">
                                            <span class="text-[9px] font-bold uppercase tracking-widest mb-1.5" 
                                                  :class="currentEvent === name ? 'text-[#5295FF]' : 'text-slate-400'" x-text="name"></span>
                                            <span class="text-sm font-mono" 
                                                  :class="currentEvent === name ? 'text-[#2A3B52] font-black' : 'text-slate-600 font-bold'" x-text="time"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1. KALENDER RAMADHAN --}}
            <div class="bg-white rounded-[2rem] p-6 md:p-8 fluent-card mb-8 relative overflow-hidden group">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6 px-2 border-b border-slate-100 pb-5">
                        <h3 class="font-black text-[#2A3B52] flex items-center gap-3 text-lg tracking-tight">
                            <span class="w-10 h-10 rounded-xl bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] flex items-center justify-center shadow-sm">
                                <i class="ph-fill ph-calendar-star text-xl"></i>
                            </span>
                            Kalender Ramadhan
                        </h3>
                        <span class="text-[10px] font-bold text-[#D83B01] bg-[#FFEFD6] px-3 py-1.5 rounded-lg uppercase tracking-widest border border-[#FFD8A8] flex items-center gap-2">
                            <i class="ph-bold ph-star"></i>
                            Mulai: {{ \Carbon\Carbon::parse($startDate)->isoFormat('D MMMM') }}
                        </span>
                    </div>

                    <div class="grid grid-cols-7 sm:grid-cols-8 md:grid-cols-10 gap-2 sm:gap-3">
                        @for ($i = 0; $i < $totalRamadanDays; $i++)
                            @php
                                $dateCheck = \Carbon\Carbon::parse($startDate)->addDays($i);
                                $dateString = $dateCheck->format('Y-m-d');
                                $isToday = $dateString === $today;
                                $isPast = $dateCheck->lt(\Carbon\Carbon::parse($today));
                                $logExists = isset($calendarLogs[$dateString]);
                                
                                $containerClass = "bg-slate-50 border-slate-200 text-slate-400";
                                $badge = null;

                                if ($isToday) {
                                    $containerClass = "bg-[#5295FF] border-[#5295FF] text-white shadow-lg shadow-[#5295FF]/30 scale-110 ring-4 ring-white z-10";
                                } elseif ($logExists) {
                                    $containerClass = "bg-[#DFF6DD] border-[#B7DFB9] text-[#107C10]";
                                    $badge = '<div class="absolute -top-1.5 -right-1.5 bg-[#107C10] text-white rounded-full p-0.5 shadow-sm border border-white"><i class="ph-bold ph-check text-[10px]"></i></div>';
                                } elseif ($isPast) {
                                    $containerClass = "bg-[#FDE7E9] border-[#F4C3C9] text-[#D13438] opacity-70";
                                    $badge = '<div class="absolute -top-1.5 -right-1.5 bg-white text-[#D13438] rounded-full p-0.5 shadow-sm border border-[#F4C3C9]"><i class="ph-bold ph-x text-[10px]"></i></div>';
                                }
                            @endphp
                            
                            <div class="aspect-square rounded-xl border flex flex-col items-center justify-center relative group cursor-default {{ $containerClass }}">
                                <span class="text-[8px] font-bold uppercase mb-0.5 opacity-80 tracking-widest">H-{{ $i + 1 }}</span>
                                <span class="text-sm font-black">{{ $dateCheck->format('d') }}</span>
                                {!! $badge !!}
                            </div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ALERTS PENGISIAN --}}
            @if(!$canFill)
            <div class="bg-[#FFEFD6] border border-[#FFD8A8] p-6 rounded-[2rem] text-center mb-8 fluent-card">
                <div class="w-12 h-12 bg-white text-[#D83B01] border border-[#FFD8A8] rounded-xl flex items-center justify-center mx-auto mb-4 shadow-sm"><i class="ph-bold ph-lock-key text-2xl"></i></div>
                <h3 class="font-bold text-[#2A3B52] text-lg">Waktu Pengisian Ditutup</h3>
                <p class="text-sm text-slate-600 mt-1 max-w-md mx-auto font-medium">Formulir mutabaah ini hanya terbuka pada jam yang telah ditentukan oleh sekolah.</p>
            </div>
            @endif
            
            @if($todayRamadanLog)
            <div class="bg-[#DFF6DD] border border-[#B7DFB9] p-6 rounded-[2rem] text-center mb-8 fluent-card">
                <div class="w-12 h-12 bg-white text-[#107C10] border border-[#B7DFB9] rounded-xl flex items-center justify-center mx-auto mb-4 shadow-sm"><i class="ph-fill ph-check-circle text-2xl"></i></div>
                <h3 class="font-bold text-[#2A3B52] text-lg">Alhamdulillah!</h3>
                <p class="text-sm text-slate-600 mt-1 font-medium">Kamu sudah mengisi jurnal mutabaah hari ini. Data tersimpan aman.</p>
            </div>
            @endif

            {{-- FORMULIR UTAMA --}}
            <form action="{{ route('student.ramadan.save') }}" method="POST" @submit="isSaving = true">
                @csrf
                <input type="hidden" name="date" value="{{ $today }}">

                <fieldset {{ !$canFill ? 'disabled' : '' }} class="contents group-disabled:opacity-50 group-disabled:pointer-events-none">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- KOLOM KIRI --}}
                        <div class="md:col-span-2 space-y-6">
                            {{-- PUASA --}}
                            <div class="bg-white p-6 rounded-[2rem] fluent-card flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] flex items-center justify-center shadow-sm"><i class="ph-bold ph-bowl-food text-2xl"></i></div>
                                    <div>
                                        <h3 class="font-bold text-[#2A3B52]">Status Puasa</h3>
                                        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Apakah kamu berpuasa hari ini?</p>
                                    </div>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="is_fasting" value="0">
                                    <input type="checkbox" name="is_fasting" value="1" class="sr-only peer" {{ ($todayRamadanLog->is_fasting ?? true) ? 'checked' : '' }}>
                                    <div class="w-12 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#5295FF]"></div>
                                </label>
                            </div>

                            {{-- SHALAT Wajib --}}
                            <div class="bg-white p-6 md:p-8 rounded-[2rem] fluent-card">
                                <h3 class="font-bold text-[#2A3B52] mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
                                    <div class="p-2 rounded-lg bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8]"><i class="ph-fill ph-clock"></i></div> 
                                    Shalat Wajib 5 Waktu
                                </h3>
                                <div class="grid grid-cols-2 sm:grid-cols-5 gap-3">
                                    @foreach(['subuh', 'dzuhur', 'ashar', 'maghrib', 'isya'] as $p)
                                    @php $checked = $todayRamadanLog->prayers[$p] ?? false; @endphp
                                    <label class="cursor-pointer group">
                                        <input type="hidden" name="prayer_{{ $p }}" value="0">
                                        <input type="checkbox" name="prayer_{{ $p }}" value="1" class="hidden peer" {{ $checked ? 'checked' : '' }}>
                                        <div class="p-4 rounded-2xl border-2 border-slate-100 bg-slate-50 text-slate-400 transition-all peer-checked:bg-[#F3F9FD] peer-checked:border-[#5295FF] peer-checked:text-[#5295FF] flex flex-col items-center gap-2 shadow-sm">
                                            <span class="text-[10px] font-bold uppercase tracking-widest">{{ $p }}</span>
                                            <i class="ph-bold ph-check-circle text-2xl"></i>
                                        </div>
                                    </label>
                                    @endforeach
                                </div>
                            </div>

                            {{-- TILAWAH --}}
                            <div class="bg-white p-6 md:p-8 rounded-[2rem] fluent-card">
                                <h3 class="font-bold text-[#2A3B52] mb-6 flex items-center gap-3 border-b border-slate-100 pb-4">
                                    <div class="p-2 rounded-lg bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8]"><i class="ph-fill ph-book-open text-lg"></i></div> 
                                    Tilawah & Murojaah
                                </h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Surah Tadarus</label>
                                        <div class="flex gap-2">
                                            <input type="text" name="tadarus_surah" value="{{ $todayRamadanLog->tadarus_surah ?? '' }}" class="flex-1 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-[#5295FF] focus:border-[#5295FF] shadow-sm text-[#2A3B52]" placeholder="Surah">
                                            <input type="number" name="tadarus_ayah" value="{{ $todayRamadanLog->tadarus_ayah ?? '' }}" class="w-24 bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-[#5295FF] focus:border-[#5295FF] shadow-sm text-[#2A3B52]" placeholder="Ayat">
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Hafalan / Murojaah</label>
                                        <input type="text" name="murojaah_surah" value="{{ $todayRamadanLog->murojaah_surah ?? '' }}" class="w-full bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-[#5295FF] focus:border-[#5295FF] shadow-sm text-[#2A3B52]" placeholder="Contoh: An-Naba">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- KOLOM KANAN --}}
                        <div class="space-y-6">
                            {{-- KULTUM --}}
                            <div class="bg-white p-6 rounded-[2rem] fluent-card">
                                <h3 class="font-bold text-[#2A3B52] mb-5 flex items-center gap-3 border-b border-slate-100 pb-4">
                                    <div class="p-2 rounded-lg bg-purple-50 text-purple-600 border border-purple-200"><i class="ph-fill ph-microphone-stage text-lg"></i></div> 
                                    Laporan Kultum
                                </h3>
                                <div class="space-y-5">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Penceramah</label>
                                        <input type="text" name="kultum_penceramah" value="{{ $todayRamadanLog->kultum_penceramah ?? '' }}" class="w-full bg-white border border-slate-200 rounded-xl text-sm font-bold focus:ring-purple-500 focus:border-purple-500 shadow-sm text-[#2A3B52]" placeholder="Nama Penceramah...">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Ringkasan Materi</label>
                                        <textarea name="kultum_summary" rows="4" class="w-full bg-white border border-slate-200 rounded-xl text-sm font-medium focus:ring-purple-500 focus:border-purple-500 shadow-sm text-[#2A3B52] resize-none" placeholder="Apa inti ceramahnya?">{{ $todayRamadanLog->kultum_summary ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- SUNNAH --}}
                            <div class="bg-white p-6 md:p-8 rounded-[2rem] fluent-card flex flex-col h-full">
                                <h3 class="font-bold text-[#2A3B52] mb-5 flex items-center gap-3 border-b border-slate-100 pb-4">
                                    <div class="p-2 rounded-lg bg-[#FFEFD6] text-[#D83B01] border border-[#FFD8A8]"><i class="ph-fill ph-star text-lg"></i></div> 
                                    Amalan Sunnah
                                </h3>
                                <div class="space-y-3 flex-1">
                                    @foreach(['tarawih', 'witir', 'dhuha', 'rawatib', 'sedekah'] as $s)
                                    @php $checked = $todayRamadanLog->sunnah_deeds[$s] ?? false; @endphp
                                    <label class="flex items-center justify-between p-4 rounded-xl border-2 transition-all cursor-pointer group hover:bg-slate-50
                                        {{ $checked ? 'border-[#5295FF] bg-[#F3F9FD]' : 'border-slate-100 bg-white' }}">
                                        <span class="text-xs font-bold capitalize tracking-wide {{ $checked ? 'text-[#5295FF]' : 'text-slate-600' }}">{{ $s }}</span>
                                        <input type="hidden" name="sunnah_{{ $s }}" value="0">
                                        <input type="checkbox" name="sunnah_{{ $s }}" value="1" class="w-5 h-5 rounded text-[#5295FF] focus:ring-[#5295FF] border-slate-300" {{ $checked ? 'checked' : '' }} onchange="this.closest('label').classList.toggle('border-[#5295FF]'); this.closest('label').classList.toggle('bg-[#F3F9FD]'); this.closest('label').classList.toggle('border-slate-100'); this.closest('label').classList.toggle('bg-white'); this.previousElementSibling.classList.toggle('text-[#5295FF]'); this.previousElementSibling.classList.toggle('text-slate-600');">
                                    </label>
                                    @endforeach
                                </div>

                                @if($canFill)
                                <button type="submit" class="w-full mt-8 bg-[#2A3B52] hover:bg-[#182436] text-white font-bold py-4 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 transform active:scale-95" :disabled="isSaving">
                                    <template x-if="!isSaving"><div class="flex items-center gap-2"><i class="ph-bold ph-floppy-disk text-lg"></i> Simpan Jurnal</div></template>
                                    <template x-if="isSaving"><div class="flex items-center gap-2"><i class="ph-bold ph-spinner animate-spin text-lg"></i> Memproses...</div></template>
                                </button>
                                @else
                                <div class="w-full mt-8 bg-slate-100 border border-slate-200 text-slate-400 font-bold py-4 rounded-xl text-center cursor-not-allowed flex justify-center items-center gap-2">
                                    <i class="ph-bold ph-lock-key text-lg"></i> Form Terkunci
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </fieldset>
            </form>
        @endif <!-- End Check !isRamadanEnded -->
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            Swal.fire({
                title: 'Alhamdulillah!',
                text: "{!! session('success') !!}",
                icon: 'success',
                confirmButtonText: 'Kembali',
                confirmButtonColor: '#107C10', 
                background: '#ffffff', 
                color: '#2A3B52', 
                iconColor: '#107C10',
                customClass: {
                    popup: 'fluent-modal rounded-[2rem] font-sans border border-slate-100',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                }
            });
        @endif
        
        @if(session('error'))
            Swal.fire({
                title: 'Perhatian',
                text: "{!! session('error') !!}",
                icon: 'warning',
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#D83B01',
                customClass: {
                    popup: 'fluent-modal rounded-[2rem] font-sans',
                    confirmButton: 'rounded-xl px-6 py-2.5 font-bold'
                }
            });
        @endif
    });


    function prayerWidgetIndex() {
        return {
            isLoading: true,
            usingGeolocation: false,
            city: null, 
            latitude: null,
            longitude: null,
            schedule: {},
            nextEventName: 'Memuat...',
            countdown: '00:00:00',
            locationName: 'Mencari Lokasi...',
            currentEvent: '',
            
            async init() {
                setTimeout(() => { this.checkLocation(); }, 500);
                setInterval(() => this.updateCountdown(), 1000);
            },

            checkLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude;
                            this.longitude = position.coords.longitude;
                            this.usingGeolocation = true;
                            this.locationName = "Lokasi Saat Ini"; 
                            this.fetchTimesByCoords();
                        },
                        (error) => { this.useFallbackCity(); }
                    );
                } else {
                    this.useFallbackCity();
                }
            },

            useFallbackCity() {
                this.usingGeolocation = false;
                this.city = '{{ $userCity }}'; 
                this.locationName = this.city;
                this.fetchTimesByCity();
            },

            async fetchTimesByCoords() {
                try {
                    const date = new Date();
                    const timestamp = Math.floor(date.getTime() / 1000);
                    const url = `https://api.aladhan.com/v1/timings/${timestamp}?latitude=${this.latitude}&longitude=${this.longitude}&method=20`;
                    
                    const res = await fetch(url);
                    const data = await res.json();
                    this.processData(data);
                } catch (e) { this.useFallbackCity(); }
            },

            async fetchTimesByCity() {
                try {
                    const date = new Date();
                    const url = `https://api.aladhan.com/v1/timingsByCity/${date.getDate()}-${date.getMonth()+1}-${date.getFullYear()}?city=${this.city}&country=Indonesia&method=20`;
                    
                    const res = await fetch(url);
                    const data = await res.json();
                    this.processData(data);
                } catch (e) {
                    this.nextEventName = "Offline";
                    this.isLoading = false;
                }
            },

            processData(data) {
                if(data.code === 200) {
                    const timings = data.data.timings;
                    this.schedule = {
                        'Imsak': timings.Imsak,
                        'Subuh': timings.Fajr,
                        'Dzuhur': timings.Dhuhr,
                        'Ashar': timings.Asr,
                        'Maghrib': timings.Maghrib,
                        'Isya': timings.Isha
                    };
                    this.updateCountdown();
                    this.isLoading = false;
                }
            },

            updateCountdown() {
                if(this.isLoading || !this.schedule['Subuh']) return;
                const now = new Date();
                let nextTime = null;
                let nextName = '';
                let minDiff = Infinity;

                for (const [name, timeStr] of Object.entries(this.schedule)) {
                    const [hours, minutes] = timeStr.split(':');
                    const timeDate = new Date();
                    timeDate.setHours(hours, minutes, 0);
                    if (timeDate < now) continue;
                    const diff = timeDate - now;
                    if (diff < minDiff) {
                        minDiff = diff;
                        nextTime = timeDate;
                        nextName = name;
                    }
                }

                if (!nextTime && this.schedule['Imsak']) {
                    const [hours, minutes] = this.schedule['Imsak'].split(':');
                    nextTime = new Date();
                    nextTime.setDate(nextTime.getDate() + 1);
                    nextTime.setHours(hours, minutes, 0);
                    nextName = 'Imsak (Besok)';
                    minDiff = nextTime - now;
                }

                if (nextTime) {
                    const h = Math.floor(minDiff / (1000 * 60 * 60));
                    const m = Math.floor((minDiff % (1000 * 60 * 60)) / (1000 * 60));
                    const s = Math.floor((minDiff % (1000 * 60)) / 1000);
                    
                    this.countdown = `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
                    if(nextName === 'Maghrib') this.nextEventName = 'Menuju Berbuka';
                    else if (nextName.includes('Imsak')) this.nextEventName = 'Menuju Imsak';
                    else this.nextEventName = `Menuju ${nextName}`;
                    this.currentEvent = nextName.replace(' (Besok)', '');
                }
            }
        }
    }
</script>
@endpush