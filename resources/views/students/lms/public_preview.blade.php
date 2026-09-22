@extends('layouts.public')

@section('title', $material->title . ' - Modul Pembelajaran SMPN 3 Lakbok')

@section('content')
<style>
    [x-cloak] { display: none !important; }
    body {
        background-color: #021124 !important;
        color: #ffffff !important;
    }
    .bg-grid-dark {
        background-image: linear-gradient(to right, rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                          linear-gradient(to bottom, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
        background-size: 32px 32px;
    }
</style>

<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 py-6 sm:py-10 relative z-10 font-sans">
    
    <!-- GLOBAL BACKGROUND -->
    <div class="fixed inset-0 z-[-1] pointer-events-none overflow-hidden">
        <div class="absolute inset-0 bg-[#021124]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_rgba(13,82,161,0.25)_0%,_rgba(2,17,36,0.9)_60%,_#021124_100%)]"></div>
        <div class="absolute inset-0 bg-grid-dark opacity-30"></div>
        <div class="absolute -top-32 -right-32 w-[500px] h-[500px] bg-sky-500/15 rounded-full blur-[140px]"></div>
        <div class="absolute top-1/2 -left-32 w-[500px] h-[500px] bg-blue-600/15 rounded-full blur-[160px]"></div>
    </div>

    <!-- BREADCRUMB -->
    <nav class="flex items-center gap-2 text-xs font-bold text-slate-400 mb-6 flex-wrap">
        <a href="{{ route('landing') }}" class="hover:text-sky-300 transition-colors flex items-center gap-1">
            <i class="ph-bold ph-house text-sm"></i> Beranda
        </a>
        <i class="ph-bold ph-caret-right text-[10px] text-slate-600"></i>
        <a href="{{ route('landing') }}#katalog-lms" class="hover:text-sky-300 transition-colors">Katalog LMS</a>
        <i class="ph-bold ph-caret-right text-[10px] text-slate-600"></i>
        <span class="text-sky-400 truncate max-w-[200px] sm:max-w-xs">{{ $material->subject->name ?? 'Mata Pelajaran' }}</span>
        <i class="ph-bold ph-caret-right text-[10px] text-slate-600"></i>
        <span class="text-slate-200 truncate max-w-[250px]">{{ $material->title }}</span>
    </nav>

    <!-- HERO / BANNER HEADER (DICODING STYLE) -->
    <div class="rounded-[2.5rem] bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] border border-white/20 shadow-2xl p-6 sm:p-10 relative overflow-hidden mb-8 backdrop-blur-xl">
        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-500 via-blue-500 to-indigo-500"></div>
        <div class="absolute -right-20 -bottom-20 w-80 h-80 bg-sky-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
            <div class="max-w-3xl">
                <!-- Badges -->
                <div class="flex items-center gap-2 flex-wrap mb-4">
                    <span class="px-3.5 py-1.5 rounded-full bg-sky-500/20 text-sky-300 border border-sky-400/30 text-xs font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm">
                        <i class="ph-bold ph-book-open"></i> {{ $material->subject->name ?? 'Mata Pelajaran' }}
                    </span>
                    @if($material->topic)
                        <span class="px-3.5 py-1.5 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 text-xs font-black uppercase tracking-wider flex items-center gap-1.5">
                            <i class="ph-bold ph-folder"></i> Topik: {{ $material->topic->title ?? $material->topic->name }}
                        </span>
                    @endif
                    @if($material->schoolClass)
                        <span class="px-3.5 py-1.5 rounded-full bg-slate-800 text-slate-300 border border-white/10 text-xs font-bold">
                            Kelas {{ $material->schoolClass->name }}
                        </span>
                    @endif
                </div>

                <!-- Title & Summary -->
                <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-snug mb-3">
                    {{ $material->title }}
                </h1>
                
                <!-- Pengampu / Guru -->
                <div class="flex items-center gap-3 mt-4 pt-4 border-t border-white/10">
                    <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-sky-500 to-blue-600 p-[2px] shadow-md shrink-0">
                        <div class="w-full h-full bg-[#021124] rounded-[14px] flex items-center justify-center font-black text-sky-400 text-sm">
                            {{ Str::upper(Str::substr($material->teacher->name ?? 'G', 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-slate-400">Guru Pengampu</p>
                        <p class="text-sm font-black text-white flex items-center gap-1.5">
                            {{ $material->teacher->name ?? 'Guru SMPN 3 Lakbok' }}
                            <i class="ph-fill ph-seal-check text-sky-400 text-base"></i>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Stats Meta Badge Card -->
            <div class="w-full lg:w-auto shrink-0 bg-white/[0.04] border border-white/10 rounded-2xl p-4 sm:p-5 flex lg:flex-col justify-around gap-4 backdrop-blur-md">
                <div class="text-center lg:text-left">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Total Lampiran</span>
                    <span class="text-xl font-black text-sky-400">{{ $material->attachments->count() + ($material->file_path ? 1 : 0) }} File</span>
                </div>
                <div class="h-8 lg:h-px w-px lg:w-full bg-white/10"></div>
                <div class="text-center lg:text-left">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 block">Latihan &amp; Kuis</span>
                    <span class="text-xl font-black text-amber-400">{{ $assignments->count() }} Soal</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT LAYOUT (TWO COLUMNS) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- LEFT COLUMN: CONTENT, SYLLABUS & QUIZZES (8 COLS) -->
        <div class="lg:col-span-8 space-y-8">
            
            <!-- 1. Deskripsi & Ringkasan Materi -->
            <div class="rounded-[2rem] bg-[#031d3d]/70 border border-white/15 p-6 sm:p-8 backdrop-blur-xl shadow-xl">
                <h2 class="text-lg sm:text-xl font-black text-white mb-4 flex items-center gap-2 border-b border-white/10 pb-3">
                    <i class="ph-bold ph-text-align-left text-sky-400"></i> Ringkasan &amp; Deskripsi Modul
                </h2>
                
                @if($material->content)
                    <div class="prose prose-invert max-w-none text-slate-300 text-sm leading-relaxed space-y-3">
                        {!! nl2br(e($material->content)) !!}
                    </div>
                @else
                    <p class="text-sm text-slate-400 italic">Tidak ada rincian deskripsi tambahan. Guru menyediakan dokumen dan latihan soal di bawah ini.</p>
                @endif
            </div>

            <!-- 2. Daftar Bab & Lampiran Materi (Files/Videos) -->
            <div class="rounded-[2rem] bg-[#031d3d]/70 border border-white/15 p-6 sm:p-8 backdrop-blur-xl shadow-xl">
                <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
                    <div>
                        <h2 class="text-lg sm:text-xl font-black text-white flex items-center gap-2">
                            <i class="ph-bold ph-folder-open text-sky-400"></i> Dokumen &amp; Bab Pembelajaran
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar file presentasi, PDF, dan dokumen materi.</p>
                    </div>
                    <span class="px-3 py-1 bg-sky-500/10 text-sky-300 rounded-xl text-xs font-bold border border-sky-500/20">
                        {{ $material->attachments->count() + ($material->file_path ? 1 : 0) }} Items
                    </span>
                </div>

                <div class="space-y-3">
                    {{-- Main File if exists --}}
                    @if($material->file_path)
                        <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-between gap-4 transition-all hover:border-sky-500/40">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-300 flex items-center justify-center text-xl shrink-0">
                                    <i class="ph-bold ph-file-pdf"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-white truncate">{{ $material->title }} (Dokumen Utama)</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-mono mt-0.5">PDF / File Materi Utama</p>
                                </div>
                            </div>
                            @if($isStudentLoggedIn)
                                <a href="{{ route('material.download', $material->id) }}" class="px-4 py-2 rounded-xl bg-sky-500 text-white hover:bg-sky-400 text-xs font-bold shrink-0 shadow-lg shadow-sky-500/20 flex items-center gap-1.5 transition-transform active:scale-95">
                                    <i class="ph-bold ph-download-simple"></i> Unduh
                                </a>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-slate-800 text-slate-400 text-xs font-bold shrink-0 border border-white/10 flex items-center gap-1">
                                    <i class="ph-bold ph-lock-key text-amber-400"></i> Login Dulu
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Attachments --}}
                    @forelse($material->attachments as $att)
                        <div class="p-4 rounded-2xl bg-white/[0.03] border border-white/10 flex items-center justify-between gap-4 transition-all hover:border-sky-500/40">
                            <div class="flex items-center gap-3.5 min-w-0">
                                <div class="w-10 h-10 rounded-xl bg-indigo-500/20 text-indigo-300 flex items-center justify-center text-xl shrink-0">
                                    <i class="ph-bold ph-file-text"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-white truncate">{{ $att->file_name ?? 'Lampiran Tambahan' }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase font-mono mt-0.5">Modul Pendukung</p>
                                </div>
                            </div>
                            @if($isStudentLoggedIn)
                                <a href="{{ asset('storage/' . $att->file_path) }}" target="_blank" class="px-4 py-2 rounded-xl bg-sky-500 text-white hover:bg-sky-400 text-xs font-bold shrink-0 shadow-lg shadow-sky-500/20 flex items-center gap-1.5 transition-transform active:scale-95">
                                    <i class="ph-bold ph-eye"></i> Buka
                                </a>
                            @else
                                <span class="px-3 py-1.5 rounded-xl bg-slate-800 text-slate-400 text-xs font-bold shrink-0 border border-white/10 flex items-center gap-1">
                                    <i class="ph-bold ph-lock-key text-amber-400"></i> Login Dulu
                                </span>
                            @endif
                        </div>
                    @empty
                        @if(!$material->file_path)
                            <div class="p-6 text-center text-slate-400 text-xs italic bg-white/[0.02] rounded-2xl border border-dashed border-white/10">
                                Belum ada berkas lampiran yang diunggah untuk materi ini.
                            </div>
                        @endif
                    @endforelse
                </div>
            </div>

            <!-- 3. Latihan Soal & Kuis Interaktif -->
            <div class="rounded-[2rem] bg-[#031d3d]/70 border border-white/15 p-6 sm:p-8 backdrop-blur-xl shadow-xl">
                <div class="flex items-center justify-between border-b border-white/10 pb-4 mb-6">
                    <div>
                        <h2 class="text-lg sm:text-xl font-black text-white flex items-center gap-2">
                            <i class="ph-bold ph-brain text-amber-400"></i> Latihan Soal &amp; Kuis Interaktif
                        </h2>
                        <p class="text-xs text-slate-400 mt-0.5">Uji pemahaman topik materi dengan mengerjakan kuis interaktif.</p>
                    </div>
                    <span class="px-3 py-1 bg-amber-500/10 text-amber-300 rounded-xl text-xs font-bold border border-amber-500/20">
                        {{ $assignments->count() }} Kuis
                    </span>
                </div>

                <div class="space-y-4">
                    @forelse($assignments as $assignment)
                        <div class="p-5 rounded-2xl bg-gradient-to-r from-amber-500/10 via-white/[0.02] to-transparent border border-amber-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-2xl shrink-0 border border-amber-500/40">
                                    <i class="ph-bold ph-exam"></i>
                                </div>
                                <div>
                                    <h4 class="font-black text-white text-base tracking-tight">{{ $assignment->title }}</h4>
                                    <div class="flex items-center gap-3 text-xs text-slate-300 font-medium mt-1 flex-wrap">
                                        <span class="flex items-center gap-1 text-slate-400">
                                            <i class="ph-bold ph-clock text-amber-400"></i>
                                            Tenggat: {{ $assignment->deadline ? \Carbon\Carbon::parse($assignment->deadline)->translatedFormat('d M Y, H:i') : 'Tanpa Batas' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            @if($isStudentLoggedIn)
                                <a href="{{ route('students.learning.assignment.quiz', $assignment->id) }}" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-gradient-to-r from-amber-500 to-orange-500 text-white font-bold text-xs flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20 transition-all hover:scale-105 active:scale-95">
                                    <i class="ph-bold ph-play"></i> Mulai Kerjakan
                                </a>
                            @else
                                <a href="{{ route('student.login', ['redirect_url' => route('lms.public.preview', $material->id)]) }}" class="w-full sm:w-auto px-5 py-3 rounded-xl bg-slate-800 text-amber-300 border border-amber-500/30 font-bold text-xs flex items-center justify-center gap-2 hover:bg-slate-700 transition-colors">
                                    <i class="ph-bold ph-lock-key"></i> Login untuk Kerjakan
                                </a>
                            @endif
                        </div>
                    @empty
                        <div class="p-6 text-center text-slate-400 text-xs italic bg-white/[0.02] rounded-2xl border border-dashed border-white/10">
                            Belum ada latihan soal atau kuis aktif yang ditambahkan untuk topik ini.
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- RIGHT COLUMN: CALL TO ACTION CARD & RELATED COURSES (4 COLS) -->
        <div class="lg:col-span-4 space-y-6">
            
            <!-- STICKY CTA CARD -->
            <div class="sticky top-24 rounded-[2.5rem] bg-gradient-to-b from-[#0a274c] via-[#031d3d] to-[#021124] border border-sky-500/40 p-6 sm:p-8 shadow-2xl backdrop-blur-2xl">
                
                @if($material->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($material->cover_image))
                    <div class="rounded-2xl overflow-hidden mb-6 h-44 w-full shadow-lg border border-white/10 relative group">
                        <img src="{{ asset('storage/' . $material->cover_image) }}" alt="{{ $material->title }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#021124] via-transparent to-transparent opacity-60"></div>
                    </div>
                @endif

                <div class="text-center mb-6">
                    @if(!$material->cover_image)
                        <div class="w-16 h-16 rounded-3xl bg-gradient-to-tr from-sky-400 to-blue-600 p-[2px] mx-auto mb-4 shadow-lg shadow-sky-500/30 animate-pulse">
                            <div class="w-full h-full bg-[#021124] rounded-[22px] flex items-center justify-center text-sky-300 text-3xl">
                                <i class="ph-bold ph-sparkle"></i>
                            </div>
                        </div>
                    @endif
                    <h3 class="text-xl font-black text-white tracking-tight">Mulai Belajar Sekarang</h3>
                    <p class="text-xs text-slate-300 mt-1.5 leading-relaxed">
                        Akses penuh semua materi pembelajaran, unduh berkas modul, dan kerjakan kuis interaktif.
                    </p>
                </div>

                @if(!$isStudentLoggedIn)
                    <!-- FORM LOGIN QUICK IN CTA CARD -->
                    <form action="{{ route('student.login.post') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="redirect_url" value="{{ route('lms.public.preview', $material->id) }}">
                        <div>
                            <label class="block text-[11px] font-bold text-sky-300 uppercase tracking-wider mb-1.5">NISN / NIS Siswa</label>
                            <div class="relative">
                                <i class="ph-bold ph-identification-card absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                                <input type="text" name="student_id" required placeholder="Masukkan NISN..." class="w-full pl-11 pr-4 py-3.5 bg-slate-900/90 border border-white/20 rounded-xl text-white font-bold text-sm focus:border-sky-400 focus:ring-2 focus:ring-sky-400/30 transition-all placeholder-slate-500">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl bg-gradient-to-r from-sky-500 via-blue-600 to-indigo-600 text-white font-black text-sm shadow-xl shadow-sky-500/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                            <span>Masuk &amp; Akses Penuh</span>
                            <i class="ph-bold ph-arrow-right"></i>
                        </button>
                    </form>
                @else
                    <!-- STATE: SUDAH LOGIN -->
                    <div class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-bold text-center mb-4">
                        <i class="ph-bold ph-check-circle text-base"></i> Anda Terautentikasi sebagai Siswa
                    </div>
                    <a href="{{ route('students.learning.index') }}" class="w-full py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black text-sm shadow-xl shadow-emerald-500/30 hover:scale-[1.02] active:scale-95 transition-all flex items-center justify-center gap-2">
                        <span>Buka Dashboard LMS</span>
                        <i class="ph-bold ph-arrow-right"></i>
                    </a>
                @endif

                <div class="mt-6 pt-6 border-t border-white/10 space-y-3">
                    <div class="flex items-center gap-3 text-xs text-slate-300">
                        <i class="ph-bold ph-check text-sky-400 text-base"></i>
                        <span>Akses GRATIS untuk seluruh siswa SMPN 3 Lakbok</span>
                    </div>
                    <div class="flex items-center gap-3 text-xs text-slate-300">
                        <i class="ph-bold ph-check text-sky-400 text-base"></i>
                        <span>Soal Kuis Otomatis Terhubung ke Rekap Nilai</span>
                    </div>
                </div>
            </div>

            <!-- MODUL TERKAIT -->
            @if($relatedMaterials->count() > 0)
                <div class="rounded-[2rem] bg-[#031d3d]/70 border border-white/15 p-6 backdrop-blur-xl shadow-xl">
                    <h4 class="font-black text-white text-base mb-4 flex items-center gap-2">
                        <i class="ph-bold ph-books text-sky-400"></i> Modul Pembelajaran Terkait
                    </h4>
                    <div class="space-y-3">
                        @foreach($relatedMaterials as $rel)
                            <a href="{{ route('lms.public.preview', $rel->id) }}" class="p-3.5 rounded-xl bg-white/[0.03] border border-white/10 hover:border-sky-400/40 block transition-all group">
                                <span class="text-[10px] font-bold text-sky-400 uppercase tracking-wider block mb-1">
                                    {{ $rel->subject->name ?? 'Mata Pelajaran' }}
                                </span>
                                <h5 class="text-xs font-bold text-white group-hover:text-sky-300 transition-colors line-clamp-2">
                                    {{ $rel->title }}
                                </h5>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection
