<x-app-layout>
    {{-- 
        SETUP DATA SEKOLAH (Agar mudah diganti tanpa ubah HTML bawah) 
        Idealnya data ini dilempar dari Controller, tapi untuk quick fix bisa disini.
    --}}
    @php
        $schoolName = "SMP NEGERI 3 LAKBOK";
        $schoolAddress = "Jl. Raya Lakbok, Kecamatan Lakbok, Kabupaten Ciamis - Jawa Barat";
        $principalName = "TANTAN SUTANDI NUGRAHA, S.Si, M.Pd.";
        $principalNIP  = "19820928201101 1002";
        $printDate     = \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y');
    @endphp

   @push('styles')
    <style>
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); }
        @media print {
            body * { visibility: hidden; }
            .print-container, .print-container * { visibility: visible; }
            .print-container { position: absolute !important; left: 0 !important; top: 0 !important; width: 100% !important; margin: 0 !important; padding: 20px !important; background: white !important; z-index: 99999; box-shadow: none !important; border: none !important; border-radius: 0 !important;}
            table { width: 100% !important; border-collapse: collapse !important; font-family: 'Times New Roman', Times, serif !important; font-size: 11px !important; }
            thead th { background-color: #f3f4f6 !important; color: #000 !important; font-weight: bold !important; border: 1px solid #000 !important; padding: 8px !important; }
            td { border: 1px solid #000 !important; padding: 6px 8px !important; color: #000 !important; vertical-align: top !important; }
            .print-header, .print-footer { display: block !important; width: 100%; }
            .no-print, .pagination-container, a[href] { display: none !important; }
            tr { page-break-inside: avoid; }
        }
        .print-header, .print-footer { display: none; }
    </style>
    @endpush

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION ELEVATE --}}
            <div class="relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 mb-8 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 no-print">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-white/30 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl">📊</span> Monitoring Jurnal
                        </h1>
                        <p class="text-[#2A3B52]/80 text-sm font-medium leading-relaxed max-w-lg">
                            Rekapitulasi aktivitas belajar mengajar (KBM) guru beserta kehadiran siswa secara terperinci.
                        </p>
                    </div>
                    <button onclick="window.print()" class="group bg-white/40 border border-white/50 backdrop-blur-md text-[#2A3B52] px-6 py-3.5 rounded-xl font-bold text-sm shadow-sm hover:bg-white/60 transition-all flex items-center gap-2 transform active:scale-95">
                        <i class="ph-bold ph-printer text-xl group-hover:scale-110 transition-transform"></i>
                        <span>Cetak Laporan</span>
                    </button>
                </div>
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-white p-6 rounded-xl fluent-card mb-8 no-print relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-bold text-[#2A3B52] flex items-center gap-2 mb-5">
                        <span class="bg-[#F3F9FD] text-[#5295FF] border border-[#D0E7F8] w-8 h-8 rounded-lg flex items-center justify-center"><i class="ph-bold ph-faders"></i></span>
                        Filter Data
                    </h3>

                    <form method="GET" action="{{ route('reports.teaching_journal') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Dari Tanggal</label>
                            <input type="date" name="start_date" value="{{ $startDate }}" class="w-full rounded-lg border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] h-11 px-4">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Sampai Tanggal</label>
                            <input type="date" name="end_date" value="{{ $endDate }}" class="w-full rounded-lg border-slate-200 bg-slate-50 font-bold text-[#2A3B52] focus:ring-[#5295FF] h-11 px-4">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Guru</label>
                            <select name="teacher_id" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm font-bold text-[#2A3B52] focus:ring-[#5295FF] h-11 px-4">
                                <option value="">Semua Guru</option>
                                @foreach($teachers as $t) <option value="{{ $t->id }}" {{ $teacherId == $t->id ? 'selected' : '' }}>{{ $t->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kelas</label>
                            <select name="class_id" class="w-full rounded-lg border-slate-200 bg-slate-50 text-sm font-bold text-[#2A3B52] focus:ring-[#5295FF] h-11 px-4">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $c) <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option> @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full h-11 bg-[#2A3B52] hover:bg-[#182436] text-white font-bold rounded-lg transition-all shadow-md flex items-center justify-center gap-2 group">
                            <i class="ph-bold ph-magnifying-glass text-lg"></i> Terapkan
                        </button>
                    </form>
                </div>
            </div>

            {{-- === CONTAINER PRINT === --}}
            <div class="bg-white rounded-xl fluent-card overflow-hidden print-container p-0 md:p-0">
                <div class="print-header px-8 pt-6 mb-4 text-center">
                    <div style="border-bottom: 2px solid black; padding-bottom: 10px;">
                        <h2 class="text-xl font-bold uppercase" style="margin:0;">PEMERINTAH KABUPATEN CIAMIS</h2>
                        <h2 class="text-2xl font-black uppercase" style="margin:5px 0;">{{ $schoolName }}</h2>
                        <p class="text-sm italic" style="margin:0;">Alamat: {{ $schoolAddress }}</p>
                    </div>
                    <div class="mt-4 text-center">
                        <h3 class="text-lg font-bold uppercase underline">LAPORAN JURNAL MENGAJAR</h3>
                        <p class="text-sm">Periode: {{ \Carbon\Carbon::parse($startDate)->locale('id')->translatedFormat('d F Y') }} s.d. {{ \Carbon\Carbon::parse($endDate)->locale('id')->translatedFormat('d F Y') }}</p>
                    </div>
                </div>

                <div class="overflow-x-auto px-0 md:px-0">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100 text-slate-500">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-24">Tanggal</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider">Guru & Mapel</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center w-24">Kelas</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider w-1/3">Materi & Aktivitas</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center">Kehadiran</th>
                                <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-center no-print w-20">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($sessions as $session)
                                @php $hadirTotal = ($session->hadir_count ?? 0) + ($session->late_count ?? 0); $alphaTotal = $session->alpha_count ?? 0; @endphp
                                <tr class="hover:bg-slate-50 transition-colors group">
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <div class="font-bold text-[#2A3B52] bg-slate-100 rounded-lg py-1 px-2 inline-block">{{ \Carbon\Carbon::parse($session->date)->format('d/m') }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-1 font-bold">{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 border-b border-slate-100 align-top">
                                        <div class="font-bold text-[#2A3B52] text-sm">{{ $session->teacher->name ?? '-' }}</div>
                                        <div class="text-xs font-bold text-[#5295FF] mt-0.5 flex items-center gap-1"><i class="ph-bold ph-book-open"></i> {{ $session->schedule->subject->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <span class="inline-block px-2.5 py-1 rounded-md border border-slate-200 font-bold text-xs bg-white text-slate-600 shadow-sm">{{ $session->schedule->schoolClass->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 border-b border-slate-100 align-top">
                                        <p class="font-bold text-[#2A3B52] text-sm mb-1">{{ $session->topic ?? 'Tanpa Topik' }}</p>
                                        <p class="text-xs text-slate-500 text-justify leading-relaxed">{{ $session->activities ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top">
                                        <div class="flex flex-col items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wide bg-[#DFF6DD] text-[#107C10] px-2 py-1 rounded border border-[#B7DFB9]">{{ $hadirTotal }} Hadir</span>
                                            @if($alphaTotal > 0)<span class="inline-flex items-center gap-1 text-[10px] font-black uppercase tracking-wide bg-[#FDE7E9] text-[#D13438] px-2 py-1 rounded border border-[#F4C3C9]">{{ $alphaTotal }} Alpha</span>@endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center border-b border-slate-100 align-top no-print">
                                        <div class="flex gap-2 justify-center">
                                            @if($session->photo_proof)<a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="w-9 h-9 rounded-lg bg-slate-100 text-[#2A3B52] flex items-center justify-center hover:bg-[#2A3B52] hover:text-white transition-all shadow-sm"><i class="ph-bold ph-image text-lg"></i></a>@endif
                                            @if($session->reference_link || $session->video_link)<a href="{{ $session->reference_link ?? $session->video_link }}" target="_blank" class="w-9 h-9 rounded-lg bg-[#F3F9FD] text-[#5295FF] flex items-center justify-center hover:bg-[#5295FF] hover:text-white transition-all shadow-sm border border-[#D0E7F8]"><i class="ph-bold ph-link text-lg"></i></a>@endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-20 text-center text-slate-400">Tidak ada data jurnal ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-50 no-print">{{ $sessions->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>