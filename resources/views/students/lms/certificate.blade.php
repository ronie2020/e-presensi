<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Kelulusan Modul - {{ $student->name }} - {{ $subject->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@700;900&family=Inter:wght@400;600;700;800&family=Pinyon+Script&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: A4 landscape;
                margin: 0;
            }
            body {
                background: white !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            .no-print {
                display: none !important;
            }
            .cert-container {
                box-shadow: none !important;
                border-radius: 0 !important;
            }
        }
        .font-cinzel { font-family: 'Cinzel', serif; }
        .font-script { font-family: 'Pinyon Script', cursive; }
    </style>
</head>
<body class="bg-slate-900 min-h-screen flex flex-col items-center justify-center p-4 md:p-8">

    <!-- FLOATING PRINT BUTTON -->
    <div class="no-print mb-6 flex items-center gap-3">
        <button onclick="window.print()" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-yellow-600 hover:from-amber-400 hover:to-yellow-500 text-slate-950 font-black rounded-2xl shadow-xl shadow-amber-500/20 flex items-center gap-2 text-sm transition-all transform hover:scale-105 active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 000-4h-6a2 2 0 000 4zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak / Simpan PDF
        </button>
        <button onclick="window.close()" class="px-5 py-3 bg-slate-800 hover:bg-slate-700 text-slate-300 font-bold rounded-2xl text-sm transition-all">
            Tutup
        </button>
    </div>

    <!-- CERTIFICATE BOARD (A4 LANDSCAPE RATIO) -->
    <div class="cert-container w-full max-w-[1050px] aspect-[1.414/1] bg-white text-slate-900 rounded-3xl shadow-2xl p-8 md:p-12 relative overflow-hidden border-[12px] border-amber-500/80 flex flex-col justify-between select-none">
        
        <!-- DECORATIVE CORNERS -->
        <div class="absolute top-0 left-0 w-32 h-32 bg-amber-500/10 rounded-br-full pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-32 h-32 bg-amber-500/10 rounded-tl-full pointer-events-none"></div>
        <div class="absolute inset-4 border-2 border-amber-600/40 rounded-2xl pointer-events-none"></div>
        <div class="absolute inset-6 border border-amber-500/20 rounded-xl pointer-events-none"></div>

        <!-- HEADER -->
        <div class="text-center relative z-10 pt-2">
            <div class="flex items-center justify-center gap-4 mb-2">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Sekolah" class="h-14 w-auto object-contain" onerror="this.style.display='none'">
                <div>
                    <h4 class="font-bold text-xs uppercase tracking-widest text-slate-500">PEMERINTAH KABUPATEN CIAMIS</h4>
                    <h3 class="font-extrabold text-sm md:text-base tracking-wider text-slate-800">SMP NEGERI 3 LAKBOK</h3>
                    <p class="text-[10px] text-slate-500">Sistem E-Learning Terpadu & Computer Based Test</p>
                </div>
            </div>

            <div class="w-48 h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent mx-auto my-3"></div>

            <h1 class="font-cinzel text-2xl md:text-4xl font-black tracking-tight text-amber-700 uppercase">SERTIFIKAT KELULUSAN MODUL</h1>
            <p class="text-xs font-mono font-semibold text-slate-400 mt-1">No. Registrasi: <span class="text-slate-700">{{ $certificateNo }}</span></p>
        </div>

        <!-- BODY CONTENT -->
        <div class="text-center relative z-10 my-4 space-y-4">
            <p class="text-xs md:text-sm text-slate-600 font-medium italic">Diberikan secara sah dan tuntas kepada:</p>
            
            <div class="py-2">
                <h2 class="font-cinzel text-2xl md:text-4xl font-black text-slate-900 tracking-wide border-b-2 border-amber-500/60 inline-block px-8 py-1 uppercase">
                    {{ $student->name }}
                </h2>
                <p class="text-xs font-bold text-slate-500 mt-2">NISN: {{ $student->nisn ?? '-' }} | Kelas: {{ $student->schoolClass->name ?? '-' }}</p>
            </div>

            <p class="text-xs md:text-sm text-slate-700 max-w-2xl mx-auto leading-relaxed font-medium">
                Telah berhasil menyelesaikan seluruh materi alur belajar, tugas penugasan mandiri, dan evaluasi kuis dengan progres ketuntasan <strong class="text-emerald-600 font-bold">100% (TUNTAS)</strong> pada mata pelajaran:
            </p>

            <div class="inline-block px-6 py-2 bg-amber-500/10 rounded-2xl border border-amber-500/30">
                <h3 class="font-cinzel text-lg md:text-xl font-bold text-amber-900 uppercase">
                    {{ $subject->name }}
                </h3>
            </div>
        </div>

        <!-- FOOTER & SIGNATURES -->
        <div class="relative z-10 flex items-end justify-between px-6 pb-2">
            <!-- QR CODE AUTHENTICITY -->
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white border border-slate-200 rounded-xl shadow-sm">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ urlencode(url('/portal/' . $student->id . '/biodata')) }}" alt="QR Code Verification" class="w-16 h-16 object-contain">
                </div>
                <div class="text-left text-[9px] text-slate-400">
                    <p class="font-bold text-slate-600">Diverifikasi Sistem LMS</p>
                    <p>Status: <span class="text-emerald-600 font-bold">Resmi & Sah</span></p>
                    <p>Tanggal Terbit: {{ $issueDate }}</p>
                </div>
            </div>

            <!-- OFFICIAL SEAL -->
            <div class="w-20 h-20 rounded-full border-4 border-amber-500/60 flex items-center justify-center text-amber-700 font-bold text-[10px] uppercase text-center p-2 bg-amber-50 shadow-inner rotate-[-12deg]">
                <span>E-Learning<br>Certified</span>
            </div>

            <!-- SIGNATURE -->
            <div class="text-center font-sans">
                <p class="text-xs text-slate-500 font-medium">Lakbok, {{ $issueDate }}</p>
                <p class="text-xs font-bold text-slate-700">Kepala SMPN 3 Lakbok</p>
                <div class="h-16 flex items-center justify-center">
                    <span class="font-script text-3xl text-amber-900 font-bold transform -rotate-6">Ttd. Resmi</span>
                </div>
                <p class="text-xs font-bold text-slate-900 border-b border-slate-400 pb-0.5 inline-block">H. GURU PENGAMPU, M.Pd.</p>
                <p class="text-[9px] text-slate-400 font-mono">NIP. 19780512 200501 1 003</p>
            </div>
        </div>

    </div>

</body>
</html>
