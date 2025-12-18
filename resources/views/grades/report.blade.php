<x-app-layout>
    {{-- CSS Khusus Cetak --}}
    @push('styles')
    <style>
        /* Watermark di tengah */
        .watermark {
            background-image: url('https://upload.wikimedia.org/wikipedia/commons/9/9c/Logo_Tut_Wuri_Handayani.png');
            background-repeat: no-repeat;
            background-position: center;
            background-size: 300px;
            opacity: 0.1;
        }

        @media print {
            /* Reset Margin Browser */
            @page {
                size: A4;
                margin: 10mm 15mm 10mm 15mm;
            }

            /* Sembunyikan elemen layout utama */
            body > *:not(.print-wrapper) {
                display: none !important;
            }

            /* Wrapper print menggantikan body */
            .print-wrapper {
                display: block !important;
                background: white;
                position: relative;
                width: 100%;
                margin: 0;
                padding: 0;
            }

            /* Cegah tabel terpotong di tengah baris */
            tr, td, th {
                page-break-inside: avoid !important;
            }
            .avoid-break {
                page-break-inside: avoid !important;
            }

            /* Header tabel muncul lagi di halaman baru jika tabel panjang */
            thead {
                display: table-header-group;
            }

            /* Hilangkan bayangan dan border saat print */
            .shadow-xl, .border-slate-100 {
                box-shadow: none !important;
                border: none !important;
            }
            
            /* Pastikan background warna tecetak */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
    @endpush

    <div class="py-8 bg-slate-100 min-h-screen no-print">
        <div class="max-w-[210mm] mx-auto">
            
            {{-- Toolbar Atas --}}
            <div class="flex justify-between items-center mb-6 px-4">
                <a href="{{ route('grades.list', ['class_id' => $student->class_id, 'academic_year' => $year, 'semester' => $semester]) }}" 
                   class="flex items-center gap-2 text-slate-500 hover:text-blue-600 font-bold transition">
                    <i class="ph-bold ph-arrow-left"></i> Kembali
                </a>
                <button onclick="window.print()" class="bg-blue-600 text-white px-5 py-2 rounded-xl font-bold shadow-lg hover:bg-blue-700 transition flex items-center gap-2">
                    <i class="ph-bold ph-printer"></i> Cetak Rapor
                </button>
            </div>

            {{-- 1. PREVIEW SCREEN CONTAINER --}}
            <div class="bg-white p-10 md:p-12 shadow-xl relative overflow-hidden text-slate-900 font-serif min-h-[297mm]">
               {{-- Panggil Component Rapor --}}
               <x-report-content 
                   :student="$student" 
                   :semester="$semester" 
                   :year="$year" 
                   :subjects="$subjects" 
                   :record="$record" 
               />
            </div>
        </div>
    </div>

    {{-- 2. PRINT WRAPPER (Khusus saat Print) --}}
    <div class="print-wrapper hidden">
        {{-- Panggil Component Rapor (Lagi) --}}
        <x-report-content 
            :student="$student" 
            :semester="$semester" 
            :year="$year" 
            :subjects="$subjects" 
            :record="$record" 
        />
    </div>

</x-app-layout>