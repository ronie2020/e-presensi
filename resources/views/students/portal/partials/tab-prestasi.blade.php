<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- KOLOM KIRI: Statistik Ringkas (Sticky) --}}
    <div class="lg:col-span-1">
        <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-emerald-100 sticky top-24">
            <h3 class="text-lg font-bold text-slate-800 mb-1 flex items-center gap-2">
                <i class="ph-fill ph-medal text-emerald-500"></i> Catatan Prestasi
            </h3>
            
            <div class="bg-emerald-50 rounded-[1.5rem] p-6 border border-emerald-100 text-center mt-6 relative overflow-hidden group">
                <div class="absolute top-0 right-0 w-24 h-24 bg-emerald-200/50 rounded-full blur-2xl -mr-10 -mt-10 group-hover:bg-emerald-300/50 transition-all"></div>
                
                {{-- Total Poin Kebaikan --}}
                <p class="text-6xl font-black text-emerald-600 relative z-10 tracking-tight">+{{ $total_merit_points ?? 0 }}</p>
                <p class="text-xs text-emerald-600 mt-2 font-bold uppercase tracking-widest relative z-10 opacity-80">Total Poin Kebaikan</p>
            </div>
            
            <div class="mt-6 bg-slate-50 p-4 rounded-2xl border border-slate-100">
                <p class="text-xs text-slate-500 italic text-center leading-relaxed">
                    "Terus tingkatkan kebaikanmu untuk menjadi inspirasi bagi teman-teman!"
                </p>
            </div>
        </div>
    </div>

    {{-- KOLOM KANAN: Timeline Prestasi --}}
    <div class="lg:col-span-2">
        <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 md:p-8">
            <h4 class="font-black text-emerald-800 mb-6 flex items-center gap-2 text-lg pb-4 border-b border-emerald-50">
                <i class="ph-duotone ph-star text-2xl"></i> Riwayat Pencapaian
            </h4>

            @if(isset($achievements) && count($achievements) > 0)
                <div class="relative border-l-2 border-slate-100 ml-3 space-y-8">
                    @foreach($achievements as $record)
                        <div class="relative pl-8 group">
                            <!-- Dot Timeline (Green) -->
                            <div class="absolute -left-[9px] top-0 w-5 h-5 bg-emerald-100 border-2 border-emerald-500 rounded-full group-hover:scale-125 transition-transform duration-300 shadow-sm"></div>
                            
                            <!-- Content Header: Nama Kebaikan & Poin -->
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-2 mb-1">
                                <h4 class="font-bold text-slate-800 text-lg group-hover:text-emerald-600 transition-colors">
                                    {{ $record->disciplineType->name ?? 'Jenis Prestasi Dihapus' }}
                                </h4>
                                
                                {{-- Badge Poin --}}
                                <span class="text-xs font-bold px-3 py-1 bg-emerald-50 text-emerald-700 rounded-lg border border-emerald-100 whitespace-nowrap shadow-sm">
                                    +{{ $record->disciplineType->point_value ?? 0 }} Poin
                                </span>
                            </div>
                            
                            <!-- Tanggal Kejadian -->
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mb-3 flex items-center gap-2">
                                <i class="ph-fill ph-calendar-blank"></i>
                                {{ \Carbon\Carbon::parse($record->date)->translatedFormat('l, d F Y') }}
                            </p>
                            
                            <!-- Catatan Detail -->
                            @if($record->notes)
                                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 text-sm text-slate-600 italic relative hover:bg-emerald-50/30 transition-colors">
                                    <i class="ph-fill ph-quotes text-emerald-200 text-2xl absolute top-2 right-2"></i>
                                    "{{ $record->notes }}"
                                </div>
                            @else
                                <div class="text-xs text-slate-300 italic pl-1">Tidak ada catatan tambahan.</div>
                            @endif

                             <!-- Guru Pencatat -->
                             @if($record->recorder)
                                <div class="mt-3 flex items-center gap-1.5 text-[10px] text-slate-400 font-bold bg-slate-50 inline-block px-2 py-1 rounded-md">
                                    <i class="ph-fill ph-user-circle text-slate-300"></i> Dicatat oleh: {{ $record->recorder->name ?? 'Guru' }}
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-16 text-center bg-emerald-50/20 rounded-[2rem] border border-dashed border-emerald-100">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mb-4 animate-bounce">
                        <i class="ph-duotone ph-trophy text-4xl text-emerald-300"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Belum Ada Prestasi</h3>
                    <p class="text-slate-500 text-sm mt-2 max-w-xs">Ayo tunjukkan bakatmu dan kumpulkan poin kebaikan!</p>
                </div>
            @endif
        </div>
    </div>
</div>