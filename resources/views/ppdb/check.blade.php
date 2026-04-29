@extends('layouts.public')

@section('content')
@php
    $currentTime = \Carbon\Carbon::now();
    $isOpen = isset($announcementDate) ? $currentTime->greaterThanOrEqualTo($announcementDate) : false;
    
    // Debug/Fallback
    if(isset($customError) || session('error')) {
        $isOpen = true; 
    }
@endphp

{{-- STYLE KHUSUS ELEVATE --}}
<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    
    .countdown-box {
        background: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-elevate-surface font-sans py-12 px-4 sm:px-6">
    
    {{-- Background Pattern & Glow (Elevate Style) --}}
    <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-elevate-peach-light/30 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="w-full max-w-lg z-10 animate-enter">
        
        {{-- Tombol Kembali --}}
        <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-white/60 border border-white/60 text-elevate-dark font-bold text-sm hover:bg-white transition-all shadow-sm backdrop-blur-md mb-6 active:scale-95">
            <i class="ph-bold ph-arrow-left text-lg"></i> Kembali ke Beranda
        </a>

        {{-- MAIN CARD --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            
            {{-- Header Card --}}
            <div class="bg-elevate-gradient-card px-8 py-10 text-center border-b border-slate-100 relative overflow-hidden">
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-elevate-primary/10 rounded-full blur-xl pointer-events-none"></div>
                <div class="w-20 h-20 bg-white shadow-sm border border-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-5 rotate-3 text-elevate-primary">
                    <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                </div>
                <h2 class="text-2xl md:text-3xl font-black text-elevate-dark tracking-tight mb-2">Cek Status PPDB</h2>
                <p class="text-sm text-elevate-dark/70 font-semibold max-w-xs mx-auto">
                    Masukkan nomor pendaftaran Anda untuk melihat hasil seleksi.
                </p>
            </div>

            <div class="p-8 md:p-10">
                @if(isset($customError) || session('error'))
                    <div class="mb-6 p-4 bg-[#FDE7E9] border border-[#F4C3C9] text-[#D13438] rounded-2xl text-sm font-bold flex items-start gap-3 shadow-sm">
                        <i class="ph-fill ph-warning-circle text-xl shrink-0 mt-0.5"></i>
                        <p>{{ $customError ?? session('error') }}</p>
                    </div>
                @endif

                @if(!$isOpen && isset($announcementDate))
                    {{-- COUNTDOWN MODE --}}
                    <div class="text-center">
                        <div class="inline-block px-4 py-2 bg-elevate-soft text-elevate-primary rounded-xl text-xs font-black uppercase tracking-widest mb-6 border border-slate-200 shadow-sm">
                            Pengumuman Belum Dibuka
                        </div>
                        <p class="text-elevate-dark/80 font-bold mb-6">Hasil seleksi akan diumumkan dalam:</p>
                        
                        <div class="grid grid-cols-4 gap-3 mb-4">
                            <div class="countdown-box p-3">
                                <div id="days" class="text-2xl font-black text-elevate-dark leading-none">00</div>
                                <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase">Hari</div>
                            </div>
                            <div class="countdown-box p-3">
                                <div id="hours" class="text-2xl font-black text-elevate-dark leading-none">00</div>
                                <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase">Jam</div>
                            </div>
                            <div class="countdown-box p-3">
                                <div id="minutes" class="text-2xl font-black text-elevate-dark leading-none">00</div>
                                <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase">Menit</div>
                            </div>
                            <div class="countdown-box p-3">
                                <div id="seconds" class="text-2xl font-black text-elevate-primary leading-none">00</div>
                                <div class="text-[10px] font-bold text-slate-400 mt-1 uppercase">Detik</div>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 font-medium italic mt-4">Tanggal: {{ $announcementDate->translatedFormat('d F Y, H:i') }} WIB</p>
                    </div>
                @else
                    {{-- FORM PENCARIAN --}}
                    <form action="{{ route('ppdb.search') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label for="registration_number" class="block text-xs font-black text-elevate-primary uppercase tracking-widest mb-2 ml-1">Nomor Registrasi</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-elevate-primary transition-colors">
                                    <i class="ph-bold ph-identification-card text-xl"></i>
                                </div>
                                <input type="text" name="registration_number" id="registration_number" required
                                       class="w-full pl-12 pr-5 py-4 bg-elevate-soft focus:bg-white border border-slate-200 rounded-2xl text-elevate-dark font-black tracking-wider focus:ring-elevate-accent/30 focus:border-elevate-accent transition-all shadow-sm placeholder:text-slate-400 placeholder:font-medium" 
                                       placeholder="PPDB-2024-XXXX">
                            </div>
                            <p class="mt-2 text-xs text-slate-500 font-semibold ml-1">Cek nomor pada bukti pendaftaran Anda.</p>
                        </div>

                        <button type="submit" class="w-full bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-2xl py-4 flex items-center justify-center gap-2 shadow-lg shadow-elevate-dark/30 transition-all active:scale-95 border border-transparent">
                            <i class="ph-bold ph-magnifying-glass text-lg"></i>
                            <span>Cek Hasil Seleksi</span>
                        </button>
                    </form>
                @endif
            </div>
            
            <div class="bg-slate-50 border-t border-slate-100 p-6 text-center">
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest">&copy; {{ date('Y') }} Panitia PPDB SMPN 3 Lakbok</p>
            </div>
        </div>
    </div>
</div>

@if(!$isOpen && isset($announcementDate))
<script>
    const targetDateStr = "{{ $announcementDate->format('Y-m-d H:i:s') }}";
    const countDownDate = new Date(targetDateStr).getTime();

    const x = setInterval(function() {
        const now = new Date().getTime();
        const distance = countDownDate - now;

        const days = Math.floor(distance / (1000 * 60 * 60 * 24));
        const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        if(document.getElementById("days")) {
            document.getElementById("days").innerText = days < 10 ? "0" + days : days;
            document.getElementById("hours").innerText = hours < 10 ? "0" + hours : hours;
            document.getElementById("minutes").innerText = minutes < 10 ? "0" + minutes : minutes;
            document.getElementById("seconds").innerText = seconds < 10 ? "0" + seconds : seconds;
        }

        if (distance < 0) {
            clearInterval(x);
            window.location.reload();
        }
    }, 1000);
</script>
@endif
@endsection