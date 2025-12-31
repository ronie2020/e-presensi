<x-student-learning-layout>
    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        {{-- HEADER DASHBOARD: DARK THEME --}}
        <div class="bg-slate-900 rounded-[2.5rem] p-8 md:p-10 mb-10 relative overflow-hidden shadow-2xl shadow-slate-900/20 border border-slate-800">
            {{-- Decoration --}}
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900 to-rose-900/20"></div>
            <div class="absolute top-0 right-0 w-64 h-64 bg-rose-500/10 rounded-full blur-3xl -mr-16 -mt-16 pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 w-40 h-40 bg-blue-500/10 rounded-full blur-3xl -ml-10 -mb-10 pointer-events-none"></div>
            <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                <div class="text-center md:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/5 border border-white/10 backdrop-blur-sm mb-4">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-slate-300 uppercase tracking-wider">Tahun Ajaran Aktif</span>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-black text-white mb-2 tracking-tight">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-400 to-orange-400">{{ explode(' ', Auth::guard('student')->user()->name)[0] }}!</span>
                    </h1>
                    
                    <p class="text-slate-400 font-medium text-sm max-w-lg mb-6 leading-relaxed">
                        Siap melanjutkan pembelajaran hari ini? Cek tugas prioritasmu di bawah ini atau jelajahi materi baru.
                    </p>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-3 text-slate-300 text-xs font-bold">
                        <div class="flex items-center gap-2 bg-slate-800/50 px-3 py-1.5 rounded-lg border border-slate-700">
                            <i class="ph-fill ph-student text-rose-400"></i>
                            <span>Kelas {{ Auth::guard('student')->user()->schoolClass->name ?? 'Umum' }}</span>
                        </div>
                        <div class="hidden sm:block w-1 h-1 bg-slate-700 rounded-full"></div>
                        <div class="flex items-center gap-2 bg-slate-800/50 px-3 py-1.5 rounded-lg border border-slate-700">
                            <i class="ph-fill ph-calendar text-blue-400"></i>
                            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
                
                {{-- Icon Besar Kanan --}}
                <div class="hidden md:block transform hover:scale-105 transition-transform duration-500">
                    <div class="w-28 h-28 bg-gradient-to-br from-slate-800 to-slate-900 rounded-[2rem] flex items-center justify-center shadow-2xl shadow-black/20 border border-slate-700/50 relative group">
                        <div class="absolute inset-0 bg-rose-500/20 blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <i class="ph-duotone ph-read-cv-logo text-6xl text-rose-400 relative z-10"></i>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN PRIORITAS (Tugas/Materi Baru) --}}
        @if(isset($prioritySubjects) && $prioritySubjects->count() > 0)
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6 px-1">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center text-rose-600 shadow-sm">
                            <i class="ph-fill ph-fire text-xl"></i>
                        </div>
                        <div>
                            <h2 class="text-xl font-black text-slate-800 leading-none">Prioritas Belajar</h2>
                            <p class="text-xs font-bold text-slate-400 mt-1">Selesaikan tugas yang tenggat waktunya dekat.</p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($prioritySubjects as $subject)
                        <a href="{{ route('students.learning.subject.show', $subject->id) }}" class="group bg-white p-6 rounded-[2rem] shadow-sm border border-slate-200 hover:shadow-xl hover:shadow-rose-900/5 hover:border-rose-200 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden">
                            
                            {{-- Hover Icon Background --}}
                            <div class="absolute -right-4 -bottom-4 opacity-0 group-hover:opacity-5 transition-opacity duration-300 transform rotate-12">
                                <i class="ph-duotone ph-notebook text-9xl text-rose-600"></i>
                            </div>
                            
                            <div class="relative z-10">
                                <div class="flex justify-between items-start mb-5">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-600 border border-slate-100 flex items-center justify-center text-2xl group-hover:bg-rose-600 group-hover:text-white group-hover:border-rose-600 transition-all duration-300 shadow-sm">
                                        <i class="ph-duotone ph-book-bookmark"></i>
                                    </div>
                                    @if($subject->active_tasks_count > 0)
                                        <span class="bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-black px-3 py-1 rounded-full animate-pulse flex items-center gap-1">
                                            <i class="ph-bold ph-warning-circle"></i> {{ $subject->active_tasks_count }} Tugas
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="font-bold text-lg text-slate-800 group-hover:text-rose-600 transition-colors mb-2 line-clamp-1">{{ $subject->name }}</h3>
                                
                                <div class="flex items-center gap-2 text-xs font-medium border-t border-slate-50 pt-4 mt-2">
                                    @if($subject->new_materials_count > 0)
                                        <span class="text-emerald-600 font-bold flex items-center gap-1.5 bg-emerald-50 px-2 py-1 rounded-md">
                                            <i class="ph-bold ph-check-circle"></i> {{ $subject->new_materials_count }} Materi Baru
                                        </span>
                                    @else
                                        <span class="text-slate-400 flex items-center gap-1.5">
                                            <i class="ph-bold ph-arrow-right"></i> Lanjutkan belajar
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- BAGIAN SEMUA MATA PELAJARAN --}}
        <div class="pb-12">
            <div class="flex items-center gap-3 mb-6 px-1">
                <div class="w-10 h-10 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-700 shadow-sm">
                    <i class="ph-fill ph-books text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-black text-slate-800 leading-none">Mata Pelajaran</h2>
                    <p class="text-xs font-bold text-slate-400 mt-1">Semua kelas yang kamu ikuti.</p>
                </div>
            </div>
            
            @if(!isset($allSubjects) || $allSubjects->isEmpty())
                <div class="bg-white rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-200">
                    <div class="w-20 h-20 bg-slate-50 rounded-[2rem] flex items-center justify-center mx-auto mb-4 text-slate-300">
                        <i class="ph-duotone ph-books text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-700">Belum Ada Mata Pelajaran</h3>
                    <p class="text-slate-500 text-sm mt-1 max-w-sm mx-auto font-medium">Data mata pelajaran belum ditambahkan oleh admin. Silakan hubungi tata usaha.</p>
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($allSubjects as $index => $subject)
                        {{-- LOGIC WARNA WARNI --}}
                        @php
                            $colors = [
                                ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'hover_border' => 'hover:border-blue-300', 'hover_bg' => 'hover:bg-blue-50', 'icon' => 'ph-globe', 'shadow' => 'hover:shadow-blue-900/10'],
                                ['bg' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100', 'hover_border' => 'hover:border-purple-300', 'hover_bg' => 'hover:bg-purple-50', 'icon' => 'ph-atom', 'shadow' => 'hover:shadow-purple-900/10'],
                                ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'hover_border' => 'hover:border-emerald-300', 'hover_bg' => 'hover:bg-emerald-50', 'icon' => 'ph-plant', 'shadow' => 'hover:shadow-emerald-900/10'],
                                ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'border' => 'border-amber-100', 'hover_border' => 'hover:border-amber-300', 'hover_bg' => 'hover:bg-amber-50', 'icon' => 'ph-calculator', 'shadow' => 'hover:shadow-amber-900/10'],
                                ['bg' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'hover_border' => 'hover:border-rose-300', 'hover_bg' => 'hover:bg-rose-50', 'icon' => 'ph-palette', 'shadow' => 'hover:shadow-rose-900/10'],
                                ['bg' => 'bg-cyan-50', 'text' => 'text-cyan-600', 'border' => 'border-cyan-100', 'hover_border' => 'hover:border-cyan-300', 'hover_bg' => 'hover:bg-cyan-50', 'icon' => 'ph-flask', 'shadow' => 'hover:shadow-cyan-900/10'],
                            ];
                            $theme = $colors[$index % count($colors)];
                        @endphp

                        <a href="{{ route('students.learning.subject.show', $subject->id) }}" class="group bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm {{ $theme['shadow'] }} {{ $theme['hover_border'] }} hover:-translate-y-1 transition-all duration-300 relative overflow-hidden h-full flex flex-col justify-between">
                            
                            {{-- Dekorasi Latar (Icon Pudar Besar) --}}
                            <div class="absolute -right-6 -top-6 opacity-[0.03] group-hover:opacity-10 transition-opacity duration-500 pointer-events-none transform rotate-12 group-hover:rotate-0 transition-transform">
                                <i class="ph-duotone {{ $theme['icon'] }} text-[8rem] {{ $theme['text'] }}"></i>
                            </div>

                            <div class="relative z-10">
                                {{-- Icon Box --}}
                                <div class="w-14 h-14 rounded-2xl {{ $theme['bg'] }} {{ $theme['text'] }} {{ $theme['border'] }} border flex items-center justify-center font-bold text-xl mb-4 group-hover:scale-110 transition-transform duration-300 shadow-sm">
                                    {{ substr($subject->name, 0, 1) }}
                                </div>
                                
                                {{-- Judul --}}
                                <h4 class="font-bold text-base text-slate-800 leading-snug group-hover:{{ $theme['text'] }} transition-colors line-clamp-2 mb-1">
                                    {{ $subject->name }}
                                </h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wide">Semester 1</p>
                            </div>
                            
                            {{-- Footer Tombol --}}
                            <div class="mt-6 pt-4 border-t border-slate-50 flex items-center justify-between">
                                <span class="text-[10px] font-bold text-slate-400 group-hover:{{ $theme['text'] }} transition-colors">Buka Kelas</span>
                                <div class="w-8 h-8 rounded-full bg-slate-50 group-hover:{{ $theme['bg'] }} group-hover:{{ $theme['text'] }} flex items-center justify-center transition-all">
                                    <i class="ph-bold ph-arrow-right text-xs"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>

    </div>

    {{-- SCRIPT SWEETALERT (TOAST ONLY) --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-xl shadow-lg border border-emerald-100 bg-white'
                    }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-xl shadow-lg border border-rose-100 bg-white'
                    }
                });
            @endif
        });
    </script>
</x-student-learning-layout>