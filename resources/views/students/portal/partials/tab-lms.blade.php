<div x-data="{ lmsTab: 'assignments' }" class="space-y-8 animate-in fade-in duration-500 font-sans">

    {{-- 1. HERO STATS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $totalAssign = 0; $submittedCount = 0;
            if(isset($lms_assignments_grouped)) {
                $studentId = Auth::guard('student')->id();
                foreach($lms_assignments_grouped as $group) { 
                    $totalAssign += $group->count(); 
                    foreach($group as $task) {
                        if($task->submissions && $task->submissions->where('student_id', $studentId)->first()) $submittedCount++;
                    }
                }
            }
            $pendingCount = max(0, $totalAssign - $submittedCount); 
            $gradedScores = array_filter($lms_grades ?? [], fn($v) => !is_null($v) && is_numeric($v));
            $avgScore = count($gradedScores) > 0 ? round(array_sum($gradedScores) / count($gradedScores)) : 0;
            $totalMateri = 0; 
            if(isset($lms_materials_grouped)) { foreach($lms_materials_grouped as $g) { $totalMateri += $g->count(); } }
        @endphp

        {{-- Stat 1: Tugas Pending --}}
        <div class="bg-elevate-dark rounded-[2.5rem] p-6 text-white shadow-xl shadow-elevate-dark/20 relative overflow-hidden group border border-elevate-primary/30">
            <div class="absolute inset-0 bg-gradient-to-br from-elevate-dark via-elevate-dark to-elevate-primary/50 pointer-events-none"></div>
            <div class="absolute top-0 right-0 w-48 h-48 bg-elevate-accent/10 rounded-full -mr-16 -mt-16 blur-2xl group-hover:bg-elevate-accent/20 transition-all duration-700 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-32 h-32 bg-elevate-peach/15 rounded-full -ml-8 -mb-8 blur-xl pointer-events-none"></div>
            <div class="absolute top-3 right-4 opacity-[0.06] group-hover:opacity-[0.12] group-hover:scale-110 transition-all duration-500 pointer-events-none">
                <i class="ph-fill ph-clipboard-text" style="font-size: 7rem;"></i>
            </div>
            <div class="relative z-10">
                <p class="text-[10px] font-black text-elevate-accent uppercase tracking-widest mb-2">Tugas Pending</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-5xl font-black">{{ $pendingCount }}</h3>
                    <span class="text-sm font-bold text-slate-300 opacity-80">Belum Selesai</span>
                </div>
                <div class="mt-4">
                    @if($pendingCount > 0)
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-elevate-peach/20 text-elevate-peach-light text-xs font-bold border border-elevate-peach/30 backdrop-blur-sm">
                            <i class="ph-bold ph-clock-countdown"></i> Segera Kerjakan
                        </div>
                    @else
                        <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500/20 text-emerald-300 text-xs font-bold border border-emerald-400/30 backdrop-blur-sm">
                            <i class="ph-bold ph-check-circle"></i> Semua Beres!
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Stat 2: Rata-rata Nilai --}}
        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-elevate-accent/50 hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-36 h-36 bg-elevate-soft rounded-full -mr-10 -mt-10 blur-2xl opacity-50 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
            <div class="absolute top-4 right-5 opacity-[0.04] group-hover:opacity-[0.08] transition-opacity pointer-events-none">
                <i class="ph-duotone ph-exam" style="font-size: 7rem; color: #0d52a1;"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Rata-rata Nilai</p>
            <div class="flex items-baseline gap-3 relative z-10">
                <h3 class="text-5xl font-black text-elevate-dark">{{ $avgScore }}</h3>
                <span class="text-[10px] font-black px-2.5 py-1.5 rounded-lg border shadow-sm {{ $avgScore >= 80 ? 'bg-elevate-soft text-elevate-primary border-elevate-accent/30' : ($avgScore >= 70 ? 'bg-amber-50 text-amber-700 border-amber-200' : 'bg-rose-50 text-rose-600 border-rose-200') }}">
                    {{ $avgScore >= 90 ? 'A' : ($avgScore >= 80 ? 'B' : ($avgScore >= 70 ? 'C' : ($avgScore > 0 ? 'D' : '—'))) }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium relative z-10">Dari {{ count($gradedScores) }} tugas dinilai.</p>
        </div>

        {{-- Stat 3: Total Materi --}}
        <div class="bg-white rounded-[2.5rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-elevate-primary/30 hover:shadow-lg transition-all duration-300">
            <div class="absolute top-0 right-0 w-36 h-36 bg-elevate-soft rounded-full -mr-10 -mt-10 blur-2xl opacity-50 group-hover:opacity-100 transition-opacity duration-500 pointer-events-none"></div>
            <div class="absolute top-4 right-5 opacity-[0.04] group-hover:opacity-[0.08] transition-opacity pointer-events-none">
                <i class="ph-duotone ph-books" style="font-size: 7rem; color: #56bbf1;"></i>
            </div>
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2 relative z-10">Total Materi</p>
            <div class="flex items-baseline gap-2 relative z-10">
                <h3 class="text-5xl font-black text-elevate-dark">{{ $totalMateri }}</h3>
                <span class="text-sm font-bold text-slate-400">Modul</span>
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium relative z-10">Siap untuk dipelajari.</p>
        </div>
    </div>

    {{-- 2. SUB-TAB SWITCHER --}}
    <div class="flex justify-center">
        <div class="bg-slate-100 p-1.5 rounded-2xl inline-flex items-center gap-1 border border-slate-200 shadow-inner">
            <button @click="lmsTab = 'assignments'" 
                :class="lmsTab === 'assignments' ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-black/5 font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                class="px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-wider transition-all flex items-center gap-2">
                <i class="ph-bold ph-clipboard-text text-lg"></i> Tugas & Kuis
            </button>
            <button @click="lmsTab = 'materials'" 
                :class="lmsTab === 'materials' ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-black/5 font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                class="px-6 py-2.5 rounded-xl text-[10px] uppercase tracking-wider transition-all flex items-center gap-2">
                <i class="ph-bold ph-book-open-text text-lg"></i> Materi Belajar
            </button>
        </div>
    </div>

    {{-- 3. KONTEN: TUGAS & KUIS --}}
    <div x-show="lmsTab === 'assignments'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        @if(isset($lms_assignments_grouped) && count($lms_assignments_grouped) > 0)
            @foreach($lms_assignments_grouped as $subjectName => $assignments)
                @php
                    $sName = strtolower($subjectName);
                    $themeOptions = [
                        ['bg' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-accent/30', 'ring' => 'ring-elevate-accent/20'],
                        ['bg' => 'bg-elevate-peach-light/20', 'text' => 'text-elevate-peach-dark', 'border' => 'border-elevate-peach/30', 'ring' => 'ring-elevate-peach/20'],
                        ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'ring' => 'ring-emerald-100']
                    ];
                    $t = $themeOptions[crc32($sName) % count($themeOptions)];

                    // Cover gradient based on subject name
                    $coverGrad = match(true) {
                        str_contains($sName, 'matematika') => ['bg' => 'from-blue-600 via-indigo-700 to-slate-900', 'icon' => 'ph-calculator'],
                        str_contains($sName, 'ipa') || str_contains($sName, 'biologi') || str_contains($sName, 'fisika') || str_contains($sName, 'kimia') => ['bg' => 'from-emerald-600 via-teal-700 to-slate-900', 'icon' => 'ph-flask'],
                        str_contains($sName, 'inggris') || str_contains($sName, 'indonesia') || str_contains($sName, 'bahasa') => ['bg' => 'from-purple-600 via-indigo-800 to-slate-900', 'icon' => 'ph-translate'],
                        str_contains($sName, 'informatika') || str_contains($sName, 'tik') || str_contains($sName, 'komputer') => ['bg' => 'from-sky-500 via-blue-700 to-slate-950', 'icon' => 'ph-code'],
                        str_contains($sName, 'ips') || str_contains($sName, 'sejarah') || str_contains($sName, 'geografi') => ['bg' => 'from-amber-600 via-orange-700 to-slate-900', 'icon' => 'ph-compass'],
                        str_contains($sName, 'agama') => ['bg' => 'from-teal-600 via-cyan-700 to-slate-900', 'icon' => 'ph-hands-praying'],
                        str_contains($sName, 'seni') || str_contains($sName, 'budaya') => ['bg' => 'from-rose-500 via-pink-700 to-slate-900', 'icon' => 'ph-palette'],
                        str_contains($sName, 'pjok') || str_contains($sName, 'olahraga') => ['bg' => 'from-lime-600 via-green-700 to-slate-900', 'icon' => 'ph-soccer-ball'],
                        default => ['bg' => 'from-sky-600 via-indigo-800 to-slate-900', 'icon' => 'ph-book-open-text']
                    };
                @endphp

                <div class="mb-10 animate-enter">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-5 pl-2 pr-2">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-1.5 rounded-full {{ $t['bg'] }} border {{ $t['border'] }}"></div>
                            <h3 class="text-xl font-black text-elevate-dark tracking-tight flex items-center gap-2">
                                {{ $subjectName }}
                                <span class="text-[10px] uppercase tracking-widest font-black px-2.5 py-1 rounded-lg {{ $t['bg'] }} {{ $t['text'] }} border {{ $t['border'] }}">
                                    {{ count($assignments) }} Tugas
                                </span>
                            </h3>
                        </div>
                        <a href="{{ route('students.learning.play', $assignments->first()->subject_id) }}" class="px-5 py-2.5 bg-elevate-primary text-white text-xs font-bold rounded-xl shadow-md shadow-elevate-primary/20 hover:bg-elevate-dark transition-all flex items-center gap-2 w-full md:w-auto justify-center">
                            <i class="ph-bold ph-play-circle text-lg"></i> Mulai Belajar Terstruktur
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($assignments as $task)
                            @php
                                $mySubmission = $task->submissions->where('student_id', Auth::guard('student')->id())->first();
                                $score = $mySubmission->grade ?? null;
                                $isGraded = $score !== null;
                                $isSubmitted = $mySubmission !== null;
                                $isQuiz = $task->assignment_type == 'quiz';
                                $isLink = $task->assignment_type == 'link';
                                $isExpired = \Carbon\Carbon::now() > \Carbon\Carbon::parse($task->deadline);
                                $deadlineFormatted = \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M, H:i');
                                $taskCoverIcon = $isQuiz ? 'ph-brain' : ($isLink ? 'ph-link' : 'ph-clipboard-text');
                                $taskCoverGrad = $isQuiz
                                    ? ['bg' => 'from-violet-600 via-purple-800 to-slate-900', 'icon' => 'ph-brain']
                                    : ($isLink ? ['bg' => 'from-rose-500 via-pink-700 to-slate-900', 'icon' => 'ph-link'] : $coverGrad);
                            @endphp

                            <div class="group relative bg-white border border-slate-100 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:ring-2 {{ $t['ring'] }}"
                                 x-data="{ openUpload: false, submissionType: '{{ ($mySubmission && $mySubmission->link_url) ? 'link' : 'file' }}' }">

                                {{-- COVER THUMBNAIL --}}
                                <div class="relative h-36 overflow-hidden shrink-0 bg-slate-900">
                                    @if($task->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($task->cover_image))
                                        <img src="{{ asset('storage/' . $task->cover_image) }}" alt="{{ $task->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent pointer-events-none"></div>
                                        <div class="absolute bottom-2.5 right-3 z-10">
                                            <p class="text-[10px] font-black text-white/90 uppercase tracking-widest truncate">{{ $subjectName }}</p>
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br {{ $taskCoverGrad['bg'] }} flex flex-col justify-between p-5 group-hover:scale-105 transition-transform duration-500 relative">
                                            <div class="absolute -right-5 -bottom-5 text-white/[0.08] pointer-events-none select-none" style="font-size: 8rem;">
                                                <i class="ph-duotone {{ $taskCoverGrad['icon'] }}"></i>
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>

                                            <div class="flex items-start justify-end relative z-10">
                                                <span class="text-[9px] font-black tracking-widest text-white/80 uppercase bg-black/25 px-2 py-1 rounded-md backdrop-blur-sm">
                                                    {{ $isQuiz ? 'Kuis Online' : ($isLink ? 'Link Tugas' : 'Upload File') }}
                                                </span>
                                            </div>
                                            <div class="relative z-10">
                                                <p class="text-[10px] font-black text-white/90 uppercase tracking-widest truncate">{{ $subjectName }}</p>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Status Badge Overlay --}}
                                    <div class="absolute top-3 left-3 z-20">
                                        @if($isGraded)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-emerald-900/75 backdrop-blur-md border border-emerald-400/30 text-emerald-300 text-[10px] font-black shadow-sm">
                                                <i class="ph-bold ph-check-circle"></i> Nilai: {{ $score }}
                                            </span>
                                        @elseif($isSubmitted)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-sky-900/75 backdrop-blur-md border border-sky-400/30 text-sky-300 text-[10px] font-black shadow-sm">
                                                <i class="ph-bold ph-check"></i> Terkirim
                                            </span>
                                        @elseif($isExpired && !$task->allow_late_submission)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-slate-900/75 backdrop-blur-md border border-slate-400/20 text-slate-300 text-[10px] font-black shadow-sm">
                                                <i class="ph-bold ph-lock-key"></i> Tutup
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-amber-900/75 backdrop-blur-md border border-amber-400/30 text-amber-300 text-[10px] font-black shadow-sm animate-pulse">
                                                <i class="ph-bold ph-clock"></i> Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- CARD BODY --}}
                                <div class="p-5 flex flex-col flex-grow relative overflow-hidden">
                                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full {{ $t['bg'] }} opacity-40 group-hover:scale-150 transition-transform duration-500 pointer-events-none blur-xl"></div>

                                    <div class="mb-4 relative z-10 flex-grow">
                                        <h4 class="font-bold text-base text-elevate-dark group-hover:text-elevate-primary transition-colors line-clamp-2 leading-snug">
                                            {{ $task->title }}
                                        </h4>
                                        <div class="flex items-center gap-1.5 mt-2">
                                            <i class="ph-bold ph-clock {{ $isExpired ? 'text-rose-400' : 'text-slate-400' }} text-xs shrink-0"></i>
                                            <p class="text-xs {{ $isExpired ? 'text-rose-500 font-bold' : 'text-slate-500 font-medium' }}">{{ $deadlineFormatted }}</p>
                                        </div>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 mt-auto relative z-10">
                                        @if($isGraded)
                                            <button disabled class="w-full py-2.5 rounded-xl bg-slate-50 text-slate-400 font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed border border-slate-100">
                                                <i class="ph-bold ph-check-circle"></i> Selesai & Dinilai
                                            </button>
                                        @else
                                            @if($isExpired && !$task->allow_late_submission)
                                                <button disabled class="w-full py-2.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed">
                                                    <i class="ph-bold ph-lock-key"></i> Waktu Habis
                                                </button>
                                            @elseif($isQuiz)
                                                <a href="{{ route('students.learning.assignment.quiz', $task->id) }}" 
                                                   class="w-full py-2.5 rounded-xl bg-elevate-peach-dark hover:bg-elevate-peach text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-elevate-peach/20 hover:-translate-y-0.5 transition-all">
                                                    <span>Mulai Kuis</span>
                                                    <i class="ph-bold ph-play-circle"></i>
                                                </a>
                                            @elseif($isLink)
                                                <div class="flex gap-2">
                                                    <a href="{{ $task->link_url }}" target="_blank"
                                                       class="flex-1 py-2.5 rounded-xl bg-white hover:bg-elevate-soft border border-elevate-accent/50 text-elevate-primary font-bold text-xs flex items-center justify-center gap-1.5 transition-all">
                                                        <i class="ph-bold ph-link"></i> Buka Link
                                                    </a>
                                                    <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" class="flex-1" id="form-link-{{ $task->id }}">
                                                        @csrf
                                                        <input type="hidden" name="submission_type" value="link"> 
                                                        <button type="button" onclick="confirmTaskSubmit('{{ $task->id }}')" 
                                                            class="w-full py-2.5 rounded-xl bg-elevate-primary hover:bg-elevate-dark text-white font-bold text-xs flex items-center justify-center gap-1.5 shadow-md shadow-elevate-primary/20 hover:-translate-y-0.5 transition-all">
                                                            <i class="ph-bold ph-check"></i> Selesai
                                                        </button>
                                                    </form>
                                                </div>
                                            @else 
                                                <button @click="openUpload = !openUpload" 
                                                    class="w-full py-2.5 rounded-xl bg-elevate-primary hover:bg-elevate-dark text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-elevate-primary/20 hover:-translate-y-0.5 transition-all">
                                                    <span x-text="openUpload ? 'Tutup Form' : '{{ $isSubmitted ? 'Edit Jawaban' : 'Kerjakan Tugas' }}'"></span>
                                                    <i class="ph-bold" :class="openUpload ? 'ph-x' : 'ph-upload-simple'"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- FORM UPLOAD --}}
                                    @if($task->assignment_type == 'file_upload' && !$isGraded)
                                        <div x-show="openUpload" x-transition class="mt-4 pt-4 border-t border-dashed border-elevate-accent/40">
                                            <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                @csrf
                                                <div class="flex p-1 bg-slate-50 rounded-lg mb-3 border border-slate-200 w-full">
                                                    <label class="flex-1 cursor-pointer text-center">
                                                        <input type="radio" name="submission_type" value="file" class="sr-only" x-model="submissionType">
                                                        <div class="py-1.5 rounded-md text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5"
                                                             :class="submissionType === 'file' ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'">
                                                            <i class="ph-bold ph-file-text"></i> Upload File
                                                        </div>
                                                    </label>
                                                    <label class="flex-1 cursor-pointer text-center">
                                                        <input type="radio" name="submission_type" value="link" class="sr-only" x-model="submissionType">
                                                        <div class="py-1.5 rounded-md text-[10px] font-bold transition-all duration-300 flex items-center justify-center gap-1.5"
                                                             :class="submissionType === 'link' ? 'bg-white text-elevate-primary shadow-sm ring-1 ring-black/5' : 'text-slate-400 hover:text-slate-600'">
                                                            <i class="ph-bold ph-link"></i> Link Tugas
                                                        </div>
                                                    </label>
                                                </div>

                                                <div x-show="submissionType === 'file'">
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">File Jawaban</label>
                                                    <input type="file" name="file" :required="submissionType === 'file'" :disabled="submissionType !== 'file'" class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-accent/20 border border-slate-200 rounded-xl">
                                                </div>

                                                <div x-show="submissionType === 'link'" style="display: none;">
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">URL Link</label>
                                                    <input type="url" name="link_url" :required="submissionType === 'link'" :disabled="submissionType !== 'link'" placeholder="https://..." value="{{ $mySubmission->link_url ?? '' }}" class="block w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-elevate-accent focus:border-elevate-accent text-elevate-dark font-medium">
                                                </div>

                                                <div>
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Catatan</label>
                                                    <textarea name="student_note" rows="2" class="w-full rounded-xl border-slate-200 text-xs focus:ring-elevate-accent focus:border-elevate-accent p-2.5 placeholder:text-slate-300" placeholder="Pesan untuk guru...">{{ $mySubmission->student_note ?? '' }}</textarea>
                                                </div>

                                                <button type="submit" class="w-full py-2.5 bg-elevate-dark text-white rounded-xl text-xs font-bold hover:bg-slate-800 transition-colors flex items-center justify-center gap-2">
                                                    <i class="ph-bold ph-paper-plane-right"></i> {{ $isSubmitted ? 'Update Jawaban' : 'Kirim Jawaban' }}
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center">
                <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary">
                    <i class="ph-duotone ph-confetti text-5xl"></i>
                </div>
                <h3 class="text-xl font-black text-elevate-dark">Tidak Ada Tugas Aktif</h3>
                <p class="text-slate-400 text-sm mt-1">Kamu sudah menyelesaikan semuanya. Hebat!</p>
            </div>
        @endif
    </div>

    {{-- 4. KONTEN: MATERI BELAJAR --}}
    <div x-show="lmsTab === 'materials'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        @if(isset($lms_materials_grouped) && count($lms_materials_grouped) > 0)
            @foreach($lms_materials_grouped as $subjectName => $materials)
                @php
                    $sName = strtolower($subjectName);
                    $themeOptions = [
                        ['bg' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-elevate-accent/30', 'ring' => 'ring-elevate-accent/20', 'icon' => 'ph-book-bookmark'],
                        ['bg' => 'bg-elevate-peach-light/20', 'text' => 'text-elevate-peach-dark', 'border' => 'border-elevate-peach/30', 'ring' => 'ring-elevate-peach/20', 'icon' => 'ph-notebook'],
                        ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'ring' => 'ring-emerald-100', 'icon' => 'ph-flask']
                    ];
                    $t = $themeOptions[crc32($sName) % count($themeOptions)];

                    // Cover gradient based on subject name
                    $coverGrad = match(true) {
                        str_contains($sName, 'matematika') => ['bg' => 'from-blue-600 via-indigo-700 to-slate-900', 'icon' => 'ph-calculator'],
                        str_contains($sName, 'ipa') || str_contains($sName, 'biologi') || str_contains($sName, 'fisika') || str_contains($sName, 'kimia') => ['bg' => 'from-emerald-600 via-teal-700 to-slate-900', 'icon' => 'ph-flask'],
                        str_contains($sName, 'inggris') || str_contains($sName, 'indonesia') || str_contains($sName, 'bahasa') => ['bg' => 'from-purple-600 via-indigo-800 to-slate-900', 'icon' => 'ph-translate'],
                        str_contains($sName, 'informatika') || str_contains($sName, 'tik') || str_contains($sName, 'komputer') => ['bg' => 'from-sky-500 via-blue-700 to-slate-950', 'icon' => 'ph-code'],
                        str_contains($sName, 'ips') || str_contains($sName, 'sejarah') || str_contains($sName, 'geografi') => ['bg' => 'from-amber-600 via-orange-700 to-slate-900', 'icon' => 'ph-compass'],
                        str_contains($sName, 'agama') => ['bg' => 'from-teal-600 via-cyan-700 to-slate-900', 'icon' => 'ph-hands-praying'],
                        str_contains($sName, 'seni') || str_contains($sName, 'budaya') => ['bg' => 'from-rose-500 via-pink-700 to-slate-900', 'icon' => 'ph-palette'],
                        str_contains($sName, 'pjok') || str_contains($sName, 'olahraga') => ['bg' => 'from-lime-600 via-green-700 to-slate-900', 'icon' => 'ph-soccer-ball'],
                        default => ['bg' => 'from-sky-600 via-indigo-800 to-slate-900', 'icon' => 'ph-book-open-text']
                    };
                @endphp

                <div class="mb-10 animate-enter">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-5 pl-2 pr-2">
                        <div class="flex items-center gap-4">
                            <div class="h-10 w-1.5 rounded-full {{ $t['bg'] }} border {{ $t['border'] }}"></div>
                            <h3 class="text-xl font-black text-elevate-dark tracking-tight flex items-center gap-2">
                                {{ $subjectName }}
                                <span class="text-[10px] uppercase tracking-widest font-black px-2.5 py-1 rounded-lg {{ $t['bg'] }} {{ $t['text'] }} border {{ $t['border'] }}">
                                    {{ count($materials) }} Materi
                                </span>
                            </h3>
                        </div>
                        <a href="{{ route('students.learning.play', $materials->first()->subject_id) }}" class="px-5 py-2.5 bg-elevate-primary text-white text-xs font-bold rounded-xl shadow-md shadow-elevate-primary/20 hover:bg-elevate-dark transition-all flex items-center gap-2 w-full md:w-auto justify-center">
                            <i class="ph-bold ph-play-circle text-lg"></i> Mulai Belajar Terstruktur
                        </a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($materials as $material)
                            @php
                                $mainAttachment = $material->attachments->first();
                                $hasFile = $mainAttachment && $mainAttachment->file_type == 'file';
                                $hasLink = $mainAttachment && ($mainAttachment->file_type == 'link' || $mainAttachment->file_type == 'video');
                                $fileUrl = '#'; $btnText = 'Lihat Detail'; $btnIcon = 'ph-list-magnifying-glass';
                                if($hasFile) { $fileUrl = asset('storage/' . $mainAttachment->file_path); $btnText = 'Buka Materi'; $btnIcon = 'ph-book-open-text'; } 
                                elseif($hasLink) { $fileUrl = $mainAttachment->file_path; $btnText = 'Buka Link'; $btnIcon = 'ph-link'; }
                            @endphp

                            <div class="group relative bg-white border border-slate-100 rounded-[2rem] overflow-hidden shadow-sm hover:shadow-xl hover:shadow-elevate-dark/5 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:ring-2 {{ $t['ring'] }}">

                                {{-- COVER THUMBNAIL --}}
                                <div class="relative h-36 overflow-hidden shrink-0">
                                    @if($material->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($material->cover_image))
                                        <img src="{{ asset('storage/' . $material->cover_image) }}" alt="{{ $material->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-black/10"></div>
                                        <div class="absolute bottom-3 left-4 z-10">
                                            <p class="text-[10px] font-black text-white/90 uppercase tracking-widest">{{ $subjectName }}</p>
                                        </div>
                                    @else
                                        <div class="w-full h-full bg-gradient-to-br {{ $coverGrad['bg'] }} flex flex-col justify-between p-5 group-hover:scale-105 transition-transform duration-500 relative">
                                            <div class="absolute -right-5 -bottom-5 text-white/[0.08] pointer-events-none select-none" style="font-size: 8rem;">
                                                <i class="ph-duotone {{ $coverGrad['icon'] }}"></i>
                                            </div>
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 via-transparent to-transparent pointer-events-none"></div>
                                            <div class="flex items-start justify-between relative z-10">
                                                <span class="w-9 h-9 rounded-xl bg-white/15 backdrop-blur-md border border-white/25 flex items-center justify-center text-base text-white shadow-sm">
                                                    <i class="ph-bold {{ $t['icon'] }}"></i>
                                                </span>
                                                <span class="text-[9px] font-black tracking-widest text-white/80 uppercase bg-black/25 px-2 py-1 rounded-md backdrop-blur-sm">MODUL DIGITAL</span>
                                            </div>
                                            <div class="relative z-10">
                                                <p class="text-[10px] font-black text-white/90 uppercase tracking-widest truncate">{{ $subjectName }}</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- CARD BODY --}}
                                <div class="p-5 flex flex-col flex-grow relative overflow-hidden">
                                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full {{ $t['bg'] }} opacity-40 group-hover:scale-150 transition-transform duration-500 pointer-events-none blur-xl"></div>

                                    <div class="mb-4 relative z-10 flex-grow">
                                        <h4 class="font-bold text-base text-elevate-dark group-hover:text-elevate-primary transition-colors line-clamp-2 leading-snug">
                                            {{ $material->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-2 line-clamp-2 font-medium">{{ $material->resume ?? 'Tidak ada deskripsi.' }}</p>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 mt-auto relative z-10 flex gap-2">
                                        @if($hasFile || $hasLink)
                                            <a href="{{ $fileUrl }}" target="_blank" class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center gap-1.5 transition-colors">
                                                <i class="ph-bold {{ $btnIcon }}"></i> {{ $btnText }}
                                            </a>
                                        @endif
                                        <a href="{{ route('students.learning.play', $material->subject_id) }}" class="flex-1 py-2.5 rounded-xl bg-elevate-soft/50 hover:bg-elevate-soft text-elevate-primary border border-elevate-accent/30 font-bold text-xs flex items-center justify-center gap-1.5 transition-colors shadow-sm">
                                            <i class="ph-bold ph-presentation-chart"></i> Buka Player
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200 flex flex-col items-center">
                <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-primary">
                    <i class="ph-duotone ph-files text-5xl"></i>
                </div>
                <h3 class="text-xl font-black text-elevate-dark">Belum Ada Materi</h3>
            </div>
        @endif
    </div>

    <script>
        function confirmTaskSubmit(taskId) {
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Pastikan Anda sudah mengerjakan soal di link yang tersedia sebelum menandai ini selesai.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d52a1', // elevate-primary
                cancelButtonColor: '#64748b', // slate-500
                confirmButtonText: 'Ya, Selesai',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-2xl', confirmButton: 'px-4 py-2 rounded-xl font-bold', cancelButton: 'px-4 py-2 rounded-xl font-bold' }
            }).then((result) => {
                if (result.isConfirmed) { document.getElementById('form-link-' + taskId).submit(); }
            });
        }
    </script>
</div>