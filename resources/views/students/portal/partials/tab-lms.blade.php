<div x-data="{ lmsTab: 'assignments' }" class="space-y-8 animate-in fade-in duration-500 font-sans">

    {{-- 1. HERO STATS (LOGIKA DIPERBAIKI) --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
            $totalAssign = 0;
            // $lms_grades berisi array [assignment_id => grade]
            // Jika siswa mensubmit tapi belum dinilai, grade biasanya NULL.
            // Kita hitung jumlah yang sudah disubmit (entah dinilai atau belum)
            $submittedCount = count($lms_grades ?? []);
            
            if(isset($lms_assignments_grouped)) {
                foreach($lms_assignments_grouped as $group) { $totalAssign += $group->count(); }
            }
            
            // Tugas Pending = Total Tugas - Submitted
            $pendingCount = max(0, $totalAssign - $submittedCount); 
            
            // Rata-rata Nilai: Hanya hitung yang SUDAH DINILAI (bukan null)
            // Filter hanya nilai angka
            $gradedScores = array_filter($lms_grades ?? [], fn($v) => !is_null($v) && is_numeric($v));
            $avgScore = count($gradedScores) > 0 ? round(array_sum($gradedScores) / count($gradedScores)) : 0;
            
            // Total Materi
            $totalMateri = 0; 
            if(isset($lms_materials_grouped)) {
                foreach($lms_materials_grouped as $g) { $totalMateri += $g->count(); }
            }
        @endphp

        {{-- Card Tugas Pending --}}
        <div class="bg-gradient-to-br from-indigo-600 to-blue-700 rounded-[2rem] p-6 text-white shadow-xl shadow-blue-500/20 relative overflow-hidden group">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform">
                <i class="ph-fill ph-clipboard-text text-8xl"></i>
            </div>
            <div class="relative z-10">
                <p class="text-xs font-bold text-blue-200 uppercase tracking-wider mb-1">Tugas Pending</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl font-black">{{ $pendingCount }}</h3>
                    <span class="text-sm font-medium opacity-80">Belum Selesai</span>
                </div>
                @if($pendingCount > 0)
                    <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/20 backdrop-blur-sm text-xs font-bold border border-white/10">
                        <i class="ph-bold ph-clock-countdown text-yellow-300"></i> Segera Kerjakan
                    </div>
                @else
                    <div class="mt-4 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-emerald-500/20 backdrop-blur-sm text-xs font-bold border border-emerald-400/30 text-emerald-100">
                        <i class="ph-bold ph-check-circle"></i> Semua Beres!
                    </div>
                @endif
            </div>
        </div>

        {{-- Card Rata-rata Nilai --}}
        <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-emerald-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ph-duotone ph-exam text-8xl text-emerald-600"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Rata-rata Nilai</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800">{{ $avgScore }}</h3>
                <span class="text-xs font-bold px-2 py-1 rounded-md {{ $avgScore >= 80 ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                    {{ $avgScore >= 90 ? 'A' : ($avgScore >= 80 ? 'B' : 'C') }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium">Dari {{ count($gradedScores) }} tugas yang dinilai.</p>
        </div>

        {{-- Card Materi --}}
        <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm relative overflow-hidden group hover:border-purple-200 transition-colors">
            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <i class="ph-duotone ph-books text-8xl text-purple-600"></i>
            </div>
            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Materi</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-800">{{ $totalMateri }}</h3>
                <span class="text-sm font-medium text-slate-400">Modul</span>
            </div>
            <p class="text-xs text-slate-400 mt-3 font-medium">Siap untuk dipelajari.</p>
        </div>
    </div>

    {{-- 2. SUB-TAB SWITCHER --}}
    <div class="flex justify-center">
        <div class="bg-slate-100 p-1.5 rounded-2xl inline-flex items-center gap-1 border border-slate-200 shadow-inner">
            <button @click="lmsTab = 'assignments'" 
                :class="lmsTab === 'assignments' ? 'bg-white text-blue-600 shadow-sm ring-1 ring-black/5 font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                class="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                <i class="ph-bold ph-clipboard-text text-lg"></i> Tugas & Kuis
            </button>
            <button @click="lmsTab = 'materials'" 
                :class="lmsTab === 'materials' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-black/5 font-black' : 'text-slate-500 hover:text-slate-700 font-bold'"
                class="px-6 py-2.5 rounded-xl text-xs uppercase tracking-wider transition-all flex items-center gap-2">
                <i class="ph-bold ph-book-open-text text-lg"></i> Materi Belajar
            </button>
        </div>
    </div>

    {{-- 3. KONTEN: TUGAS & KUIS --}}
    <div x-show="lmsTab === 'assignments'" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
        @if(isset($lms_assignments_grouped) && count($lms_assignments_grouped) > 0)
            @foreach($lms_assignments_grouped as $subjectName => $assignments)
                {{-- THEME LOGIC --}}
                @php
                    $sName = strtolower($subjectName);
                    $theme = ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'light' => 'bg-blue-100', 'ring' => 'ring-blue-100', 'icon' => 'ph-book-bookmark'];

                    if (str_contains($sName, 'matematika')) $theme = ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'light' => 'bg-orange-100', 'ring' => 'ring-orange-100', 'icon' => 'ph-calculator'];
                    elseif (str_contains($sName, 'ipa') || str_contains($sName, 'fisika')) $theme = ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'light' => 'bg-emerald-100', 'ring' => 'ring-emerald-100', 'icon' => 'ph-flask'];
                @endphp

                <div class="mb-10 animate-enter">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="h-10 w-1.5 rounded-full {{ str_replace('text-', 'bg-', $theme['text']) }} opacity-50"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                            {{ $subjectName }}
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $theme['bg'] }} {{ $theme['text'] }} border {{ $theme['border'] }}">
                                {{ count($assignments) }} Tugas
                            </span>
                        </h3>
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
                            @endphp

                            <div class="group relative bg-white border border-slate-100 rounded-[2rem] p-1 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 {{ $theme['ring'] }}"
                                 x-data="{ openUpload: false }">
                                
                                <div class="bg-white rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden">
                                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full {{ $theme['bg'] }} opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                    <div class="flex justify-between items-start mb-4 relative z-10">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border {{ $theme['border'] }} {{ $theme['bg'] }} {{ $theme['text'] }}">
                                            <i class="ph-duotone {{ $isQuiz ? 'ph-brain' : ($isLink ? 'ph-link' : 'ph-clipboard-text') }}"></i>
                                        </div>
                                        
                                        @if($isGraded)
                                            <div class="flex flex-col items-end">
                                                <span class="text-2xl font-black {{ $score < 75 ? 'text-rose-500' : 'text-emerald-500' }}">{{ $score }}</span>
                                                <span class="text-[9px] font-black uppercase text-slate-400 tracking-widest">Nilai</span>
                                            </div>
                                        @elseif($isSubmitted)
                                            <span class="bg-blue-50 text-blue-600 border border-blue-100 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider flex items-center gap-1">
                                                <i class="ph-bold ph-check"></i> Terkirim
                                            </span>
                                        @else
                                            @if($isExpired && !$task->allow_late_submission)
                                                <span class="bg-slate-100 text-slate-500 border border-slate-200 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider flex items-center gap-1">
                                                    <i class="ph-bold ph-lock-key"></i> Tutup
                                                </span>
                                            @else
                                                <span class="bg-rose-50 text-rose-600 border border-rose-100 px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider flex items-center gap-1 animate-pulse">
                                                    <i class="ph-bold ph-clock"></i> Aktif
                                                </span>
                                            @endif
                                        @endif
                                    </div>

                                    <div class="mb-4 relative z-10 flex-grow">
                                        <h4 class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                            {{ $task->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">
                                            Deadline: {{ $deadlineFormatted }}
                                        </p>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 mt-auto relative z-10">
                                        @if($isGraded)
                                            <button disabled class="w-full py-3 rounded-xl bg-slate-50 text-slate-400 font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed border border-slate-100">
                                                <i class="ph-bold ph-check-circle"></i> Selesai
                                            </button>
                                        @else
                                            {{-- LOGIKA TOMBOL BERDASARKAN TIPE --}}
                                            @if($isExpired && !$task->allow_late_submission)
                                                <button disabled class="w-full py-3 rounded-xl bg-slate-100 text-slate-400 font-bold text-xs flex items-center justify-center gap-2 cursor-not-allowed">
                                                    <i class="ph-bold ph-lock-key"></i> Waktu Habis
                                                </button>
                                            @elseif($isQuiz)
                                                <a href="{{ route('students.learning.assignment.quiz', $task->id) }}" 
                                                   class="w-full py-3 rounded-xl bg-purple-600 hover:bg-purple-700 shadow-purple-200 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5 transition-all">
                                                    <span>Mulai Kuis</span>
                                                    <i class="ph-bold ph-play-circle"></i>
                                                </a>
                                            @elseif($isLink)
                                                <div class="flex gap-2">
                                                    <a href="{{ $task->link_url }}" target="_blank"
                                                       class="flex-1 py-3 rounded-xl bg-amber-50 hover:bg-amber-100 border border-amber-200 text-amber-700 font-bold text-xs flex items-center justify-center gap-2 transition-all">
                                                        <i class="ph-bold ph-link"></i> Buka Link
                                                    </a>
                                                    <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" class="flex-1" id="form-link-{{ $task->id }}">
                                                        @csrf
                                                        <button type="button" onclick="confirmTaskSubmit('{{ $task->id }}')" 
                                                            class="w-full py-3 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-md hover:-translate-y-0.5 transition-all">
                                                            <i class="ph-bold ph-check"></i> Selesai
                                                        </button>
                                                    </form>
                                                </div>
                                            @else 
                                                {{-- FILE UPLOAD --}}
                                                <button @click="openUpload = !openUpload" 
                                                    class="w-full py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg hover:-translate-y-0.5 transition-all">
                                                    <span x-text="openUpload ? 'Tutup Form' : 'Upload Jawaban'"></span>
                                                    <i class="ph-bold" :class="openUpload ? 'ph-x' : 'ph-upload-simple'"></i>
                                                </button>
                                            @endif
                                        @endif
                                    </div>

                                    {{-- FORM UPLOAD EXPANDABLE --}}
                                    @if($task->assignment_type == 'file_upload' && !$isGraded)
                                        <div x-show="openUpload" 
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100"
                                             class="mt-4 pt-4 border-t border-dashed border-blue-200">
                                            
                                            <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                                                @csrf
                                                <div>
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Pilih File (PDF/JPG)</label>
                                                    <input type="file" name="file" required class="block w-full text-xs text-slate-500 file:mr-2 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 border border-slate-200 rounded-xl">
                                                </div>
                                                <div>
                                                    <label class="text-[10px] font-bold text-slate-400 uppercase mb-1 block">Catatan</label>
                                                    <textarea name="student_note" rows="2" class="w-full rounded-xl border-slate-200 text-xs focus:ring-blue-500 focus:border-blue-500" placeholder="Pesan untuk guru..."></textarea>
                                                </div>
                                                <button type="submit" class="w-full py-2.5 bg-slate-800 text-white rounded-xl text-xs font-bold hover:bg-slate-700 transition-colors flex items-center justify-center gap-2">
                                                    <i class="ph-bold ph-paper-plane-right"></i> Kirim
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
            <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-duotone ph-confetti text-5xl text-emerald-400"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800">Tidak Ada Tugas Aktif</h3>
            </div>
        @endif
    </div>

    {{-- 4. KONTEN: MATERI BELAJAR --}}
    <div x-show="lmsTab === 'materials'" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
        @if(isset($lms_materials_grouped) && count($lms_materials_grouped) > 0)
            @foreach($lms_materials_grouped as $subjectName => $materials)
                @php
                    $sName = strtolower($subjectName);
                    $theme = ['bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200', 'light' => 'bg-blue-100', 'ring' => 'ring-blue-100', 'icon' => 'ph-book-bookmark'];
                    if (str_contains($sName, 'matematika')) $theme = ['bg' => 'bg-orange-50', 'text' => 'text-orange-700', 'border' => 'border-orange-200', 'light' => 'bg-orange-100', 'ring' => 'ring-orange-100', 'icon' => 'ph-calculator'];
                    elseif (str_contains($sName, 'ipa') || str_contains($sName, 'fisika')) $theme = ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'light' => 'bg-emerald-100', 'ring' => 'ring-emerald-100', 'icon' => 'ph-flask'];
                @endphp

                <div class="mb-10 animate-enter">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="h-10 w-1.5 rounded-full {{ str_replace('text-', 'bg-', $theme['text']) }} opacity-50"></div>
                        <h3 class="text-xl font-black text-slate-800 tracking-tight flex items-center gap-2">
                            {{ $subjectName }}
                            <span class="text-xs font-bold px-2.5 py-1 rounded-lg {{ $theme['bg'] }} {{ $theme['text'] }} border {{ $theme['border'] }}">
                                {{ count($materials) }} Materi
                            </span>
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($materials as $material)
                            @php
                                $mainAttachment = $material->attachments->first();
                                $hasFile = $mainAttachment && $mainAttachment->file_type == 'file';
                                $hasLink = $mainAttachment && ($mainAttachment->file_type == 'link' || $mainAttachment->file_type == 'video');
                                
                                $fileUrl = '#';
                                $btnText = 'Lihat Detail';
                                $btnIcon = 'ph-list-magnifying-glass';
                                
                                if($hasFile) {
                                    $fileUrl = asset('storage/' . $mainAttachment->file_path);
                                    $btnText = 'Buka Materi';
                                    $btnIcon = 'ph-book-open-text';
                                } elseif($hasLink) {
                                    $fileUrl = $mainAttachment->file_path;
                                    $btnText = 'Buka Link';
                                    $btnIcon = 'ph-link';
                                }
                            @endphp

                            <div class="group relative bg-white border border-slate-100 rounded-[2rem] p-1 shadow-sm hover:shadow-xl hover:shadow-slate-200/50 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 {{ $theme['ring'] }}">
                                <div class="bg-white rounded-[1.8rem] p-6 h-full flex flex-col relative overflow-hidden">
                                    <div class="absolute -right-8 -top-8 w-28 h-28 rounded-full {{ $theme['bg'] }} opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                    <div class="flex justify-between items-start mb-4 relative z-10">
                                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl shadow-sm border {{ $theme['border'] }} {{ $theme['bg'] }} {{ $theme['text'] }}">
                                            <i class="ph-duotone {{ $theme['icon'] }}"></i>
                                        </div>
                                    </div>

                                    <div class="mb-4 relative z-10 flex-grow">
                                        <h4 class="font-bold text-lg text-slate-800 group-hover:text-blue-600 transition-colors line-clamp-2 leading-snug">
                                            {{ $material->title }}
                                        </h4>
                                        <p class="text-xs text-slate-500 mt-2 line-clamp-2">{{ $material->resume ?? 'Tidak ada deskripsi.' }}</p>
                                    </div>

                                    <div class="pt-4 border-t border-slate-100 mt-auto relative z-10 flex gap-2">
                                        @if($hasFile || $hasLink)
                                            <a href="{{ $fileUrl }}" target="_blank" class="flex-1 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                                                <i class="ph-bold {{ $btnIcon }}"></i> {{ $btnText }}
                                            </a>
                                        @endif

                                        <a href="{{ route('students.learning.subject.show', $material->subject_id) }}" class="flex-1 py-2.5 rounded-xl bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-bold text-xs flex items-center justify-center gap-2 transition-colors">
                                            <i class="ph-bold ph-eye"></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @else
            <div class="text-center py-20 bg-white rounded-[3rem] border-2 border-dashed border-slate-200">
                <div class="w-24 h-24 bg-purple-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <i class="ph-duotone ph-files text-5xl text-purple-400"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800">Belum Ada Materi</h3>
            </div>
        @endif
    </div>

    {{-- SCRIPT: CONFIRM TASK LINK --}}
    <script>
        function confirmTaskSubmit(taskId) {
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Pastikan Anda sudah mengerjakan soal di link yang tersedia sebelum menandai ini selesai.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669', // emerald-600
                cancelButtonColor: '#64748b', // slate-500
                confirmButtonText: 'Ya, Selesai',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'px-4 py-2 rounded-xl font-bold',
                    cancelButton: 'px-4 py-2 rounded-xl font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-link-' + taskId).submit();
                }
            });
        }
    </script>
</div>