<x-student-learning-layout>
    {{-- CDN SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- CUSTOM STYLES --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        /* Shimmer Effect untuk Prioritas */
        @keyframes shimmer {
            0% { background-position: -1000px 0; }
            100% { background-position: 1000px 0; }
        }
        .animate-shimmer {
            animation: shimmer 2s infinite linear;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.4) 50%, rgba(255,255,255,0) 100%);
            background-size: 1000px 100%;
        }
    </style>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
        
        {{-- HEADER DASHBOARD --}}
        <div class="animate-enter bg-slate-900 rounded-[2.5rem] p-8 md:p-12 mb-12 relative overflow-hidden shadow-2xl shadow-slate-900/20 border border-slate-800 group">
            {{-- Decoration --}}
            <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-900 to-indigo-900/40"></div>
            <div class="absolute top-0 right-0 w-80 h-80 bg-rose-500/20 rounded-full blur-[100px] -mr-20 -mt-20 pointer-events-none group-hover:bg-rose-500/30 transition-all duration-1000"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/20 rounded-full blur-[80px] -ml-10 -mb-10 pointer-events-none group-hover:bg-blue-500/30 transition-all duration-1000"></div>
            <div class="absolute inset-0 opacity-30 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-10">
                <div class="text-center md:text-left flex-1">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 backdrop-blur-md mb-6 shadow-sm">
                        <span class="relative flex h-2.5 w-2.5">
                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                          <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                        </span>
                        <span class="text-[11px] font-bold text-slate-200 uppercase tracking-widest">Tahun Ajaran Aktif</span>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight leading-tight">
                        Halo, <span class="text-transparent bg-clip-text bg-gradient-to-r from-rose-300 via-purple-300 to-indigo-300 animate-gradient">{{ explode(' ', Auth::guard('student')->user()->name)[0] }}!</span>
                    </h1>
                    
                    <p class="text-slate-400 font-medium text-base md:text-lg max-w-xl mb-8 leading-relaxed">
                        Selamat datang kembali di ruang belajar digitalmu. <br class="hidden md:block"> Jangan lupa cek tugas prioritas sebelum memulai materi baru hari ini.
                    </p>

                    <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-slate-300 text-sm font-bold">
                        <div class="flex items-center gap-2 bg-white/5 px-4 py-2 rounded-xl border border-white/10 hover:bg-white/10 transition-colors">
                            <i class="ph-fill ph-student text-rose-400 text-lg"></i>
                            <span>Kelas {{ Auth::guard('student')->user()->schoolClass->name ?? 'Umum' }}</span>
                        </div>
                        <div class="flex items-center gap-2 bg-white/5 px-4 py-2 rounded-xl border border-white/10 hover:bg-white/10 transition-colors">
                            <i class="ph-fill ph-calendar text-blue-400 text-lg"></i>
                            <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                        </div>
                    </div>
                </div>
                
                {{-- Ilustrasi 3D Sederhana (CSS Only) --}}
                <div class="hidden md:block relative transform hover:scale-105 transition-transform duration-700">
                    <div class="relative w-40 h-40">
                        <div class="absolute inset-0 bg-gradient-to-tr from-blue-600 to-purple-600 rounded-[2rem] rotate-6 opacity-60 blur-lg"></div>
                        <div class="absolute inset-0 bg-gradient-to-tr from-slate-800 to-slate-900 rounded-[2rem] shadow-2xl border border-white/10 flex items-center justify-center z-10">
                            <i class="ph-duotone ph-rocket-launch text-7xl text-white drop-shadow-[0_0_15px_rgba(255,255,255,0.5)]"></i>
                        </div>
                        {{-- Floating Badges --}}
                        <div class="absolute -top-4 -right-4 bg-white text-slate-900 p-3 rounded-xl shadow-lg font-black text-xs z-20 animate-bounce">
                            <i class="ph-fill ph-star text-yellow-400"></i> Semangat!
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- BAGIAN PRIORITAS (Tugas/Materi Baru) --}}
        @if(isset($prioritySubjects) && $prioritySubjects->count() > 0)
            <div class="animate-enter mb-16" style="animation-delay: 100ms">
                <div class="flex items-end justify-between mb-8 px-2">
                    <div>
                        <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                            <span class="w-10 h-10 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center text-xl shadow-sm rotate-3">
                                <i class="ph-fill ph-fire"></i>
                            </span>
                            Prioritas Belajar
                        </h2>
                        <p class="text-sm font-bold text-slate-400 mt-2 ml-14">Selesaikan tugas ini segera.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($prioritySubjects as $index => $subject)
                        <a href="{{ route('students.learning.subject.show', $subject->id) }}" class="group relative bg-white rounded-[2rem] p-1 shadow-sm hover:shadow-xl hover:shadow-rose-900/10 transition-all duration-300 hover:-translate-y-1">
                            {{-- Gradient Border Fake --}}
                            <div class="absolute inset-0 bg-gradient-to-br from-rose-100 to-orange-50 rounded-[2rem] opacity-50 group-hover:opacity-100 transition-opacity"></div>
                            
                            <div class="relative bg-white rounded-[1.8rem] p-6 h-full border border-rose-100/50 flex flex-col">
                                {{-- Header Kartu --}}
                                <div class="flex justify-between items-start mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-50 to-orange-50 text-rose-600 border border-rose-100 flex items-center justify-center text-2xl shadow-inner group-hover:scale-110 transition-transform">
                                        <i class="ph-duotone ph-warning-circle"></i>
                                    </div>
                                    @if($subject->active_tasks_count > 0)
                                        <span class="relative overflow-hidden bg-rose-600 text-white text-[10px] font-black px-3 py-1.5 rounded-lg shadow-md shadow-rose-200 uppercase tracking-wider">
                                            <span class="relative z-10">{{ $subject->active_tasks_count }} Tugas</span>
                                            <div class="absolute inset-0 -translate-x-full animate-shimmer z-0"></div>
                                        </span>
                                    @endif
                                </div>
                                
                                <h3 class="font-bold text-lg text-slate-800 mb-1 group-hover:text-rose-600 transition-colors line-clamp-1">{{ $subject->name }}</h3>
                                <p class="text-xs text-slate-400 font-bold mb-4">Deadline semakin dekat!</p>
                                
                                {{-- Footer Action --}}
                                <div class="mt-auto pt-4 border-t border-slate-50 flex items-center justify-between">
                                    <span class="text-xs font-bold text-rose-500 group-hover:underline">Kerjakan Sekarang</span>
                                    <i class="ph-bold ph-arrow-right text-rose-400 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- BAGIAN SEMUA MATA PELAJARAN --}}
        <div class="animate-enter" style="animation-delay: 200ms">
            <div class="flex items-end justify-between mb-8 px-2">
                <div>
                    <h2 class="text-2xl font-black text-slate-800 flex items-center gap-3">
                        <span class="w-10 h-10 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center text-xl shadow-sm -rotate-3">
                            <i class="ph-fill ph-books"></i>
                        </span>
                        Ruang Kelas
                    </h2>
                    <p class="text-sm font-bold text-slate-400 mt-2 ml-14">Daftar semua mata pelajaranmu.</p>
                </div>
            </div>
            
            @if(!isset($allSubjects) || $allSubjects->isEmpty())
                <div class="bg-white rounded-[2.5rem] p-12 text-center border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <i class="ph-duotone ph-books text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-slate-800 mb-2">Belum Ada Mata Pelajaran</h3>
                    <p class="text-slate-500 font-medium max-w-md mx-auto">Data mata pelajaran belum ditambahkan oleh admin atau kamu belum terdaftar di kelas manapun.</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    @foreach($allSubjects as $index => $subject)
                        {{-- 
                            LOGIC TEMA & ICON DINAMIS 
                            Disini kita cek nama mapel untuk menentukan icon & warna yang tepat.
                        --}}
                        @php
                            // 1. Definisi Pilihan Warna (Palette)
                            $palettes = [
                                'blue'    => ['bg' => 'from-blue-500 to-indigo-500', 'light' => 'bg-blue-50', 'text' => 'text-blue-600', 'border' => 'border-blue-100', 'shadow' => 'shadow-blue-200'],
                                'emerald' => ['bg' => 'from-emerald-500 to-teal-500', 'light' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'border' => 'border-emerald-100', 'shadow' => 'shadow-emerald-200'],
                                'orange'  => ['bg' => 'from-orange-500 to-amber-500', 'light' => 'bg-orange-50', 'text' => 'text-orange-600', 'border' => 'border-orange-100', 'shadow' => 'shadow-orange-200'],
                                'purple'  => ['bg' => 'from-purple-500 to-fuchsia-500', 'light' => 'bg-purple-50', 'text' => 'text-purple-600', 'border' => 'border-purple-100', 'shadow' => 'shadow-purple-200'],
                                'rose'    => ['bg' => 'from-rose-500 to-pink-500', 'light' => 'bg-rose-50', 'text' => 'text-rose-600', 'border' => 'border-rose-100', 'shadow' => 'shadow-rose-200'],
                                'cyan'    => ['bg' => 'from-cyan-500 to-sky-500', 'light' => 'bg-cyan-50', 'text' => 'text-cyan-600', 'border' => 'border-cyan-100', 'shadow' => 'shadow-cyan-200'],
                            ];

                            // 2. Deteksi Nama Mapel
                            $name = strtolower($subject->name);
                            $selectedIcon = 'ph-book-bookmark'; // Default Icon
                            $selectedTheme = 'blue'; // Default Theme

                            if (str_contains($name, 'informatika') || str_contains($name, 'tik') || str_contains($name, 'komputer') || str_contains($name, 'coding')) {
                                $selectedIcon = 'ph-desktop'; // Icon Komputer
                                $selectedTheme = 'cyan';
                            } elseif (str_contains($name, 'seni') || str_contains($name, 'budaya') || str_contains($name, 'lukis') || str_contains($name, 'musik')) {
                                $selectedIcon = 'ph-palette'; // Icon Palette Lukis
                                $selectedTheme = 'purple';
                            } elseif (str_contains($name, 'matematika') || str_contains($name, 'kalkulus') || str_contains($name, 'aljabar')) {
                                $selectedIcon = 'ph-calculator'; // Icon Kalkulator
                                $selectedTheme = 'orange';
                            } elseif (str_contains($name, 'ipa') || str_contains($name, 'fisika') || str_contains($name, 'kimia') || str_contains($name, 'biologi')) {
                                $selectedIcon = 'ph-flask'; // Icon Lab
                                $selectedTheme = 'emerald';
                            } elseif (str_contains($name, 'ips') || str_contains($name, 'sejarah') || str_contains($name, 'geografi') || str_contains($name, 'ekonomi')) {
                                $selectedIcon = 'ph-globe-hemisphere-west'; // Icon Globe
                                $selectedTheme = 'blue';
                            } elseif (str_contains($name, 'bahasa') || str_contains($name, 'inggris') || str_contains($name, 'indonesia') || str_contains($name, 'sunda') || str_contains($name, 'jawa')) {
                                $selectedIcon = 'ph-translate'; // Icon Bahasa
                                $selectedTheme = 'rose';
                            } elseif (str_contains($name, 'agama') || str_contains($name, 'pai') || str_contains($name, 'quran') || str_contains($name, 'akidah')) {
                                $selectedIcon = 'ph-hands-praying'; // Icon Ibadah
                                $selectedTheme = 'emerald';
                            } elseif (str_contains($name, 'pjok') || str_contains($name, 'olahraga') || str_contains($name, 'penjas')) {
                                $selectedIcon = 'ph-soccer-ball'; // Icon Bola
                                $selectedTheme = 'orange';
                            } elseif (str_contains($name, 'pkn') || str_contains($name, 'pancasila') || str_contains($name, 'kewarganegaraan')) {
                                $selectedIcon = 'ph-scales'; // Icon Timbangan/Hukum
                                $selectedTheme = 'blue';
                            } elseif (str_contains($name, 'prakarya') || str_contains($name, 'kewirausahaan')) {
                                $selectedIcon = 'ph-lightbulb'; // Icon Ide
                                $selectedTheme = 'purple';
                            } else {
                                // Fallback acak biar variatif
                                $keys = array_keys($palettes);
                                $selectedTheme = $keys[$index % count($keys)];
                            }

                            $t = $palettes[$selectedTheme];
                        @endphp

                        <a href="{{ route('students.learning.subject.show', $subject->id) }}" class="group relative flex flex-col bg-white rounded-[2rem] p-1.5 shadow-sm hover:shadow-2xl hover:shadow-slate-200/50 transition-all duration-300 hover:-translate-y-1.5 h-full">
                            
                            {{-- Card Inner Container --}}
                            <div class="flex-1 bg-white rounded-[1.7rem] p-5 border border-slate-100 relative overflow-hidden">
                                
                                {{-- Dekorasi Background Hover --}}
                                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-gradient-to-br {{ $t['bg'] }} opacity-0 group-hover:opacity-10 blur-2xl transition-opacity duration-500"></div>

                                {{-- Icon & Meta Header --}}
                                <div class="flex justify-between items-start mb-5 relative z-10">
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br {{ $t['bg'] }} text-white flex items-center justify-center text-2xl shadow-lg {{ $t['shadow'] }} group-hover:scale-110 transition-transform duration-300">
                                        {{-- ICON DINAMIS --}}
                                        <i class="ph-duotone {{ $selectedIcon }}"></i>
                                    </div>
                                    
                                    {{-- Status Badge (Materi Baru) --}}
                                    @if($subject->new_materials_count > 0)
                                        <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-600 border border-emerald-100 px-2.5 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                            {{ $subject->new_materials_count }} Materi
                                        </span>
                                    @endif
                                </div>
                                
                                {{-- Judul Mapel --}}
                                <h4 class="font-bold text-lg text-slate-800 leading-snug mb-1 group-hover:{{ $t['text'] }} transition-colors line-clamp-2">
                                    {{ $subject->name }}
                                </h4>
                                <p class="text-xs font-bold text-slate-400">Guru Pengampu</p>
                                
                                {{-- Teacher Info (Optional / Placeholder) --}}
                                <div class="mt-4 flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 border border-white shadow-sm">
                                        <i class="ph-bold ph-user"></i>
                                    </div>
                                    <span class="text-xs font-bold text-slate-600 truncate">Tim Pengajar</span>
                                </div>
                            </div>

                            {{-- Footer Button Area --}}
                            <div class="px-5 py-3 flex items-center justify-between">
                                <span class="text-[10px] font-black text-slate-300 uppercase tracking-widest group-hover:{{ $t['text'] }} transition-colors">Masuk Kelas</span>
                                <div class="w-8 h-8 rounded-full {{ $t['light'] }} {{ $t['text'] }} flex items-center justify-center opacity-0 group-hover:opacity-100 transform translate-x-2 group-hover:translate-x-0 transition-all duration-300">
                                    <i class="ph-bold ph-arrow-right"></i>
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