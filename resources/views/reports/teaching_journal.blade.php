<x-app-layout>
    {{-- CSS Khusus Cetak --}}
    @push('styles')
    <style>
        /* --- LOGIKA PRINT "JURUS PAMUNGKAS" --- */
        @media print {
            /* 1. Sembunyikan SEMUA elemen di halaman */
            body * {
                visibility: hidden;
            }

            /* 2. Kecuali Container Laporan & isinya */
            .print-container, .print-container * {
                visibility: visible;
            }

            /* 3. Posisikan Container Laporan di pojok kiri atas kertas */
            .print-container {
                position: absolute !important;
                left: 0 !important;
                top: 0 !important;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
                z-index: 99999; /* Pastikan di paling atas */
                box-shadow: none !important;
                border: none !important;
                border-radius: 0 !important;
            }

            /* 4. Reset Format Tabel agar Tajam di Kertas */
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-family: 'Times New Roman', Times, serif !important; /* Font resmi */
                font-size: 11px !important;
            }
            
            thead th {
                background-color: #e5e7eb !important; /* Abu-abu muda untuk header */
                color: #000 !important;
                font-weight: bold !important;
                border: 1px solid #000 !important;
                -webkit-print-color-adjust: exact; /* Paksa cetak warna background */
            }

            td {
                border: 1px solid #000 !important; /* Border hitam tegas */
                padding: 4px 6px !important;
                color: #000 !important;
                vertical-align: top !important;
            }

            /* 5. Tampilkan Elemen Khusus Cetak (Kop Surat & TTD) */
            .print-header, .print-footer {
                display: block !important;
                width: 100%;
            }

            /* 6. Sembunyikan elemen web yang tidak perlu */
            .no-print, .pagination-container, a[href] {
                display: none !important;
                text-decoration: none !important;
            }
            
            /* Hapus Pagination & Link Biru */
            a { color: #000 !important; text-decoration: none !important; }
        }

        /* Default Layar: Sembunyikan Elemen Cetak */
        .print-header, .print-footer { display: none; }
    </style>
    @endpush

    <div class="py-8 sm:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HEADER LAYAR (WEB ONLY) --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 no-print">
                <div>
                    <h1 class="text-3xl font-black text-slate-800 tracking-tight">Monitoring Jurnal</h1>
                    <p class="text-slate-500 mt-1">Rekapitulasi aktivitas KBM guru & kehadiran siswa.</p>
                </div>
                <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-slate-200 text-slate-700 font-bold rounded-xl hover:bg-slate-50 hover:text-blue-600 transition shadow-sm flex items-center gap-2">
                    <i class="ph-bold ph-printer"></i> Cetak Laporan
                </button>
            </div>

            {{-- FILTER SECTION (WEB ONLY) --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 mb-8 no-print">
                <form method="GET" action="{{ route('reports.teaching_journal') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-xl border-slate-200 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Guru</label>
                        <select name="teacher_id" class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Guru</option>
                            @foreach($teachers as $t) <option value="{{ $t->id }}" {{ $teacherId == $t->id ? 'selected' : '' }}>{{ $t->name }}</option> @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Kelas</label>
                        <select name="class_id" class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $c) <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option> @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-funnel"></i> Terapkan
                    </button>
                </form>
            </div>

            {{-- === CONTAINER YANG AKAN DICETAK (PRINT CONTAINER) === --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 overflow-hidden print-container p-0 md:p-0">
                
                {{-- KOP SURAT (HANYA MUNCUL SAAT PRINT) --}}
                <div class="print-header px-8 pt-6 mb-4 text-center">
                    <div style="border-bottom: 2px solid black; padding-bottom: 10px;">
                        <h2 class="text-xl font-bold uppercase" style="margin:0;">PEMERINTAH KABUPATEN CIAMIS</h2>
                        <h2 class="text-2xl font-black uppercase" style="margin:5px 0;">SMP NEGERI 3 LAKBOK</h2>
                        <p class="text-sm italic" style="margin:0;">Alamat: Jl. Raya Lakbok, Kecamatan Lakbok, Kabupaten Ciamis - Jawa Barat</p>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-lg font-bold uppercase underline">LAPORAN JURNAL MENGAJAR</h3>
                        <p class="text-sm">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</p>
                    </div>
                </div>

                {{-- TABEL DATA --}}
                <div class="overflow-x-auto px-0 md:px-0">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center w-24">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Guru & Mapel</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center w-20">Kelas</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider w-1/3">Materi & Aktivitas</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Kehadiran</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center no-print">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($sessions as $session)
                                <tr class="hover:bg-blue-50/20 transition-colors">
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <div class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($session->date)->format('d/m/y') }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-1">
                                            {{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 border-b border-slate-100 align-top">
                                        <div class="font-bold text-slate-800">{{ $session->teacher->name ?? '-' }}</div>
                                        <div class="text-xs text-blue-600 font-bold mt-1">{{ $session->schedule->subject->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <span class="inline-block px-2 py-1 rounded-lg border border-slate-200 font-bold text-xs bg-slate-50 text-slate-600">
                                            {{ $session->schedule->schoolClass->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 border-b border-slate-100 align-top">
                                        <p class="font-bold text-slate-800 text-sm mb-1" style="color: black !important;">{{ $session->topic ?? 'Tanpa Topik' }}</p>
                                        <p class="text-xs text-slate-500 text-justify" style="color: black !important;">{{ $session->activities ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-xs font-black text-emerald-600" style="color: black !important;">{{ $session->hadir_count + $session->late_count }} Hadir</span>
                                            @if($session->alpha_count > 0)
                                                <span class="text-[10px] font-bold text-rose-500 bg-rose-50 px-1.5 py-0.5 rounded" style="color: black !important;">
                                                    {{ $session->alpha_count }} Alpha
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top no-print">
                                        <div class="flex gap-2 justify-center">
                                            @if($session->photo_proof)
                                                <a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center hover:bg-purple-600 hover:text-white transition">
                                                    <i class="ph-bold ph-image"></i>
                                                </a>
                                            @endif
                                            @if($session->reference_link || $session->video_link)
                                                <a href="{{ $session->reference_link ?? $session->video_link }}" target="_blank" class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-600 hover:text-white transition">
                                                    <i class="ph-bold ph-link"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <i class="ph-duotone ph-folder-dashed text-3xl mb-2"></i>
                                            <p>Tidak ada data jurnal ditemukan.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- FOOTER TTD (HANYA MUNCUL SAAT PRINT) --}}
                <div class="print-footer mt-8 px-8 pb-8 avoid-break">
                    <table style="width: 100%; border: none !important;">
                        <tr style="border: none !important;">
                            <td style="width: 70%; border: none !important;"></td>
                            <td style="width: 30%; text-align: center; border: none !important; vertical-align: top;">
                                <p class="text-sm">Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                <p class="text-sm font-bold mt-1">Kepala Sekolah</p>
                                <br><br><br><br>
                                <p class="font-bold underline text-sm">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
                                <p class="text-xs">NIP. 19800101 200501 1 001</p>
                            </td>
                        </tr>
                    </table>
                </div>

                {{-- Pagination (Hilang saat Print) --}}
                <div class="p-6 border-t border-slate-50 no-print">
                    {{ $sessions->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>