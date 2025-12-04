<x-app-layout>
    {{-- STYLE KHUSUS CETAK --}}
    @push('styles')
    <style>
        @media print {
            /* 1. Sembunyikan Elemen UI Aplikasi (Sidebar, Navbar, dll) */
            nav, header, aside, .sidebar-container, .mobile-overlay, .no-print {
                display: none !important;
            }
            
            /* 2. Reset Layout Container agar Full Width Kertas */
            body { 
                background: white !important; 
                font-family: 'Times New Roman', serif; /* Font resmi laporan */
                -webkit-print-color-adjust: exact; 
            }
            main { padding: 0 !important; margin: 0 !important; width: 100% !important; }
            .min-h-screen { height: auto !important; }
            
            /* Hilangkan border radius dan shadow layout utama */
            .flex-1.bg-gray-50, .bg-white, .rounded-3xl { 
                background: white !important; 
                border-radius: 0 !important; 
                margin: 0 !important; 
                box-shadow: none !important;
                border: none !important;
            }

            /* 3. Format Tabel Cetak */
            .table-container {
                border: none !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                overflow: visible !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                font-size: 11px !important; /* Perkecil font agar muat banyak */
            }
            th, td {
                border: 1px solid #000 !important; /* Border hitam tegas */
                padding: 6px !important;
                color: black !important;
            }
            th { 
                background-color: #f0f0f0 !important; 
                text-transform: uppercase;
                font-weight: bold;
                text-align: center;
            }
            
            /* 4. Elemen Khusus Cetak */
            .print-header { display: block !important; }
            .print-footer { display: block !important; page-break-inside: avoid; }
            
            /* Hapus Pagination */
            .pagination-container { display: none !important; }
            
            /* Reset warna teks untuk hemat tinta */
            .text-blue-600, .text-emerald-600, .text-rose-500 { color: black !important; }
            .bg-slate-100, .bg-rose-50 { background-color: transparent !important; }
        }

        /* Default: Sembunyikan Elemen Cetak di Layar */
        .print-header, .print-footer { display: none; }
    </style>
    @endpush

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- [CETAK SAJA] KOP SURAT --}}
            <div class="print-header mb-6 pb-4 border-b-2 border-black text-center">
                <h1 class="text-xl font-bold uppercase tracking-wide">Pemerintah Kabupaten Ciamis</h1>
                <h2 class="text-2xl font-black uppercase">SMP Negeri 3 Lakbok</h2>
                <p class="text-sm italic">Alamat: Jl. Raya Lakbok, Kecamatan Lakbok, Kabupaten Ciamis - Jawa Barat</p>
                <div class="mt-4 pt-2 border-t border-black">
                    <h3 class="text-lg font-bold uppercase underline">Laporan Monitoring Kegiatan Belajar Mengajar</h3>
                    <p class="text-sm">Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}</p>
                </div>
            </div>

            {{-- [LAYAR SAJA] HEADER PAGE --}}
            <div class="mb-8 no-print">
                <h1 class="text-3xl font-black text-slate-800 tracking-tight flex items-center gap-3">
                    <i class="ph-duotone ph-monitor-play text-blue-600"></i> Monitoring Jurnal Mengajar
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Rekap aktivitas pembelajaran guru, materi, dan kehadiran siswa.
                </p>
            </div>

            {{-- [LAYAR SAJA] FILTER SECTION --}}
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 mb-8 no-print">
                <form method="GET" action="{{ route('reports.teaching_journal') }}" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4 items-end">
                    
                    {{-- Tanggal Mulai --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700">
                    </div>

                    {{-- Tanggal Selesai --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-xl border-slate-200 text-sm font-bold text-slate-700">
                    </div>

                    {{-- Filter Guru --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Guru</label>
                        <select name="teacher_id" class="w-full rounded-xl border-slate-200 text-sm">
                            <option value="">Semua Guru</option>
                            @foreach($teachers as $t)
                                <option value="{{ $t->id }}" {{ $teacherId == $t->id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Kelas --}}
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1">Kelas</label>
                        <select name="class_id" class="w-full rounded-xl border-slate-200 text-sm">
                            <option value="">Semua Kelas</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Filter --}}
                    <div>
                        <button type="submit" class="w-full py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/30 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-funnel"></i> Filter Data
                        </button>
                    </div>
                </form>
            </div>

            {{-- TABEL DATA --}}
            <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden table-container">
                <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/50 no-print">
                    <h3 class="font-bold text-slate-800 text-lg">Hasil Rekapitulasi</h3>
                    <div class="flex gap-2">
                        <button onclick="window.print()" class="px-4 py-2 bg-white border border-slate-200 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 flex items-center gap-2">
                            <i class="ph-bold ph-printer"></i> Cetak Laporan
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase">
                            <tr>
                                <th class="px-6 py-4 w-24 text-center">Tanggal</th>
                                <th class="px-6 py-4 w-20 text-center">Waktu</th>
                                <th class="px-6 py-4">Guru & Mapel</th>
                                <th class="px-6 py-4 w-16 text-center">Kelas</th>
                                <th class="px-6 py-4 w-1/3">Materi Pembelajaran</th>
                                <th class="px-6 py-4 text-center w-24">Kehadiran</th>
                                <th class="px-6 py-4 text-center no-print">Bukti</th> 
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($sessions as $session)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 align-top text-center border-b border-slate-100">
                                        <div class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($session->date)->format('d/m/Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 align-top text-center border-b border-slate-100 font-mono text-xs">
                                        {{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }} - 
                                        {{ $session->ended_at ? \Carbon\Carbon::parse($session->ended_at)->format('H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 align-top border-b border-slate-100">
                                        <div class="font-bold text-blue-600">{{ $session->teacher->name ?? '-' }}</div>
                                        <div class="text-xs font-bold text-slate-500 mt-1">{{ $session->schedule->subject->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 align-top text-center border-b border-slate-100">
                                        <span class="inline-block px-2 py-1 rounded border border-slate-200 font-bold text-xs">
                                            {{ $session->schedule->schoolClass->name ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 align-top border-b border-slate-100">
                                        <p class="font-bold text-slate-800 text-sm mb-1">{{ $session->topic ?? 'Tanpa Topik' }}</p>
                                        <p class="text-xs text-slate-500 line-clamp-3">{{ $session->activities ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 align-top text-center border-b border-slate-100">
                                        <div class="flex flex-col items-center gap-1">
                                            <span class="text-sm font-black text-emerald-600">{{ $session->hadir_count + $session->late_count }} Hadir</span>
                                            @if($session->alpha_count > 0)
                                                <span class="text-[10px] font-bold text-rose-500">
                                                    {{ $session->alpha_count }} Alpha
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 align-top text-center no-print border-b border-slate-100">
                                        <div class="flex gap-2 justify-center">
                                            @if($session->photo_proof)
                                                <a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="text-purple-600 hover:text-purple-800" title="Foto">
                                                    <i class="ph-bold ph-image text-lg"></i>
                                                </a>
                                            @endif
                                            @if($session->reference_link || $session->video_link)
                                                <a href="{{ $session->reference_link ?? $session->video_link }}" target="_blank" class="text-blue-600 hover:text-blue-800" title="Link">
                                                    <i class="ph-bold ph-link text-lg"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-slate-400">
                                        <p>Tidak ada data jurnal ditemukan pada periode ini.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination (Hilang saat Print) --}}
                <div class="p-6 border-t border-slate-50 no-print pagination-container">
                    {{ $sessions->links() }}
                </div>
            </div>

            {{-- [CETAK SAJA] FOOTER TANDA TANGAN --}}
            <div class="print-footer mt-8 pt-4">
                <table style="width: 100%; border: none !important;">
                    <tr style="border: none !important;">
                        <td style="width: 70%; border: none !important;"></td>
                        <td style="width: 30%; text-align: center; border: none !important;">
                            <p>Lakbok, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                            <p>Mengetahui,</p>
                            <p>Kepala Sekolah</p>
                            <br><br><br><br>
                            <p class="font-bold underline">TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.</p>
                            <p>NIP. 19800101 200501 1 001</p>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</x-app-layout>