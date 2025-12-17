@extends('layouts.student')

@section('content')
<div class="min-h-screen bg-slate-50">
    
    <!-- 1. HERO HEADER (Konsisten dengan halaman Show) -->
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-700 pb-24 pt-12 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[3rem] shadow-xl">
        <!-- Dekorasi Background -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-cyan-400 opacity-10 rounded-full blur-3xl -ml-10 -mb-10"></div>

        <div class="relative max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-6">
            <!-- Sapaan -->
            <div class="text-white">
                <p class="text-blue-100 font-medium text-lg mb-1 flex items-center gap-2">
                    Selamat Datang Kembali 👋
                </p>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight">
                    {{ Auth::guard('student')->user()->name ?? 'Siswa' }}
                </h1>
                <p class="mt-2 text-blue-100/80 text-sm md:text-base max-w-lg">
                    Siap untuk belajar hal baru hari ini? Cek tugas prioritasmu di bawah ini.
                </p>
            </div>

            <!-- Badge Kelas & Statistik Ringkas -->
            <div class="flex items-center gap-3 bg-white/10 backdrop-blur-md border border-white/20 p-4 rounded-2xl text-white shadow-lg">
                <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center text-2xl shadow-inner">
                    <i class="ph-duotone ph-student"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-blue-200 uppercase tracking-widest">Kelas Kamu</p>
                    <p class="text-xl font-bold">{{ Auth::guard('student')->user()->schoolClass->name ?? 'Umum' }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MAIN CONTENT (Overlap ke atas) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 pb-20 space-y-10">

        <!-- SECTION A: PRIORITAS (Tugas/Materi Baru) -->
        @if($prioritySubjects->isNotEmpty())
            <div>
                <div class="flex items-center justify-between mb-4 px-1">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2 drop-shadow-md">
                        <i class="ph-fill ph-fire text-orange-400"></i> Perlu Perhatianmu
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($prioritySubjects as $subject)
                        @php $theme = getSubjectTheme($subject->name); @endphp
                        
                        <a href="{{ route('students.learning.subject.show', $subject->id) }}" class="group relative bg-white rounded-[2rem] shadow-lg hover:shadow-2xl hover:shadow-rose-500/10 border border-slate-100 hover:border-rose-200 transition-all duration-300 overflow-hidden flex flex-col h-full transform hover:-translate-y-1">
                            
                            <!-- Header Card dengan Gradient -->
                            <div class="h-24 bg-gradient-to-br {{ $theme['bg'] }} relative overflow-hidden">
                                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                                <i class="ph-duotone {{ $theme['icon'] }} absolute -bottom-6 -right-6 text-[8rem] text-white opacity-10 transform rotate-12 transition-transform group-hover:rotate-6 group-hover:scale-110"></i>
                                
                                <!-- Badges Notifikasi -->
                                <div class="absolute top-3 right-3 flex flex-col items-end gap-1.5">
                                    @if($subject->active_tasks_count > 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/95 text-rose-600 text-[10px] font-bold shadow-sm backdrop-blur-sm animate-pulse border border-rose-100">
                                            <i class="ph-fill ph-warning-circle text-xs"></i> {{ $subject->active_tasks_count }} Tugas
                                        </span>
                                    @endif
                                    @if($subject->new_materials_count > 0)
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/95 text-emerald-600 text-[10px] font-bold shadow-sm backdrop-blur-sm border border-emerald-100">
                                            <i class="ph-fill ph-sparkle text-xs"></i> Materi Baru
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Floating Icon -->
                            <div class="absolute top-12 left-6 w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center text-3xl border-4 border-white {{ $theme['color'] }} group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-fill {{ $theme['icon'] }}"></i>
                            </div>

                            <!-- Body -->
                            <div class="pt-12 pb-6 px-6 flex-1 flex flex-col">
                                <h3 class="font-bold text-lg text-slate-900 group-hover:text-rose-600 transition-colors line-clamp-1 mb-1">{{ $subject->name }}</h3>
                                <p class="text-xs text-slate-500 font-medium">Klik untuk mulai mengerjakan</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- SECTION B: SEMUA PELAJARAN -->
        <div>
            <div class="flex items-center gap-3 mb-6 px-1">
                <div class="p-2 bg-blue-100 text-blue-600 rounded-lg">
                    <i class="ph-bold ph-books text-xl"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-800">Semua Mata Pelajaran</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($allSubjects as $subject)
                    @php $theme = getSubjectTheme($subject->name); @endphp

                    <a href="{{ route('students.learning.subject.show', $subject->id) }}" class="group bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:border-blue-300 hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300 flex items-center gap-4 relative overflow-hidden">
                        <!-- Hover Effect Decoration -->
                        <div class="absolute inset-y-0 left-0 w-1 bg-gradient-to-b {{ $theme['bg'] }} opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <!-- Icon Box -->
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $theme['bg'] }} flex items-center justify-center text-white text-xl shadow-sm shrink-0 group-hover:scale-110 transition-transform">
                            <i class="ph-duotone {{ $theme['icon'] }}"></i>
                        </div>

                        <!-- Text -->
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-800 text-sm group-hover:text-blue-700 transition-colors line-clamp-1">
                                {{ $subject->name }}
                            </h3>
                            <p class="text-[10px] text-slate-400 font-medium uppercase tracking-wide mt-0.5">
                                {{ $subject->teacher_name ?? 'Pengajar Aktif' }}
                            </p>
                        </div>

                        <!-- Arrow -->
                        <div class="text-slate-300 group-hover:text-blue-500 transition-colors transform group-hover:translate-x-1">
                            <i class="ph-bold ph-caret-right"></i>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($allSubjects->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="ph-duotone ph-books text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Belum Ada Mata Pelajaran</h3>
                    <p class="text-slate-500 text-sm mt-1">Jadwal mata pelajaran belum tersedia untuk kelas ini.</p>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- HELPER PHP UNTUK TEMA --}}
@php
    function getSubjectTheme($name) {
        $name = strtolower($name);
        // Default: Slate/Gray
        $theme = ['bg' => 'from-slate-500 to-slate-700', 'icon' => 'ph-books', 'color' => 'text-slate-600'];

        if (Str::contains($name, ['matematika', 'math', 'fisika'])) {
            $theme = ['bg' => 'from-blue-500 to-indigo-600', 'icon' => 'ph-calculator', 'color' => 'text-blue-600'];
        } elseif (Str::contains($name, ['bahasa', 'indonesia', 'inggris', 'sunda', 'jawa'])) {
            $theme = ['bg' => 'from-amber-400 to-orange-500', 'icon' => 'ph-translate', 'color' => 'text-orange-600'];
        } elseif (Str::contains($name, ['ipa', 'biologi', 'kimia'])) {
            $theme = ['bg' => 'from-emerald-500 to-teal-600', 'icon' => 'ph-flask', 'color' => 'text-emerald-600'];
        } elseif (Str::contains($name, ['ips', 'sejarah', 'geografi', 'pkn', 'sosial'])) {
            $theme = ['bg' => 'from-rose-500 to-red-600', 'icon' => 'ph-globe-hemisphere-west', 'color' => 'text-rose-600'];
        } elseif (Str::contains($name, ['agama', 'pai', 'quran'])) {
            $theme = ['bg' => 'from-green-600 to-emerald-800', 'icon' => 'ph-star-and-crescent', 'color' => 'text-green-700'];
        } elseif (Str::contains($name, ['seni', 'budaya', 'prakarya'])) {
            $theme = ['bg' => 'from-pink-500 to-fuchsia-600', 'icon' => 'ph-palette', 'color' => 'text-pink-600'];
        } elseif (Str::contains($name, ['pjok', 'olahraga'])) {
            $theme = ['bg' => 'from-orange-500 to-red-500', 'icon' => 'ph-basketball', 'color' => 'text-orange-600'];
        } elseif (Str::contains($name, ['tik', 'informatika', 'komputer'])) {
            $theme = ['bg' => 'from-violet-600 to-purple-700', 'icon' => 'ph-desktop', 'color' => 'text-violet-700'];
        }
        return $theme;
    }
@endphp
@endsection