<x-app-layout>
    @push('styles')
    <style>
        .watermark {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 300px;
            opacity: 0.05; 
            pointer-events: none;
        }

        @media screen {
            .print-area { display: none !important; }
        }

        @media print {
            .screen-area { display: none !important; }
            header, nav, aside, footer { display: none !important; }
            
            body, html, #app, main, .min-h-screen {
                height: auto !important;
                min-height: auto !important;
                overflow: visible !important;
                background-color: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            
            .print-area { 
                display: block !important; 
                width: 100% !important; 
            }
            
            @page { size: A4; margin: 10mm; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            
            .page-break { 
                page-break-after: always !important; 
                break-after: page !important; 
            }
        }
    </style>
    @endpush

    {{-- SCREEN AREA --}}
    <div class="screen-area py-6 bg-slate-100 min-h-screen font-sans text-slate-800 flex flex-col items-center">
        <div class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[90%] max-w-4xl">
            <div class="bg-white/90 backdrop-blur-xl text-[#2c3f61] p-4 rounded-2xl shadow-xl flex flex-col md:flex-row justify-between items-center gap-4 border border-slate-200">
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <button onclick="window.close()" 
                       class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center hover:bg-slate-200 transition text-slate-500 hover:text-rose-500 shrink-0"
                       title="Tutup Tab">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                    <div class="min-w-0">
                        <h2 class="font-bold text-sm md:text-base leading-tight truncate">Cetak Semua Rapor</h2>
                        <p class="text-[10px] md:text-xs text-[#2c3f61]/70 font-mono">Kelas {{ $class->name }} | {{ count($reportData) }} Siswa</p>
                    </div>
                </div>

                <button onclick="window.print()" class="w-full md:w-auto px-6 py-2.5 bg-[#2c3f61] hover:bg-[#1c2940] text-white font-bold rounded-xl shadow-lg shadow-[#2c3f61]/20 transition flex items-center justify-center gap-2 text-sm">
                    <i class="ph-bold ph-printer text-lg"></i>
                    <span>Cetak Sekarang</span>
                </button>
            </div>
        </div>

        <div class="mt-28 text-center max-w-lg">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-[#0d52a1] shadow-lg shadow-[#0d52a1]/10">
                <i class="ph-duotone ph-files text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-[#2c3f61] mb-2">Mode Cetak Massal Aktif</h3>
            <p class="text-sm text-[#2c3f61]/70 font-medium mb-4">
                Memuat data {{ count($reportData) }} siswa. Sistem akan memisahkan rapor masing-masing siswa ke halaman baru.
            </p>
            <div class="inline-flex items-center gap-2 text-xs font-bold text-amber-600 bg-amber-50 px-4 py-2 rounded-lg border border-amber-200">
                <i class="ph-bold ph-warning-circle"></i> Pastikan pengaturan margin printer diset ke "Default" atau "None".
            </div>
        </div>
    </div>

    {{-- PRINT AREA --}}
    <div class="print-area">
        @foreach($reportData as $data)
            <div style="position: relative; width: 100%; min-height: 297mm; break-inside: avoid;">
                <div class="watermark" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 0;"></div>
                
                <div style="position: relative; z-index: 10;">
                    <x-report-content 
                        :student="$data['student']" 
                        :semester="$semester" 
                        :year="$academic_year" 
                        :subjects="$data['subjects']" 
                        :record="$data['record']" 
                    />
                </div>
            </div>
            
            @if(!$loop->last)
                <div class="page-break"></div>
            @endif
        @endforeach
    </div>

    @push('scripts')
    <script>
        window.addEventListener('load', function() {
            setTimeout(() => {
                window.print();
            }, 1000);
        });
    </script>
    @endpush
</x-app-layout>