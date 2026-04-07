<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pratinjau - {{ $exam->title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    
    {{-- MathJax Config --}}
    <script>
        window.MathJax = {
            tex: { inlineMath: [['$', '$'], ['\\(', '\\)']] },
            svg: { fontCache: 'global' }
        };
    </script>
    <script id="MathJax-script" async src="https://cdn.jsdelivr.net/npm/mathjax@3/es5/tex-mml-chtml.js"></script>

    <style>
        .trix-content ul { list-style-type: disc; padding-left: 1.5rem; }
        .trix-content ol { list-style-type: decimal; padding-left: 1.5rem; }
    </style>
</head>
<body class="bg-slate-100 font-sans text-slate-800 selection:bg-indigo-100 selection:text-indigo-900">

    {{-- HEADER --}}
    <div class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-4xl mx-auto px-4 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-black">
                    <i class="ph-bold ph-desktop text-xl"></i>
                </div>
                <div>
                    <h1 class="font-black text-lg leading-tight">{{ $exam->title }}</h1>
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Mode Pratinjau Siswa • {{ $exam->subject_name }}</p>
                </div>
            </div>
            <button onclick="window.close()" class="px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-bold text-sm hover:bg-slate-200 transition">
                Tutup Pratinjau
            </button>
        </div>
    </div>

    {{-- CONTENT --}}
    <div class="max-w-4xl mx-auto px-4 py-8 space-y-6">
        @foreach($exam->questions as $index => $q)
            @php $qType = $q->question_type ?? 'choice'; @endphp
            
            <div class="bg-white rounded-3xl p-6 md:p-8 shadow-sm border border-slate-200 flex gap-4 md:gap-6">
                
                {{-- Nomer Soal --}}
                <div class="w-10 h-10 shrink-0 bg-slate-100 rounded-xl flex items-center justify-center font-black text-slate-600 border border-slate-200">
                    {{ $index + 1 }}
                </div>

                {{-- Isi Soal --}}
                <div class="flex-1 overflow-hidden">
                    
                    {{-- Gambar Soal Jika Ada --}}
                    @if($q->question_image)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $q->question_image) }}" class="max-h-64 rounded-xl border border-slate-200 shadow-sm object-contain bg-slate-50">
                        </div>
                    @endif

                    {{-- Teks Soal --}}
                    <div class="prose prose-slate max-w-none mb-6 text-base leading-relaxed trix-content">
                        {!! $q->question_text !!}
                    </div>

                    {{-- Opsi Jawaban Berdasarkan Tipe --}}
                    @if($qType == 'choice')
                        <div class="space-y-3">
                            @foreach(['A','B','C','D'] as $opt)
                                @php 
                                    $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? ''); 
                                    $imgVal = isset($q->{'image_'.$opt}) ? $q->{'image_'.$opt} : ($q->options['image_'.$opt] ?? null);
                                @endphp
                                @if($val !== '' || $imgVal)
                                    <label class="flex items-start gap-4 p-4 rounded-2xl border-2 border-slate-100 hover:border-indigo-200 hover:bg-indigo-50/30 cursor-pointer transition">
                                        <input type="radio" name="preview_ans_{{ $q->id }}" class="mt-1 w-5 h-5 border-slate-300 text-indigo-600 focus:ring-indigo-600">
                                        <div class="flex-1">
                                            <span class="font-black text-slate-400 mr-2">{{ $opt }}.</span>
                                            <span class="font-medium text-slate-700">{!! $val !!}</span>
                                            @if($imgVal)
                                                <img src="{{ asset('storage/' . $imgVal) }}" class="mt-3 max-h-32 rounded-xl border border-slate-200 object-contain bg-white">
                                            @endif
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        </div>

                    @elseif($qType == 'true_false')
                        <div class="flex flex-col sm:flex-row gap-4">
                            <label class="flex-1 flex items-center justify-center p-4 rounded-2xl border-2 border-slate-100 hover:border-emerald-200 hover:bg-emerald-50 cursor-pointer transition text-emerald-700 font-bold">
                                <input type="radio" name="preview_ans_{{ $q->id }}" class="mr-3 w-5 h-5 text-emerald-600 focus:ring-emerald-600"> BENAR
                            </label>
                            <label class="flex-1 flex items-center justify-center p-4 rounded-2xl border-2 border-slate-100 hover:border-rose-200 hover:bg-rose-50 cursor-pointer transition text-rose-700 font-bold">
                                <input type="radio" name="preview_ans_{{ $q->id }}" class="mr-3 w-5 h-5 text-rose-600 focus:ring-rose-600"> SALAH
                            </label>
                        </div>

                    @elseif($qType == 'matching')
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200">
                            <p class="text-sm font-bold text-slate-500 mb-4 uppercase tracking-wide"><i class="ph-bold ph-arrows-left-right"></i> Pasangkan Jawaban Berikut:</p>
                            @php 
                                $pairs = is_string($q->options) ? json_decode($q->options, true)['pairs'] ?? [] : $q->options['pairs'] ?? [];
                            @endphp
                            <div class="space-y-4">
                                @foreach($pairs as $p)
                                    <div class="flex flex-col sm:flex-row items-center gap-4">
                                        {{-- Kiri --}}
                                        <div class="flex-1 w-full bg-white p-4 rounded-xl border border-slate-200 text-center font-medium shadow-sm">
                                            @if(isset($p['left_image']) && $p['left_image'])
                                                <img src="{{ asset('storage/' . $p['left_image']) }}" class="h-16 mx-auto mb-2 rounded object-contain">
                                            @endif
                                            {{ $p['left'] }}
                                        </div>
                                        <div class="shrink-0 text-slate-300 rotate-90 sm:rotate-0"><i class="ph-bold ph-arrow-right text-xl"></i></div>
                                        {{-- Kanan (Dropdown Mockup) --}}
                                        <div class="flex-1 w-full relative">
                                            <select class="w-full bg-white p-4 rounded-xl border-2 border-dashed border-slate-300 text-center font-medium text-slate-500 appearance-none cursor-pointer hover:border-indigo-400 hover:text-indigo-600 transition">
                                                <option>-- Pilih Pasangan --</option>
                                                <option>{{ $p['right'] }}</option>
                                            </select>
                                            <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                    @elseif($qType == 'essay')
                        <textarea rows="4" class="w-full p-4 rounded-2xl border-2 border-slate-200 focus:border-indigo-500 focus:ring-0 text-slate-700 transition" placeholder="Siswa akan mengetik jawabannya di sini..."></textarea>
                    @endif

                </div>
            </div>
        @endforeach

        <div class="text-center py-8">
            <p class="text-slate-400 font-bold text-sm">Akhir dari Pratinjau Soal</p>
        </div>
    </div>

</body>
</html>