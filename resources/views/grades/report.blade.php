<x-app-layout>
    {{-- CSS Khusus Cetak --}}
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
        @media print {
            @page { size: A4; margin: 10mm; }
            body > *:not(.print-wrapper) { display: none !important; }
            .print-wrapper { display: block !important; background: white; width: 100%; height: 100%; }
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            tr, td, th, .avoid-break { page-break-inside: avoid !important; }
            .no-print { display: none !important; }
        }
    </style>
    @endpush

    {{-- WRAPPER UTAMA DENGAN ALPINE JS UNTUK ZOOM --}}
    <div class="py-6 bg-slate-200 min-h-screen no-print font-sans text-slate-800 overflow-hidden"
         x-data="{ 
            scale: 1,
            zoomIn() { if(this.scale < 1.5) this.scale += 0.1 },
            zoomOut() { if(this.scale > 0.5) this.scale -= 0.1 },
            resetZoom() { this.scale = 1 }
         }">
        
        {{-- TOOLBAR FLOATING --}}
        <div class="fixed top-6 left-1/2 -translate-x-1/2 z-50 w-[90%] max-w-4xl">
            <div class="bg-slate-900/90 backdrop-blur-xl text-white p-3 rounded-2xl shadow-2xl flex flex-col md:flex-row justify-between items-center gap-4 border border-white/10 ring-1 ring-black/5">
                
                {{-- Info Siswa & Kembali --}}
                <div class="flex items-center gap-4 w-full md:w-auto">
                    <a href="{{ route('grades.list', ['class_id' => $student->class_id, 'academic_year' => $year, 'semester' => $semester]) }}" 
                       class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center hover:bg-white/20 transition text-white shrink-0"
                       title="Kembali ke Daftar">
                        <i class="ph-bold ph-arrow-left text-lg"></i>
                    </a>
                    <div class="min-w-0">
                        <h2 class="font-bold text-sm md:text-base leading-tight truncate">{{ $student->name }}</h2>
                        <p class="text-[10px] md:text-xs text-blue-200 font-mono">{{ $student->student_id }} | Kelas {{ $student->schoolClass->name ?? '-' }}</p>
                    </div>
                </div>

                {{-- Kontrol Zoom & Navigasi --}}
                <div class="flex items-center gap-2 bg-black/20 p-1 rounded-xl">
                    
                    {{-- NAVIGASI SISWA (SUDAH AKTIF) --}}
                    <div class="flex items-center mr-2 border-r border-white/10 pr-2 gap-1">
                        {{-- Tombol Previous --}}
                        @if(isset($prevStudentId) && $prevStudentId)
                            <a href="{{ route('grades.report', ['student_id' => $prevStudentId, 'year' => $year, 'semester' => $semester]) }}" 
                               class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-white transition"
                               title="Siswa Sebelumnya">
                                <i class="ph-bold ph-caret-left"></i>
                            </a>
                        @else
                            <button disabled class="w-8 h-8 flex items-center justify-center rounded-lg text-white/30 cursor-not-allowed">
                                <i class="ph-bold ph-caret-left"></i>
                            </button>
                        @endif

                        <span class="text-xs font-bold text-slate-400 select-none">Navigasi</span>

                        {{-- Tombol Next --}}
                        @if(isset($nextStudentId) && $nextStudentId)
                            <a href="{{ route('grades.report', ['student_id' => $nextStudentId, 'year' => $year, 'semester' => $semester]) }}" 
                               class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-white transition"
                               title="Siswa Selanjutnya">
                                <i class="ph-bold ph-caret-right"></i>
                            </a>
                        @else
                            <button disabled class="w-8 h-8 flex items-center justify-center rounded-lg text-white/30 cursor-not-allowed">
                                <i class="ph-bold ph-caret-right"></i>
                            </button>
                        @endif
                    </div>

                    {{-- Zoom Controls --}}
                    <button @click="zoomOut()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-white transition"><i class="ph-bold ph-minus"></i></button>
                    <span class="text-xs font-mono w-12 text-center select-none" x-text="Math.round(scale * 100) + '%'"></span>
                    <button @click="zoomIn()" class="w-8 h-8 flex items-center justify-center rounded-lg hover:bg-white/10 text-white transition"><i class="ph-bold ph-plus"></i></button>
                </div>
                
                {{-- Tombol Cetak --}}
                <button onclick="window.print()" class="w-full md:w-auto px-5 py-2 bg-blue-600 hover:bg-blue-500 text-white font-bold rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2 text-sm">
                    <i class="ph-bold ph-printer text-lg"></i>
                    <span class="hidden sm:inline">Cetak / PDF</span>
                </button>
            </div>
        </div>

        {{-- AREA PREVIEW (Scrollable) --}}
        <div class="mt-24 pb-20 overflow-auto flex justify-center custom-scrollbar h-[calc(100vh-100px)]">
            <div class="transition-transform duration-200 origin-top" :style="`transform: scale(${scale})`">
                
                {{-- KERTAS A4 --}}
                <div class="bg-white w-[210mm] min-h-[297mm] p-[15mm] md:p-[20mm] shadow-2xl relative text-slate-900 font-serif mx-auto">
                    
                    {{-- Watermark Absolut --}}
                    <div class="absolute inset-0 watermark z-0"></div>

                    {{-- Konten Laporan --}}
                    <div class="relative z-10">
                        <x-report-content 
                            :student="$student" 
                            :semester="$semester" 
                            :year="$year" 
                            :subjects="$subjects" 
                            :record="$record" 
                        />

                        {{-- Footer Validasi --}}
                        <div class="mt-12 pt-4 border-t border-slate-200 flex justify-between items-end text-[10px] text-slate-400 no-print">
                            <div>
                                <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
                                <p>Oleh: {{ Auth::user()->name }}</p>
                            </div>
                            <div class="text-right flex flex-col items-center gap-1">
                                <div class="w-12 h-12 bg-slate-100 border border-slate-200 flex items-center justify-center rounded">
                                    <i class="ph-duotone ph-qr-code text-2xl"></i>
                                </div>
                                <span>Dokumen Valid</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- WRAPPER CETAK --}}
    <div class="print-wrapper hidden">
        <div class="watermark fixed inset-0 z-0"></div>
        <div class="relative z-10">
            <x-report-content 
                :student="$student" 
                :semester="$semester" 
                :year="$year" 
                :subjects="$subjects" 
                :record="$record" 
            />
        </div>
    </div>

</x-app-layout>