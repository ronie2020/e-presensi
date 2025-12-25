<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Status Seleksi - {{ $registrant->full_name }}</title>
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 text-slate-800 antialiased min-h-screen flex flex-col items-center justify-center p-4">

    <!-- Card Wrapper -->
    <div class="bg-white rounded-3xl shadow-xl w-full max-w-lg overflow-hidden border border-slate-100">
        
        <!-- HEADER STATUS -->
        @if($registrant->status == 'accepted')
            <!-- STATUS: DITERIMA -->
            <div class="bg-emerald-600 p-8 text-center text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-20"></div>
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm animate-bounce">
                    <i class="ph-fill ph-confetti text-4xl"></i>
                </div>
                <h1 class="text-3xl font-extrabold mb-1">SELAMAT!</h1>
                <p class="text-emerald-100 font-medium">Anda dinyatakan <span class="font-black bg-white/20 px-2 rounded">DITERIMA</span></p>
            </div>
        @elseif($registrant->status == 'rejected')
            <!-- STATUS: DITOLAK -->
            <div class="bg-slate-700 p-8 text-center text-white relative overflow-hidden">
                <div class="w-20 h-20 bg-white/10 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <i class="ph-fill ph-x-circle text-4xl text-slate-300"></i>
                </div>
                <h1 class="text-2xl font-extrabold mb-1">MOHON MAAF</h1>
                <p class="text-slate-300 font-medium">Anda dinyatakan <span class="font-black bg-white/10 px-2 rounded">TIDAK LULUS</span></p>
            </div>
        @elseif($registrant->status == 'verified')
            <!-- STATUS: TERVERIFIKASI -->
            <div class="bg-blue-600 p-8 text-center text-white relative overflow-hidden">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <i class="ph-fill ph-file-text text-4xl"></i>
                </div>
                <h1 class="text-2xl font-extrabold mb-1">BERKAS TERVERIFIKASI</h1>
                <p class="text-blue-100 font-medium">Data Anda valid, menunggu pengumuman akhir.</p>
            </div>
        @else
            <!-- STATUS: PENDING -->
            <div class="bg-yellow-500 p-8 text-center text-white relative overflow-hidden">
                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4 backdrop-blur-sm">
                    <i class="ph-fill ph-hourglass text-4xl"></i>
                </div>
                <h1 class="text-2xl font-extrabold mb-1">MENUNGGU VERIFIKASI</h1>
                <p class="text-yellow-100 font-medium">Data Anda sedang diperiksa oleh panitia.</p>
            </div>
        @endif

        <!-- BODY DATA -->
        <div class="p-8">
            <div class="text-center mb-8 border-b border-slate-100 pb-6">
                <h2 class="text-xl font-bold text-slate-900">{{ $registrant->full_name }}</h2>
                <p class="text-slate-500 text-sm mt-1 font-mono">{{ $registrant->registration_number }}</p>
            </div>

            <div class="space-y-4 text-sm">
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-500 font-medium">Jalur Pendaftaran</span>
                    <span class="font-bold text-slate-700 uppercase">{{ $registrant->track }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-500 font-medium">Asal Sekolah</span>
                    <span class="font-bold text-slate-700">{{ $registrant->school_origin }}</span>
                </div>
                <div class="flex justify-between items-center p-3 bg-slate-50 rounded-xl">
                    <span class="text-slate-500 font-medium">Nilai Rata-rata</span>
                    <span class="font-bold text-slate-700">{{ $registrant->average_grade }}</span>
                </div>
                
                @if($registrant->admin_note)
                <div class="mt-6 p-4 bg-yellow-50 border border-yellow-100 rounded-xl">
                    <p class="text-xs font-bold text-yellow-700 uppercase mb-1">Catatan Panitia:</p>
                    <p class="text-sm text-yellow-800 italic">"{{ $registrant->admin_note }}"</p>
                </div>
                @endif
            </div>

            <div class="mt-8">
                <a href="{{ route('ppdb.check') }}" class="block w-full py-3 bg-slate-100 text-slate-600 font-bold rounded-xl text-center hover:bg-slate-200 transition">
                    Cek Peserta Lain
                </a>
            </div>
        </div>
    </div>

</body>
</html>