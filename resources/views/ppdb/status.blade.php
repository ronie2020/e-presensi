@extends('layouts.public')

@section('content')

<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    /* Custom Status Cards */
    .status-badge-accepted {
        background: radial-gradient(circle at top right, #34d399, #107C10);
        box-shadow: 0 20px 40px -10px rgba(16, 124, 16, 0.4);
    }
    
    .status-badge-rejected {
        background: radial-gradient(circle at top right, #f472b6, #D13438);
        box-shadow: 0 20px 40px -10px rgba(209, 52, 56, 0.4);
    }
    
    .status-badge-pending {
        background: radial-gradient(circle at top right, #fbbf24, #D83B01);
        box-shadow: 0 20px 40px -10px rgba(216, 59, 1, 0.4);
    }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-elevate-surface font-sans py-12 px-4 sm:px-6">
    
    {{-- Background Effect Elevate --}}
    <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

    <div class="w-full max-w-2xl z-10 animate-enter">
        
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden relative">
            
            {{-- Header/Title --}}
            <div class="bg-elevate-gradient-card px-8 py-8 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-black text-elevate-dark tracking-tight">Hasil Seleksi PPDB</h2>
                    <p class="text-xs text-elevate-dark/70 font-bold uppercase tracking-widest mt-1">Tahun Ajaran {{ date('Y') }}/{{ date('Y')+1 }}</p>
                </div>
                <div class="w-12 h-12 bg-white rounded-xl shadow-sm border border-slate-100 flex items-center justify-center text-elevate-primary">
                    <i class="ph-bold ph-identification-card text-2xl"></i>
                </div>
            </div>

            <div class="p-8 md:p-10">
                <div class="bg-elevate-soft/50 border border-slate-100 rounded-[2rem] p-6 md:p-8 mb-8 text-center relative overflow-hidden">
                    <div class="absolute -top-10 -left-10 w-32 h-32 bg-white rounded-full opacity-50 blur-xl"></div>
                    <p class="text-[10px] text-elevate-primary font-black uppercase tracking-[0.2em] mb-2 relative z-10">Nomor Registrasi</p>
                    <h3 class="text-2xl md:text-3xl font-black text-elevate-dark tracking-widest font-mono relative z-10">{{ $registrant->registration_number }}</h3>
                    <p class="text-sm text-elevate-dark/80 font-bold mt-2 relative z-10">{{ $registrant->full_name }}</p>
                </div>

                <div class="text-center">
                    <p class="text-xs text-slate-500 font-bold mb-4 uppercase tracking-widest">Berdasarkan hasil seleksi, Anda dinyatakan:</p>
                    
                    @if($registrant->status === 'Diterima')
                        <div class="status-badge-accepted rounded-[2rem] p-8 md:p-10 text-white relative overflow-hidden mb-6 group">
                            <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-check-circle text-[15rem]"></i>
                            </div>
                            <div class="relative z-10">
                                <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-4 drop-shadow-sm leading-tight">DITERIMA</h1>
                                <p class="text-emerald-50 text-lg font-medium leading-relaxed max-w-lg mx-auto">
                                    Selamat! Anda telah diterima sebagai siswa baru. Silakan unduh surat keterangan diterima untuk syarat daftar ulang.
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-4 mt-8">
                            <a href="{{ route('ppdb.check') }}" class="flex-1 py-4 bg-white text-elevate-dark font-bold rounded-2xl text-sm hover:bg-elevate-soft transition-colors border border-slate-200 text-center active:scale-95 shadow-sm">
                                <i class="ph-bold ph-arrow-left mr-1.5"></i> Kembali
                            </a>
                            <a href="{{ route('ppdb.printLetter', $registrant->id) }}" target="_blank" class="flex-1 py-4 bg-elevate-dark text-white font-bold rounded-2xl text-sm shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 active:scale-95 border border-transparent">
                                <i class="ph-bold ph-printer text-xl"></i> Cetak Surat
                            </a>
                        </div>
                    
                    @elseif($registrant->status === 'Ditolak')
                        <div class="status-badge-rejected rounded-[2rem] p-8 md:p-10 text-white relative overflow-hidden mb-6 group">
                            <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-x-circle text-[15rem]"></i>
                            </div>
                            <div class="relative z-10">
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-4 drop-shadow-sm leading-tight">MOHON MAAF</h1>
                                <p class="text-rose-50 text-lg font-medium leading-relaxed max-w-lg mx-auto">
                                    Anda belum lolos seleksi penerimaan siswa baru tahun ini. Tetap semangat dan jangan putus asa.
                                </p>
                            </div>
                        </div>
                        <div class="mt-8 text-center">
                            <a href="{{ route('ppdb.check') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg transition-all hover:bg-elevate-primary active:scale-95 text-sm">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                        </div>
                    
                    @else
                        <div class="status-badge-pending rounded-[2rem] p-8 md:p-10 text-white relative overflow-hidden mb-6 group">
                            <div class="absolute -right-10 -bottom-10 opacity-10 group-hover:scale-110 transition-transform duration-500">
                                <i class="ph-fill ph-clock-countdown text-[15rem]"></i>
                            </div>
                            <div class="relative z-10">
                                <h2 class="text-xs font-bold text-amber-100 mb-3 uppercase tracking-[0.2em] border-b border-white/20 inline-block pb-1">Status Data</h2>
                                <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-4 drop-shadow-sm">VERIFIKASI</h1>
                                <p class="text-amber-50 text-lg font-medium leading-relaxed max-w-lg mx-auto">
                                    Data pendaftaran Anda sedang dalam proses pemeriksaan oleh panitia. Mohon cek kembali secara berkala.
                                </p>
                            </div>
                        </div>
                        <div class="mt-8 text-center">
                            <a href="{{ route('ppdb.check') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl shadow-lg transition-all hover:bg-elevate-primary active:scale-95 text-sm">
                                <i class="ph-bold ph-arrow-left"></i> Cek Ulang Nanti
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        
        <div class="mt-8 text-center">
            <p class="text-[10px] text-slate-400 font-bold tracking-widest uppercase">&copy; {{ date('Y') }} Panitia PPDB SMPN 3 Lakbok</p>
        </div>
    </div>
</div>
@endsection