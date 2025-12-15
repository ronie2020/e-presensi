@extends('layouts.public')

@section('content')
<style>
    .bg-grid-pattern {
        background-image: linear-gradient(to right, rgba(255,255,255,0.05) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(255,255,255,0.05) 1px, transparent 1px);
        background-size: 40px 40px;
    }
    .text-shadow { text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
    .glass-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.5);
    }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center relative overflow-hidden bg-[#1a0b2e]">
    
    <!-- BACKGROUND -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-900 via-[#2e1065] to-slate-900"></div>
        <div class="absolute inset-0 bg-grid-pattern opacity-20"></div>
    </div>

    <!-- MAIN CONTAINER -->
    <div class="relative z-10 w-full max-w-4xl px-4 py-10">
        
        <!-- HEADER -->
        <div class="text-center mb-10" data-aos="fade-down">
            <div class="inline-block p-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20 mb-6 shadow-2xl shadow-purple-900/50">
                <x-application-logo class="w-20 h-20 fill-current text-white" />
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight leading-tight mb-2 text-shadow uppercase">
                Pengumuman Kelulusan
            </h1>
            <p class="text-purple-200 text-lg font-medium tracking-wide">
                Tahun Pelajaran {{ date('Y') }}/{{ date('Y')+1 }}
            </p>
        </div>

        <!-- CONTENT CARD -->
        <div class="glass-card rounded-[2.5rem] shadow-2xl overflow-hidden relative" data-aos="fade-up">
            <div class="h-2 w-full bg-gradient-to-r from-purple-500 via-pink-500 to-indigo-500"></div>

            <div class="p-8 md:p-12">
                @if(!isset($student))
                {{-- FORM PENCARIAN --}}
                <div class="max-w-xl mx-auto text-center">
                    <h2 class="text-2xl font-bold text-slate-800 mb-2">Cek Status Kelulusan</h2>
                    <p class="text-slate-500 mb-8">Silakan masukkan Nomor Induk Siswa Nasional (NISN).</p>

                    <form action="{{ route('graduation.check') }}" method="POST" class="relative group">
                        @csrf
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                                <i class="ph-bold ph-student text-2xl text-purple-400"></i>
                            </div>
                            <input type="text" name="nisn" class="block w-full pl-14 pr-4 py-5 bg-purple-50/50 border-2 border-purple-100 text-slate-800 text-lg font-bold rounded-2xl focus:ring-4 focus:ring-purple-100 focus:border-purple-500 focus:bg-white transition-all placeholder:text-slate-400 outline-none" placeholder="Contoh: 0056xxxx" required autocomplete="off" autofocus>
                        </div>
                        
                        <button type="submit" class="w-full mt-4 bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white font-bold py-4 rounded-2xl shadow-lg shadow-purple-600/30 transform hover:-translate-y-1 transition-all flex items-center justify-center gap-2">
                            <span>Periksa Data</span>
                            <i class="ph-bold ph-magnifying-glass text-xl"></i>
                        </button>
                    </form>

                    @if(session('error'))
                        <div class="mt-6 p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-center gap-3 text-left animate-pulse">
                            <div class="bg-rose-100 p-2 rounded-full text-rose-600"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                            <div>
                                <h4 class="font-bold text-rose-700 text-sm">Pencarian Gagal</h4>
                                <p class="text-xs text-rose-600">{{ session('error') }}</p>
                            </div>
                        </div>
                    @endif
                </div>

                @else
                {{-- HASIL PENCARIAN --}}
                <div class="animate-fade-in-up">
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="w-full md:w-1/3 flex flex-col items-center text-center">
                            <div class="w-40 h-40 rounded-full p-1 bg-gradient-to-br from-purple-500 to-pink-500 shadow-xl mb-4">
                                <div class="w-full h-full rounded-full bg-white overflow-hidden border-4 border-white relative">
                                    @if($student->photo_path)
                                        <img src="{{ asset('storage/' . $student->photo_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full bg-slate-100 flex items-center justify-center text-4xl font-bold text-slate-300">{{ substr($student->name, 0, 1) }}</div>
                                    @endif
                                </div>
                            </div>
                            <h3 class="text-xl font-bold text-slate-800">{{ $student->name }}</h3>
                            <p class="text-slate-500 font-mono text-sm bg-slate-100 px-3 py-1 rounded-full mt-2">{{ $student->student_id }}</p>
                        </div>

                        <div class="w-full md:w-2/3">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Kelas</p>
                                    <p class="font-bold text-slate-800 text-lg">{{ $student->schoolClass->name ?? '-' }}</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100">
                                    <p class="text-xs text-slate-400 font-bold uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</p>
                                    <p class="font-bold text-slate-800 text-lg">{{ $student->pob }}, {{ \Carbon\Carbon::parse($student->dob)->format('d M Y') }}</p>
                                </div>
                            </div>

                            @if($student->graduation->status === 'LULUS')
                                <div class="bg-gradient-to-r from-emerald-500 to-teal-500 rounded-3xl p-8 text-center text-white shadow-xl shadow-emerald-500/20 relative overflow-hidden mb-6">
                                    <h2 class="text-lg font-medium text-emerald-100 mb-1">Hasil Rapat Pleno Dewan Guru</h2>
                                    <h1 class="text-4xl md:text-5xl font-black tracking-tight mb-2 drop-shadow-md">ANDA LULUS</h1>
                                    <p class="text-sm text-emerald-100 opacity-90">Selamat atas pencapaian luar biasa ini!</p>
                                </div>
                                <div class="flex gap-3">
                                    <a href="{{ route('graduation.print', $student->id) }}" target="_blank" class="flex-1 bg-slate-800 hover:bg-slate-900 text-white font-bold py-3.5 rounded-xl transition-all flex items-center justify-center gap-2 shadow-lg">
                                        <i class="ph-bold ph-printer text-lg"></i> Cetak SKL
                                    </a>
                                    <a href="{{ route('graduation.index') }}" class="px-6 py-3.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all">Cari Lagi</a>
                                </div>
                            @else
                                <div class="bg-gradient-to-r from-rose-500 to-red-600 rounded-3xl p-8 text-center text-white shadow-xl shadow-rose-500/20 relative overflow-hidden mb-6">
                                    <h2 class="text-lg font-medium text-rose-100 mb-1">Hasil Rapat Pleno Dewan Guru</h2>
                                    <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-2 drop-shadow-md uppercase">{{ $student->graduation->status ?? 'DITUNDA' }}</h1>
                                    <p class="text-sm text-rose-100 opacity-90">Silakan hubungi pihak sekolah.</p>
                                </div>
                                <a href="{{ route('graduation.index') }}" class="w-full block text-center px-6 py-3.5 bg-slate-100 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">Kembali</a>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
            
            <div class="bg-slate-50/50 border-t border-slate-100 p-4 text-center">
                <p class="text-xs text-slate-400 font-medium">&copy; {{ date('Y') }} Sistem Informasi Sekolah.</p>
            </div>
        </div>
    </div>
</div>
@endsection