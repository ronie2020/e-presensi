<x-student-learning-layout>
    {{-- CSS CUSTOM --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div x-data="{ activeTab: 'materi' }" class="min-h-screen bg-elevate-surface pb-20">
        
        {{-- HEADER MATA PELAJARAN --}}
        <div class="animate-enter relative bg-gradient-to-br from-elevate-dark via-elevate-primary to-elevate-dark pb-24 pt-12 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[3rem] shadow-2xl shadow-elevate-dark/20">
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
            <div class="absolute top-0 right-0 w-80 h-80 bg-elevate-accent/10 rounded-full blur-[100px] -mr-20 -mt-20 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-elevate-peach/10 rounded-full blur-[80px] -ml-10 -mb-10 pointer-events-none"></div>
            
            <div class="relative max-w-6xl mx-auto z-10">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    <div class="flex-1">
                        <div class="flex items-center gap-4 mb-4">
                            <a href="{{ route('portal.show', Auth::guard('student')->id()) }}"  class="group flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 backdrop-blur-md border border-white/10 text-white/80 hover:bg-white/20 hover:text-white hover:border-white/30 transition-all active:scale-95 shrink-0 shadow-sm" title="Kembali">
                                <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-0.5 transition-transform"></i>
                            </a>
                            <span class="px-3 py-1 rounded-full bg-elevate-accent/20 border border-elevate-accent/30 text-elevate-accent text-[10px] font-black uppercase tracking-widest backdrop-blur-md flex items-center gap-1.5">
                                <i class="ph-fill ph-student"></i>
                                {{ Auth::guard('student')->user()->schoolClass->name ?? 'Kelas Umum' }}
                            </span>
                        </div>

                        <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight leading-tight mb-2">
                            {{ $subject->name }}
                        </h1>
                        <p class="text-elevate-soft text-sm md:text-base max-w-xl leading-relaxed font-medium opacity-90">
                            Selamat belajar! Akses materi dan kerjakan tugas yang tersedia di bawah ini.
                        </p>
                    </div>
                    
                    <div class="flex gap-3 w-full md:w-auto overflow-x-auto no-scrollbar pb-2 md:pb-0">
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px] shadow-lg flex-1 md:flex-none">
                            <p class="text-[10px] text-elevate-soft font-bold uppercase tracking-widest mb-1 opacity-80">Materi</p>
                            <p class="text-2xl font-black text-elevate-accent">{{ $materials->count() }}</p>
                        </div>
                        <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px] shadow-lg flex-1 md:flex-none">
                            <p class="text-[10px] text-elevate-soft font-bold uppercase tracking-widest mb-1 opacity-80">Tugas</p>
                            <p class="text-2xl font-black text-elevate-peach-light">{{ $assignments->count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
            
            {{-- ALERT MESSAGES --}}
            @if(session('success'))
                <div class="animate-enter mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 px-5 py-4 rounded-[1.5rem] flex items-center gap-3 shadow-lg shadow-emerald-900/5">
                    <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600"><i class="ph-fill ph-check-circle text-xl"></i></div>
                    <span class="font-bold text-sm">{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="animate-enter mb-6 bg-elevate-peach-light/30 border border-elevate-peach text-elevate-peach-dark px-5 py-4 rounded-[1.5rem] flex items-center gap-3 shadow-lg shadow-elevate-peach/10">
                    <div class="bg-elevate-peach p-2 rounded-xl text-white"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                    <span class="font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            {{-- TAB MENU ELEVATE --}}
            <div class="bg-white p-2 rounded-[1.5rem] shadow-xl shadow-elevate-dark/5 border border-elevate-soft flex gap-2 mb-8 overflow-x-auto no-scrollbar animate-enter">
                <button @click="activeTab = 'materi'" 
                        :class="activeTab === 'materi' 
                            ? 'bg-elevate-dark text-white shadow-lg shadow-elevate-dark/20' 
                            : 'text-elevate-dark/60 hover:bg-elevate-soft hover:text-elevate-dark'"
                        class="flex-1 min-w-[140px] py-3.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2.5">
                    <i class="ph-bold ph-book-open-text text-lg" :class="activeTab === 'materi' ? 'text-elevate-accent' : ''"></i>
                    Materi Belajar
                </button>
                
                <button @click="activeTab = 'tugas'" 
                        :class="activeTab === 'tugas' 
                            ? 'bg-elevate-primary text-white shadow-lg shadow-elevate-primary/30' 
                            : 'text-elevate-dark/60 hover:bg-elevate-soft hover:text-elevate-dark'"
                        class="flex-1 min-w-[140px] py-3.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2.5 relative">
                    <i class="ph-bold ph-pencil-simple-line text-lg" :class="activeTab === 'tugas' ? 'text-elevate-peach-light' : ''"></i>
                    Tugas & PR
                    @if($assignments->where('deadline', '>', now())->count() > 0)
                        <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-elevate-peach opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-elevate-peach-dark border border-white"></span>
                        </span>
                    @endif
                </button>
            </div>

            {{-- KONTEN MATERI --}}
            <div x-show="activeTab === 'materi'" class="space-y-6" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @if($materials->isEmpty())
                    <div class="animate-enter text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-elevate-soft group hover:border-elevate-accent transition-colors">
                        <div class="w-24 h-24 bg-elevate-soft/50 rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-dark/30 group-hover:text-elevate-primary group-hover:bg-elevate-soft transition-all duration-500 transform group-hover:scale-110">
                            <i class="ph-duotone ph-folder-notch-open text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-elevate-dark">Belum Ada Materi</h3>
                        <p class="text-elevate-dark/60 font-medium mt-1">Guru belum mengunggah materi pelajaran.</p>
                    </div>
                @else
                    @foreach($materials as $index => $item)
                        <div class="animate-enter bg-white rounded-[2rem] shadow-sm border border-elevate-soft overflow-hidden hover:shadow-xl hover:shadow-elevate-primary/5 transition-all duration-300 group" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                            <div class="p-6 md:p-8 border-b border-elevate-soft bg-gradient-to-r from-white to-elevate-soft/30">
                                <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                    <div class="flex items-start gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-elevate-soft text-elevate-primary flex items-center justify-center shrink-0 border border-elevate-soft shadow-sm group-hover:bg-elevate-primary group-hover:text-white transition-colors duration-300">
                                            <i class="ph-duotone ph-book-bookmark text-2xl"></i>
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-xl text-elevate-dark leading-tight group-hover:text-elevate-primary transition-colors">{{ $item->title }}</h3>
                                            <div class="flex flex-wrap items-center gap-3 mt-2 text-xs font-bold text-elevate-dark/50">
                                                <span class="flex items-center gap-1 bg-elevate-soft px-2 py-1 rounded-md text-elevate-dark/70 border border-elevate-soft/50">
                                                    <i class="ph-fill ph-calendar-blank"></i> {{ $item->created_at->format('d M Y') }}
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <i class="ph-fill ph-clock"></i> {{ $item->created_at->format('H:i') }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="self-start bg-elevate-soft/80 text-elevate-primary text-[10px] font-black px-3 py-1.5 rounded-lg uppercase tracking-widest border border-elevate-soft">
                                        Materi Pembelajaran
                                    </span>
                                </div>
                            </div>
                            <div class="p-6 md:p-8">
                                @if($item->resume)
                                    <div class="prose prose-sm max-w-none text-elevate-dark/80 mb-8 bg-elevate-soft/30 p-6 rounded-2xl border border-elevate-soft leading-relaxed relative">
                                        <div class="absolute -top-3 left-6 bg-white px-3 py-1 rounded-lg border border-elevate-soft shadow-sm text-[10px] font-bold text-elevate-primary uppercase tracking-widest flex items-center gap-1">
                                            <i class="ph-bold ph-info"></i> Ringkasan
                                        </div>
                                        {!! nl2br(e($item->resume)) !!}
                                    </div>
                                @elseif($item->description)
                                    <p class="text-elevate-dark/80 mb-8 italic pl-4 border-l-4 border-elevate-soft py-2">{{ $item->description }}</p>
                                @endif
                                @if($item->attachments->count() > 0)
                                    <h4 class="font-black text-elevate-dark text-xs uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <i class="ph-fill ph-paperclip text-lg text-elevate-accent"></i> Lampiran & File
                                    </h4>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        @foreach($item->attachments as $att)
                                            <div class="flex items-center gap-4 p-4 rounded-2xl border border-elevate-soft bg-white hover:border-elevate-accent hover:shadow-lg hover:shadow-elevate-primary/5 transition-all group/file cursor-pointer relative overflow-hidden"
                                                 onclick="window.open('{{ $att->file_type == 'file' ? asset('storage/'.$att->file_path) : $att->file_path }}', '_blank')">
                                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 shadow-sm transition-all duration-300
                                                    {{ $att->file_type == 'file' ? 'bg-elevate-peach-light/30 text-elevate-peach-dark group-hover/file:bg-elevate-peach-dark group-hover/file:text-white' : ($att->file_type == 'video' ? 'bg-elevate-peach/20 text-elevate-peach group-hover/file:bg-elevate-peach group-hover/file:text-white' : 'bg-elevate-soft text-elevate-primary group-hover/file:bg-elevate-primary group-hover/file:text-white') }}">
                                                    @if($att->file_type == 'file') <i class="ph-duotone ph-file-pdf text-3xl"></i>
                                                    @elseif($att->file_type == 'video') <i class="ph-duotone ph-youtube-logo text-3xl"></i>
                                                    @else <i class="ph-duotone ph-link text-3xl"></i> @endif
                                                </div>
                                                <div class="flex-1 min-w-0 z-10">
                                                    <p class="text-sm font-bold text-elevate-dark truncate group-hover/file:text-elevate-primary transition-colors">
                                                        {{ $att->file_name ?? 'Lampiran Materi' }}
                                                    </p>
                                                    <p class="text-[10px] text-elevate-dark/40 uppercase font-black tracking-wider mt-1 flex items-center gap-1">
                                                        {{ $att->file_type }} <i class="ph-bold ph-arrow-up-right text-xs opacity-0 group-hover/file:opacity-100 transition-opacity"></i>
                                                    </p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            {{-- KONTEN TUGAS --}}
            <div x-show="activeTab === 'tugas'" class="space-y-6" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
                @if($assignments->isEmpty())
                    <div class="animate-enter text-center py-20 bg-white rounded-[2.5rem] border-2 border-dashed border-elevate-soft">
                        <div class="w-24 h-24 bg-elevate-soft/50 rounded-full flex items-center justify-center mx-auto mb-6 text-elevate-dark/30">
                            <i class="ph-duotone ph-confetti text-5xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-elevate-dark">Tidak Ada Tugas</h3>
                        <p class="text-elevate-dark/60 font-medium mt-1">Hore! Kamu bebas dari tugas untuk saat ini.</p>
                    </div>
                 @else
                    @foreach($assignments as $index => $task)
                        @php
                            $mySubmission = $task->submissions->first(); 
                            $isLate = now() > $task->deadline;
                            $deadlineFormatted = $task->deadline->translatedFormat('l, d F Y • H:i');
                            
                            $typeIcon = 'ph-clipboard-text';
                            $typeColor = 'text-elevate-primary bg-elevate-soft border-elevate-soft';
                            $typeLabel = 'Tugas File';
                            
                            if($task->assignment_type == 'quiz') {
                                $typeIcon = 'ph-list-checks';
                                $typeColor = 'text-purple-600 bg-purple-50 border-purple-100';
                                $typeLabel = 'Kuis Online';
                            } elseif($task->assignment_type == 'link') {
                                $typeIcon = 'ph-link';
                                $typeColor = 'text-elevate-peach-dark bg-elevate-peach-light/30 border-elevate-peach/30';
                                $typeLabel = 'Tugas Link';
                            } elseif($task->assignment_type == 'interactive_video') {
                                $typeIcon = 'ph-youtube-logo';
                                $typeColor = 'text-red-600 bg-red-50 border-red-100';
                                $typeLabel = 'Video Interaktif';
                            }
                        @endphp

                        <div class="animate-enter bg-white rounded-[2rem] shadow-sm border border-elevate-soft overflow-hidden hover:shadow-xl hover:shadow-elevate-primary/10 transition-all duration-300 group hover:-translate-y-1" 
                             style="animation-delay: {{ ($index + 1) * 100 }}ms" 
                             x-data="{ openUpload: false, submissionType: 'file' }">
                            
                            <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-start justify-between gap-6 relative">
                                {{-- Status Stripe --}}
                                <div class="absolute left-0 top-8 bottom-8 w-1.5 rounded-r-full {{ $mySubmission ? 'bg-emerald-500' : ($isLate ? 'bg-elevate-peach-dark' : 'bg-elevate-soft') }}"></div>

                                <div class="flex items-start gap-5 pl-4">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 {{ $typeColor }} border shadow-sm">
                                        <i class="ph-duotone {{ $typeIcon }} text-3xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex flex-wrap items-center gap-2 mb-2">
                                            <span class="text-[10px] font-black uppercase tracking-widest px-2 py-1 rounded-md bg-elevate-soft text-elevate-dark/60 border border-elevate-soft/50">{{ $typeLabel }}</span>
                                            @if($isLate)
                                                <span class="bg-elevate-peach-light/30 text-elevate-peach-dark px-2 py-1 rounded-md text-[10px] font-black uppercase tracking-widest border border-elevate-peach/30 flex items-center gap-1">
                                                    <i class="ph-fill ph-warning-circle"></i> Terlewat
                                                </span>
                                            @endif
                                        </div>
                                        <h3 class="font-bold text-elevate-dark text-xl group-hover:text-elevate-primary transition-colors leading-tight mb-2">{{ $task->title }}</h3>
                                        <div class="flex items-center gap-4 text-xs font-bold text-elevate-dark/50">
                                            <span class="flex items-center gap-1.5 {{ $isLate ? 'text-elevate-peach-dark' : '' }}">
                                                <i class="ph-fill ph-clock"></i> Deadline: {{ $deadlineFormatted }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Status Submission --}}
                                <div class="shrink-0 pl-4 md:pl-0 border-l-2 md:border-l-0 border-elevate-soft md:text-right">
                                    @if($mySubmission)
                                        @if(isset($mySubmission->grade))
                                            <div class="flex flex-col md:items-end">
                                                <span class="text-[10px] uppercase font-black text-elevate-dark/40 mb-1 tracking-wider">Nilai Kamu</span>
                                                <div class="px-6 py-2 bg-elevate-dark text-elevate-accent rounded-xl text-2xl font-black shadow-lg shadow-elevate-dark/20 border border-elevate-primary">
                                                    {{ $mySubmission->grade }}
                                                </div>
                                            </div>
                                        @else
                                            <div class="inline-flex items-center gap-2 px-4 py-2 bg-elevate-soft/80 text-elevate-primary rounded-xl border border-elevate-soft shadow-sm">
                                                <div class="w-2 h-2 rounded-full bg-elevate-primary animate-pulse"></div>
                                                <span class="text-xs font-bold uppercase tracking-wide">Menunggu Dinilai</span>
                                            </div>
                                        @endif
                                    @else
                                        @if($isLate && !$task->allow_late_submission)
                                            <div class="px-5 py-2.5 bg-slate-100 text-slate-500 rounded-xl font-bold text-xs uppercase border border-slate-200">
                                                <i class="ph-fill ph-lock-key"></i> Ditutup
                                            </div>
                                        @else
                                            <div class="px-5 py-2.5 bg-yellow-50 text-yellow-700 rounded-xl border border-yellow-100 font-bold text-xs uppercase flex items-center gap-2 shadow-sm">
                                                <i class="ph-fill ph-warning-circle"></i> Belum Dikerjakan
                                            </div>
                                        @endif
                                    @endif
                                </div>
                            </div>

                             <div class="px-6 md:px-8 pb-8">
                                <div class="prose prose-sm max-w-none text-elevate-dark/80 bg-elevate-soft/30 p-6 rounded-2xl border border-elevate-soft mb-6 leading-relaxed">
                                    {{ $task->description }}
                                </div>

                                {{-- ACTION BUTTONS --}}
                                @if(!$mySubmission)
                                    @if($isLate && !$task->allow_late_submission)
                                        {{-- Disabled State --}}
                                    @else
                                        {{-- KUIS ONLINE --}}
                                        @if($task->assignment_type == 'quiz')
                                            <a href="{{ route('students.learning.assignment.quiz', $task->id) }}" class="w-full py-4 bg-elevate-primary text-white font-bold rounded-2xl hover:bg-elevate-dark transition shadow-lg shadow-elevate-primary/20 flex items-center justify-center gap-2 group/btn relative overflow-hidden active:scale-[0.98]">
                                                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                                                <i class="ph-bold ph-play-circle text-xl text-elevate-accent"></i>
                                                <span class="relative">Mulai Kerjakan Kuis</span>
                                            </a>
                                            
                                        {{-- VIDEO INTERAKTIF (BARU) --}}
                                        @elseif($task->assignment_type == 'interactive_video')
                                            <a href="{{ route('students.learning.play', $subject->id) }}" class="w-full py-4 bg-red-600 text-white font-bold rounded-2xl hover:bg-red-700 transition shadow-lg shadow-red-600/20 flex items-center justify-center gap-2 group/btn relative overflow-hidden active:scale-[0.98]">
                                                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                                                <i class="ph-bold ph-play-circle text-xl"></i>
                                                <span class="relative">Buka Player Interaktif</span>
                                            </a>

                                        {{-- TUGAS LINK (GURU) --}}
                                        @elseif($task->assignment_type == 'link')
                                            <div class="flex flex-col sm:flex-row gap-4">
                                                <a href="{{ $task->link_url }}" target="_blank" class="flex-1 py-3.5 bg-elevate-peach-light/30 text-elevate-peach-dark border border-elevate-peach/30 font-bold rounded-2xl hover:bg-elevate-peach/20 transition flex items-center justify-center gap-2 active:scale-[0.98]">
                                                    <i class="ph-bold ph-arrow-square-out text-lg"></i> Buka Link Soal
                                                </a>
                                                <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" class="flex-1" id="form-complete-{{ $task->id }}">
                                                    @csrf
                                                    <button type="button" onclick="confirmTaskSubmit('{{ $task->id }}')" class="w-full py-3.5 bg-elevate-primary text-white font-bold rounded-2xl hover:bg-elevate-dark transition shadow-lg shadow-elevate-primary/20 flex items-center justify-center gap-2 active:scale-[0.98]">
                                                        <i class="ph-bold ph-check-circle text-lg"></i> Tandai Selesai
                                                    </button>
                                                </form>
                                            </div>

                                        {{-- TUGAS FILE (UPLOAD) --}}
                                        @else
                                            <button @click="openUpload = !openUpload" class="w-full py-4 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition shadow-lg shadow-elevate-dark/20 flex items-center justify-center gap-2 group/btn active:scale-[0.98]">
                                                <i class="ph-bold ph-upload-simple text-xl text-elevate-accent group-hover/btn:-translate-y-1 transition-transform"></i>
                                                <span x-text="openUpload ? 'Batal' : 'Kerjakan Tugas'"></span>
                                            </button>
                                            
                                            <div x-show="openUpload" x-transition class="mt-6 p-6 md:p-8 border-2 border-dashed border-elevate-primary/40 rounded-[2rem] bg-elevate-soft/50">
                                                <h4 class="font-black text-elevate-primary mb-6 flex items-center gap-2 text-sm uppercase tracking-widest"><i class="ph-fill ph-paper-plane-right text-lg"></i> Form Pengumpulan</h4>
                                                
                                                <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    
                                                    {{-- TAB SWITCHER --}}
                                                    <div class="flex p-1 bg-white rounded-xl mb-6 shadow-sm w-fit border border-elevate-soft">
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="submission_type" value="file" class="sr-only" x-model="submissionType">
                                                            <div class="px-5 py-2 rounded-lg text-xs font-bold transition-all duration-300 flex items-center gap-2"
                                                                 :class="submissionType === 'file' ? 'bg-elevate-soft text-elevate-primary' : 'text-elevate-dark/50 hover:text-elevate-dark'">
                                                                <i class="ph-bold ph-file-text"></i> Upload File
                                                            </div>
                                                        </label>
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="submission_type" value="link" class="sr-only" x-model="submissionType">
                                                            <div class="px-5 py-2 rounded-lg text-xs font-bold transition-all duration-300 flex items-center gap-2"
                                                                 :class="submissionType === 'link' ? 'bg-elevate-peach-light/30 text-elevate-peach-dark' : 'text-elevate-dark/50 hover:text-elevate-dark'">
                                                                <i class="ph-bold ph-link"></i> Kirim Link
                                                            </div>
                                                        </label>
                                                    </div>

                                                    <div class="space-y-5">
                                                        {{-- INPUT FILE --}}
                                                        <div x-show="submissionType === 'file'">
                                                            <label class="block text-xs font-bold text-elevate-dark/60 uppercase mb-2 ml-1">File Jawaban (PDF/JPG)</label>
                                                            <input type="file" name="file" class="block w-full text-sm text-elevate-dark/60 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-primary file:text-white hover:file:bg-elevate-dark border border-elevate-soft rounded-2xl bg-white shadow-sm transition cursor-pointer">
                                                        </div>

                                                        {{-- INPUT LINK --}}
                                                        <div x-show="submissionType === 'link'" style="display: none;">
                                                            <label class="block text-xs font-bold text-elevate-dark/60 uppercase mb-2 ml-1">Link Tugas (G-Drive / Youtube / Blog)</label>
                                                            <input type="url" name="link_url" placeholder="https://..." class="block w-full p-4 text-sm font-medium border border-elevate-soft rounded-2xl focus:border-elevate-primary focus:ring-elevate-primary/30 shadow-sm bg-elevate-soft/30 focus:bg-white text-elevate-dark">
                                                            <p class="text-[10px] text-elevate-dark/40 mt-2 ml-1 flex items-center gap-1"><i class="ph-fill ph-info"></i> Pastikan link dapat diakses publik (Anyone with the link).</p>
                                                        </div>

                                                        <div>
                                                            <label class="block text-xs font-bold text-elevate-dark/60 uppercase mb-2 ml-1">Catatan (Opsional)</label>
                                                            <textarea name="student_note" rows="3" class="w-full rounded-2xl border-elevate-soft focus:border-elevate-primary focus:ring-elevate-primary/30 text-sm p-4 placeholder:text-elevate-dark/30 transition-shadow bg-elevate-soft/30 focus:bg-white text-elevate-dark" placeholder="Tulis pesan untuk guru di sini..."></textarea>
                                                        </div>
                                                        <button type="submit" class="w-full py-4 bg-elevate-primary text-white font-bold rounded-2xl hover:bg-elevate-dark shadow-lg shadow-elevate-primary/30 transition flex items-center justify-center gap-2 active:scale-[0.98]">
                                                            <i class="ph-bold ph-paper-plane-right text-lg"></i> Kirim Jawaban
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    {{-- INFO PENGUMPULAN --}}
                                    <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-6">
                                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                            <div class="flex items-center gap-4">
                                                <div class="bg-emerald-100 text-emerald-600 p-3 rounded-xl shadow-sm">
                                                    <i class="ph-fill ph-check-fat text-2xl"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-emerald-900">Tugas Berhasil Terkirim</p>
                                                    <div class="flex flex-wrap gap-2 mt-1">
                                                        <p class="text-xs text-emerald-600 font-medium">Dikirim pada {{ $mySubmission->submitted_at->translatedFormat('d F Y • H:i') }}</p>
                                                        
                                                        @if($mySubmission->link_url)
                                                            <a href="{{ $mySubmission->link_url }}" target="_blank" class="text-[10px] font-bold bg-white px-2 py-0.5 rounded border border-emerald-200 text-emerald-600 hover:text-emerald-800 flex items-center gap-1">
                                                                <i class="ph-bold ph-link"></i> Link Tugas
                                                            </a>
                                                        @elseif($mySubmission->file_path)
                                                            <a href="{{ route('students.learning.material.download', $task->id) }}" class="text-[10px] font-bold bg-white px-2 py-0.5 rounded border border-emerald-200 text-emerald-600 hover:text-emerald-800 flex items-center gap-1">
                                                                <i class="ph-bold ph-file"></i> File Tugas
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            @if(!$mySubmission->grade && $task->assignment_type == 'file_upload')
                                                <button @click="openUpload = !openUpload" class="text-xs font-bold text-elevate-primary bg-white border border-elevate-soft px-4 py-2 rounded-xl hover:bg-elevate-soft shadow-sm flex items-center gap-2 transition-all">
                                                    <i class="ph-bold ph-pencil-simple"></i> Edit Jawaban
                                                </button>
                                            @endif
                                        </div>

                                        {{-- FORM EDIT JAWABAN --}}
                                        @if(!$mySubmission->grade && $task->assignment_type == 'file_upload')
                                            <div x-show="openUpload" x-transition class="mt-6 p-6 border-t border-emerald-100">
                                                <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="flex p-1 bg-white rounded-xl mb-6 shadow-sm w-fit border border-elevate-soft">
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="submission_type" value="file" class="sr-only" x-model="submissionType">
                                                            <div class="px-5 py-2 rounded-lg text-xs font-bold transition-all duration-300 flex items-center gap-2" :class="submissionType === 'file' ? 'bg-elevate-soft text-elevate-primary' : 'text-elevate-dark/50 hover:text-elevate-dark'">
                                                                <i class="ph-bold ph-file-text"></i> File
                                                            </div>
                                                        </label>
                                                        <label class="cursor-pointer">
                                                            <input type="radio" name="submission_type" value="link" class="sr-only" x-model="submissionType">
                                                            <div class="px-5 py-2 rounded-lg text-xs font-bold transition-all duration-300 flex items-center gap-2" :class="submissionType === 'link' ? 'bg-elevate-peach-light/30 text-elevate-peach-dark' : 'text-elevate-dark/50 hover:text-elevate-dark'">
                                                                <i class="ph-bold ph-link"></i> Link
                                                            </div>
                                                        </label>
                                                    </div>

                                                    <div class="space-y-4">
                                                        <div x-show="submissionType === 'file'">
                                                            <input type="file" name="file" class="block w-full text-sm text-elevate-dark/60 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-soft file:text-elevate-primary hover:file:bg-elevate-primary hover:file:text-white border border-elevate-soft bg-white">
                                                        </div>
                                                        <div x-show="submissionType === 'link'" style="display: none;">
                                                            <input type="url" name="link_url" placeholder="Update Link Tugas..." class="block w-full p-3 text-sm border border-elevate-soft bg-elevate-soft/30 focus:bg-white rounded-xl text-elevate-dark">
                                                        </div>
                                                        <textarea name="student_note" rows="2" class="w-full rounded-xl border-elevate-soft bg-elevate-soft/30 focus:bg-white text-sm p-3 placeholder:text-elevate-dark/40 text-elevate-dark" placeholder="Update catatan...">{{ $mySubmission->student_note }}</textarea>
                                                        <button type="submit" class="w-full py-3 bg-elevate-primary text-white font-bold rounded-xl text-sm hover:bg-elevate-dark">Simpan Perubahan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif

                                        @if($mySubmission->teacher_feedback)
                                            <div class="mt-4 bg-white p-5 rounded-2xl border border-emerald-100 shadow-sm relative">
                                                <div class="absolute -top-2 left-8 w-4 h-4 bg-white border-t border-l border-emerald-100 transform rotate-45"></div>
                                                <p class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-2 flex items-center gap-1">
                                                    <i class="ph-bold ph-chat-text"></i> Catatan Guru
                                                </p>
                                                <p class="text-slate-700 text-sm leading-relaxed">"{{ $mySubmission->teacher_feedback }}"</p>
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

    <script>
        function confirmTaskSubmit(taskId) {
            Swal.fire({
                title: 'Tandai Selesai?',
                text: "Pastikan Anda sudah mengerjakan soal di link yang tersedia sebelum menandai ini selesai.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0d52a1', // elevate-primary
                cancelButtonColor: '#2c3f61', // elevate-dark
                confirmButtonText: 'Ya, Tandai Selesai',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans',
                    confirmButton: 'px-6 py-3 rounded-xl font-bold shadow-lg shadow-elevate-primary/20',
                    cancelButton: 'px-6 py-3 rounded-xl font-bold hover:bg-elevate-soft text-elevate-dark/80'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-complete-' + taskId).submit();
                }
            });
        }
    </script>
</x-student-learning-layout>