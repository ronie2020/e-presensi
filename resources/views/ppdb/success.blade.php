<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pendaftaran Berhasil - SMP Negeri 3 Lakbok</title>
    
    <!-- Fonts & Icons -->
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            .print-area { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 antialiased min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Card Bukti -->
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg overflow-hidden print-area relative">
        <!-- Decoration -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-blue-500 via-blue-600 to-indigo-600"></div>

        <div class="p-8 text-center">
            <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 text-green-600 animate-bounce">
                <i class="ph-fill ph-check-circle text-5xl"></i>
            </div>
            
            <h1 class="text-2xl font-extrabold text-slate-900 mb-2">Pendaftaran Berhasil!</h1>
            <p class="text-slate-500 text-sm mb-8">Data Anda telah berhasil disimpan di sistem kami.</p>

            <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-left mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 p-3 opacity-10">
                    <i class="ph-bold ph-ticket text-6xl"></i>
                </div>

                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Nomor Pendaftaran</p>
                <p class="text-3xl font-black text-blue-600 tracking-tight mb-4">{{ $registrant->registration_number }}</p>

                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Nama Siswa</span>
                        <span class="font-bold text-slate-700">{{ $registrant->full_name }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Jalur</span>
                        <span class="font-bold text-slate-700 uppercase">{{ $registrant->track }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Tanggal Daftar</span>
                        <span class="font-bold text-slate-700">{{ $registrant->created_at->format('d M Y H:i') }}</span>
                    </div>
                </div>
            </div>

            <div class="bg-yellow-50 rounded-xl p-4 text-left flex gap-3 border border-yellow-100 mb-8">
                <i class="ph-fill ph-info text-yellow-600 text-xl shrink-0"></i>
                <p class="text-xs text-yellow-800 leading-relaxed">
                    <strong>PENTING:</strong> Simpan nomor pendaftaran ini atau screenshot halaman ini sebagai bukti. Pantau status kelulusan secara berkala melalui website sekolah.
                </p>
            </div>

            <div class="flex gap-3 no-print">
                <a href="{{ url('/') }}" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-200 transition text-center">
                    Kembali
                </a>
                <button onclick="window.print()" class="flex-1 py-3 bg-blue-600 text-white font-bold rounded-xl text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-printer"></i> Cetak Bukti
                </button>
            </div>
        </div>
    </div>

</body>
</html>