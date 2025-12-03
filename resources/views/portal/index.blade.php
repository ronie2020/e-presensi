@extends('layouts.public')

@section('content')
<div class="max-w-3xl mx-auto text-center">
    
    <!-- Hero Text -->
    <div class="mb-10" data-aos="fade-up">
        <span class="inline-block py-1 px-3 rounded-full bg-blue-50 text-blue-600 text-xs font-bold uppercase tracking-wider mb-4 border border-blue-100">
            Portal Akademik Siswa
        </span>
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight mb-4">
            Cek Prestasi & <br> <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-cyan-500">Kehadiranmu Disini</span>
        </h1>
        <p class="text-lg text-slate-500 max-w-xl mx-auto">
            Masukkan Nomor Induk Siswa (NIS/NISN) untuk mengakses data akademik, riwayat absensi, dan poin kedisiplinan.
        </p>
    </div>

    <!-- Search Form Card -->
    <div class="bg-white p-2 rounded-3xl shadow-xl shadow-blue-900/5 border border-slate-100 max-w-xl mx-auto relative overflow-hidden" data-aos="fade-up" data-aos-delay="100">
        
        <!-- Decoration -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-blue-100 rounded-full mix-blend-multiply filter blur-2xl opacity-50 -mr-10 -mt-10 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-32 h-32 bg-purple-100 rounded-full mix-blend-multiply filter blur-2xl opacity-50 -ml-10 -mb-10 pointer-events-none"></div>

        <div class="relative bg-white rounded-2xl p-6 sm:p-8">
            <form action="{{ route('portal.search') }}" method="POST" class="space-y-4">
                
                {{-- [PENTING] INI YANG MENYEBABKAN ERROR 419 JIKA TIDAK ADA --}}
                @csrf 
                
                <div class="text-left">
                    <label for="student_id" class="block text-sm font-bold text-slate-700 mb-2 ml-1">Nomor Induk Siswa (NIS/NISN)</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                            <i class="ph-bold ph-identification-card text-xl"></i>
                        </div>
                        <input type="text" name="student_id" id="student_id" 
                            class="block w-full pl-11 pr-4 py-4 bg-slate-50 border-slate-200 text-slate-900 text-lg font-bold rounded-xl focus:ring-blue-500 focus:border-blue-500 placeholder-slate-300 transition-all shadow-sm" 
                            placeholder="Contoh: 2024001" required autofocus>
                    </div>
                </div>

                <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl shadow-lg shadow-blue-500/30 transition-all transform hover:-translate-y-0.5 flex items-center justify-center gap-2 text-lg">
                    <i class="ph-bold ph-magnifying-glass"></i>
                    Cari Data Saya
                </button>
            </form>
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-12">
        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm text-center" data-aos="fade-up" data-aos-delay="200">
            <div class="w-10 h-10 mx-auto bg-green-50 text-green-600 rounded-full flex items-center justify-center mb-3">
                <i class="ph-fill ph-calendar-check text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-600 uppercase">Absensi Realtime</p>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm text-center" data-aos="fade-up" data-aos-delay="300">
            <div class="w-10 h-10 mx-auto bg-blue-50 text-blue-600 rounded-full flex items-center justify-center mb-3">
                <i class="ph-fill ph-trend-up text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-600 uppercase">Monitoring Poin</p>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm text-center" data-aos="fade-up" data-aos-delay="400">
            <div class="w-10 h-10 mx-auto bg-yellow-50 text-yellow-600 rounded-full flex items-center justify-center mb-3">
                <i class="ph-fill ph-trophy text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-600 uppercase">Rekap Prestasi</p>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm text-center" data-aos="fade-up" data-aos-delay="500">
            <div class="w-10 h-10 mx-auto bg-purple-50 text-purple-600 rounded-full flex items-center justify-center mb-3">
                <i class="ph-fill ph-books text-xl"></i>
            </div>
            <p class="text-xs font-bold text-slate-600 uppercase">Riwayat Pustaka</p>
        </div>
    </div>

    <!-- Error Alert (Jika data tidak ditemukan) -->
    @if(session('error'))
        <div class="mt-8 p-4 bg-rose-50 text-rose-600 rounded-2xl border border-rose-100 flex items-center justify-center gap-2 max-w-md mx-auto animate-bounce">
            <i class="ph-fill ph-warning-circle text-xl"></i>
            <span class="font-bold text-sm">{{ session('error') }}</span>
        </div>
    @endif

</div>
@endsection