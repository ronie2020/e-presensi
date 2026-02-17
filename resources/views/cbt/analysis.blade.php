<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Analisis Butir Soal') }}
        </h2>
    </x-slot>

    <div class="py-8 sm:py-10 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Header Info --}}
            <div class="bg-white p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <a href="{{ route('cbt.recap', $exam->id) }}" class="text-xs font-bold text-slate-400 hover:text-blue-600 transition flex items-center gap-1">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Rekap
                        </a>
                    </div>
                    <h3 class="text-2xl font-black text-slate-800">{{ $exam->title }}</h3>
                    <p class="text-slate-500 text-sm font-medium">Analisis Kualitas Soal • Sampel: {{ $totalStudents }} Siswa</p>
                </div>
                
                {{-- Legend Tingkat Kesukaran (Hanya relevan untuk PG, tapi disimpan sebagai referensi) --}}
                <div class="flex gap-3 text-[10px] uppercase font-bold text-slate-500 bg-slate-50 p-3 rounded-xl border border-slate-200">
                    <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Mudah (>75%)</div>
                    <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-blue-500"></span> Sedang</div>
                    <div class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> Sukar (<30%)</div>
                </div>
            </div>

            {{-- Tabel Analisis --}}
            <div class="bg-white rounded-[2rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-slate-600">
                        <thead class="bg-slate-50 text-slate-400 font-bold uppercase text-[10px] tracking-wider border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 w-12 text-center">No</th>
                                <th class="px-6 py-4 w-1/3">Cuplikan Soal</th>
                                <th class="px-6 py-4 text-center">Tipe & Kunci</th>
                                <th class="px-6 py-4 text-center">Tingkat Kesukaran</th>
                                <th class="px-6 py-4 w-1/3">Distribusi Jawaban Siswa</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($analysis as $index => $item)
                                <tr class="hover:bg-slate-50/50 transition">
                                    <td class="px-6 py-4 text-center font-black text-slate-400">{{ $index + 1 }}</td>
                                    
                                    <td class="px-6 py-4">
                                        <p class="font-medium text-slate-700 line-clamp-2" title="{{ $item->text }}">{{ $item->text }}</p>
                                    </td>
                                    
                                    {{-- Kolom Tipe & Kunci (Handling Essai Panjang) --}}
                                    <td class="px-6 py-4 text-center">
                                        @if(in_array($item->type, ['choice', 'true_false']))
                                            <span class="inline-block mb-1 text-[9px] font-bold text-slate-400 uppercase">PG / B-S</span><br>
                                            <span class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 font-black flex items-center justify-center mx-auto border border-slate-200">
                                                {{ $item->correct_key }}
                                            </span>
                                        @elseif($item->type == 'essay')
                                            <span class="inline-block mb-1 text-[9px] font-bold text-indigo-400 uppercase">ESSAI</span><br>
                                            <button onclick="Swal.fire({title: 'Kunci Jawaban', text: '{{ addslashes($item->correct_key) }}', confirmButtonColor: '#4f46e5'})" 
                                                    class="text-xs font-bold text-indigo-600 hover:underline cursor-pointer">
                                                Lihat Kunci
                                            </button>
                                        @elseif($item->type == 'matching')
                                            <span class="inline-block mb-1 text-[9px] font-bold text-orange-400 uppercase">MATCHING</span><br>
                                            <span class="text-xs text-slate-400">-</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider mb-2 {{ $item->difficulty_badge }}">
                                            {{ $item->difficulty_label }}
                                        </span>
                                        
                                        {{-- Progress Bar Kesukaran --}}
                                        <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden flex">
                                            <div class="h-full {{ str_contains($item->difficulty_badge, 'emerald') ? 'bg-emerald-400' : (str_contains($item->difficulty_badge, 'rose') ? 'bg-rose-400' : 'bg-blue-400') }}" 
                                                 style="width: {{ $item->difficulty_index }}%"></div>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-400 mt-1">{{ $item->difficulty_index }}% Siswa Benar</p>
                                    </td>

                                    <td class="px-6 py-4">
                                        {{-- Jika PG, Tampilkan Grafik Batang --}}
                                        @if(in_array($item->type, ['choice', 'true_false']))
                                            <div class="flex items-end gap-2 h-16 w-full pb-1 border-b border-slate-200">
                                                @foreach(['A','B','C','D'] as $opt)
                                                    @php 
                                                        $count = $item->options[$opt] ?? 0;
                                                        $percent = $totalStudents > 0 ? ($count / $totalStudents) * 100 : 0;
                                                        $isKey = $opt == $item->correct_key;
                                                        $color = $isKey ? 'bg-emerald-400' : 'bg-slate-300';
                                                        if(!$isKey && $percent > 20) $color = 'bg-amber-400'; // Distractor kuat
                                                    @endphp
                                                    <div class="flex-1 flex flex-col justify-end items-center group relative">
                                                        {{-- Tooltip --}}
                                                        <div class="absolute bottom-full mb-1 opacity-0 group-hover:opacity-100 transition text-[10px] font-bold bg-slate-800 text-white px-2 py-1 rounded whitespace-nowrap z-10">
                                                            {{ $count }} Siswa ({{ round($percent) }}%)
                                                        </div>
                                                        
                                                        <div class="w-full rounded-t-sm transition-all duration-500 {{ $color }}" 
                                                             style="height: {{ $percent > 0 ? $percent : 2 }}%"></div>
                                                        <span class="text-[10px] font-bold {{ $isKey ? 'text-emerald-600' : 'text-slate-400' }} mt-1">{{ $opt }}</span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        
                                        {{-- Jika Essai, Tampilkan Pesan --}}
                                        @elseif($item->type == 'essay')
                                            <div class="h-16 w-full flex items-center justify-center bg-slate-50 rounded-lg border border-dashed border-slate-200 text-center px-4">
                                                <p class="text-xs text-slate-400 italic">
                                                    Analisis distribusi jawaban tidak tersedia untuk soal Essai.
                                                    <br><span class="font-bold text-indigo-500">Cek menu koreksi manual.</span>
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
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">Belum ada data analisis.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</x-app-layout>