@extends('layouts.public')

@section('content')

{{-- STYLE KHUSUS HALAMAN SUKSES PENDAFTARAN INDIVIDUAL --}}
<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    @media print {
        body, .min-h-screen { 
            background: white !important; 
            height: auto !important; 
            overflow: visible !important; 
            display: block !important;
        }
        .no-print, .bg-ornaments, nav, footer { display: none !important; }
        
        .print-area {
            box-shadow: none !important;
            border: 1px solid #000 !important;
            background: white !important;
            color: black !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 20px !important;
            border-radius: 0 !important;
        }
        
        .print-header { display: block !important; text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 15px; }
        .print-header h3 { margin: 0; font-size: 14pt; font-weight: bold; }
        .print-header h4 { margin: 0; font-size: 16pt; font-weight: bold; }
        .print-header p { margin: 2px 0; font-size: 10pt; }
        
        table { border-collapse: collapse !important; width: 100% !important; }
        th, td { border: 1px solid #000 !important; padding: 8px !important; color: #000 !important; font-size: 10pt !important; }
        th { background-color: #f3f4f6 !important; font-weight: bold !important; -webkit-print-color-adjust: exact; }
    }
    
    .print-header { display: none; }
</style>

<div class="min-h-screen w-full flex flex-col items-center justify-center py-10 px-4 relative overflow-hidden bg-elevate-surface font-sans">
    
    {{-- Background Ornaments --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-ornaments">
        <div class="absolute top-[-10%] right-[-5%] w-[600px] h-[600px] bg-emerald-500/10 rounded-full blur-[120px] opacity-70"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[500px] h-[500px] bg-elevate-primary/10 rounded-full blur-[100px] opacity-60"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
    </div>

    <div class="relative z-10 w-full max-w-3xl animate-enter">
        
        {{-- KOP SURAT KHUSUS CETAK --}}
        <div class="print-header">
            <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
            <h3>DINAS PENDIDIKAN</h3>
            <h4>SMP NEGERI 3 LAKBOK</h4>
            <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis 46385</p>
            <br>
            <h3 style="text-decoration: underline; margin-top: 10px;">BUKTI PENDAFTARAN ONLINE (INDIVIDUAL)</h3>
            <p style="text-align: left; margin-top: 15px;">Tanggal Cetak: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
        </div>

        {{-- KARTU UTAMA --}}
        <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-elevate-dark/10 border border-slate-100 overflow-hidden print-area">
            
            {{-- Header Banner Hijau --}}
            <div class="bg-gradient-to-r from-emerald-600 via-teal-600 to-emerald-700 px-8 py-10 text-white relative overflow-hidden no-print">
                <div class="absolute -right-10 -bottom-10 opacity-20 text-white pointer-events-none">
                    <i class="ph-fill ph-check-circle text-[180px]"></i>
                </div>
                
                <div class="flex flex-col sm:flex-row items-center sm:items-start text-center sm:text-left gap-5 relative z-10">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur-md border border-white/30 rounded-3xl flex items-center justify-center shrink-0 shadow-xl">
                        <i class="ph-bold ph-check text-4xl text-white"></i>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/20 text-emerald-100 text-[11px] font-extrabold uppercase tracking-widest mb-2 border border-white/30 backdrop-blur-sm">
                            <i class="ph-bold ph-sparkle"></i> Pendaftaran Berhasil
                        </div>
                        <h2 class="text-3xl font-black tracking-tight leading-tight">Selamat, Formulir Terkirim!</h2>
                        <p class="text-emerald-100 text-sm font-medium mt-1 max-w-lg">
                            Data calon siswa baru telah resmi terdaftar di database PPDB Online SMPN 3 Lakbok.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-10">
                
                {{-- REGISTRATION CODE DISPLAY BOX --}}
                <div class="bg-emerald-50/70 border border-emerald-200/80 rounded-3xl p-6 mb-8 text-center relative overflow-hidden">
                    <p class="text-xs font-black text-emerald-700 uppercase tracking-widest mb-1">Nomor Registrasi Anda</p>
                    <div class="flex items-center justify-center gap-3 my-2">
                        <span id="regCode" class="text-3xl sm:text-4xl font-black font-mono text-emerald-800 tracking-wider">
                            {{ $registrant->registration_number }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 font-medium max-w-md mx-auto">
                        Simpan Nomor Registrasi ini baik-baik. Anda dapat menggunakannya untuk mengecek status seleksi kelulusan.
                    </p>
                </div>

                {{-- SUMMARY TABLE --}}
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-wider mb-3 flex items-center gap-2">
                    <i class="ph-bold ph-user-circle text-sky-500 text-base"></i> Ringkasan Data Calon Siswa
                </h3>
                <div class="overflow-x-auto rounded-2xl border border-slate-200 mb-8">
                    <table class="w-full text-left text-sm">
                        <tbody class="divide-y divide-slate-100">
                            <tr>
                                <th class="py-3 px-5 text-xs font-bold text-slate-400 bg-slate-50/80 w-1/3">Nama Lengkap</th>
                                <td class="py-3 px-5 font-extrabold text-slate-800">{{ $registrant->full_name }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 px-5 text-xs font-bold text-slate-400 bg-slate-50/80">NISN</th>
                                <td class="py-3 px-5 font-mono font-bold text-slate-700">{{ $registrant->nisn }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 px-5 text-xs font-bold text-slate-400 bg-slate-50/80">Asal Sekolah</th>
                                <td class="py-3 px-5 font-semibold text-slate-700">{{ $registrant->school_origin }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 px-5 text-xs font-bold text-slate-400 bg-slate-50/80">Jalur Seleksi</th>
                                <td class="py-3 px-5">
                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-sky-100 text-sky-800 text-xs font-bold uppercase tracking-wider">
                                        <i class="ph-bold ph-path text-sky-600"></i> {{ str_replace('_', ' ', $registrant->track) }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th class="py-3 px-5 text-xs font-bold text-slate-400 bg-slate-50/80">Nomor HP Ortu/Wali</th>
                                <td class="py-3 px-5 font-mono font-semibold text-slate-700">{{ $registrant->parent_phone }}</td>
                            </tr>
                            <tr>
                                <th class="py-3 px-5 text-xs font-bold text-slate-400 bg-slate-50/80">Status Pendaftaran</th>
                                <td class="py-3 px-5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-xs font-bold uppercase tracking-wider">
                                        <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span> {{ strtoupper($registrant->status) }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="flex flex-col sm:flex-row items-center gap-3 no-print">
                    <button onclick="window.print()" class="w-full sm:w-auto flex-1 py-4 px-6 rounded-2xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 hover:from-sky-400 hover:to-indigo-500 text-white font-black text-sm flex items-center justify-center gap-2 shadow-lg shadow-sky-500/25 active:scale-95 transition-all">
                        <i class="ph-bold ph-printer text-lg"></i>
                        <span>Cetak Bukti Pendaftaran</span>
                    </button>
                    
                    <a href="{{ route('ppdb.check') }}" class="w-full sm:w-auto py-4 px-6 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-sm flex items-center justify-center gap-2 transition-all">
                        <i class="ph-bold ph-magnifying-glass text-lg"></i>
                        <span>Cek Status Pendaftaran</span>
                    </a>

                    <a href="{{ url('/') }}" class="w-full sm:w-auto py-4 px-6 rounded-2xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-500 hover:text-slate-800 font-bold text-sm flex items-center justify-center transition-all">
                        <span>Ke Beranda</span>
                    </a>
                </div>

            </div>
        </div>
        
    </div>
</div>
@endsection