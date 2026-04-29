<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-in fade-in duration-500 font-sans" x-data="{ showHistory: false }">
    
    {{-- LOGIKA HITUNG STATISTIK DARI DATA YANG TAMPIL --}}
    @php
        $stats = [
            'present' => 0,
            'sick' => 0,
            'permission' => 0,
            'alpha' => 0,
            'total' => isset($teaching_journals) ? $teaching_journals->count() : 0
        ];

        if(isset($teaching_journals)) {
            foreach($teaching_journals as $journal) {
                // Gunakan pencarian strict jika tipe ID berbeda (string vs int)
                $attendance = $journal->attendances->first(function ($att) use ($student) {
                    return (string)$att->student_id === (string)$student->id;
                });
                
                $status = $attendance ? $attendance->status : null;
                
                // Logika Auto-Alpha jika closed
                if($journal->status == 'closed' && !$status) $status = 'alpha';

                if($status == 'present') $stats['present']++;
                elseif($status == 'sick') $stats['sick']++;
                elseif($status == 'permission') $stats['permission']++;
                elseif($status == 'alpha') $stats['alpha']++;
            }
        }
        
        // Menghitung persentase untuk tinggi diagram (dengan number_format agar CSS valid)
        $total = $stats['total'] > 0 ? $stats['total'] : 1;
        $pctPresent = number_format(($stats['present'] / $total) * 100, 1);
        $pctSickPermit = number_format((($stats['sick'] + $stats['permission']) / $total) * 100, 1);
        $pctAlpha = number_format(($stats['alpha'] / $total) * 100, 1);
    @endphp

   {{-- KOLOM KIRI --}}
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-slate-100 sticky top-24 group overflow-hidden hover:border-elevate-accent/30 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] group-hover:scale-110 transition-all duration-500 pointer-events-none">
                <i class="ph-fill ph-chalkboard-teacher text-9xl text-elevate-primary"></i>
            </div>

            <div class="relative z-10">
                <h3 class="text-lg font-black text-elevate-dark mb-1">Pantauan KBM</h3>
                <p class="text-slate-500 text-xs mb-6 font-medium">Ringkasan kehadiran di kelas ({{ $stats['total'] }} Sesi).</p>

                {{-- Chart Bar --}}
                <div class="flex items-end gap-3 h-32 mb-6 px-4 pb-2 border-b border-slate-50">
                    <div class="flex-1 flex flex-col items-center gap-2 group/bar h-full justify-end">
                        <div class="w-full bg-elevate-soft rounded-t-lg relative h-full flex items-end overflow-hidden">
                            <div style="height: {{ $pctPresent }}%" class="w-full bg-elevate-primary transition-all duration-1000 group-hover/bar:bg-elevate-dark relative">
                                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-elevate-dark opacity-0 group-hover/bar:opacity-100 transition-opacity">{{ $pctPresent }}%</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Hadir</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-2 group/bar h-full justify-end">
                        <div class="w-full bg-slate-100 rounded-t-lg relative h-full flex items-end overflow-hidden">
                            <div style="height: {{ $pctSickPermit }}%" class="w-full bg-slate-400 transition-all duration-1000 group-hover/bar:bg-slate-500 relative">
                                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-slate-600 opacity-0 group-hover/bar:opacity-100 transition-opacity">{{ $pctSickPermit }}%</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Izin</span>
                    </div>
                    <div class="flex-1 flex flex-col items-center gap-2 group/bar h-full justify-end">
                        <div class="w-full bg-rose-50 rounded-t-lg relative h-full flex items-end overflow-hidden">
                            <div style="height: {{ $pctAlpha }}%" class="w-full bg-rose-500 transition-all duration-1000 group-hover/bar:bg-rose-400 relative">
                                <span class="absolute -top-6 left-1/2 -translate-x-1/2 text-[10px] font-bold text-rose-600 opacity-0 group-hover/bar:opacity-100 transition-opacity">{{ $pctAlpha }}%</span>
                            </div>
                        </div>
                        <span class="text-[10px] font-bold text-slate-500 uppercase">Alpha</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-elevate-soft/50 border border-elevate-accent/20">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-elevate-primary/20 text-elevate-primary flex items-center justify-center shadow-sm">
                                <i class="ph-bold ph-check"></i>
                            </div>
                            <span class="text-xs font-bold text-elevate-dark">Mengikuti Kelas</span>
                        </div>
                        <span class="text-lg font-black text-elevate-primary">{{ $stats['present'] }}</span>
                    </div>
                    
                    @if($stats['alpha'] > 0)
                    <div class="flex items-center justify-between p-3 rounded-2xl bg-rose-50 border border-rose-100 animate-pulse">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-rose-200 text-rose-700 flex items-center justify-center shadow-sm">
                                <i class="ph-bold ph-x"></i>
                            </div>
                            <span class="text-xs font-bold text-rose-800">Tidak Hadir (Alpha)</span>
                        </div>
                        <span class="text-lg font-black text-rose-600">{{ $stats['alpha'] }}</span>
                    </div>
                    @endif
                </div>

                <div class="mt-6 pt-4 border-t border-slate-50 text-center">
                    <button @click="showHistory = true" class="w-full py-3 bg-elevate-dark text-white rounded-xl text-xs font-bold hover:bg-elevate-primary transition-all flex items-center justify-center gap-2 shadow-lg shadow-elevate-dark/20 active:scale-95">
                        <i class="ph-bold ph-list-dashes"></i> Lihat Riwayat Lengkap
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN --}}
    <div class="lg:col-span-2 space-y-6">
        @if(isset($teaching_journals) && count($teaching_journals) > 0)
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 sm:p-8">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-50">
                    <h4 class="font-black text-elevate-dark flex items-center gap-2 text-lg">
                        <i class="ph-duotone ph-notebook text-elevate-primary text-xl"></i> Riwayat Pembelajaran
                    </h4>
                </div>

                <div class="relative space-y-8 pl-4">
                    <div class="absolute left-4 top-4 bottom-4 w-0.5 bg-slate-100 -ml-[0.5px]"></div>

                    @foreach($teaching_journals->take(10) as $journal)
                        @php
                            $attendance = $journal->attendances->first(function ($att) use ($student) { return (string)$att->student_id === (string)$student->id; });
                            $status = $attendance ? $attendance->status : null;
                            if ($journal->status == 'closed' && !$status) $status = 'alpha';
                            
                            $statusConfig = [
                                'present' => ['bg' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-accent/30', 'label' => 'Hadir', 'icon' => 'ph-check-circle'],
                                'sick' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'border' => 'border-slate-200', 'label' => 'Sakit', 'icon' => 'ph-thermometer'],
                                'permission' => ['bg' => 'bg-elevate-peach-light/20', 'text' => 'text-elevate-peach-dark', 'border' => 'border-elevate-peach/30', 'label' => 'Izin', 'icon' => 'ph-hand-waving'],
                                'alpha' => ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'label' => 'Alpha', 'icon' => 'ph-x-circle'],
                                'default' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-400', 'border' => 'border-slate-100', 'label' => 'Belum Absen', 'icon' => 'ph-question'],
                            ];
                            $config = $statusConfig[$status] ?? $statusConfig['default'];
                        @endphp

                        <div class="relative pl-10 group">
                            <div class="absolute left-0 top-0 w-8 h-8 rounded-full bg-white border-2 border-slate-100 shadow-sm flex items-center justify-center z-10 group-hover:scale-110 group-hover:border-elevate-accent transition-transform">
                                <i class="ph-bold ph-book-bookmark text-slate-400 group-hover:text-elevate-primary transition-colors"></i>
                            </div>

                            <div class="bg-white rounded-3xl p-5 border border-slate-100 shadow-sm hover:shadow-md hover:border-elevate-primary/30 transition-all duration-300 relative overflow-hidden">
                                <div class="absolute top-0 right-0">
                                    <div class="{{ $config['bg'] }} {{ $config['text'] }} px-4 py-1.5 rounded-bl-2xl text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 border-b border-l {{ $config['border'] }}">
                                        <i class="ph-bold {{ $config['icon'] }}"></i> {{ $config['label'] }}
                                    </div>
                                </div>

                                <div class="mb-4 pr-20">
                                    <div class="flex flex-wrap items-center gap-2 mb-1.5">
                                        <span class="px-2.5 py-1 rounded-lg bg-elevate-soft text-elevate-primary text-[10px] font-bold uppercase tracking-wide border border-elevate-accent/20">
                                            {{ $journal->schedule?->subject?->name ?? 'Mata Pelajaran' }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                            <i class="ph-fill ph-clock"></i> {{ \Carbon\Carbon::parse($journal->started_at)->format('H:i') }}
                                        </span>
                                        <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1">
                                            <i class="ph-fill ph-calendar-blank"></i> {{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d M Y') }}
                                        </span>
                                    </div>
                                    <h3 class="text-base font-black text-elevate-dark leading-snug group-hover:text-elevate-primary transition-colors">
                                        {{ $journal->topic ?? 'Topik Pembelajaran' }}
                                    </h3>
                                    <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-1.5">
                                        <i class="ph-fill ph-chalkboard-teacher text-slate-300"></i> {{ $journal->schedule?->teacher?->name ?? 'Guru Pengajar' }}
                                    </p>
                                </div>

                                <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 relative mb-4">
                                    <i class="ph-fill ph-quotes text-slate-200 text-2xl absolute top-2 right-2"></i>
                                    <p class="text-xs text-slate-600 leading-relaxed relative z-10 whitespace-pre-line">
                                        {{ $journal->activities ?? 'Tidak ada catatan aktivitas.' }}
                                    </p>
                                </div>

                                @if($journal->photo_proof)
                                    <div x-data="{ open: false }">
                                        <button @click="open = true" class="flex items-center gap-2 text-xs font-bold text-slate-500 bg-white border border-slate-200 px-3 py-2 rounded-xl hover:bg-elevate-soft hover:text-elevate-primary transition-colors w-full sm:w-auto shadow-sm hover:border-elevate-accent/30">
                                            <i class="ph-bold ph-image text-elevate-primary"></i> Lihat Dokumentasi Kelas
                                        </button>
                                        <div x-show="open" x-transition.opacity class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4" style="display: none;">
                                            <div @click.away="open = false" class="relative max-w-3xl w-full">
                                                <button @click="open = false" class="absolute -top-10 right-0 text-white hover:text-elevate-peach"><i class="ph-bold ph-x text-2xl"></i></button>
                                                <img src="{{ asset('storage/' . $journal->photo_proof) }}" class="w-full h-auto rounded-xl shadow-2xl border-2 border-white/10">
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-[3rem] border-2 border-dashed border-slate-200 p-16 text-center group hover:border-elevate-accent transition-colors h-full flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform">
                    <i class="ph-duotone ph-notebook text-5xl text-elevate-primary"></i>
                </div>
                <h3 class="font-black text-elevate-dark text-xl">Belum Ada Riwayat</h3>
                <p class="text-sm text-slate-400 mt-2 max-w-xs mx-auto leading-relaxed">
                    Jurnal kegiatan belajar mengajar belum tersedia saat ini.
                </p>
            </div>
        @endif
    </div>

    {{-- MODAL RIWAYAT LENGKAP --}}
    <div x-show="showHistory" x-transition.opacity class="fixed inset-0 z-[60] flex items-center justify-center px-4" style="display: none;">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showHistory = false"></div>
        <div class="relative bg-white rounded-[2.5rem] shadow-2xl w-full max-w-4xl max-h-[85vh] flex flex-col overflow-hidden animate-in zoom-in-95 duration-300">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <h3 class="text-xl font-black text-elevate-dark flex items-center gap-2">
                    <i class="ph-duotone ph-list-checks text-elevate-primary"></i> Rekapitulasi KBM
                </h3>
                <button @click="showHistory = false" class="w-8 h-8 rounded-full bg-slate-100 hover:bg-rose-100 hover:text-rose-600 flex items-center justify-center transition-colors">
                    <i class="ph-bold ph-x"></i>
                </button>
            </div>
            <div class="overflow-y-auto p-0 custom-scrollbar">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-[10px] font-black text-slate-400 uppercase tracking-widest sticky top-0 z-10 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-5">Tanggal</th>
                            <th class="px-6 py-5">Mata Pelajaran</th>
                            <th class="px-6 py-5">Guru</th>
                            <th class="px-6 py-5 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @if(isset($teaching_journals))
                            @foreach($teaching_journals as $journal)
                                @php
                                    $attendance = $journal->attendances->first(function ($att) use ($student) { return (string)$att->student_id === (string)$student->id; });
                                    $status = $attendance ? $attendance->status : null;
                                    if ($journal->status == 'closed' && !$status) $status = 'alpha';
                                    $badge = match($status) {
                                        'present' => 'bg-elevate-soft text-elevate-primary border-elevate-accent/30',
                                        'sick' => 'bg-slate-100 text-slate-600 border-slate-200',
                                        'permission' => 'bg-elevate-peach-light/20 text-elevate-peach-dark border-elevate-peach/30',
                                        'alpha' => 'bg-rose-50 text-rose-600 border-rose-200',
                                        default => 'bg-slate-50 text-slate-400 border-slate-100'
                                    };
                                    $label = match($status) { 'present' => 'Hadir', 'sick' => 'Sakit', 'permission' => 'Izin', 'alpha' => 'Alpha', default => 'Belum Ada' };
                                @endphp
                                <tr class="hover:bg-elevate-soft/30 transition-colors">
                                    <td class="px-6 py-4 font-bold text-elevate-dark">
                                        {{ \Carbon\Carbon::parse($journal->date)->translatedFormat('d F Y') }}
                                        <span class="block text-[10px] text-slate-400 font-normal">{{ \Carbon\Carbon::parse($journal->started_at)->format('H:i') }} WIB</span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-elevate-primary">
                                        {{ $journal->schedule?->subject?->name ?? '-' }}
                                        <span class="block text-[10px] text-slate-500 font-normal truncate max-w-[200px]">Topik: {{ $journal->topic ?? '-' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-slate-500 font-medium">{{ $journal->schedule?->teacher?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border {{ $badge }} shadow-sm">
                                            {{ $label }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>