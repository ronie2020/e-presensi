@extends('layouts.student')

@section('content')
<div class="min-h-screen bg-slate-50/50">
    
    <div class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 pb-24 pt-12 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[3rem] shadow-2xl shadow-blue-900/20">
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-600 opacity-10 rounded-full blur-3xl -mr-20 -mt-20"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-yellow-500 opacity-5 rounded-full blur-3xl -ml-10 -mb-10"></div>

        <div class="relative max-w-7xl mx-auto flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="text-white">
                <p class="text-blue-200 font-bold text-sm uppercase tracking-widest mb-2 flex items-center gap-2">
                    <span class="w-8 h-[2px] bg-yellow-400 inline-block"></span> Selamat Datang Kembali
                </p>
                <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight text-white drop-shadow-md">
                    {{ Auth::guard('student')->user()->name ?? 'Siswa' }}
                </h1>
                <p class="mt-3 text-slate-300 text-sm md:text-base max-w-lg leading-relaxed">
                    Siap untuk belajar hal baru hari ini? Cek tugas prioritasmu di bawah ini.
                </p>
            </div>

            <div class="flex items-center gap-4 bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-white shadow-lg group hover:bg-white/10 transition-colors">
                <div class="w-14 h-14 rounded-xl bg-blue-950 border border-blue-800 flex items-center justify-center text-2xl shadow-inner text-yellow-400 group-hover:scale-110 transition-transform">
                    <i class="ph-duotone ph-student"></i>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Kelas Kamu</p>
                    <p class="text-2xl font-black tracking-tight">{{ Auth::guard('student')->user()->schoolClass->name ?? 'Umum' }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10 pb-20 space-y-10">

        @if($prioritySubjects->isNotEmpty())
            <div>
                <div class="flex items-center justify-between mb-5 px-2">
                    <h2 class="text-xl font-bold text-white flex items-center gap-2 drop-shadow-md">
                        <div class="p-1.5 bg-orange-500/20 rounded-lg border border-orange-500/30 backdrop-blur-sm">
                            <i class="ph-fill ph-fire text-orange-400"></i> 
                        </div>
                        Perlu Perhatianmu
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($prioritySubjects as $subject)
                        @php $theme = getSubjectTheme($subject->name); @endphp
                        
                        <a href="{{ route('students.learning.subject.show', $subject->id) }}" class="group relative bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 hover:shadow-2xl hover:shadow-blue-900/10 border border-slate-100 transition-all duration-300 overflow-hidden flex flex-col h-full transform hover:-translate-y-1">
                            
                            <div class="h-28 bg-gradient-to-br {{ $theme['bg'] }} relative overflow-hidden">
                                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
                                <i class="ph-duotone {{ $theme['icon'] }} absolute -bottom-6 -right-6 text-[8rem] text-white opacity-10 transform rotate-12 transition-transform group-hover:rotate-6 group-hover:scale-110"></i>
                                
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
                            
                            <div class="absolute top-14 left-6 w-16 h-16 bg-white rounded-2xl shadow-lg flex items-center justify-center text-3xl border-4 border-white {{ $theme['color'] }} group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                                <i class="ph-fill {{ $theme['icon'] }}"></i>
                            </div>

                            <div class="pt-12 pb-6 px-6 flex-1 flex flex-col">
                                <h3 class="font-bold text-lg text-slate-900 group-hover:text-blue-900 transition-colors line-clamp-1 mb-1">{{ $subject->name }}</h3>
                                <p class="text-xs text-slate-500 font-medium flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                    Klik untuk mulai mengerjakan
                                </p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        <div>
            <div class="flex items-center gap-3 mb-6 px-2">
                <div class="p-2.5 bg-white border border-slate-200 shadow-sm text-blue-900 rounded-xl">
                    <i class="ph-bold ph-books text-xl"></i>
                </div>
                <h2 class="text-xl font-bold text-slate-800">Semua Mata Pelajaran</h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
                @foreach($allSubjects as $subject)
                    @php $theme = getSubjectTheme($subject->name); @endphp

                    <a href="{{ route('students.learning.subject.show', $subject->id) }}" class="group bg-white rounded-2xl p-4 shadow-sm border border-slate-100 hover:border-blue-900/30 hover:shadow-xl hover:shadow-blue-900/5 transition-all duration-300 flex items-center gap-4 relative overflow-hidden">
                        <div class="absolute inset-y-0 left-0 w-1 bg-yellow-400 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br {{ $theme['bg'] }} flex items-center justify-center text-white text-2xl shadow-sm shrink-0 group-hover:scale-105 transition-transform">
                            <i class="ph-duotone {{ $theme['icon'] }}"></i>
                        </div>

                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-800 text-sm group-hover:text-blue-900 transition-colors line-clamp-1">
                                {{ $subject->name }}
                            </h3>
                            <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide mt-1 flex items-center gap-1">
                                <i class="ph-fill ph-chalkboard-teacher"></i>
                                {{ $subject->teacher_name ?? 'Pengajar Aktif' }}
                            </p>
                        </div>

                        <div class="text-slate-300 group-hover:text-yellow-500 transition-colors transform group-hover:translate-x-1">
                            <i class="ph-bold ph-caret-right"></i>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($allSubjects->isEmpty())
                <div class="text-center py-20 bg-white rounded-3xl border-2 border-dashed border-slate-200 group">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 group-hover:text-blue-900 group-hover:bg-blue-50 transition-all">
                        <i class="ph-duotone ph-books text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Belum Ada Mata Pelajaran</h3>
                    <p class="text-slate-500 text-sm mt-1">Jadwal mata pelajaran belum tersedia untuk kelas ini.</p>
                </div>
            @endif
        </div>

    </div>
</div>

{{-- HELPER PHP UNTUK TEMA (Tweak Default Theme to Dark/Gold) --}}
@php
    function getSubjectTheme($name) {
        $name = strtolower($name);
        // Default: Dark Slate Theme (Harmonized)
        $theme = ['bg' => 'from-slate-700 to-slate-900', 'icon' => 'ph-books', 'color' => 'text-slate-700'];

        if (Str::contains($name, ['matematika', 'math', 'fisika'])) {
            $theme = ['bg' => 'from-blue-600 to-indigo-800', 'icon' => 'ph-calculator', 'color' => 'text-blue-700'];
        } elseif (Str::contains($name, ['bahasa', 'indonesia', 'inggris', 'sunda', 'jawa'])) {
            $theme = ['bg' => 'from-amber-400 to-orange-600', 'icon' => 'ph-translate', 'color' => 'text-orange-600'];
        } elseif (Str::contains($name, ['ipa', 'biologi', 'kimia'])) {
            $theme = ['bg' => 'from-emerald-500 to-teal-700', 'icon' => 'ph-flask', 'color' => 'text-emerald-700'];
        } elseif (Str::contains($name, ['ips', 'sejarah', 'geografi', 'pkn', 'sosial'])) {
            $theme = ['bg' => 'from-rose-500 to-red-700', 'icon' => 'ph-globe-hemisphere-west', 'color' => 'text-rose-700'];
        } elseif (Str::contains($name, ['agama', 'pai', 'quran'])) {
            $theme = ['bg' => 'from-green-600 to-emerald-800', 'icon' => 'ph-star-and-crescent', 'color' => 'text-green-800'];
        } elseif (Str::contains($name, ['seni', 'budaya', 'prakarya'])) {
            $theme = ['bg' => 'from-pink-500 to-fuchsia-700', 'icon' => 'ph-palette', 'color' => 'text-pink-700'];
        } elseif (Str::contains($name, ['pjok', 'olahraga'])) {
            $theme = ['bg' => 'from-orange-500 to-red-600', 'icon' => 'ph-basketball', 'color' => 'text-orange-700'];
        } elseif (Str::contains($name, ['tik', 'informatika', 'komputer'])) {
            $theme = ['bg' => 'from-violet-600 to-purple-800', 'icon' => 'ph-desktop', 'color' => 'text-violet-800'];
        }
        return $theme;
    }
@endphp
@endsection