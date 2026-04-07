<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ujian CBT - {{ $exam->title }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        h1, h2, h3 { text-align: center; margin: 5px 0; }
        .header-box { border-bottom: 2px solid #000; padding-bottom: 15px; margin-bottom: 20px; }
        table.meta { width: 100%; margin-bottom: 20px; font-weight: bold; }
        .question-container { margin-bottom: 20px; page-break-inside: avoid; }
        table.options { width: 100%; border-collapse: collapse; margin-top: 5px; }
        table.options td { padding: 4px 0; vertical-align: top; }
        .opt-label { width: 25px; font-weight: bold; }
        .img-box { max-width: 300px; max-height: 300px; margin: 10px 0; display: block; }
        .answer-key { margin-top: 50px; border-top: 1px dashed #000; padding-top: 20px; }
        table.key-table { width: 100%; border-collapse: collapse; }
        table.key-table th, table.key-table td { border: 1px solid #000; padding: 5px; text-align: center; }
    </style>
</head>
<body>

    <div class="header-box">
        <h2>UJIAN SEKOLAH (CBT)</h2>
        <h3>{{ strtoupper($exam->title) }}</h3>
        <table class="meta">
            <tr>
                <td width="15%">Mata Pelajaran</td><td width="2%">:</td><td>{{ $exam->subject_name }}</td>
                <td width="15%">Kelas</td><td width="2%">:</td><td>{{ $exam->class_level }}</td>
            </tr>
            <tr>
                <td>Waktu / Durasi</td><td>:</td><td>{{ \Carbon\Carbon::parse($exam->start_time)->format('d M Y') }} ({{ $exam->duration_minutes }} Menit)</td>
                <td>Total Soal</td><td>:</td><td>{{ $exam->questions->count() }}</td>
            </tr>
        </table>
    </div>

    @foreach($exam->questions as $index => $q)
        <div class="question-container">
            <table width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td width="25" valign="top"><b>{{ $index + 1 }}.</b></td>
                    <td valign="top">
                        @if($q->question_image)
                            <img src="{{ url('storage/' . $q->question_image) }}" class="img-box"><br>
                        @endif
                        
                        {!! $q->question_text !!}

                        @if($q->question_type == 'choice')
                            <table class="options">
                                @foreach(['A','B','C','D'] as $opt)
                                    @php 
                                        $val = isset($q->{'option_'.$opt}) ? $q->{'option_'.$opt} : ($q->options[$opt] ?? ''); 
                                        $imgVal = isset($q->{'image_'.$opt}) ? $q->{'image_'.$opt} : ($q->options['image_'.$opt] ?? null);
                                    @endphp
                                    @if($val !== '' || $imgVal)
                                        <tr>
                                            <td class="opt-label">{{ $opt }}.</td>
                                            <td>
                                                {!! $val !!}
                                                @if($imgVal)
                                                    <br><img src="{{ url('storage/' . $imgVal) }}" class="img-box">
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </table>
                        @elseif($q->question_type == 'true_false')
                            <p><i>Pilihan: (Benar / Salah)</i></p>
                        @elseif($q->question_type == 'essay')
                            <br><br><br>
                        @elseif($q->question_type == 'matching')
                            <p><b>Pasangkan:</b></p>
                            @php 
                                $pairs = is_string($q->options) ? json_decode($q->options, true)['pairs'] ?? [] : $q->options['pairs'] ?? [];
                            @endphp
                            <table border="1" cellpadding="5" cellspacing="0" width="80%">
                                @foreach($pairs as $p)
                                    <tr>
                                        <td width="45%">
                                            @if(isset($p['left_image']) && $p['left_image'])
                                                <img src="{{ url('storage/' . $p['left_image']) }}" height="50"><br>
                                            @endif
                                            {{ $p['left'] }}
                                        </td>
                                        <td width="10%" align="center"> ---> </td>
                                        <td width="45%">
                                            @if(isset($p['right_image']) && $p['right_image'])
                                                <img src="{{ url('storage/' . $p['right_image']) }}" height="50"><br>
                                            @endif
                                            {{ $p['right'] }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    @endforeach

    {{-- KUNCI JAWABAN DI HALAMAN TERAKHIR --}}
    <br clear="all" style="page-break-before:always" />
    <div class="answer-key">
        <h3>KUNCI JAWABAN & PEMBOBOTAN</h3>
        <table class="key-table">
            <tr>
                <th width="10%">No</th>
                <th width="20%">Tipe Soal</th>
                <th width="50%">Kunci / Jawaban Benar</th>
                <th width="20%">Bobot Poin</th>
            </tr>
            @foreach($exam->questions as $index => $q)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($q->question_type == 'choice') PG 
                        @elseif($q->question_type == 'true_false') B/S 
                        @elseif($q->question_type == 'essay') Essai 
                        @else Menjodohkan @endif
                    </td>
                    <td>
                        @if($q->question_type == 'matching')
                            <i>(Terlampir pada tabel soal)</i>
                        @else
                            <b>{{ $q->correct_answer ?: '-' }}</b>
                        @endif
                    </td>
                    <td>{{ $q->score_weight }}</td>
                </tr>
            @endforeach
        </table>
    </div>

</body>
</html>