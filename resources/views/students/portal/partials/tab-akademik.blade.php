{{-- BAGIAN 1: NILAI SEMESTER BERJALAN (Bawaan Sistem) --}}
<div class="mb-8">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
        <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
            <i class="ph-fill ph-book-open text-elevate-primary"></i> Laporan Nilai Siswa
        </h3>

        {{-- TAMBAHAN: FORM FILTER TAHUN & SEMESTER --}}
        <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap items-center gap-2 bg-white p-1.5 rounded-xl border border-slate-200 shadow-sm">
            
            {{-- FIX: Pertahankan parameter query (seperti active tab) agar tidak ter-reset saat reload --}}
            @foreach(request()->except(['academic_year', 'semester']) as $key => $value)
                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            
            {{-- FIX: Paksa kembali ke tab akademik setelah data difilter --}}
            @if(!request()->has('tab'))
                <input type="hidden" name="tab" value="akademik">
            @endif

            <div class="flex items-center pl-2">
                <i class="ph-bold ph-calendar text-slate-400"></i>
            </div>
            <select name="academic_year" onchange="this.form.submit()" class="border-transparent bg-transparent text-sm font-bold text-elevate-dark focus:ring-0 py-1.5 cursor-pointer hover:text-elevate-primary transition-colors">
                @if(isset($years))
                    @foreach($years as $year)
                        <option value="{{ $year->name }}" {{ (isset($selectedYear) && $selectedYear == $year->name) ? 'selected' : '' }}>
                            TA. {{ $year->name }}
                        </option>
                    @endforeach
                @else
                    <!-- Fallback jika variabel $years tidak dikirim -->
                    <option value="2023/2024">TA. 2023/2024</option>
                    <option value="2024/2025" selected>TA. 2024/2025</option>
                @endif
            </select>
            
            <span class="text-slate-300">|</span>
            
            <select name="semester" onchange="this.form.submit()" class="border-transparent bg-transparent text-sm font-bold text-elevate-dark focus:ring-0 py-1.5 cursor-pointer hover:text-elevate-primary transition-colors pr-8">
                <option value="1" {{ (isset($selectedSemester) && $selectedSemester == '1') ? 'selected' : '' }}>Smt Ganjil</option>
                <option value="2" {{ (isset($selectedSemester) && $selectedSemester == '2') ? 'selected' : '' }}>Smt Genap</option>
            </select>
        </form>
    </div>

    @if($academic_record)
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-6 mb-6 relative overflow-hidden group hover:border-elevate-accent/30 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity pointer-events-none">
                <i class="ph-fill ph-chart-line-up text-9xl text-elevate-primary"></i>
            </div>
            <div class="h-72 w-full relative z-10">
                <canvas id="academicChart"></canvas>
            </div>
        </div>
        
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-4 md:p-6">
            <!-- Tambahan max-h dan overflow-auto untuk scroll -->
            <div class="overflow-auto max-h-[500px] w-full custom-scrollbar rounded-xl border border-slate-100">
                <table class="w-full text-left border-collapse min-w-[600px] relative">
                    <!-- Tambahan sticky top-0 agar header tetap terlihat saat scroll -->
                    <thead class="sticky top-0 z-10 bg-slate-50 text-[10px] font-black text-slate-500 uppercase tracking-widest shadow-sm">
                        <tr>
                            <th class="px-6 py-5 border-b border-slate-200">Mata Pelajaran</th>
                            <th class="px-6 py-5 text-center border-b border-slate-200">Nilai</th>
                            <th class="px-6 py-5 text-center border-b border-slate-200">Predikat</th>
                            <th class="px-6 py-5 hidden md:table-cell border-b border-slate-200">Deskripsi Capaian</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm bg-white">
                        @php $totalScore = 0; $countScore = 0; @endphp
                        @foreach($academic_record->items as $item)
                            @php 
                                if(is_numeric($item->score)) {
                                    $totalScore += $item->score;
                                    $countScore++;
                                }
                            @endphp
                            <tr class="hover:bg-elevate-soft/30 transition-colors group">
                                <td class="px-6 py-5 font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors">
                                    {{ $item->subject->name ?? 'Mapel Dihapus' }}
                                </td>
                                <td class="px-6 py-5 text-center">
                                    @php 
                                        $gradeColor = match($item->predicate) { 
                                            'A' => 'bg-emerald-50 text-emerald-600 border-emerald-200', 
                                            'B' => 'bg-elevate-soft text-elevate-primary border-elevate-accent/30', 
                                            'C' => 'bg-elevate-peach-light/20 text-elevate-peach-dark border-elevate-peach/30', 
                                            default => 'bg-rose-50 text-rose-600 border-rose-200' 
                                        }; 
                                    @endphp
                                    <span class="px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-wider border {{ $gradeColor }} shadow-sm">
                                        {{ $item->predicate }}
                                    </span>
                                </td>
                                <td class="px-6 py-5 text-slate-500 hidden md:table-cell max-w-sm leading-relaxed text-xs font-medium">
                                    {{Str::limit($item->description, 100) ?? '-' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    
                    {{-- TAMBAHAN: BARIS RATA-RATA NILAI SEMESTER BERJALAN --}}
                    <tfoot class="bg-slate-50 border-t-2 border-slate-200 sticky bottom-0 z-10">
                        <tr>
                            <td class="px-6 py-4 font-black text-right text-elevate-dark uppercase tracking-wider text-xs">Rata-rata Nilai:</td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-block font-black text-elevate-primary text-xl">
                                    {{ $countScore > 0 ? round($totalScore / $countScore, 1) : '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center"></td>
                            <td class="hidden md:table-cell px-6 py-4"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @else
        <div class="bg-white rounded-[3rem] border-2 border-dashed border-slate-200 p-16 text-center group hover:border-elevate-accent transition-colors flex flex-col items-center">
            <div class="w-20 h-20 bg-elevate-soft rounded-full flex items-center justify-center mb-4 text-elevate-primary group-hover:scale-110 transition-transform">
                <i class="ph-duotone ph-exam text-4xl"></i>
            </div>
            <h3 class="font-black text-elevate-dark text-lg">Belum Ada Data Nilai</h3>
            <p class="text-sm text-slate-400 mt-1 max-w-xs mx-auto">Data akademik semester ini akan muncul setelah guru mempublikasikan nilai.</p>
        </div>
    @endif
</div>

{{-- BAGIAN 2: RIWAYAT BUKU INDUK (Data 6 Semester) --}}
<div class="mt-12">
    <h3 class="text-lg font-black text-elevate-dark mb-4 flex items-center gap-2">
        <i class="ph-fill ph-clock-counter-clockwise text-elevate-primary"></i> Riwayat Nilai Raport (Buku Induk)
    </h3>
    
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-4 md:p-6">
        <!-- Tambahan max-h-[600px] dan overflow-auto -->
        <div class="overflow-auto max-h-[600px] w-full custom-scrollbar rounded-xl border border-slate-200">
            <table class="w-full text-center text-xs md:text-sm border-collapse min-w-[800px] relative">
                <!-- Tambahan sticky top-0 dan z-10 -->
                <thead class="sticky top-0 z-10 font-bold text-elevate-dark bg-white shadow-sm">
                    <tr>
                        <th rowspan="2" class="border border-slate-200 p-3 w-12 bg-slate-50">No</th>
                        <th rowspan="2" class="border border-slate-200 p-3 text-left bg-slate-50">Mata Pelajaran</th>
                        <!-- Menggunakan warna solid agar baris di bawahnya tidak tembus saat scroll -->
                        <th colspan="2" class="border border-slate-200 p-3 bg-blue-100">Kelas VII</th>
                        <th colspan="2" class="border border-slate-200 p-3 bg-emerald-100">Kelas VIII</th>
                        <th colspan="2" class="border border-slate-200 p-3 bg-amber-100">Kelas IX</th>
                    </tr>
                     <tr class="text-[10px] md:text-xs uppercase tracking-wider text-slate-600">
                        <th class="border border-slate-200 p-2 bg-blue-50">Smt 1</th>
                        <th class="border border-slate-200 p-2 bg-blue-50">Smt 2</th>
                        <th class="border border-slate-200 p-2 bg-emerald-50">Smt 1</th>
                        <th class="border border-slate-200 p-2 bg-emerald-50">Smt 2</th>
                        <th class="border border-slate-200 p-2 bg-amber-50">Smt 1</th>
                        <th class="border border-slate-200 p-2 bg-amber-50">Smt 2</th>
                    </tr>
                </thead>
                <tbody class="text-slate-600 bg-white">
                    @php
                        // Memastikan variabel $student ada (di portal biasanya dikirim dengan nama $student atau Auth::user()->student)
                        $targetStudent = isset($student) ? $student : auth()->user()->student;
                        
                        // --- LOGIKA CERDAS PENCARIAN NILAI BERDASARKAN TAHUN AJARAN ---
                        $activeYearStr = \App\Models\AcademicYear::where('is_active', true)->first()->name ?? '2024/2025';
                        $activeStartYear = (int) substr($activeYearStr, 0, 4);

                        $ta7 = ''; $ta8 = ''; $ta9 = '';
                        
                        // Jika Alumni, hitung mundur dari tahun lulus
                        if ($targetStudent && ($targetStudent->status === 'graduated' || !empty($targetStudent->graduated_date))) {
                            $gradYear = !empty($targetStudent->graduated_date) 
                                ? (int) \Carbon\Carbon::parse($targetStudent->graduated_date)->format('Y') 
                                : $activeStartYear;
                            
                            $ta9 = ($gradYear - 1) . '/' . $gradYear;
                            $ta8 = ($gradYear - 2) . '/' . ($gradYear - 1);
                            $ta7 = ($gradYear - 3) . '/' . ($gradYear - 2);
                        } elseif ($targetStudent) {
                            $level = 7;
                            $className = $targetStudent->schoolClass->name ?? '';
                            if (preg_match('/^VIII|^8/i', $className)) $level = 8;
                            if (preg_match('/^IX|^9/i', $className)) $level = 9;

                            $ta7 = ($activeStartYear - ($level - 7)) . '/' . ($activeStartYear - ($level - 7) + 1);
                            $ta8 = ($activeStartYear - ($level - 8)) . '/' . ($activeStartYear - ($level - 8) + 1);
                            $ta9 = ($activeStartYear - ($level - 9)) . '/' . ($activeStartYear - ($level - 9) + 1);
                        }

                        // Ambil SEMUA nilai siswa ini sekaligus
                        $mappedScores = [];
                        if ($targetStudent) {
                            $allGrades = \App\Models\GradeRecord::with('items.subject')->where('student_id', $targetStudent->id)->get();
                            foreach($allGrades as $rec) {
                                foreach($rec->items as $item) {
                                    $subjName = strtolower(trim($item->subject->name ?? ''));
                                    $mappedScores[$rec->academic_year][$rec->semester][$subjName] = $item->score;
                                }
                            }
                        }

                        $mapelInduk = \App\Models\Subject::orderBy('order')->get();
                        $no = 1;

                        // Siapkan array untuk menampung total nilai & jumlah mapel per semester
                        $totals = ['71' => 0, '72' => 0, '81' => 0, '82' => 0, '91' => 0, '92' => 0];
                        $counts = ['71' => 0, '72' => 0, '81' => 0, '82' => 0, '91' => 0, '92' => 0];
                    @endphp

                    @if($targetStudent)
                        @foreach($mapelInduk as $mapel)
                        @php
                            $mName = strtolower(trim($mapel->name));
                            // Petakan nilai langsung dari dictionary tahun ajaran
                            $v71 = $mappedScores[$ta7][1][$mName] ?? '-';
                            $v72 = $mappedScores[$ta7][2][$mName] ?? '-';
                            $v81 = $mappedScores[$ta8][1][$mName] ?? '-';
                            $v82 = $mappedScores[$ta8][2][$mName] ?? '-';
                            $v91 = $mappedScores[$ta9][1][$mName] ?? '-';
                            $v92 = $mappedScores[$ta9][2][$mName] ?? '-';

                            // Akumulasi rata-rata
                            if(is_numeric($v71)) { $totals['71'] += (float)$v71; $counts['71']++; }
                            if(is_numeric($v72)) { $totals['72'] += (float)$v72; $counts['72']++; }
                            if(is_numeric($v81)) { $totals['81'] += (float)$v81; $counts['81']++; }
                            if(is_numeric($v82)) { $totals['82'] += (float)$v82; $counts['82']++; }
                            if(is_numeric($v91)) { $totals['91'] += (float)$v91; $counts['91']++; }
                            if(is_numeric($v92)) { $totals['92'] += (float)$v92; $counts['92']++; }
                        @endphp
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="border border-slate-200 p-2">{{ $no++ }}</td>
                            <td class="border border-slate-200 p-2 text-left font-semibold text-elevate-dark">{{ $mapel->name }}</td>
                            
                            <td class="border border-slate-200 p-2 font-mono">{{ $v71 }}</td>
                            <td class="border border-slate-200 p-2 font-mono">{{ $v72 }}</td>
                            
                            <td class="border border-slate-200 p-2 font-mono">{{ $v81 }}</td>
                            <td class="border border-slate-200 p-2 font-mono">{{ $v82 }}</td>
                            
                            <td class="border border-slate-200 p-2 font-mono">{{ $v91 }}</td>
                            <td class="border border-slate-200 p-2 font-mono">{{ $v92 }}</td>
                        </tr>
                        @endforeach
                        
                        {{-- TAMBAHAN: BARIS RATA-RATA RIWAYAT 6 SEMESTER --}}
                        <tr class="bg-slate-50 font-bold text-elevate-dark">
                            <td colspan="2" class="border border-slate-200 p-3 text-right">Rata-rata Nilai</td>
                            <td class="border border-slate-200 p-3 font-mono text-elevate-primary">{{ $counts['71'] > 0 ? round($totals['71'] / $counts['71'], 1) : '-' }}</td>
                            <td class="border border-slate-200 p-3 font-mono text-elevate-primary">{{ $counts['72'] > 0 ? round($totals['72'] / $counts['72'], 1) : '-' }}</td>
                            <td class="border border-slate-200 p-3 font-mono text-elevate-primary">{{ $counts['81'] > 0 ? round($totals['81'] / $counts['81'], 1) : '-' }}</td>
                            <td class="border border-slate-200 p-3 font-mono text-elevate-primary">{{ $counts['82'] > 0 ? round($totals['82'] / $counts['82'], 1) : '-' }}</td>
                            <td class="border border-slate-200 p-3 font-mono text-elevate-primary">{{ $counts['91'] > 0 ? round($totals['91'] / $counts['91'], 1) : '-' }}</td>
                            <td class="border border-slate-200 p-3 font-mono text-elevate-primary">{{ $counts['92'] > 0 ? round($totals['92'] / $counts['92'], 1) : '-' }}</td>
                        </tr>
                    @else
                        <tr>
                            <td colspan="8" class="border border-slate-200 p-4 text-slate-400 italic">Data profil siswa tidak ditemukan.</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>