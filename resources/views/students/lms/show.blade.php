@extends('layouts.student')

@section('content')
<div x-data="{ activeTab: 'materi' }" class="min-h-screen bg-slate-50/50">
    
    <!-- 1. HEADER KELAS (Gradient Style) -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 pb-20 pt-10 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[2.5rem] shadow-xl">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white opacity-10 rounded-full blur-3xl -mr-16 -mt-16"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-cyan-400 opacity-10 rounded-full blur-2xl -ml-10 -mb-10"></div>

        <div class="relative max-w-5xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-6">
                <!-- Tombol Kembali -->
                <a href="{{ route('students.learning.index') }}" class="group flex items-center justify-center w-12 h-12 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white hover:bg-white hover:text-blue-600 transition-all duration-300">
                    <i class="ph-bold ph-arrow-left text-xl group-hover:-translate-x-1 transition-transform"></i>
                </a>
                
                <!-- Info Mapel -->
                <div class="text-white">
                    <div class="flex items-center gap-2 mb-1 opacity-90">
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-white/20 border border-white/10 uppercase tracking-wider">Kelas Online</span>
                        <i class="ph-fill ph-circle text-[6px]"></i>
                        <span class="text-xs font-medium">{{ Auth::guard('student')->user()->schoolClass->name ?? 'Umum' }}</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">{{ $subject->name }}</h1>
                </div>
            </div>
            
            <!-- Icon Mapel Besar (Dekorasi) -->
            <div class="hidden md:block opacity-20">
                <i class="ph-duotone ph-books text-9xl text-white"></i>
            </div>
        </div>
    </div>

    <!-- 2. MAIN CONTENT CONTAINER -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10 pb-20">
        
        <!-- ALERT NOTIFIKASI -->
        @if(session('success'))
            <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                <div class="bg-emerald-100 p-1.5 rounded-full"><i class="ph-fill ph-check-circle text-xl"></i></div>
                <span class="font-medium text-sm">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in-down">
                <div class="bg-rose-100 p-1.5 rounded-full"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                <span class="font-medium text-sm">{{ session('error') }}</span>
            </div>
        @endif

        <!-- TAB NAVIGASI (Pills Style) -->
        <div class="bg-white p-2 rounded-2xl shadow-lg border border-slate-100 flex gap-2 mb-8">
            <button @click="activeTab = 'materi'" 
                    :class="activeTab === 'materi' ? 'bg-blue-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'"
                    class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2">
                <i class="ph-bold ph-book-open-text text-lg"></i>
                Materi Belajar
            </button>
            <button @click="activeTab = 'tugas'" 
                    :class="activeTab === 'tugas' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-500 hover:bg-slate-50'"
                    class="flex-1 py-3 rounded-xl text-sm font-bold transition-all duration-300 flex items-center justify-center gap-2 relative">
                <i class="ph-bold ph-pencil-simple-line text-lg"></i>
                Tugas & PR
                @if($assignments->where('deadline', '>', now())->count() > 0)
                    <span class="absolute top-2 right-2 md:right-auto md:ml-2 md:relative md:top-0 flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-red-500"></span>
                    </span>
                @endif
            </button>
        </div>

        <!-- === KONTEN TAB: MATERI === -->
        <div x-show="activeTab === 'materi'" class="space-y-8">
            
            @if($materials->isEmpty())
                <div class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="ph-duotone ph-folder-notch-open text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Belum Ada Materi</h3>
                    <p class="text-slate-500 text-sm mt-1">Guru belum mengunggah materi pelajaran.</p>
                </div>
            @else
                @foreach($materials as $item)
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-6">
                        
                        <!-- HEADER MATERI -->
                        <div class="p-6 border-b border-slate-50 bg-slate-50/30">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h3 class="font-bold text-xl text-slate-800">{{ $item->title }}</h3>
                                    <p class="text-xs text-slate-400 mt-1 font-medium flex items-center gap-1">
                                        <i class="ph-fill ph-clock"></i> Diposting {{ $item->created_at->format('d M Y') }}
                                    </p>
                                </div>
                                <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wide border border-blue-200">Materi</span>
                            </div>
                        </div>

                        <div class="p-6">
                            <!-- 1. RESUME / TEKS PEMBELAJARAN -->
                            @if($item->resume)
                                <div class="prose prose-sm max-w-none text-slate-600 mb-8 leading-relaxed bg-slate-50 p-5 rounded-2xl border border-slate-100">
                                    <h4 class="text-xs font-bold text-slate-400 uppercase mb-3 tracking-widest flex items-center gap-1">
                                        <i class="ph-bold ph-read-cv-logo"></i> Penjelasan Materi
                                    </h4>
                                    {!! nl2br(e($item->resume)) !!}
                                </div>
                            @else
                                @if($item->description)
                                    <p class="text-slate-600 mb-6 italic">{{ $item->description }}</p>
                                @endif
                            @endif

                            <!-- 2. DAFTAR LAMPIRAN (ATTACHMENTS) -->
                            @if($item->attachments->count() > 0)
                                <h4 class="font-bold text-slate-800 text-sm mb-3 flex items-center gap-2">
                                    <i class="ph-fill ph-paperclip"></i> Lampiran & Referensi ({{ $item->attachments->count() }})
                                </h4>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    @foreach($item->attachments as $att)
                                        <div class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 bg-white hover:border-blue-300 hover:shadow-md transition-all group">
                                            
                                            <!-- Icon berdasarkan tipe -->
                                            <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 shadow-sm
                                                {{ $att->file_type == 'file' ? 'bg-orange-50 text-orange-600' : ($att->file_type == 'video' ? 'bg-red-50 text-red-600' : 'bg-blue-50 text-blue-600') }}">
                                                @if($att->file_type == 'file') <i class="ph-duotone ph-file-pdf text-2xl"></i>
                                                @elseif($att->file_type == 'video') <i class="ph-duotone ph-youtube-logo text-2xl"></i>
                                                @else <i class="ph-duotone ph-link text-2xl"></i> @endif
                                            </div>

                                            <!-- Info File -->
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-bold text-slate-700 truncate group-hover:text-blue-600 transition-colors">
                                                    {{ $att->file_name ?? 'Lampiran Materi' }}
                                                </p>
                                                <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wide mt-0.5">{{ $att->file_type }}</p>
                                            </div>

                                            <!-- Action Button -->
                                            <a href="{{ $att->file_type == 'file' ? asset('storage/'.$att->file_path) : $att->file_path }}" 
                                               target="_blank" 
                                               class="w-8 h-8 rounded-full bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all shadow-sm">
                                                <i class="ph-bold {{ $att->file_type == 'file' ? 'ph-download-simple' : 'ph-arrow-up-right' }}"></i>
                                            </a>
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

        <!-- === KONTEN TAB: TUGAS === -->
        <div x-show="activeTab === 'tugas'" class="space-y-6" style="display: none;">
            @if($assignments->isEmpty())
                <div class="text-center py-16 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
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
                        
                        // Tentukan Ikon & Warna Berdasarkan Tipe
                        $typeIcon = 'ph-clipboard-text';
                        $typeColor = 'text-indigo-600 bg-indigo-50';
                        $typeLabel = 'Tugas File';
                        
                        if($task->assignment_type == 'quiz') {
                            $typeIcon = 'ph-list-checks';
                            $typeColor = 'text-purple-600 bg-purple-50';
                            $typeLabel = 'Kuis Online';
                        } elseif($task->assignment_type == 'link') {
                            $typeIcon = 'ph-link';
                            $typeColor = 'text-orange-600 bg-orange-50';
                            $typeLabel = 'Tugas Link';
                        }
                    @endphp

                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-lg transition-all duration-300" x-data="{ openUpload: false }">
                        
                        <!-- Header Card Tugas -->
                        <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-gradient-to-r from-white to-slate-50/50">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 {{ $typeColor }}">
                                    <i class="ph-duotone {{ $typeIcon }} text-2xl"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-md border border-slate-200 bg-white text-slate-500">{{ $typeLabel }}</span>
                                        @if($isLate)
                                            <span class="bg-rose-100 text-rose-700 px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wide">Terlewat</span>
                                        @endif
                                    </div>
                                    <h3 class="font-bold text-slate-900 text-lg group-hover:text-indigo-600 transition-colors">{{ $task->title }}</h3>
                                    <div class="flex items-center gap-3 mt-1.5 text-xs font-medium text-slate-500">
                                        <span class="flex items-center gap-1 {{ $isLate ? 'text-rose-500' : '' }}">
                                            <i class="ph-fill ph-clock"></i> Deadline: {{ $deadlineFormatted }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge (Kanan) -->
                            <div class="shrink-0">
                                @if($mySubmission)
                                    @if(isset($mySubmission->grade))
                                        <div class="flex flex-col items-end">
                                            <span class="text-[10px] uppercase font-bold text-slate-400 mb-1">Nilai Kamu</span>
                                            <div class="px-4 py-1.5 bg-indigo-600 text-white rounded-lg text-xl font-black shadow-md shadow-indigo-200">
                                                {{ $mySubmission->grade }}
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-xl border border-blue-100">
                                            <div class="w-2 h-2 rounded-full bg-blue-500 animate-pulse"></div>
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

                        <!-- Body: Soal -->
                        <div class="p-6">
                            <div class="prose prose-sm max-w-none text-slate-600 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 mb-6">
                                {{ $task->description }}
                                @if($task->assignment_type == 'quiz')
                                    <div class="mt-2 pt-2 border-t border-slate-200 text-xs font-bold text-slate-500 flex gap-4">
                                        <span><i class="ph-bold ph-timer"></i> Durasi: {{ $task->duration_minutes ?? 60 }} Menit</span>
                                        <span><i class="ph-bold ph-list-numbers"></i> {{ $task->questions->count() }} Soal</span>
                                    </div>
                                @endif
                            </div>

                            <!-- ACTION AREA (LOGIKA UTAMA) -->
                            @if(!$mySubmission)
                                {{-- BELUM MENGUMPULKAN --}}
                                @if($isLate && !$task->allow_late_submission)
                                    <div class="w-full py-3 bg-slate-100 text-slate-400 text-center rounded-xl text-sm font-bold border border-slate-200 flex flex-col items-center justify-center gap-1">
                                        <i class="ph-duotone ph-lock-key text-xl"></i>
                                        <span>Maaf, waktu pengumpulan tugas sudah berakhir.</span>
                                    </div>
                                @else
                                    
                                    {{-- CASE 1: KUIS ONLINE --}}
                                    @if($task->assignment_type == 'quiz')
                                        <a href="{{ route('students.learning.assignment.quiz', $task->id) }}" class="w-full py-3 bg-purple-600 text-white font-bold rounded-xl hover:bg-purple-700 transition shadow-lg shadow-purple-200 flex items-center justify-center gap-2 group">
                                            <i class="ph-bold ph-play-circle text-lg group-hover:scale-110 transition-transform"></i>
                                            Mulai Kerjakan Kuis
                                        </a>

                                    {{-- CASE 2: LINK EKSTERNAL --}}
                                    @elseif($task->assignment_type == 'link')
                                        <div class="flex gap-3">
                                            <a href="{{ $task->link_url }}" target="_blank" class="flex-1 py-3 bg-orange-100 text-orange-700 font-bold rounded-xl hover:bg-orange-200 transition flex items-center justify-center gap-2">
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
                                        <button @click="openUpload = !openUpload" class="w-full py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition shadow-lg shadow-indigo-200 flex items-center justify-center gap-2 group">
                                            <i class="ph-bold ph-upload-simple text-lg group-hover:-translate-y-1 transition-transform"></i>
                                            <span x-text="openUpload ? 'Batal Upload' : 'Kerjakan & Upload File'"></span>
                                        </button>
                                        
                                        <!-- Form Upload -->
                                        <div x-show="openUpload" x-transition class="mt-4 p-6 border-2 border-dashed border-indigo-200 rounded-2xl bg-indigo-50/30">
                                            <h4 class="font-bold text-indigo-900 mb-4 flex items-center gap-2"><i class="ph-fill ph-cloud-arrow-up"></i> Form Pengumpulan</h4>
                                            <form action="{{ route('students.learning.assignment.submit', $task->id) }}" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                <div class="space-y-4">
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">File Jawaban (PDF/JPG)</label>
                                                        <input type="file" name="file" required class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-white file:text-indigo-600 hover:file:bg-indigo-50 border border-slate-300 rounded-xl bg-white shadow-sm transition">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-bold text-slate-500 uppercase mb-2">Catatan (Opsional)</label>
                                                        <textarea name="student_note" rows="2" class="w-full rounded-xl border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Pesan untuk guru..."></textarea>
                                                    </div>
                                                    <button type="submit" class="w-full py-3 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 shadow-md transition flex items-center justify-center gap-2">
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
                                            <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg">
                                                <i class="ph-fill ph-check-fat text-xl"></i>
                                            </div>
                                            <div>
                                                <p class="font-bold text-emerald-800 text-sm">Tugas Terkirim</p>
                                                <p class="text-xs text-emerald-600">{{ $mySubmission->submitted_at->translatedFormat('d F Y • H:i') }}</p>
                                            </div>
                                        </div>
                                        @if(!$mySubmission->grade && $task->assignment_type == 'file_upload')
                                            <button @click="openUpload = !openUpload" class="text-xs font-bold text-indigo-600 hover:underline flex items-center gap-1">
                                                <i class="ph-bold ph-pencil-simple"></i> Edit / Kirim Ulang
                                            </button>
                                        @endif
                                    </div>

                                    @if($mySubmission->teacher_feedback)
                                        <div class="mt-3 bg-white p-4 rounded-xl border border-emerald-100 shadow-sm relative">
                                            <div class="absolute -top-2 left-6 w-4 h-4 bg-white border-t border-l border-emerald-100 transform rotate-45"></div>
                                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1">Catatan Guru:</p>
                                            <p class="text-slate-700 text-sm italic">"{{ $mySubmission->teacher_feedback }}"</p>
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
@endsection