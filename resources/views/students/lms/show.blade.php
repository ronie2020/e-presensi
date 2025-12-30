<x-student-learning-layout>
    {{-- Hapus @extends dan @section, ganti dengan pembungkus component ini --}}
    
    <div x-data="{ activeTab: 'materi' }" class="min-h-screen bg-slate-50/50">
        
        {{-- HEADER MATA PELAJARAN --}}
        <div class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 pb-20 pt-10 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[2.5rem] shadow-2xl shadow-blue-900/20">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500 opacity-10 rounded-full blur-3xl -mr-16 -mt-16"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-yellow-500 opacity-5 rounded-full blur-2xl -ml-10 -mb-10"></div>

            <div class="relative max-w-5xl mx-auto flex items-center justify-between">
                <div class="flex items-center gap-6">
                    <a href="{{ route('students.learning.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-xl bg-white/5 backdrop-blur-md border border-white/10 text-blue-100 hover:bg-white/10 hover:text-yellow-400 hover:border-yellow-400/50 transition-all duration-300">
                        <i class="ph-bold ph-arrow-left text-xl group-hover:-translate-x-1 transition-transform"></i>
                    </a>
                    
                    <div class="text-white">
                        <div class="flex items-center gap-2 mb-1 opacity-90">
                            <span class="px-2.5 py-0.5 rounded text-[10px] font-bold bg-blue-800/50 border border-blue-700/50 text-blue-200 uppercase tracking-wider shadow-sm">Kelas Online</span>
                            <i class="ph-fill ph-circle text-[6px] text-yellow-500"></i>
                            <span class="text-xs font-medium text-blue-100">{{ Auth::guard('student')->user()->schoolClass->name ?? 'Umum' }}</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight text-white drop-shadow-sm">{{ $subject->name }}</h1>
                    </div>
                </div>
                
                <div class="hidden md:block opacity-10 transform rotate-12">
                    <i class="ph-duotone ph-books text-9xl text-white"></i>
                </div>
            </div>
        </div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10 pb-20">
            
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                    <div class="bg-emerald-100 p-1.5 rounded-full"><i class="ph-fill ph-check-circle text-xl text-emerald-600"></i></div>
                    <span class="font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                    <div class="bg-rose-100 p-1.5 rounded-full"><i class="ph-fill ph-warning-circle text-xl text-rose-600"></i></div>
                    <span class="font-medium text-sm">{{ session('error') }}</span>
                </div>
            @endif

            {{-- TAB MENU --}}
            <div class="bg-white p-2 rounded-2xl shadow-lg shadow-slate-200/50 border border-slate-100 flex gap-2 mb-8">
                <button @click="activeTab = 'materi'" 
                        :class="activeTab === 'materi' 
                            ? 'bg-slate-800 text-yellow-400 shadow-md shadow-slate-800/20' 
                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                        class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-book-open-text text-lg"></i>
                    Materi Belajar
                </button>
                
                <button @click="activeTab = 'tugas'" 
                        :class="activeTab === 'tugas' 
                            ? 'bg-blue-900 text-yellow-400 shadow-md shadow-blue-900/20' 
                            : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                        class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2 relative">
                    <i class="ph-bold ph-pencil-simple-line text-lg"></i>
                    Tugas & PR
                    @if($assignments->where('deadline', '>', now())->count() > 0)
                        <span class="absolute top-2 right-2 md:right-auto md:ml-2 md:relative md:top-0 flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                        </span>
                    @endif
                </button>
            </div>

            {{-- KONTEN MATERI --}}
            <div x-show="activeTab === 'materi'" class="space-y-8" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                
                @if($materials->isEmpty())
                    <div class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-slate-200 group hover:border-blue-300 transition-colors">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 group-hover:text-blue-500 group-hover:bg-blue-50 transition-all">
                            <i class="ph-duotone ph-folder-notch-open text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Belum Ada Materi</h3>
                        <p class="text-slate-500 text-sm mt-1">Guru belum mengunggah materi pelajaran.</p>
                    </div>
                @else
                    @foreach($materials as $item)
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6 hover:shadow-md transition-shadow duration-300">
                            
                            <div class="p-6 border-b border-slate-50 bg-slate-50/50">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="font-bold text-xl text-slate-800 leading-tight">{{ $item->title }}</h3>
                                        <p class="text-xs text-slate-400 mt-2 font-medium flex items-center gap-1.5">
                                            <i class="ph-fill ph-calendar-blank text-blue-900"></i> {{ $item->created_at->format('d M Y') }}
                                            <span class="text-slate-300">•</span>
                                            <i class="ph-fill ph-clock text-blue-900"></i> {{ $item->created_at->format('H:i') }}
                                        </p>
                                    </div>
                                    <span class="bg-blue-50 text-blue-800 text-[10px] font-bold px-3 py-1.5 rounded-lg uppercase tracking-wide border border-blue-100">
                                        Materi
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                @if($item->resume)
                                    <div class="prose prose-sm max-w-none text-slate-600 mb-8 leading-relaxed bg-slate-50 p-6 rounded-2xl border border-slate-100/80">
                                        <h4 class="text-xs font-bold text-blue-900 uppercase mb-4 tracking-widest flex items-center gap-2 border-b border-slate-200 pb-2">
                                            <i class="ph-bold ph-read-cv-logo text-lg"></i> Penjelasan Materi
                                        </h4>
                                        {!! nl2br(e($item->resume)) !!}
                                    </div>
                                @else
                                    @if($item->description)
                                        <p class="text-slate-600 mb-6 italic pl-4 border-l-4 border-slate-200">{{ $item->description }}</p>
                                    @endif
                                @endif

                                @if($item->attachments->count() > 0)
                                    <h4 class="font-bold text-slate-800 text-sm mb-4 flex items-center gap-2">
                                        <div class="w-6 h-6 rounded bg-slate-800 text-yellow-400 flex items-center justify-center text-xs">
                                            <i class="ph-fill ph-paperclip"></i> 
                                        </div>
                                        Lampiran ({{ $item->attachments->count() }})
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        @foreach($item->attachments as $att)
                                            <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 bg-white hover:border-blue-400 hover:shadow-lg hover:shadow-blue-900/5 transition-all group cursor-pointer"
                                                 onclick="window.open('{{ $att->file_type == 'file' ? asset('storage/'.$att->file_path) : $att->file_path }}', '_blank')">
                                                
                                                <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm transition-colors
                                                    {{ $att->file_type == 'file' ? 'bg-orange-50 text-orange-600 group-hover:bg-orange-100' : ($att->file_type == 'video' ? 'bg-rose-50 text-rose-600 group-hover:bg-rose-100' : 'bg-blue-50 text-blue-600 group-hover:bg-blue-100') }}">
                                                    @if($att->file_type == 'file') <i class="ph-duotone ph-file-pdf text-2xl"></i>
                                                    @elseif($att->file_type == 'video') <i class="ph-duotone ph-youtube-logo text-2xl"></i>
                                                    @else <i class="ph-duotone ph-link text-2xl"></i> @endif
                                                </div>

                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-bold text-slate-700 truncate group-hover:text-blue-800 transition-colors">
                                                        {{ $att->file_name ?? 'Lampiran Materi' }}
                                                    </p>
                                                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wide mt-0.5">{{ $att->file_type }}</p>
                                                </div>

                                                <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 group-hover:bg-blue-900 group-hover:text-yellow-400 group-hover:border-blue-900 transition-all shadow-sm">
                                                    <i class="ph-bold {{ $att->file_type == 'file' ? 'ph-download-simple' : 'ph-arrow-up-right' }}"></i>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-slate-400 italic">Tidak ada file lampiran.</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- KONTEN TUGAS --}}
            <div x-show="activeTab === 'tugas'" class="space-y-6" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @if($assignments->isEmpty())
                    <div class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-slate-200 group">
                        <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 group-hover:text-emerald-500 group-hover:bg-emerald-50 transition-all">
                            <i class="ph-duotone ph-confetti text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-700">Tidak Ada Tugas</h3>
                        <p class="text-slate-500 text-sm mt-1">Hore! Kamu bebas dari tugas untuk saat ini.</p>
                    </div>
                @else
                    @foreach($assignments as $task)
                        @php
                            $mySubmission = $task->submissions->first(); 
                            $isLate = now() > $task->deadline;
                            $deadlineFormatted = $task->deadline->translatedFormat('l, d F Y • H:i');
                            
                            $typeIcon = 'ph-clipboard-text';
                            $typeColor = 'text-indigo-700 bg-indigo-50';
                            $typeLabel = 'Tugas File';
                            
                            if($task->assignment_type == 'quiz') {
                                $typeIcon = 'ph-list-checks';
                                $typeColor = 'text-purple-700 bg-purple-50';
                                $typeLabel = 'Kuis Online';
                            } elseif($task->assignment_type == 'link') {
                                $typeIcon = 'ph-link';
                                $typeColor = 'text-amber-700 bg-amber-50';
                                $typeLabel = 'Tugas Link';
                            }
                        @endphp

                        <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 group" x-data="{ openUpload: false }">
                            
                            <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-white to-slate-50/50">
                                <div class="flex items-start gap-4">
                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $typeColor }} ring-1 ring-inset ring-black/5">
                                        <i class="ph-duotone {{ $typeIcon }} text-2xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border border-slate-200 bg-white text-slate-500">{{ $typeLabel }}</span>
                                            @if($isLate)
                                                <span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide border border-rose-200">Terlewat</span>
                                            @endif
                                        </div>
                                        <h3 class="font-bold text-slate-900 text-lg group-hover:text-blue-800 transition-colors">{{ $task->title }}</h3>
                                        <div class="flex items-center gap-3 mt-1.5 text-xs font-medium text-slate-500">
                                            <span class="flex items-center gap-1 {{ $isLate ? 'text-rose-600 font-bold' : '' }}">
                                                <i class="ph-fill ph-clock"></i> Deadline: {{ $deadlineFormatted }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0">
                                    @if($mySubmission)
                                        @if(isset($mySubmission->grade))
                                            <div class="flex flex-col items-end">
                                                <span class="text-[10px] uppercase font-bold text-slate-400 mb-1">Nilai Kamu</span>
                                                <div class="px-5 py-2 bg-blue-900 text-yellow-400 rounded-xl text-xl font-black shadow-lg shadow-blue-900/20 border border-blue-800">
                                                    {{ $mySubmission->grade }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-800 rounded-xl border border-blue-100">
                                                <div class="w-2 h-2 rounded-full bg-blue-600 animate-pulse"></div>
                                                <span class="text-xs font-bold uppercase tracking-wide">Menunggu Dinilai</span>
                                            </div>
                                        @endif
                                    @else
                                        @if($isLate && !$task->allow_late_submission)
                                            <div class="px-4 py-2 bg-slate-100 text-slate-500 rounded-xl font-bold text-xs uppercase border border-slate-200">
                                                Ditutup
                                            </div>
                                        @else
                                            <div class="px-4 py-2 bg-amber-50 text-amber-700 rounded-xl border border-amber-100 font-bold text-xs uppercase flex items-center gap-2">
                                                <i class="ph-fill ph-warning-circle"></i> Belum Dikerjakan
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                            <div class="p-6">
                                <div class="prose prose-sm max-w-none text-slate-600 bg-slate-50 p-5 rounded-2xl border border-slate-200/60 mb-6">
                                    {{ $task->description }}
                                    @if($task->assignment_type == 'quiz')
                                        <div class="mt-4 pt-4 border-t border-slate-200 text-xs font-bold text-slate-500 flex gap-6">
                                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-timer text-purple-600"></i> {{ $task->duration_minutes ?? 60 }} Menit</span>
                                            <span class="flex items-center gap-1.5"><i class="ph-bold ph-list-numbers text-purple-600"></i> {{ $task->questions->count() }} Soal</span>
                                        </div>
                                    @endif
                                </div>

                                @if(!$mySubmission)
                                    {{-- BELUM MENGUMPULKAN --}}
                                    @if($isLate && !$task->allow_late_submission)
                                        <div class="w-full py-4 bg-slate-100 text-slate-400 text-center rounded-xl text-sm font-bold border border-slate-200 flex flex-col items-center justify-center gap-1">
                                            <i class="ph-duotone ph-lock-key text-2xl mb-1"></i>
                                            <span>Maaf, waktu pengumpulan tugas sudah berakhir.</span>
                                        </div>
                                    @else
                                        
                                        {{-- CASE 1: KUIS ONLINE --}}
                                        @if($task->assignment_type == 'quiz')
                                            <a href="{{ route('students.learning.assignment.quiz', $task->id) }}" class="w-full py-3.5 bg-purple-700 text-white font-bold rounded-xl hover:bg-purple-800 transition shadow-lg shadow-purple-200 flex items-center justify-center gap-2 group/btn relative overflow-hidden">
                                                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                                                <i class="ph-bold ph-play-circle text-lg group-hover/btn:scale-110 transition-transform"></i>
                                                <span class="relative">Mulai Kerjakan Kuis</span>
                                            </a>

                                        {{-- CASE 2: LINK EKSTERNAL --}}
                                        @elseif($task->assignment_type == 'link')
                                            <div class="flex flex-col sm:flex-row gap-3">
                                                <a href="{{ $task->link_url }}" target="_blank" class="flex-1 py-3 bg-amber-50 text-amber-700 border border-amber-200 font-bold rounded-xl hover:bg-amber-100 transition flex items-center justify-center gap-2">
                                                    <i class="ph-bold ph-arrow-square-out text-lg"></i> Buka Link Soal
                                                </a>
                                                <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg flex items-center justify-center gap-2" onclick="return confirm('Pastikan Anda sudah mengerjakan soal di link tersebut. Tandai selesai?')">
                                                        <i class="ph-bold ph-check-circle text-lg"></i> Tandai Selesai
                                                    </button>
                                                </form>
                                            </div>

                                        {{-- CASE 3: UPLOAD FILE (DEFAULT) --}}
                                        @else
                                            <button @click="openUpload = !openUpload" class="w-full py-3.5 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 transition shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 group/btn">
                                                <i class="ph-bold ph-upload-simple text-lg text-yellow-400 group-hover/btn:-translate-y-1 transition-transform"></i>
                                                <span x-text="openUpload ? 'Batal Upload' : 'Kerjakan & Upload File'"></span>
                                            </button>
                                            
                                            <div x-show="openUpload" x-transition class="mt-4 p-6 border-2 border-dashed border-blue-200 rounded-2xl bg-blue-50/30">
                                                <h4 class="font-bold text-blue-900 mb-4 flex items-center gap-2"><i class="ph-fill ph-cloud-arrow-up"></i> Form Pengumpulan</h4>
                                                <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">File Jawaban (PDF/JPG)</label>
                                                            <input type="file" name="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-blue-900 file:text-yellow-400 hover:file:bg-blue-800 border border-slate-300 rounded-xl bg-white shadow-sm transition cursor-pointer">
                                                        </div>
                                                        <div>
                                                            <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan (Opsional)</label>
                                                            <textarea name="student_note" rows="2" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm placeholder:text-slate-400" placeholder="Pesan untuk guru..."></textarea>
                                                        </div>
                                                        <button type="submit" class="w-full py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 shadow-md transition flex items-center justify-center gap-2">
                                                            <i class="ph-bold ph-paper-plane-right"></i> Kirim Jawaban
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    {{-- SUDAH MENGUMPULKAN --}}
                                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-5">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-emerald-100 text-emerald-700 p-2.5 rounded-xl">
                                                    <i class="ph-fill ph-check-fat text-xl"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-emerald-800 text-sm">Tugas Terkirim</p>
                                                    <p class="text-xs text-emerald-600 font-medium">{{ $mySubmission->submitted_at->translatedFormat('d F Y • H:i') }}</p>
                                                </div>
                                            </div>
                                            @if(!$mySubmission->grade && $task->assignment_type == 'file_upload')
                                                <button @click="openUpload = !openUpload" class="text-xs font-bold text-blue-600 hover:text-blue-800 hover:underline flex items-center gap-1 transition-colors">
                                                    <i class="ph-bold ph-pencil-simple"></i> Edit / Kirim Ulang
                                                </button>
                                            @endif
                                        </div>

                                        @if($mySubmission->teacher_feedback)
                                            <div class="mt-3 bg-white p-5 rounded-xl border border-emerald-100 shadow-sm relative">
                                                <div class="absolute -top-2 left-7 w-4 h-4 bg-white border-t border-l border-emerald-100 transform rotate-45"></div>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2">Catatan Guru:</p>
                                                <p class="text-slate-700 text-sm italic leading-relaxed">"{{ $mySubmission->teacher_feedback }}"</p>
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

        </div>
    </div>

    <style>
        /* Custom Animation */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fadeInDown 0.3s ease-out forwards;
        }
    </style>
</x-student-learning-layout>