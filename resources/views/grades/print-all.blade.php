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
    <div class="screen-area py-6 bg-slate-200 min-h-screen font-sans text-slate-800 flex flex-col items-center">
        <div class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[90%] max-w-4xl">
            <div class="bg-slate-900/90 backdrop-blur-xl text-white p-4 rounded-2xl shadow-2xl flex flex-col md:flex-row justify-between items-center gap-4 border border-white/10 ring-1 ring-black/5">
                
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <button onclick="window.close()" 
                       class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white shrink-0"
                       title="Tutup Tab">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                    <div class="min-w-0">
                        <h2 class="font-bold text-sm md:text-base leading-tight truncate">Cetak Semua Rapor</h2>
                        <p class="text-[10px] md:text-xs text-blue-200 font-mono">Kelas {{ $class->name }} | {{ count($reportData) }} Siswa</p>
                    </div>
                </div>

                <button onclick="window.print()" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2 text-sm">
                    <i class="ph-bold ph-printer text-lg"></i>
                    <span>Cetak Sekarang</span>
                </button>
            </div>
        </div>

        <div class="mt-28 text-center max-w-lg">
            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center mx-auto mb-4 text-blue-500 shadow-lg shadow-blue-500/10">
                <i class="ph-duotone ph-files text-4xl"></i>
            </div>
            <h3 class="text-xl font-black text-slate-800 mb-2">Mode Cetak Massal Aktif</h3>
            <p class="text-sm text-slate-500 font-medium mb-4">
                Memuat data {{ count($reportData) }} siswa. Sistem akan memisahkan rapor masing-masing siswa ke halaman baru.
            </p>
            {{-- PERBAIKAN: Feedback visual sebelum print --}}
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

    {{-- PERBAIKAN: Auto-print dialog saat halaman selesai dimuat --}}
    @push('scripts')
    <script>
        window.addEventListener('load', function() {
            // Memberikan jeda sedikit untuk memastikan font/gambar termuat
            setTimeout(() => {
                window.print();
            }, 1000);
        });
    </script>
    @endpush
</x-app-layout>