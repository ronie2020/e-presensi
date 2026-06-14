@extends('layouts.public')

@section('content')

{{-- STYLE TAMBAHAN UNTUK CETAK/PRINT --}}
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
        .no-print, .bg-ornaments, .navbar, footer { display: none !important; }
        
        .print-area {
            box-shadow: none !important;
            border: none !important;
            background: white !important;
            color: black !important;
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }
        
        /* Kop Surat Mode Print */
        .print-header { display: block !important; text-align: center; margin-bottom: 20px; border-bottom: 3px double #000; padding-bottom: 15px; }
        .print-header h3 { margin: 0; font-size: 14pt; font-weight: bold; }
        .print-header h4 { margin: 0; font-size: 16pt; font-weight: bold; }
        .print-header p { margin: 2px 0; font-size: 10pt; }
        
        /* Tabel Mode Print */
        table { border-collapse: collapse !important; width: 100% !important; }
        th, td { border: 1px solid #000 !important; padding: 8px !important; color: #000 !important; font-size: 10pt !important; }
        th { background-color: #f3f4f6 !important; font-weight: bold !important; -webkit-print-color-adjust: exact; }
    }
    
    /* Disembunyikan di layar, hanya muncul saat print */
    .print-header { display: none; }
</style>

<div class="min-h-screen w-full flex flex-col items-center py-10 relative overflow-hidden bg-elevate-surface font-sans">
    
    {{-- Background Ornaments --}}
    <div class="fixed inset-0 z-0 pointer-events-none overflow-hidden bg-ornaments">
        <div class="absolute top-[-10%] left-[-5%] w-[600px] h-[600px] bg-emerald-100/40 rounded-full blur-[100px] opacity-60 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30"></div>
    </div>

    <div class="relative z-10 w-full max-w-4xl px-4 animate-enter print-area">
        
        {{-- KOP SURAT KHUSUS CETAK --}}
        <div class="print-header">
            <h3>PEMERINTAH KABUPATEN CIAMIS</h3>
            <h3>DINAS PENDIDIKAN</h3>
            <h4>SMP NEGERI 3 LAKBOK</h4>
            <p>Jalan Mekarjaya No. 199 Sidaharja Kecamatan Lakbok Kabupaten Ciamis 46385</p>
            <br>
            <h3 style="text-decoration: underline; margin-top: 10px;">REKAPITULASI PENDAFTARAN KOLEKTIF</h3>
            <p style="text-align: left; margin-top: 15px;">Tanggal Import: {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y') }}</p>
        </div>

        {{-- KARTU TAMPILAN LAYAR --}}
        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden print-area">
            
            <div class="bg-emerald-50 px-8 py-8 border-b border-emerald-100 relative overflow-hidden no-print">
                <div class="absolute -right-10 -top-10 text-emerald-100 opacity-50">
                    <i class="ph-fill ph-check-circle text-[150px]"></i>
                </div>
                
                <div class="flex items-center gap-4 relative z-10">
                    <div class="w-16 h-16 bg-emerald-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                        <i class="ph-bold ph-check text-3xl"></i>
                    </div>
                    <div>
                        <h2 class="text-2xl font-black text-emerald-800 tracking-tight">Import Berhasil!</h2>
                        <p class="text-sm text-emerald-600 font-medium mt-1">
                            Sebanyak <strong>{{ count(session('imported_data', [])) }}</strong> data siswa telah berhasil dimasukkan ke sistem.
                        </p>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="flex justify-between items-end mb-6 no-print">
                    <div>
                        <h3 class="text-lg font-black text-elevate-dark">Daftar Nomor Registrasi Siswa</h3>
                        <p class="text-sm text-slate-500 font-medium">Simpan atau cetak daftar ini untuk keperluan pengecekan kelulusan.</p>
                    </div>
                    <button onclick="window.print()" class="px-5 py-2.5 bg-elevate-dark text-white font-bold rounded-xl shadow-lg shadow-elevate-dark/20 hover:bg-elevate-primary hover:-translate-y-1 transition-all flex items-center gap-2">
                        <i class="ph-bold ph-printer text-lg"></i> Cetak Rekap
                    </button>
                </div>

                {{-- TABEL DATA --}}
                <div class="overflow-x-auto rounded-2xl border border-slate-200 print-area">
                    <table class="w-full text-left">
                       <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="py-4 px-5 text-xs font-black text-slate-500 uppercase tracking-wider w-16 text-center">No</th>
                                <th class="py-4 px-5 text-xs font-black text-slate-500 uppercase tracking-wider">No. Registrasi</th>
                                <th class="py-4 px-5 text-xs font-black text-slate-500 uppercase tracking-wider">Nama Siswa</th>
                                <th class="py-4 px-5 text-xs font-black text-slate-500 uppercase tracking-wider">NISN</th>
                                <th class="py-4 px-5 text-xs font-black text-slate-500 uppercase tracking-wider">Asal Sekolah</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse(session('imported_data', []) as $index => $data)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3 px-5 text-sm text-slate-500 font-bold text-center">{{ $index + 1 }}</td>
                                <td class="py-3 px-5">
                                    <span class="inline-flex py-1 px-2.5 rounded-md bg-elevate-soft text-elevate-primary font-mono font-bold text-sm border border-elevate-accent/20">
                                        {{ $data['registration_number'] }}
                                    </span>
                                </td>
                                <td class="py-3 px-5 text-sm font-bold text-elevate-dark">{{ $data['full_name'] }}</td>
                                <td class="py-3 px-5 text-sm font-mono text-slate-600">{{ $data['nisn'] }}</td>
                                <td class="py-3 px-5 text-sm text-slate-500">{{ $data['school_origin'] }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-8 px-5 text-center text-sm font-bold text-slate-400">
                                    Sesi telah berakhir atau tidak ada data yang di-import.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-100 flex justify-center no-print">
                    <a href="{{ route('ppdb.collective') }}" class="px-6 py-3 text-sm font-bold text-slate-500 hover:text-elevate-dark border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-2">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Form Kolektif
                    </a>
                </div>
            </div>
        </div>
        
    </div>
</div>
@endsection