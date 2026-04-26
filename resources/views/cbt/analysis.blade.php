<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between print:hidden">
            <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">
                {{ __('Analisis Butir Soal') }}
            </h2>
            
            {{-- cetak dokumen formal --}}
            <a href="{{ route('cbt.analysis.print', $exam->id) }}" target="_blank" class="text-sm font-bold text-[#2c3f61] hover:text-[#0d52a1] flex items-center gap-2 transition bg-white px-4 py-2 rounded-xl border border-slate-200 shadow-sm active:scale-95">
                <i class="ph-bold ph-printer text-lg"></i> Cetak Laporan Formal
            </a>
        </div>
    </x-slot>

    {{-- Style Khusus Print (Sangat penting untuk mencetak grafik batang) --}}
    <style>
        @media print {
            body { background: white; }
            .print\:hidden { display: none !important; }
            .print-area { box-shadow: none !important; border: none !important; }
            table { width: 100%; font-size: 11px; color: black; border-collapse: collapse; }
            th, td { border: 1px solid #cbd5e1 !important; padding: 8px !important; }
            
            /* Memaksa browser mencetak warna background (Grafik Batang) */
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            .bg-emerald-400 { background-color: #34d399 !important; }
            .bg-blue-400 { background-color: #60a5fa !important; }
            .bg-rose-400 { background-color: #fb7185 !important; }
            .bg-amber-400 { background-color: #fbbf24 !important; }
            .bg-slate-300 { background-color: #cbd5e1 !important; }
        }
    </style>

    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]" x-data="{ search: '' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Header Info (Elevate Card Style) --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-xl shadow-[#56bbf1]/5 border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4 print-area print:mb-6 print:p-0">
                <div>
                    <div class="flex items-center gap-2 mb-1 print:hidden">
                        <a href="{{ route('cbt.recap', $exam->id) }}" class="text-xs font-bold text-slate-400 hover:text-[#0d52a1] transition flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Rekap
                        </a>
                    </div>
                    <h3 class="text-2xl font-black text-[#2c3f61]">{{ $exam->title }}</h3>
                    <p class="text-[#2c3f61]/60 text-sm font-medium">Analisis Kualitas Soal • Mapel: {{ $exam->subject_name }} • Sampel: <b>{{ $totalStudents ?? 0 }} Siswa</b></p>
                </div>
                
                {{-- Legend Tingkat Kesukaran --}}
                <div class="flex gap-3 text-[10px] uppercase font-bold text-slate-500 bg-[#e5eff5]/50 p-3 rounded-xl border border-slate-200 print:bg-transparent print:border-none print:p-0">
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-emerald-400"></span> Mudah (>75%)</div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-[#56bbf1]"></span> Sedang</div>
                    <div class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-full bg-rose-400"></span> Sukar (<30%)</div>
                </div>
            </div>

            {{-- NEW: ANALISIS TAGS / KOMPETENSI DASAR (KD) --}}
            @if(isset($tagAnalysis) && count($tagAnalysis) > 0)
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-[#56bbf1]/10 overflow-hidden print-area print:rounded-none">
                <div class="p-6 border-b border-slate-100 bg-[#e5eff5]/50 flex items-center gap-3 print:bg-white print:border-b-2 print:border-black print:px-0">
                    <div class="w-10 h-10 rounded-xl bg-[#56bbf1]/20 text-[#0d52a1] flex items-center justify-center text-xl shadow-sm print:hidden"><i class="ph-fill ph-target"></i></div>
                    <div>
                        <h4 class="font-bold text-[#2c3f61] text-lg">Analisis Penguasaan Materi (KD)</h4>
                        <p class="text-xs text-[#2c3f61]/60 font-medium">Berdasarkan tags materi yang dipasang pada soal.</p>
                    </div>
                </div>
                <div class="p-8 print:px-0">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 print:gap-4 print:grid-cols-2">
                        @foreach($tagAnalysis as $tag => $data)
                            @php
                                $percent = $data['total'] > 0 ? round(($data['correct'] / $data['total']) * 100) : 0;
                                $color = $percent >= 75 ? 'bg-emerald-400' : ($percent >= 40 ? 'bg-[#56bbf1]' : 'bg-rose-400');
                                $textColor = $percent >= 75 ? 'text-emerald-600' : ($percent >= 40 ? 'text-[#0d52a1]' : 'text-rose-600');
                                $message = $percent >= 75 ? 'Sangat Dikuasai' : ($percent >= 40 ? 'Cukup Dikuasai' : 'Perlu Evaluasi (Lemah)');
                            @endphp
                            <div class="bg-slate-50 p-4 rounded-2xl border border-slate-100 print:border print:border-slate-300 print:bg-transparent">
                                <div class="flex justify-between items-end mb-2">
                                    <h5 class="font-bold text-[#2c3f61] text-sm flex items-center gap-2"><i class="ph-fill ph-tag text-slate-400 print:hidden"></i> {{ $tag }}</h5>
                                    <span class="font-black text-xl {{ $textColor }} print:text-black">{{ $percent }}%</span>
                                </div>
                                <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden mb-2 print:border print:border-slate-300">
                                    <div class="h-full {{ $color }} rounded-full" style="width: {{ $percent }}%"></div>
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider print:text-black">{{ $message }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            {{-- Tabel Analisis --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-[#56bbf1]/10 overflow-hidden print-area print:rounded-none">
                
                {{-- Toolbar Pencarian --}}
                <div class="p-6 border-b border-slate-100 bg-[#e5eff5]/30 flex flex-col md:flex-row justify-between items-center gap-4 print:hidden">
                    <h4 class="font-bold text-[#2c3f61] flex items-center gap-2 text-lg">
                        <i class="ph-fill ph-chart-pie-slice text-[#56bbf1]"></i> Detail Analisis per Butir
                    </h4>
                    <div class="relative w-full md:w-72">
                        <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-[#2c3f61]/40"></i>
                        <input type="text" x-model="search" placeholder="Cari potongan soal atau tag..." class="w-full pl-10 pr-4 py-2.5 text-sm font-bold border-slate-200 rounded-xl focus:ring-[#56bbf1] focus:border-[#56bbf1] bg-white shadow-sm transition-shadow text-[#2c3f61]">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-[#2c3f61]/80">
                        <thead class="bg-[#e5eff5]/50 text-[#2c3f61]/60 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100 print:bg-white print:text-black">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4 w-1/3">Cuplikan Soal & Materi</th>
                                <th class="px-6 py-4 text-center">Tipe & Kunci</th>
                                <th class="px-6 py-4 text-center">Tingkat Kesukaran</th>
                                <th class="px-6 py-4 w-1/3">Distribusi Jawaban Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 print:divide-gray-300">
                            @forelse($analysis as $index => $item)
                                <tr x-show="search === '' || '{{ strtolower(addslashes(strip_tags($item->text . ' ' . ($item->tags ?? '')))) }}'.includes(search.toLowerCase())" 
                                    class="hover:bg-[#56bbf1]/5 transition group print:hover:bg-transparent">
                                    
                                    <td class="px-6 py-4 text-center font-black text-slate-400 print:text-black">{{ $index + 1 }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-[#2c3f61] line-clamp-3 print:line-clamp-none mb-2" title="{{ $item->text }}">{{ $item->text }}</p>
                                        {{-- Menampilkan Tag di Tabel Analisis --}}
                                        @if(!empty($item->tags))
                                            <div class="flex flex-wrap gap-1 mt-1 print:mt-0">
                                                @foreach(explode(',', $item->tags) as $tag)
                                                    <span class="text-[9px] font-bold bg-[#56bbf1]/10 text-[#0d52a1] px-2 py-0.5 rounded border border-[#56bbf1]/20 print:border-slate-300 print:text-[#2c3f61] print:bg-transparent">{{ trim($tag) }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </td>
                                    
                                    {{-- Kolom Tipe & Kunci --}}
                                    <td class="px-6 py-4 text-center">
                                        @if(in_array($item->type, ['choice', 'true_false']))
                                            <span class="inline-block mb-1 text-[9px] font-bold text-slate-400 uppercase">PG / B-S</span><br>
                                            <span class="w-8 h-8 rounded-lg bg-slate-50 text-[#2c3f61] font-black flex items-center justify-center mx-auto border border-slate-200 print:bg-transparent print:border-black">
                                                {{ $item->correct_key }}
                                            </span>
                                        @elseif($item->type == 'essay')
                                            <span class="inline-block mb-1 text-[9px] font-bold text-[#56bbf1] uppercase">ESSAI</span><br>
                                            <button onclick="Swal.fire({title: 'Kunci Jawaban', text: '{{ addslashes($item->correct_key) }}', confirmButtonColor: '#0d52a1', customClass: { popup: 'rounded-[2rem]' }})" 
                                                    class="text-xs font-bold text-[#0d52a1] hover:underline cursor-pointer print:hidden">
                                                Lihat Kunci
                                            </button>
                                            <span class="hidden print:block text-xs text-[#2c3f61]">{{ $item->correct_key ?: '-' }}</span>
                                        @elseif($item->type == 'matching')
                                            <span class="inline-block mb-1 text-[9px] font-bold text-[#c86845] uppercase">MATCHING</span><br>
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>

                                    {{-- Tingkat Kesukaran --}}
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider mb-2 {{ $item->difficulty_badge }} print:border print:border-black print:text-black print:bg-transparent">
                                            {{ $item->difficulty_label }}
                                        </span>
                                        
                                        {{-- Progress Bar Kesukaran --}}
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden flex print:h-2 print:border print:border-black">
                                            <div class="h-full {{ str_contains($item->difficulty_badge, 'emerald') ? 'bg-emerald-400' : (str_contains($item->difficulty_badge, 'rose') ? 'bg-rose-400' : 'bg-[#56bbf1]') }}" 
                                                 style="width: {{ $item->difficulty_index }}%"></div>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1 print:text-black">{{ $item->difficulty_index }}% Siswa Benar</p>
                                    </td>

                                    {{-- Distribusi Jawaban --}}
                                    <td class="px-6 py-4">
                                        {{-- Jika PG / B-S Tampilkan Grafik Batang --}}
                                        @if(in_array($item->type, ['choice', 'true_false']))
                                            <div class="flex items-end gap-2 h-16 w-full pb-1 border-b border-slate-200">
                                                @foreach(['A','B','C','D', 'E'] as $opt)
                                                    @if(isset($item->options[$opt]) || $opt != 'E') {{-- Tampilkan E hanya jika ada datanya --}}
                                                        @php 
                                                            $count = $item->options[$opt] ?? 0;
                                                            $percent = $totalStudents > 0 ? ($count / $totalStudents) * 100 : 0;
                                                            $isKey = $opt == $item->correct_key;
                                                            $color = $isKey ? 'bg-emerald-400' : 'bg-slate-300';
                                                            if(!$isKey && $percent > 20) $color = 'bg-amber-400'; // Distractor yang kuat (Kuning)
                                                        @endphp
                                                        <div class="flex-1 flex flex-col justify-end items-center group/bar relative">
                                                            {{-- Tooltip (Hover) --}}
                                                            <div class="absolute bottom-full mb-1 opacity-0 group-hover/bar:opacity-100 transition text-[10px] font-bold bg-[#2c3f61] text-white px-2 py-1 rounded whitespace-nowrap z-10 print:hidden pointer-events-none">
                                                                {{ $count }} Siswa ({{ round($percent) }}%)
                                                            </div>
                                                            
                                                            {{-- Bar --}}
                                                            <div class="w-full rounded-t-sm transition-all duration-500 {{ $color }}" 
                                                                 style="height: {{ $percent > 0 ? $percent : 2 }}%"></div>
                                                            
                                                            {{-- Label Bawah --}}
                                                            <span class="text-[10px] font-bold {{ $isKey ? 'text-emerald-600' : 'text-slate-400' }} mt-1 print:text-black">{{ $opt }}</span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        
                                        {{-- Jika Essai --}}
                                        @elseif($item->type == 'essay')
                                            <div class="h-16 w-full flex items-center justify-center bg-slate-50 rounded-lg border border-dashed border-slate-200 text-center px-4 print:bg-transparent print:border-solid">
                                                <p class="text-[10px] font-bold text-slate-400 uppercase">
                                                    Dikoreksi Manual
                                                </p>
                                            </div>

                                        {{-- Jika Matching --}}
                                        @else
                                            <div class="h-16 w-full flex items-center justify-center">
                                                <p class="text-xs text-slate-400">-</p>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="w-16 h-16 bg-[#e5eff5] rounded-full flex items-center justify-center mx-auto mb-3 text-[#0d52a1]/50">
                                            <i class="ph-duotone ph-chart-bar text-3xl"></i>
                                        </div>
                                        Belum ada data analisis. Pastikan ujian sudah dikerjakan siswa.
                                    </td>
                                </tr>
                            @endforelse

                            {{-- State Empty Search --}}
                            <tr x-show="search !== '' && document.querySelectorAll('tbody tr[x-show]:not([style*=\'display: none\'])').length === 0" style="display: none;">
                                <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                                    <p class="font-medium">Tidak ada data yang cocok dengan pencarian "<span x-text="search" class="font-bold text-[#2c3f61]"></span>"</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-app-layout>