@extends('layouts.public')

@section('content')

{{-- STYLE TAMBAHAN UNTUK PRINT --}}
<style>
    @media print {
        body, .min-h-screen { 
            background: white !important; 
            height: auto !important; 
            overflow: visible !important; 
            display: block !important;
        }
        .no-print, .bg-ornaments { display: none !important; }
        .print-area {
            box-shadow: none !important;
            border: 2px solid #000 !important;
            background: white !important;
            color: black !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }
        .text-white { color: black !important; }
        .text-slate-400 { color: #555 !important; }
        .bg-gradient-to-r { background: none !important; border-bottom: 2px solid #000; }
    }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-elevate-surface font-sans py-12 px-4 sm:px-6">
    
    {{-- Background Pattern Elevate --}}
    <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl bg-ornaments"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-elevate-peach-light/30 rounded-full blur-3xl pointer-events-none -z-10 bg-ornaments"></div>

    <div class="w-full max-w-lg z-10 animate-enter print-area bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            
        <div class="bg-elevate-gradient-card px-8 py-10 text-center border-b border-slate-100 relative overflow-hidden">
            <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-elevate-primary/10 rounded-full blur-xl pointer-events-none bg-ornaments"></div>
            
            <div class="w-20 h-20 bg-white shadow-sm rounded-2xl flex items-center justify-center mx-auto mb-5 rotate-3 border border-slate-100 text-[#107C10]">
                <i class="ph-fill ph-check-circle text-5xl"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-elevate-dark tracking-tight mb-2">Pendaftaran Sukses!</h2>
            <p class="text-sm text-elevate-dark/80 font-bold max-w-xs mx-auto">
                Data Anda telah masuk ke dalam sistem panitia PPDB.
            </p>
        </div>

        <div class="p-8 md:p-10">
            <div class="text-center">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-3">Nomor Registrasi Anda</p>
                <div class="bg-elevate-soft border-2 border-slate-200 rounded-2xl py-6 px-4 mb-6 shadow-sm">
                    <h3 class="text-3xl md:text-4xl font-black text-elevate-dark tracking-widest font-mono select-all">
                        {{ session('registration_number') }}
                    </h3>
                </div>
                
                <p class="text-sm font-bold text-elevate-dark mb-6">
                    Nama: <span class="font-black text-elevate-primary">{{ session('student_name') }}</span>
                </p>

                <div class="bg-[#FFEFD6] text-[#D83B01] rounded-2xl p-5 text-left flex gap-3 border border-[#FFD8A8] mb-8 shadow-sm no-print">
                    <i class="ph-fill ph-info text-xl shrink-0 mt-0.5"></i>
                    <p class="text-xs font-bold leading-relaxed">
                        PENTING: Screenshot atau cetak halaman ini. Gunakan Nomor Registrasi di atas untuk mengecek status kelulusan nanti.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 no-print">
                    <a href="{{ url('/') }}" class="flex-1 py-4 bg-white text-elevate-dark font-bold rounded-2xl text-sm hover:bg-elevate-soft transition-colors text-center border border-slate-200 shadow-sm active:scale-95">
                        Ke Beranda
                    </a>
                    <button onclick="window.print()" class="flex-1 py-4 bg-elevate-dark text-white font-bold rounded-2xl text-sm shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 active:scale-95 border border-transparent">
                        <i class="ph-bold ph-printer text-xl"></i> Cetak Bukti
                    </button>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-50 border-t border-slate-100 p-6 text-center">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">&copy; {{ date('Y') }} Panitia PPDB SMPN 3 Lakbok</p>
        </div>

    </div>
</div>
@endsection@extends('layouts.public')

@section('content')

{{-- STYLE TAMBAHAN UNTUK PRINT --}}
<style>
    @media print {
        body, .min-h-screen { 
            background: white !important; 
            height: auto !important; 
            overflow: visible !important; 
            display: block !important;
        }
        .no-print, .bg-ornaments { display: none !important; }
        .print-area {
            box-shadow: none !important;
            border: 2px solid #000 !important;
            background: white !important;
            color: black !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            border-radius: 0 !important;
        }
        .text-white { color: black !important; }
        .text-slate-400 { color: #555 !important; }
        .bg-gradient-to-r { background: none !important; border-bottom: 2px solid #000; }
    }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-elevate-surface font-sans py-12 px-4 sm:px-6">
    
    {{-- Background Pattern Elevate --}}
    <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl bg-ornaments"></div>
    <div class="absolute bottom-0 right-0 w-[500px] h-[500px] bg-elevate-peach-light/30 rounded-full blur-3xl pointer-events-none -z-10 bg-ornaments"></div>

    <div class="w-full max-w-lg z-10 animate-enter print-area bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
            
        <div class="bg-elevate-gradient-card px-8 py-10 text-center border-b border-slate-100 relative overflow-hidden">
            <div class="absolute -left-10 -bottom-10 w-32 h-32 bg-elevate-primary/10 rounded-full blur-xl pointer-events-none bg-ornaments"></div>
            
            <div class="w-20 h-20 bg-white shadow-sm rounded-2xl flex items-center justify-center mx-auto mb-5 rotate-3 border border-slate-100 text-[#107C10]">
                <i class="ph-fill ph-check-circle text-5xl"></i>
            </div>
            <h2 class="text-2xl md:text-3xl font-black text-elevate-dark tracking-tight mb-2">Pendaftaran Sukses!</h2>
            <p class="text-sm text-elevate-dark/80 font-bold max-w-xs mx-auto">
                Data Anda telah masuk ke dalam sistem panitia PPDB.
            </p>
        </div>

        <div class="p-8 md:p-10">
            <div class="text-center">
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mb-3">Nomor Registrasi Anda</p>
                <div class="bg-elevate-soft border-2 border-slate-200 rounded-2xl py-6 px-4 mb-6 shadow-sm">
                    <h3 class="text-3xl md:text-4xl font-black text-elevate-dark tracking-widest font-mono select-all">
                        {{ session('registration_number') }}
                    </h3>
                </div>
                
                <p class="text-sm font-bold text-elevate-dark mb-6">
                    Nama: <span class="font-black text-elevate-primary">{{ session('student_name') }}</span>
                </p>

                <div class="bg-[#FFEFD6] text-[#D83B01] rounded-2xl p-5 text-left flex gap-3 border border-[#FFD8A8] mb-8 shadow-sm no-print">
                    <i class="ph-fill ph-info text-xl shrink-0 mt-0.5"></i>
                    <p class="text-xs font-bold leading-relaxed">
                        PENTING: Screenshot atau cetak halaman ini. Gunakan Nomor Registrasi di atas untuk mengecek status kelulusan nanti.
                    </p>
                </div>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-3 no-print">
                    <a href="{{ url('/') }}" class="flex-1 py-4 bg-white text-elevate-dark font-bold rounded-2xl text-sm hover:bg-elevate-soft transition-colors text-center border border-slate-200 shadow-sm active:scale-95">
                        Ke Beranda
                    </a>
                    <button onclick="window.print()" class="flex-1 py-4 bg-elevate-dark text-white font-bold rounded-2xl text-sm shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 active:scale-95 border border-transparent">
                        <i class="ph-bold ph-printer text-xl"></i> Cetak Bukti
                    </button>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-50 border-t border-slate-100 p-6 text-center">
            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">&copy; {{ date('Y') }} Panitia PPDB SMPN 3 Lakbok</p>
        </div>

    </div>
</div>
@endsection