@extends('layouts.public')

@section('content')

{{-- STYLE KHUSUS (Wajib ada agar tampilan sama) --}}
<style>
    .glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
    .text-shadow { text-shadow: 0 4px 20px rgba(0,0,0,0.5); }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-slate-950 font-sans py-10">
    
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-blue-950 to-slate-900"></div>
        <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
        <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-blue-600/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="relative z-10 w-full max-w-5xl px-4">
        
        <div class="mb-6">
            <a href="{{ route('ppdb.check') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition font-bold text-sm bg-slate-800/50 px-4 py-2 rounded-full border border-slate-700 hover:bg-slate-700">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Pencarian
            </a>
        </div>

        <div class="glass-card rounded-[2.5rem] overflow-hidden relative" data-aos="fade-up">
            <div class="h-1.5 w-full bg-gradient-to-r from-blue-500 via-indigo-600 to-violet-600"></div>

            <div class="p-8 md:p-12">
                <div class="flex flex-col md:flex-row gap-8 items-start">
                    
                    {{-- BAGIAN KIRI: FOTO & NAMA --}}
                    <div class="w-full md:w-1/3 flex flex-col items-center text-center">
                        <div class="relative w-48 h-48 mb-6 group">
                            {{-- Efek Glow sesuai status --}}
                            @php
                                $glowColor = match($registrant->status) {
                                    'accepted' => 'from-emerald-400 to-green-600',
                                    'rejected' => 'from-rose-400 to-red-600',
                                    default => 'from-amber-400 to-yellow-600',
                                };
                            @endphp
                            <div class="absolute inset-0 bg-gradient-to-br {{ $glowColor }} rounded-full blur-xl opacity-50 group-hover:opacity-75 transition-opacity"></div>
                            
                            <div class="relative w-full h-full rounded-full p-1.5 bg-gradient-to-br from-slate-700 to-slate-800 border border-slate-600 overflow-hidden shadow-2xl">
                                <div class="w-full h-full rounded-full bg-slate-800 overflow-hidden relative flex items-center justify-center">
                                    @if($registrant->file_photo)
                                        <img src="{{ asset('storage/' . $registrant->file_photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-5xl font-black text-slate-600 select-none">{{ substr($registrant->full_name, 0, 1) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <h3 class="text-2xl font-black text-white leading-tight mb-2">{{ $registrant->full_name }}</h3>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-slate-800 border border-slate-700">
                            <span class="text-slate-400 text-xs font-bold uppercase tracking-wider">No. Reg</span>
                            <span class="text-blue-400 font-mono font-bold">{{ $registrant->registration_number }}</span>
                        </div>
                    </div>

                    {{-- BAGIAN KANAN: STATUS --}}
                    <div class="w-full md:w-2/3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                            <div class="bg-slate-800/50 p-5 rounded-3xl border border-white/5 hover:border-white/10 transition-colors">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Jalur Pendaftaran</p>
                                <p class="font-bold text-white text-xl capitalize">{{ str_replace('_', ' ', $registrant->track) }}</p>
                            </div>
                            <div class="bg-slate-800/50 p-5 rounded-3xl border border-white/5 hover:border-white/10 transition-colors">
                                <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Asal Sekolah</p>
                                <p class="font-bold text-white text-xl">{{ $registrant->school_origin }}</p>
                            </div>
                        </div>

                        {{-- KARTU STATUS (DINAMIS) --}}
                        @if($registrant->status === 'accepted')
                            {{-- STATUS: DITERIMA --}}
                            <div class="relative bg-gradient-to-r from-emerald-600 to-teal-600 rounded-[2rem] p-8 text-center text-white shadow-2xl shadow-emerald-900/50 overflow-hidden mb-6 border border-emerald-400/30">
                                <div class="absolute top-0 left-0 w-full h-full bg-[url('https://www.transparenttextures.com/patterns/confetti.png')] opacity-20"></div>
                                <div class="relative z-10">
                                    <h2 class="text-sm font-bold text-emerald-100 mb-2 uppercase tracking-widest">Hasil Seleksi PPDB</h2>
                                    <div class="inline-block border-y-2 border-emerald-100/30 py-2 mb-4">
                                        <h1 class="text-4xl md:text-5xl font-black tracking-tight drop-shadow-md">DITERIMA</h1>
                                    </div>
                                    <p class="text-emerald-100 font-medium">Selamat! Anda diterima menjadi siswa baru di SMPN 3 Lakbok.</p>
                                </div>
                            </div>
                            {{-- PERBAIKAN DI SINI: route('ppdb.print-letter') diganti menjadi route('ppdb.print.letter') --}}
                            <a href="{{ route('ppdb.print.letter', $registrant->id) }}" target="_blank" class="w-full bg-white text-slate-900 hover:bg-blue-50 font-bold py-4 rounded-2xl transition-all flex items-center justify-center gap-2 shadow-lg group">
                                <i class="ph-printer text-xl group-hover:scale-110 transition-transform"></i> 
                                <span>Cetak Surat Kelulusan</span>
                            </a>

                        @elseif($registrant->status === 'rejected')
                            {{-- STATUS: TIDAK DITERIMA --}}
                            <div class="bg-gradient-to-r from-rose-600 to-red-700 rounded-[2rem] p-8 text-center text-white shadow-2xl shadow-rose-900/50 overflow-hidden mb-6 border border-rose-500/30">
                                <div class="relative z-10">
                                    <h2 class="text-sm font-bold text-rose-100 mb-2 uppercase tracking-widest">Hasil Seleksi PPDB</h2>
                                    <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-2 drop-shadow-md">MAAF, BELUM DITERIMA</h1>
                                    <p class="text-rose-100 font-medium">Jangan patah semangat, teruslah belajar dan mencoba.</p>
                                </div>
                            </div>
                        
                        @else
                            {{-- STATUS: PENDING / VERIFIKASI --}}
                            <div class="bg-gradient-to-r from-amber-500 to-orange-600 rounded-[2rem] p-8 text-center text-white shadow-2xl shadow-amber-900/50 overflow-hidden mb-6 border border-amber-400/30">
                                <div class="relative z-10">
                                    <h2 class="text-sm font-bold text-amber-100 mb-2 uppercase tracking-widest">Status Pendaftaran</h2>
                                    <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2 drop-shadow-md">SEDANG DIVERIFIKASI</h1>
                                    <p class="text-amber-100 font-medium">Data Anda sedang dalam proses pemeriksaan oleh panitia.</p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
            
            <div class="bg-slate-900/80 border-t border-white/5 p-4 text-center backdrop-blur-sm">
                <p class="text-xs text-slate-500 font-medium tracking-wide">&copy; {{ date('Y') }} Panitia PPDB SMPN 3 Lakbok</p>
            </div>
        </div>
    </div>
</div>
@endsection