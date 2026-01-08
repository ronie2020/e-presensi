@extends('layouts.public')

@section('content')
    @php \Carbon\Carbon::setLocale('id'); @endphp

    <style>
        @import url('https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap');
        .font-jakarta { font-family: 'Plus Jakarta Sans', sans-serif; }
        .bottom-nav { box-shadow: 0 -4px 20px rgba(0,0,0,0.05); }
        [x-cloak] { display: none !important; }
        
        /* Hide scrollbar for clean UI */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>

    <div class="font-jakarta bg-slate-50 min-h-screen pb-32 md:pb-12" 
         x-data="{ 
            activeSection: 'overview',
            habitsChecked: {
                h1: {{ isset($todayEntry) && $todayEntry->check_1 ? 'true' : 'false' }},
                h2: {{ isset($todayEntry) && $todayEntry->check_2 ? 'true' : 'false' }},
                h3: {{ isset($todayEntry) && $todayEntry->check_3 ? 'true' : 'false' }},
                h4: {{ isset($todayEntry) && $todayEntry->check_4 ? 'true' : 'false' }},
                h5: {{ isset($todayEntry) && $todayEntry->check_5 ? 'true' : 'false' }},
                h6: {{ isset($todayEntry) && $todayEntry->check_6 ? 'true' : 'false' }},
                h7: {{ isset($todayEntry) && $todayEntry->check_7 ? 'true' : 'false' }}
            },
            get totalDone() {
                return Object.values(this.habitsChecked).filter(v => v === true).length;
            },
            get progress() {
                return Math.round((this.totalDone / 7) * 100);
            }
         }">

        <!-- 1. TOP HEADER (STICKY) -->
        <header class="bg-white/90 backdrop-blur-md sticky top-0 z-40 border-b border-slate-100 transition-all duration-300">
            <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <i class="ph-fill ph-student text-2xl"></i>
                    </div>
                    <div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-0.5">Anak Hebat Hub</p>
                        <h2 class="text-sm font-bold text-slate-800 leading-none">{{ Auth::guard('student')->user()->name }}</h2>
                    </div>
                </div>
                <a href="{{ route('portal.show', Auth::guard('student')->id()) }}" class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition-colors">
                    <i class="ph-bold ph-user"></i>
                </a>
            </div>
        </header>

        <main class="max-w-6xl mx-auto px-4 pt-6">
            
            <!-- 2. OVERVIEW SECTION -->
            <div x-show="activeSection === 'overview'" x-transition:enter.duration.500ms>
                
                <!-- Welcome Banner & Progress -->
                <div class="relative bg-gradient-to-br from-blue-900 via-blue-800 to-slate-900 rounded-[2.5rem] p-8 mb-8 text-white shadow-2xl shadow-blue-900/30 overflow-hidden border border-white/10">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl -mr-20 -mt-20"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="text-center md:text-left flex-1">
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/10 border border-white/10 text-[10px] font-bold uppercase tracking-widest mb-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                            </div>
                            <h1 class="text-2xl md:text-4xl font-black mb-2 tracking-tight leading-tight">Apa misimu hari ini? 🚀</h1>
                            <p class="text-blue-100/70 text-sm max-w-sm mx-auto md:mx-0">
                                Selesaikan 7 kebiasaan baikmu untuk membuka potensi terbaik dirimu.
                            </p>
                        </div>
                        
                        <!-- Progress Circle -->
                        <div class="shrink-0 relative group cursor-pointer" @click="document.getElementById('habitSection').scrollIntoView({behavior: 'smooth'})">
                            <div class="w-32 h-32 bg-white/10 rounded-full flex items-center justify-center backdrop-blur-md border border-white/10 relative shadow-inner">
                                <svg class="w-full h-full transform -rotate-90">
                                    <circle cx="50%" cy="50%" r="42%" stroke="currentColor" stroke-width="8" fill="transparent" class="text-slate-900/30"></circle>
                                    <circle cx="50%" cy="50%" r="42%" stroke="currentColor" stroke-width="8" fill="transparent" 
                                            class="text-emerald-400 transition-all duration-1000 ease-out"
                                            :stroke-dasharray="264"
                                            :stroke-dashoffset="264 - (264 * progress / 100)"></circle>
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-2xl font-black" x-text="progress + '%'"></span>
                                    <span class="text-[8px] font-bold uppercase tracking-widest text-blue-200">Selesai</span>
                                </div>
                            </div>
                            <!-- Tooltip -->
                            <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 opacity-0 group-hover:opacity-100 transition-opacity text-[10px] font-bold text-white bg-slate-900 px-2 py-1 rounded">
                                Klik untuk isi
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widgets Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6 mb-8">
                    
                    <!-- 1. Jadwal Widget -->
                    <a href="{{ route('student.schedule.index') }}" class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 group hover:border-blue-200 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest flex items-center gap-2">
                                <i class="ph-fill ph-calendar text-blue-500 text-base"></i> Jadwal Pelajaran
                            </h3>
                            <i class="ph-bold ph-arrow-right text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 group-hover:bg-blue-50/50 group-hover:border-blue-100 transition-colors">
                            @php
                                // Logika sederhana untuk jadwal (bisa disesuaikan dengan data real dari controller)
                                $today = \Carbon\Carbon::now()->locale('id')->dayName;
                                $schedule = Auth::guard('student')->user()->schoolClass->schedules->where('day', $today)->sortBy('start_time')->first();
                            @endphp
                            
                            @if($schedule)
                                <p class="text-xs font-black text-blue-600 mb-0.5">{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} WIB</p>
                                <h4 class="font-bold text-slate-800 leading-tight line-clamp-1">{{ $schedule->subject->name }}</h4>
                                <p class="text-[10px] text-slate-400 mt-1 line-clamp-1">{{ $schedule->teacher->name }}</p>
                            @else
                                <div class="text-center py-2">
                                    <p class="text-xs font-bold text-slate-400 italic">Tidak ada jadwal aktif saat ini.</p>
                                </div>
                            @endif
                        </div>
                    </a>

                    <!-- 2. Poin Merit Widget -->
                    <div class="bg-emerald-600 p-5 rounded-[2rem] shadow-xl shadow-emerald-900/10 text-white relative overflow-hidden group">
                        <div class="absolute -right-6 -bottom-6 text-9xl text-white/10 group-hover:rotate-12 transition-transform duration-500">
                            <i class="ph-fill ph-crown"></i>
                        </div>
                        <h3 class="font-bold text-emerald-200 text-[10px] uppercase tracking-widest mb-4">Poin Karakter (Merit)</h3>
                        <div class="flex items-baseline gap-2 relative z-10">
                            <span class="text-5xl font-black tracking-tighter">{{ number_format($totalPoints ?? 0) }}</span>
                            <span class="text-xs font-bold text-emerald-200 bg-white/20 px-2 py-0.5 rounded">XP</span>
                        </div>
                        <div class="mt-4 pt-4 border-t border-white/20 relative z-10">
                            <p class="text-[10px] text-emerald-50 font-medium italic flex items-center gap-1">
                                <i class="ph-fill ph-trend-up"></i> Pertahankan prestasimu!
                            </p>
                        </div>
                    </div>

                    <!-- 3. Pesan Sekolah Widget -->
                    <a href="{{ route('student.liaison.index') }}" class="bg-white p-5 rounded-[2rem] shadow-sm border border-slate-100 group hover:border-indigo-200 transition-all">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-black text-slate-400 text-[10px] uppercase tracking-widest flex items-center gap-2">
                                <i class="ph-fill ph-chat-circle-text text-indigo-500 text-base"></i> Pesan Masuk
                            </h3>
                            @if(isset($liaison_messages) && $liaison_messages->count() > 0)
                                <span class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></span>
                            @endif
                        </div>
                        
                        @php $lastMsg = isset($liaison_messages) ? $liaison_messages->first() : null; @endphp
                        
                        @if($lastMsg)
                            <div class="bg-indigo-50/50 rounded-2xl p-4 border border-indigo-100 group-hover:bg-indigo-50 transition-colors h-24 flex flex-col justify-center">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-bold bg-white text-indigo-600 px-1.5 py-0.5 rounded border border-indigo-100 shadow-sm uppercase">Info</span>
                                    <p class="text-[10px] font-black text-slate-700 line-clamp-1">{{ $lastMsg->title }}</p>
                                </div>
                                <p class="text-[10px] text-slate-500 line-clamp-2 font-medium italic leading-relaxed">"{{ $lastMsg->message }}"</p>
                            </div>
                        @else
                            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 h-24 flex flex-col items-center justify-center text-center">
                                <i class="ph-duotone ph-envelope-open text-2xl text-slate-300 mb-1"></i>
                                <p class="text-[10px] font-bold text-slate-400">Tidak ada pesan baru</p>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- FORM 7 KEBIASAAN (Full Integrated) -->
                <div id="habitSection" class="bg-white p-6 md:p-8 rounded-[2.5rem] border border-slate-100 shadow-sm scroll-mt-24">
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 flex items-center gap-2">
                                <i class="ph-fill ph-list-checks text-blue-500"></i> Checklist Kebiasaan
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">Klik pada kartu untuk menandai selesai.</p>
                        </div>
                        @if(isset($todayEntry))
                            <div class="px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl text-xs font-bold border border-emerald-100 flex items-center gap-2">
                                <i class="ph-fill ph-check-circle"></i> Laporan Hari Ini Tersimpan
                            </div>
                        @endif
                    </div>

                    <form action="{{ route('student.habits.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        {{-- Hidden Checkboxes synced with UI --}}
                        <input type="checkbox" name="check_1" x-model="habitsChecked.h1" class="hidden">
                        <input type="checkbox" name="check_2" x-model="habitsChecked.h2" class="hidden">
                        <input type="checkbox" name="check_3" x-model="habitsChecked.h3" class="hidden">
                        <input type="checkbox" name="check_4" x-model="habitsChecked.h4" class="hidden">
                        <input type="checkbox" name="check_5" x-model="habitsChecked.h5" class="hidden">
                        <input type="checkbox" name="check_6" x-model="habitsChecked.h6" class="hidden">
                        <input type="checkbox" name="check_7" x-model="habitsChecked.h7" class="hidden">

                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                            @php
                                $habitsList = [
                                    ['id' => 'h1', 'icon' => 'sun-horizon', 'color' => 'blue', 'label' => 'Bangun Pagi'],
                                    ['id' => 'h2', 'icon' => 'drop', 'color' => 'cyan', 'label' => 'Mandi Rapi'],
                                    ['id' => 'h3', 'icon' => 'sneaker-move', 'color' => 'indigo', 'label' => 'Olahraga'],
                                    ['id' => 'h4', 'icon' => 'book-open-text', 'color' => 'blue', 'label' => 'Belajar'],
                                    ['id' => 'h5', 'icon' => 'carrot', 'color' => 'emerald', 'label' => 'Makan Sehat'],
                                    ['id' => 'h6', 'icon' => 'users-three', 'color' => 'purple', 'label' => 'Sosial'],
                                    ['id' => 'h7', 'icon' => 'moon-stars', 'color' => 'slate', 'label' => 'Tidur Cukup'],
                                ];
                            @endphp

                            @foreach($habitsList as $h)
                                <div @if(!isset($todayEntry)) @click="habitsChecked.{{ $h['id'] }} = !habitsChecked.{{ $h['id'] }}" @endif
                                     :class="habitsChecked.{{ $h['id'] }} ? 'bg-{{ $h['color'] }}-50 border-{{ $h['color'] }}-200 ring-1 ring-{{ $h['color'] }}-200' : 'bg-white border-slate-100 hover:border-{{ $h['color'] }}-200'"
                                     class="relative p-5 rounded-[1.8rem] border-2 flex flex-col items-center justify-center gap-3 cursor-pointer transition-all active:scale-95 group overflow-hidden {{ isset($todayEntry) ? 'opacity-80 pointer-events-none' : '' }}">
                                    
                                    {{-- Checkmark Animation --}}
                                    <div x-show="habitsChecked.{{ $h['id'] }}" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="scale-0" x-transition:enter-end="scale-100" class="absolute top-3 right-3 text-{{ $h['color'] }}-500">
                                        <i class="ph-fill ph-check-circle text-xl bg-white rounded-full"></i>
                                    </div>

                                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center text-2xl transition-transform group-hover:scale-110"
                                         :class="habitsChecked.{{ $h['id'] }} ? 'bg-white text-{{ $h['color'] }}-500 shadow-sm' : 'bg-{{ $h['color'] }}-50 text-{{ $h['color'] }}-500'">
                                        <i class="ph-duotone ph-{{ $h['icon'] }}"></i>
                                    </div>
                                    <span class="text-[10px] font-black text-slate-700 text-center uppercase tracking-tight leading-tight">{{ $h['label'] }}</span>
                                </div>
                            @endforeach
                            
                            {{-- Card Tambahan untuk upload jika belum lapor --}}
                            @if(!isset($todayEntry))
                                <label class="relative p-5 rounded-[1.8rem] border-2 border-dashed border-slate-300 flex flex-col items-center justify-center gap-2 cursor-pointer hover:bg-slate-50 hover:border-slate-400 transition-all group bg-slate-50/50">
                                    <div class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-400 group-hover:text-blue-500 shadow-sm">
                                        <i class="ph-bold ph-camera"></i>
                                    </div>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase text-center group-hover:text-blue-500">Upload Bukti</span>
                                    <input type="file" name="habit_photo" class="hidden" accept="image/*" required onchange="alert('Foto terpilih!')">
                                </label>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        @if(!isset($todayEntry))
                            <div x-show="totalDone > 0" x-transition class="flex justify-end">
                                <button type="submit" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-slate-900/20 hover:bg-blue-600 transition-all flex items-center gap-3 active:scale-95">
                                    <span>Simpan Laporan</span>
                                    <i class="ph-bold ph-paper-plane-right text-lg"></i>
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            <!-- 3. MENU SECTION (Grid Navigation) -->
            <div x-show="activeSection === 'menu'" x-cloak x-transition:enter.duration.500ms>
                <div class="grid grid-cols-2 gap-4">
                    <a href="{{ route('student.schedule.index') }}" class="p-6 bg-white rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center gap-4 group hover:bg-blue-600 transition-all">
                        <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center text-3xl group-hover:bg-white/20 group-hover:text-white transition-colors">
                            <i class="ph-fill ph-calendar-blank"></i>
                        </div>
                        <span class="text-xs font-black text-slate-700 group-hover:text-white uppercase tracking-widest">Jadwal Pelajaran</span>
                    </a>
                    <a href="{{ route('student.liaison.index') }}" class="p-6 bg-white rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center gap-4 group hover:bg-indigo-600 transition-all">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center text-3xl group-hover:bg-white/20 group-hover:text-white transition-colors">
                            <i class="ph-fill ph-notebook"></i>
                        </div>
                        <span class="text-xs font-black text-slate-700 group-hover:text-white uppercase tracking-widest">Buku Penghubung</span>
                    </a>
                    <a href="{{ route('student.complaints.index') }}" class="p-6 bg-white rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center gap-4 group hover:bg-rose-600 transition-all">
                        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-3xl flex items-center justify-center text-3xl group-hover:bg-white/20 group-hover:text-white transition-colors">
                            <i class="ph-fill ph-warning-circle"></i>
                        </div>
                        <span class="text-xs font-black text-slate-700 group-hover:text-white uppercase tracking-widest">Layanan Pengaduan</span>
                    </a>
                    <a href="{{ route('portal.show', Auth::guard('student')->id()) }}" class="p-6 bg-white rounded-[2rem] border border-slate-100 shadow-sm flex flex-col items-center gap-4 group hover:bg-slate-800 transition-all">
                        <div class="w-16 h-16 bg-slate-50 text-slate-800 rounded-3xl flex items-center justify-center text-3xl group-hover:bg-white/20 group-hover:text-white transition-colors">
                            <i class="ph-fill ph-user-circle"></i>
                        </div>
                        <span class="text-xs font-black text-slate-700 group-hover:text-white uppercase tracking-widest">Profil Saya</span>
                    </a>
                </div>
                
                <div class="mt-8 text-center">
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-rose-400 hover:text-rose-600 transition-colors uppercase tracking-widest">
                            Keluar Aplikasi
                        </button>
                    </form>
                </div>
            </div>

        </main>

        <!-- 4. BOTTOM NAVIGATION (App-like Experience) -->
        <nav class="bottom-nav fixed bottom-6 left-1/2 -translate-x-1/2 w-[90%] max-w-md bg-white/90 backdrop-blur-xl border border-white/50 ring-1 ring-slate-200/50 rounded-[2.5rem] p-2 flex items-center justify-between z-50 shadow-2xl">
            <button @click="activeSection = 'overview'" 
                    :class="activeSection === 'overview' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'text-slate-400 hover:bg-slate-50'"
                    class="flex-1 py-4 rounded-[2rem] flex flex-col items-center gap-1 transition-all duration-300 group">
                <i class="ph-fill ph-house text-2xl group-hover:scale-110 transition-transform"></i>
                <span class="text-[8px] font-black uppercase tracking-widest" x-show="activeSection === 'overview'" x-transition>Home</span>
            </button>
            
            <a href="{{ route('students.learning.index') }}" class="flex-1 py-4 text-slate-400 hover:text-blue-600 flex flex-col items-center gap-1 transition-colors group">
                <i class="ph-fill ph-books text-2xl group-hover:scale-110 transition-transform"></i>
            </a>

            <!-- Floating Main Action (Highlight) -->
            <div class="relative -top-8 group">
                <button @click="document.getElementById('habitSection').scrollIntoView({behavior: 'smooth'})" class="w-16 h-16 bg-gradient-to-br from-blue-600 to-indigo-600 rounded-full flex items-center justify-center text-white shadow-2xl shadow-blue-500/40 border-[6px] border-slate-50 active:scale-90 transition-all hover:-translate-y-1">
                    <i class="ph-bold ph-plus text-2xl"></i>
                </button>
            </div>

            <a href="{{ route('student.exam.index') }}" class="flex-1 py-4 text-slate-400 hover:text-rose-600 flex flex-col items-center gap-1 transition-colors group">
                <i class="ph-fill ph-desktop text-2xl group-hover:scale-110 transition-transform"></i>
            </a>

            <button @click="activeSection = 'menu'" 
                    :class="activeSection === 'menu' ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/20' : 'text-slate-400 hover:bg-slate-50'"
                    class="flex-1 py-4 rounded-[2rem] flex flex-col items-center gap-1 transition-all duration-300 group">
                <i class="ph-fill ph-squares-four text-2xl group-hover:scale-110 transition-transform"></i>
                <span class="text-[8px] font-black uppercase tracking-widest" x-show="activeSection === 'menu'" x-transition>Menu</span>
            </button>
        </nav>

    </div>
@endsection