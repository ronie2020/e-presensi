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
        
        /* Background Animations */
        .animate-blob { animation: blob 7s infinite; }
        .animation-delay-2000 { animation-delay: 2s; }
        
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        @media print {
            body { background: white; color: black; }
            .bg-slate-900 { background: white !important; }
            .no-print { display: none !important; }
            .print-area { 
                box-shadow: none !important; 
                border: 2px solid #000 !important; 
                background: white !important;
                backdrop-filter: none !important;
                color: black !important;
            }
            .text-white { color: black !important; }
            .text-slate-300, .text-slate-400, .text-slate-500 { color: #333 !important; }
            /* Hide decorative blobs when printing */
            .fixed.inset-0, .animate-blob { display: none !important; }
        }
    </style>
</head>
<body class="bg-slate-900 text-slate-800 antialiased min-h-screen flex flex-col items-center justify-center p-4 relative overflow-hidden">

    <!-- BACKGROUND LAYERS (Sama dengan Theme Lain) -->
    <div class="fixed inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay pointer-events-none"></div>
    <div class="fixed inset-0 bg-gradient-to-br from-slate-900 via-slate-900/95 to-blue-900/90 pointer-events-none"></div>
    <div class="fixed top-0 right-0 w-[500px] h-[500px] bg-blue-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob pointer-events-none"></div>
    <div class="fixed bottom-0 left-0 w-[500px] h-[500px] bg-indigo-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 animate-blob animation-delay-2000 pointer-events-none"></div>

    <!-- Card Bukti -->
    <div class="print-area w-full max-w-lg relative z-10">
        
        <div class="bg-white/95 backdrop-blur-xl rounded-3xl shadow-2xl shadow-black/30 border border-white/20 overflow-hidden">
            <!-- Header Decoration -->
            <div class="h-2 bg-gradient-to-r from-green-400 via-emerald-500 to-teal-500"></div>

            <div class="p-8 text-center">
                <!-- Icon Sukses -->
                <div class="w-24 h-24 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6 text-green-600 shadow-lg shadow-green-500/20 animate-bounce">
                    <i class="ph-fill ph-check-circle text-6xl"></i>
                </div>
                
                <h1 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Pendaftaran Berhasil!</h1>
                <p class="text-slate-500 text-sm mb-8 leading-relaxed">Data Anda telah aman tersimpan di sistem kami. <br>Silakan simpan bukti pendaftaran berikut.</p>

                <!-- Tiket Info -->
                <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200 text-left mb-8 relative overflow-hidden group hover:border-blue-300 transition-colors">
                    <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                        <i class="ph-bold ph-ticket text-7xl text-slate-900"></i>
                    </div>

                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest mb-1">Nomor Registrasi</p>
                    <p class="text-4xl font-black text-blue-600 tracking-tighter mb-5">{{ $registrant->registration_number }}</p>

                    <div class="space-y-3 text-sm border-t border-slate-200 pt-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Nama Siswa</span>
                            <span class="font-bold text-slate-800 text-right">{{ $registrant->full_name }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Jalur Seleksi</span>
                            <span class="font-bold text-white bg-blue-500 px-2 py-0.5 rounded text-xs uppercase">{{ $registrant->track }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-500 font-medium">Waktu Daftar</span>
                            <span class="font-bold text-slate-700 font-mono">{{ $registrant->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Info Penting -->
                <div class="bg-yellow-50 rounded-xl p-4 text-left flex gap-3 border border-yellow-200/60 mb-8">
                    <i class="ph-fill ph-info text-yellow-600 text-xl shrink-0 mt-0.5"></i>
                    <p class="text-xs text-yellow-800 leading-relaxed font-medium">
                        <strong>PENTING:</strong> Simpan halaman ini atau screenshot sebagai bukti pendaftaran sah. Gunakan Nomor Registrasi untuk mengecek status kelulusan secara berkala.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 no-print">
                    <a href="{{ url('/') }}" class="flex-1 py-3.5 bg-slate-100 text-slate-600 font-bold rounded-xl text-sm hover:bg-slate-200 hover:text-slate-800 transition text-center border border-slate-200">
                        Ke Beranda
                    </a>
                    <button onclick="window.print()" class="flex-1 py-3.5 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-600/30 hover:shadow-blue-600/50 hover:scale-[1.02] transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-printer text-lg"></i> Cetak Bukti
                    </button>
                </div>
            </div>
            
            <!-- Footer Card -->
            <div class="bg-slate-50 p-3 text-center border-t border-slate-100">
                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Panitia PPDB SMPN 3 Lakbok</p>
            </div>
        </div>
        
        <p class="text-center text-slate-500 text-xs mt-6 font-medium no-print">
            &copy; {{ date('Y') }} Sistem Informasi Akademik
        </p>
    </div>

</body>
</html>