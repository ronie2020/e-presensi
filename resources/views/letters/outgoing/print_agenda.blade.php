<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku Agenda Surat Keluar - SMP Negeri 3 Lakbok</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"/>
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css"/>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; color: black !important; padding: 0 !important; }
            .print-page { border: none !important; box-shadow: none !important; width: 100% !important; max-width: 100% !important; padding: 0 !important; margin: 0 !important; }
            thead { display: table-header-group; }
            tr { page-break-inside: avoid; }
        }
        @page {
            size: A4 landscape;
            margin: 1.5cm 1cm 1.5cm 1cm;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-900 font-sans p-4 sm:p-8 min-h-screen">

    {{-- Toolbar Tombol Cetak / Navigasi (Hanya di Layar) --}}
    <div class="max-w-6xl mx-auto mb-6 flex items-center justify-between no-print bg-white p-4 rounded-2xl shadow-sm border border-slate-200">
        <div class="flex items-center gap-3">
            <a href="{{ route('letters.outgoing.index') }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-slate-700 bg-slate-100 hover:bg-slate-200 transition-colors">
                <i class="ph-bold ph-arrow-left"></i> Kembali ke Surat Keluar
            </a>
            <span class="text-xs text-slate-400 font-semibold">|</span>
            <span class="text-xs font-bold text-slate-600">Pratinjau Cetak Buku Agenda</span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('letters.outgoing.export-excel', request()->all()) }}" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition-all">
                <i class="ph-bold ph-file-xls text-sm"></i> Download Excel
            </a>
            <button onclick="window.print()" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-xl text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition-all hover:scale-105 active:scale-95">
                <i class="ph-bold ph-printer text-sm"></i> Cetak Dokumen / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Lembar Cetak --}}
    <div class="print-page max-w-6xl mx-auto bg-white p-8 sm:p-12 shadow-md rounded-2xl border border-slate-200 text-black">
        
        {{-- KOP SURAT RESMI SMP NEGERI 3 LAKBOK --}}
        <div class="border-b-4 border-double border-slate-900 pb-4 mb-6">
            <div class="text-center space-y-1">
                <h4 class="text-base font-bold tracking-wider uppercase text-slate-800">PEMERINTAH KABUPATEN CIAMIS</h4>
                <h4 class="text-base font-bold tracking-wider uppercase text-slate-800">DINAS PENDIDIKAN</h4>
                <h2 class="text-2xl font-black tracking-wide uppercase text-slate-950 font-serif">SMP NEGERI 3 LAKBOK</h2>
                <p class="text-xs text-slate-600 font-medium">
                    Jalan Cintaratu, Kec. Lakbok, Kabupaten Ciamis, Jawa Barat 46385 &bull; Email: smpn3lakbok@gmail.com
                </p>
            </div>
        </div>

        {{-- JUDUL LAPORAN & PERIODE --}}
        <div class="text-center my-6">
            <h3 class="text-lg font-black uppercase tracking-wider underline">BUKU AGENDA SURAT KELUAR</h3>
            <p class="text-xs text-slate-600 mt-1 font-medium">
                Tahun: <strong class="text-slate-900">{{ $filterInfo['year'] }}</strong> &bull; 
                Bulan: <strong class="text-slate-900">{{ $filterInfo['month'] }}</strong> &bull; 
                Sifat: <strong class="text-slate-900">{{ $filterInfo['sifat'] }}</strong>
            </p>
        </div>

        {{-- TABEL AGENDA & EKSPEDISI --}}
        <div class="overflow-x-auto my-6">
            <table class="w-full text-xs text-left border-collapse border border-slate-800">
                <thead>
                    <tr class="bg-slate-100 text-slate-900 font-bold uppercase text-center border-b border-slate-800">
                        <th class="border border-slate-800 px-2 py-2.5 w-12">No.</th>
                        <th class="border border-slate-800 px-3 py-2.5 w-24">No. Agenda</th>
                        <th class="border border-slate-800 px-3 py-2.5 w-44">Nomor Surat</th>
                        <th class="border border-slate-800 px-3 py-2.5 w-28">Tanggal</th>
                        <th class="border border-slate-800 px-3 py-2.5 w-20">Sifat</th>
                        <th class="border border-slate-800 px-4 py-2.5 w-48">Tujuan / Kepada</th>
                        <th class="border border-slate-800 px-4 py-2.5">Perihal / Isi Ringkas</th>
                        <th class="border border-slate-800 px-3 py-2.5 w-28 text-center">Tanda Terima / Paraf</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($letters as $index => $letter)
                    <tr class="border-b border-slate-800 align-top hover:bg-slate-50">
                        <td class="border border-slate-800 px-2 py-2 text-center font-medium">{{ $index + 1 }}</td>
                        <td class="border border-slate-800 px-3 py-2 text-center font-mono font-bold">{{ $letter->nomor_agenda }}</td>
                        <td class="border border-slate-800 px-3 py-2 font-mono font-bold">{{ $letter->nomor_surat }}</td>
                        <td class="border border-slate-800 px-3 py-2 text-center whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($letter->tgl_surat)->translatedFormat('d/m/Y') }}
                        </td>
                        <td class="border border-slate-800 px-3 py-2 text-center font-semibold">{{ $letter->sifat_surat }}</td>
                        <td class="border border-slate-800 px-4 py-2 font-bold">{{ $letter->tujuan_surat }}</td>
                        <td class="border border-slate-800 px-4 py-2 leading-relaxed">
                            {{ $letter->perihal }}
                            @if($letter->spt)
                                <div class="text-[10px] text-blue-700 font-semibold mt-1">
                                    [SPT: {{ $letter->spt->nomor_spt }}]
                                </div>
                            @endif
                        </td>
                        <td class="border border-slate-800 px-3 py-2 text-center h-12"></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="border border-slate-800 px-4 py-8 text-center text-slate-500 italic">
                            Tidak ada arsip data surat keluar untuk periode ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- TANDA TANGAN PEJABAT & PETUGAS TU --}}
        <div class="mt-12 pt-6 grid grid-cols-2 text-xs break-inside-avoid">
            <div class="text-left pl-6">
                <p>Mengetahui,</p>
                <p class="font-bold">Kepala SMP Negeri 3 Lakbok,</p>
                <div class="h-20"></div>
                <p class="font-black text-sm uppercase underline">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
                <p class="text-slate-700">NIP. 19820928 201101 1 002</p>
            </div>
            <div class="text-right pr-6">
                <p>Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="font-bold">Pengelola Arsip / Tata Usaha,</p>
                <div class="h-20"></div>
                <p class="font-black text-sm uppercase underline">{{ Auth::user()->name ?? 'Petugas TU' }}</p>
                <p class="text-slate-700">NIP. {{ Auth::user()->nip ?? '-' }}</p>
            </div>
        </div>

    </div>

</body>
</html>
