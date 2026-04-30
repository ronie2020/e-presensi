<x-app-layout>
    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION ELEVATE --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-6 md:p-10 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60">
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl text-elevate-primary">📊</span> Monitoring Jurnal
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-semibold leading-relaxed max-w-lg">
                            Rekapitulasi aktivitas belajar mengajar (KBM) guru beserta kehadiran siswa secara terperinci.
                        </p>
                    </div>
                    
                    {{-- TOMBOL CETAK DIPERBARUI --}}
                    {{-- Tombol ini sekarang mengirim parameter &print=true ke controller untuk membuka versi PDF --}}
                    <a href="{{ route('reports.teaching_journal', array_merge(request()->all(), ['print' => 'true'])) }}" target="_blank" class="group bg-white/60 border border-white/50 backdrop-blur-md text-elevate-dark hover:bg-white px-6 py-3.5 rounded-xl font-bold text-sm shadow-sm transition-all flex items-center gap-2 transform active:scale-95">
                        <i class="ph-bold ph-printer text-xl group-hover:scale-110 transition-transform"></i>
                        <span>Cetak Laporan / PDF</span>
                    </a>
                </div>
            </div>

            {{-- FILTER CARD --}}
            <div class="bg-white p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/40 mb-8 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-3 mb-6">
                        <span class="bg-elevate-soft text-elevate-primary w-10 h-10 rounded-xl flex items-center justify-center"><i class="ph-bold ph-faders text-xl"></i></span>
                        Filter Data
                    </h3>

                    <form method="GET" action="{{ route('reports.teaching_journal') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5 items-end">
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Dari Tanggal</label>
                            {{-- Tambahkan ?? '' --}}
                            <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Sampai Tanggal</label>
                            {{-- Tambahkan ?? '' --}}
                            <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full rounded-2xl border-slate-200 bg-elevate-soft font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Guru</label>
                            <select name="teacher_id" class="w-full rounded-2xl border-slate-200 bg-elevate-soft text-sm font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors">
                                <option value="">Semua Guru</option>
                                @foreach($teachers as $t) <option value="{{ $t->id }}" {{ $teacherId == $t->id ? 'selected' : '' }}>{{ $t->name }}</option> @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-elevate-primary uppercase tracking-wider mb-2 ml-1">Kelas</label>
                            <select name="class_id" class="w-full rounded-2xl border-slate-200 bg-elevate-soft text-sm font-bold text-elevate-dark focus:bg-white focus:ring-elevate-accent/30 focus:border-elevate-accent h-14 px-5 transition-colors">
                                <option value="">Semua Kelas</option>
                                @foreach($classes as $c) <option value="{{ $c->id }}" {{ $classId == $c->id ? 'selected' : '' }}>{{ $c->name }}</option> @endforeach
                            </select>
                        </div>
                        <button type="submit" class="w-full h-14 bg-elevate-dark hover:bg-elevate-primary text-white font-bold rounded-2xl transition-colors shadow-lg shadow-elevate-dark/30 flex items-center justify-center gap-2 group active:scale-95">
                            <i class="ph-bold ph-magnifying-glass text-lg"></i> Terapkan
                        </button>
                    </form>
                </div>
            </div>

            {{-- === CONTAINER TABEL UTAMA === --}}
            {{-- Tidak perlu lagi class "print-container" atau class "no-print" karena cetaknya di file terpisah --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/40 overflow-hidden">
                <div class="overflow-x-auto custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-elevate-soft/50 border-b border-slate-100 text-elevate-primary">
                            <tr>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-24">Tanggal</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider">Guru & Mapel</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-24">Kelas</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider w-1/3">Materi & Aktivitas</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center">Kehadiran</th>
                                <th class="px-6 py-5 text-xs font-black uppercase tracking-wider text-center w-20">Bukti</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm">
                            @forelse($sessions as $session)
                                @php $hadirTotal = ($session->hadir_count ?? 0) + ($session->late_count ?? 0); $alphaTotal = $session->alpha_count ?? 0; @endphp
                                <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="font-black text-elevate-dark bg-elevate-soft rounded-lg py-1.5 px-3 inline-block">{{ \Carbon\Carbon::parse($session->date)->format('d/m') }}</div>
                                        <div class="text-[10px] text-slate-400 font-mono mt-1.5 font-bold">{{ $session->started_at ? \Carbon\Carbon::parse($session->started_at)->format('H:i') : '-' }}</div>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <div class="font-black text-elevate-dark text-base">{{ $session->teacher->name ?? '-' }}</div>
                                        <div class="text-xs font-bold text-elevate-primary mt-1 flex items-center gap-1.5"><i class="ph-bold ph-book-open"></i> {{ $session->schedule->subject->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <span class="inline-block px-3 py-1.5 rounded-lg border border-slate-200 font-bold text-xs bg-white text-slate-600 shadow-sm">{{ $session->schedule->schoolClass->name ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-5 align-top">
                                        <p class="font-black text-elevate-dark mb-1.5">{{ $session->topic ?? 'Tanpa Topik' }}</p>
                                        <p class="text-xs text-slate-500 font-semibold text-justify leading-relaxed">{{ $session->activities ?? '-' }}</p>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="flex flex-col items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wide bg-[#DFF6DD] text-[#107C10] px-2.5 py-1 rounded border border-[#B7DFB9]">{{ $hadirTotal }} Hadir</span>
                                            @if($alphaTotal > 0)<span class="inline-flex items-center gap-1.5 text-[10px] font-black uppercase tracking-wide bg-[#FDE7E9] text-[#D13438] px-2.5 py-1 rounded border border-[#F4C3C9]">{{ $alphaTotal }} Alpha</span>@endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 text-center align-top">
                                        <div class="flex gap-2 justify-center">
                                            @if($session->photo_proof)<a href="{{ asset('storage/' . $session->photo_proof) }}" target="_blank" class="w-10 h-10 rounded-xl bg-elevate-soft text-elevate-dark flex items-center justify-center hover:bg-elevate-dark hover:text-white transition-colors shadow-sm"><i class="ph-bold ph-image text-lg"></i></a>@endif
                                            @if($session->reference_link || $session->video_link)<a href="{{ $session->reference_link ?? $session->video_link }}" target="_blank" class="w-10 h-10 rounded-xl bg-white text-elevate-primary flex items-center justify-center hover:bg-elevate-primary hover:text-white transition-colors shadow-sm border border-slate-200"><i class="ph-bold ph-link text-lg"></i></a>@endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-24 text-center text-slate-400 font-bold italic">Tidak ada data jurnal ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-slate-100 bg-white rounded-b-[2.5rem]">{{ $sessions->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>