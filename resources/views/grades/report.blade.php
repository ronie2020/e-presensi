<x-app-layout>
    {{-- CSS Khusus Cetak (TETAP SAMA AGAR TIDAK RUSAK FORMAT) --}}
    @push('styles')
    <style>
        .watermark {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 300px;
            opacity: 0.1;
        }
        @media print {
            @page { size: A4; margin: 10mm 15mm 10mm 15mm; }
            body > *:not(.print-wrapper) { display: none !important; }
            .print-wrapper { display: block !important; background: white; width: 100%; margin: 0; padding: 0; }
            tr, td, th, .avoid-break { page-break-inside: avoid !important; }
            thead { display: table-header-group; }
            .shadow-xl, .border-slate-100 { box-shadow: none !important; border: none !important; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }
    </style>
    @endpush

    {{-- WRAPPER UTAMA (UI PREVIEW MODERN) --}}
    <div class="py-8 bg-slate-100 min-h-screen no-print font-sans text-slate-800">
        <div class="max-w-[220mm] mx-auto">
            
            {{-- Toolbar Atas Floating --}}
            <div class="sticky top-6 z-50 mb-8 mx-4">
                <div class="bg-gray-900/80 backdrop-blur-md text-white p-4 rounded-2xl shadow-2xl flex flex-col md:flex-row justify-between items-center gap-4 border border-white/10">
                    <div class="flex items-center gap-4">
                        <a href="{{ route('grades.list', ['class_id' => $student->class_id, 'academic_year' => $year, 'semester' => $semester]) }}" 
                           class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white">
                            <i class="ph-bold ph-arrow-left text-lg"></i>
                        </a>
                        <div>
                            <h2 class="font-bold text-lg leading-tight">Preview Rapor</h2>
                            <p class="text-xs text-blue-200 font-mono">{{ $student->name }} - {{ $student->student_id }}</p>
                        </div>
                    </div>
                    
                    <button onclick="window.print()" class="w-full md:w-auto px-6 py-2.5 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-printer text-lg"></i>
                        <span>Cetak Dokumen</span>
                    </button>
                </div>
            </div>

            {{-- 1. PREVIEW PAPER CONTAINER --}}
            {{-- Shadow dibuat realistis seperti kertas di atas meja --}}
            <div class="bg-white p-10 md:p-12 shadow-[0_20px_50px_-12px_rgba(0,0,0,0.3)] relative overflow-hidden text-slate-900 font-serif min-h-[297mm] mx-4 rounded-sm">
               <x-report-content 
                   :student="$student" 
                   :semester="$semester" 
                   :year="$year" 
                   :subjects="$subjects" 
                   :record="$record" 
               />
            </div>
            
            <div class="text-center mt-8 text-slate-400 text-xs font-medium pb-8">
                &copy; {{ date('Y') }} Sistem Informasi Akademik Sekolah
            </div>
        </div>
    </div>

    {{-- 2. PRINT WRAPPER (Hidden saat view biasa) --}}
    <div class="print-wrapper hidden">
        <x-report-content 
            :student="$student" 
            :semester="$semester" 
            :year="$year" 
            :subjects="$subjects" 
            :record="$record" 
        />
    </div>

</x-app-layout>