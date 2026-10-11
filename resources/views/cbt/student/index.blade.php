@extends('layouts.student')

@section('content')
<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    html { scroll-behavior: smooth; }
</style>

@php
    $alpineItems = [];
    if(isset($exams) && $exams->isNotEmpty()) {
        foreach($exams as $examItem) {
            $status = $examItem->student_status;
            $filterCategory = in_array($status, ['open', 'ongoing']) ? 'active' : ($status == 'upcoming' ? 'upcoming' : $status);
            $alpineItems[] = [
                'id' => $examItem->id,
                'title' => strtolower($examItem->title),
                'subject' => strtolower($examItem->subject_name),
                'filter' => $filterCategory
            ];
        }
    }
@endphp

<div class="pb-20 relative z-10" x-data='{ 
        searchQuery: "", 
        activeFilter: "all",
        items: @json($alpineItems),
        get hasVisibleItems() {
            return this.items.some(item => {
                const matchFilter = this.activeFilter === "all" || this.activeFilter === item.filter;
                const matchSearch = item.title.includes(this.searchQuery.toLowerCase()) || item.subject.includes(this.searchQuery.toLowerCase());
                return matchFilter && matchSearch;
            });
        }
    }'>
    
    <!-- Ambient Glow Effects -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-3xl pointer-events-none -z-10"></div>
    <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8">
        
        <!-- HEADER SECTION (MICROSOFT ELEVATE DARK THEME) -->
        <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-8 md:p-12 mb-8 text-white shadow-2xl backdrop-blur-xl border border-white/10 overflow-hidden">
            <!-- Ornaments -->
            <div class="absolute -top-16 -left-16 w-60 h-60 bg-sky-500/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
            <div class="absolute -bottom-20 -right-20 w-72 h-72 bg-blue-600/10 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
            <div class="absolute top-10 right-28 w-24 h-24 bg-white/5 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3.5 py-1 rounded-full bg-sky-500/20 border border-sky-400/30 text-sky-300 text-[10px] font-black uppercase tracking-widest backdrop-blur-md shadow-sm flex items-center gap-1.5">
                            <i class="ph-fill ph-monitor-play text-sm"></i> CBT System &bull; Portal Siswa
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-3">
                        Ruang Ujian <span class="text-transparent bg-clip-text bg-gradient-to-r from-sky-400 via-sky-200 to-blue-400">Online</span>
                    </h1>
                    <p class="text-slate-300 text-sm md:text-base max-w-lg leading-relaxed font-medium">
                        Pilih jadwal ujian aktif yang tersedia untuk kelas Anda. Pastikan koneksi internet stabil sebelum memulai.
                    </p>
                </div>
                
                <!-- Statistik Real -->
                <div class="flex gap-4 shrink-0">
                    <div class="bg-slate-900/80 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center min-w-[120px] shadow-xl">
                        <p class="text-[10px] text-sky-400 font-bold uppercase tracking-widest mb-1">Tersedia</p>
                        <p class="text-3xl font-black text-sky-300">
                            {{ isset($exams) ? $exams->filter(fn($e) => in_array($e->student_status, ['open', 'ongoing']))->count() : 0 }}
                        </p>
                    </div>
                    <div class="bg-slate-900/80 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center min-w-[120px] shadow-xl">
                        <p class="text-[10px] text-emerald-400 font-bold uppercase tracking-widest mb-1">Selesai</p>
                        <p class="text-3xl font-black text-emerald-400">
                            {{ isset($exams) ? $exams->where('student_status', 'finished')->count() : 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- ALERT NOTIFIKASI -->
        @if(session('error'))
            <div class="animate-enter mb-6 bg-rose-500/10 border border-rose-500/20 text-rose-300 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-lg backdrop-blur-xl">
                <div class="bg-rose-500/20 p-2 rounded-xl text-rose-400"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif
        
        @if(session('success'))
            <div class="animate-enter mb-6 bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-lg backdrop-blur-xl">
                <div class="bg-emerald-500/20 p-2 rounded-xl text-emerald-400"><i class="ph-fill ph-check-circle text-xl"></i></div>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($exams) && $exams->isNotEmpty())
            <!-- TOOLBAR SEARCH & FILTER (Sticky) -->
            <div class="animate-enter sticky top-20 z-30 bg-[#031d3d]/90 backdrop-blur-2xl p-3 sm:p-4 rounded-[2rem] shadow-2xl border border-white/15 mb-8 flex flex-col sm:flex-row gap-4 justify-between items-center transition-all">
                <!-- Tabs Filter -->
                <div class="flex p-1 bg-slate-900/80 border border-white/10 rounded-2xl gap-1 w-full sm:w-auto overflow-x-auto custom-scrollbar">
                    <button @click="activeFilter = 'all'" 
                            :class="activeFilter === 'all' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-md shadow-sky-500/25' : 'text-slate-400 hover:text-white'" 
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap">Semua</button>
                    <button @click="activeFilter = 'active'" 
                            :class="activeFilter === 'active' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-md shadow-sky-500/25' : 'text-slate-400 hover:text-white'" 
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-sky-300 animate-pulse"></span> Tersedia
                    </button>
                    <button @click="activeFilter = 'upcoming'" 
                            :class="activeFilter === 'upcoming' ? 'bg-gradient-to-r from-sky-500 to-blue-600 text-white shadow-md shadow-sky-500/25' : 'text-slate-400 hover:text-white'" 
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap">Belum Mulai</button>
                    <button @click="activeFilter = 'finished'" 
                            :class="activeFilter === 'finished' ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-md shadow-emerald-500/25' : 'text-slate-400 hover:text-white'" 
                            class="px-4 py-2.5 rounded-xl text-xs font-bold transition-all whitespace-nowrap">Selesai</button>
                </div>

                <!-- Search Input -->
                <div class="relative w-full sm:w-72 group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400 group-focus-within:text-sky-400 transition-colors">
                        <i class="ph-bold ph-magnifying-glass text-lg"></i>
                    </div>
                    <input x-model="searchQuery" type="text" 
                           class="w-full pl-11 pr-4 py-2.5 rounded-2xl border-white/15 bg-slate-900/90 text-sm focus:bg-slate-900 focus:border-sky-400 focus:ring-sky-400 transition-all font-bold placeholder-slate-500 text-white" 
                           placeholder="Cari mapel atau judul...">
                </div>
            </div>
        @endif

        @if(!isset($exams) || $exams->isEmpty())
            <!-- STATE KOSONG -->
            <div class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-12 text-center border border-white/10 shadow-2xl backdrop-blur-xl">
                <div class="w-24 h-24 bg-sky-500/20 border border-sky-400/30 rounded-full flex items-center justify-center mx-auto mb-6 text-sky-400">
                    <i class="ph-duotone ph-desktop text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-white">Tidak Ada Ujian Aktif</h3>
                <p class="text-slate-400 mt-2 font-medium">Saat ini belum ada jadwal ujian yang tersedia untuk Anda.</p>
            </div>
        @else
            <!-- DAFTAR UJIAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($exams as $index => $examItem)
                    @php
                        $status = $examItem->student_status;
                        $statusLabel = 'Tersedia';
                        $statusClass = 'bg-sky-500/20 text-sky-300 border-sky-400/30';
                        $filterCategory = in_array($status, ['open', 'ongoing']) ? 'active' : ($status == 'upcoming' ? 'upcoming' : $status);
                        
                        if($status == 'finished') {
                            $statusLabel = 'Selesai';
                            $statusClass = 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30';
                        } elseif($status == 'ongoing') {
                            $statusLabel = 'Sedang Dikerjakan';
                            $statusClass = 'bg-amber-500/20 text-amber-300 border-amber-500/30 animate-pulse';
                        } elseif($status == 'upcoming') {
                            $statusLabel = 'Belum Mulai';
                            $statusClass = 'bg-slate-800 text-slate-400 border-white/10';
                        }

                        $kkm = $examItem->passing_grade ?? 0;
                        $score = $examItem->student_score ?? 0;
                        $isPassed = $score >= $kkm;
                    @endphp

                    <div x-show='(activeFilter === "all" || activeFilter === "{{ $filterCategory }}") && 
                                 (@json(strtolower($examItem->title)).includes(searchQuery.toLowerCase()) || 
                                  @json(strtolower($examItem->subject_name)).includes(searchQuery.toLowerCase()))'
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="animate-enter group bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] border border-white/10 shadow-2xl hover:border-sky-400/40 hover:shadow-sky-500/10 transition-all duration-300 overflow-hidden flex flex-col h-full hover:-translate-y-1.5 backdrop-blur-xl relative" style="animation-delay: {{ ($index + 1) * 50 }}ms">
                        
                        <!-- Header Card -->
                        <div class="p-6 md:p-8 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-sky-500/10 rounded-bl-[3rem] -mr-4 -mt-4 transition-transform group-hover:scale-125 blur-xl pointer-events-none"></div>
                            
                            <div class="relative z-10 flex justify-between items-start mb-5">
                                <div class="w-14 h-14 rounded-2xl bg-sky-500/20 border border-sky-400/30 text-sky-300 flex items-center justify-center shadow-sm group-hover:bg-gradient-to-r group-hover:from-sky-500 group-hover:to-blue-600 group-hover:text-white transition-all duration-300">
                                    <i class="ph-duotone ph-exam text-2xl"></i>
                                </div>
                                <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wide border shadow-sm {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            
                            <h3 class="relative z-10 text-lg md:text-xl font-black text-white leading-tight group-hover:text-sky-300 transition-colors mb-3 line-clamp-2" title="{{ $examItem->title }}">
                                {{ $examItem->title ?? 'Judul Ujian' }}
                            </h3>
                            <div class="relative z-10 flex items-center gap-2 text-xs font-bold text-sky-300 bg-sky-500/20 w-fit px-3 py-1.5 rounded-lg border border-sky-400/30">
                                <i class="ph-fill ph-chalkboard-teacher text-sky-400"></i>
                                {{ $examItem->subject_name ?? 'Mata Pelajaran' }}
                            </div>
                        </div>

                        <!-- Info Detail -->
                        <div class="px-6 md:px-8 pb-6 flex-1">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-4 rounded-2xl bg-slate-900/70 border border-white/10">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Tanggal</p>
                                    <p class="text-xs font-bold text-white flex items-center gap-1.5">
                                        <i class="ph-bold ph-calendar-blank text-sky-400"></i>
                                        {{ \Carbon\Carbon::parse($examItem->start_time)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-900/70 border border-white/10">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Durasi</p>
                                    <p class="text-xs font-bold text-white flex items-center gap-1.5">
                                        <i class="ph-bold ph-timer text-amber-400"></i>
                                        {{ $examItem->duration_minutes }} Menit
                                    </p>
                                </div>
                                <div class="p-4 rounded-2xl bg-slate-900/70 border border-white/10">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Soal</p>
                                    <p class="text-xs font-bold text-white flex items-center gap-1.5">
                                        <i class="ph-bold ph-list-numbers text-sky-400"></i>
                                        {{ $examItem->questions_count ?? 0 }} Butir
                                    </p>
                                </div>
                                
                                @if($status == 'finished')
                                    <div class="p-4 rounded-2xl bg-emerald-500/15 border border-emerald-500/30">
                                        <p class="text-[10px] uppercase font-bold text-emerald-400 mb-1">Status Nilai</p>
                                        <p class="text-xs font-black text-emerald-300 flex items-center gap-1.5">
                                            <i class="ph-fill ph-check-circle text-emerald-400"></i>
                                            Terekam
                                        </p>
                                    </div>
                                @else
                                    <div class="p-4 rounded-2xl bg-slate-900/70 border border-white/10">
                                        <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Target KKM</p>
                                        <p class="text-xs font-bold text-white flex items-center gap-1.5">
                                            <i class="ph-bold ph-target text-emerald-400"></i>
                                            {{ $kkm }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Action -->
                        <div class="p-4 md:p-5 bg-slate-950/60 border-t border-white/10" x-data="{ isNavigating: false }">
                            @if($status == 'finished')
                                <button disabled class="w-full py-4 rounded-xl bg-slate-800/80 text-slate-500 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2 border border-white/5">
                                    <i class="ph-fill ph-check-circle text-emerald-400"></i> Selesai Dikerjakan
                                </button>
                            @elseif($status == 'upcoming')
                                <button disabled class="w-full py-4 rounded-xl bg-slate-900 text-slate-500 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2 border border-white/10">
                                    <i class="ph-bold ph-lock-key"></i> Belum Dibuka
                                </button>
                            @elseif($status == 'ongoing')
                                <a href="{{ route('student.exam.run', $examItem->id) }}" @click="isNavigating = true" class="w-full py-4 rounded-xl bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold text-sm shadow-lg shadow-amber-500/20 hover:from-amber-400 hover:to-orange-500 transition-all flex items-center justify-center gap-2 group/btn relative overflow-hidden">
                                    <template x-if="!isNavigating">
                                        <div class="flex items-center gap-2">
                                            <span>Lanjutkan Mengerjakan</span>
                                            <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                                        </div>
                                    </template>
                                    <template x-if="isNavigating">
                                        <div class="flex items-center gap-2">
                                            <i class="ph-bold ph-spinner animate-spin"></i>
                                            <span>Memuat Ujian...</span>
                                        </div>
                                    </template>
                                </a>
                            @else
                                {{-- Status: Open --}}
                                <a href="{{ route('student.exam.show', $examItem->id) }}" @click="isNavigating = true" class="w-full py-4 rounded-xl bg-gradient-to-r from-sky-500 to-blue-600 text-white font-bold text-sm shadow-lg shadow-sky-500/25 hover:from-sky-400 hover:to-blue-500 transition-all flex items-center justify-center gap-2 group/btn relative overflow-hidden">
                                    <template x-if="!isNavigating">
                                        <div class="flex items-center gap-2 relative z-10">
                                            <span>Masuk Ruang Ujian</span>
                                            <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                                        </div>
                                    </template>
                                    <template x-if="isNavigating">
                                        <div class="flex items-center gap-2 relative z-10">
                                            <i class="ph-bold ph-spinner animate-spin"></i>
                                            <span>Menyiapkan Ruangan...</span>
                                        </div>
                                    </template>
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
            
            <!-- STATE KOSONG PENCARIAN -->
            <div x-show="!hasVisibleItems" x-cloak class="animate-enter bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2.5rem] p-12 text-center border border-white/10 mt-6 shadow-2xl backdrop-blur-xl">
                <div class="w-20 h-20 bg-sky-500/20 border border-sky-400/30 rounded-full flex items-center justify-center mx-auto mb-4 text-sky-400">
                    <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-white">Ujian Tidak Ditemukan</h3>
                <p class="text-slate-400 text-sm mt-1">Coba gunakan filter atau kata kunci pencarian yang lain.</p>
            </div>
        @endif
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
                        popup: 'rounded-2xl shadow-xl border border-white/10 bg-slate-900 text-white'
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
                        popup: 'rounded-2xl shadow-xl border border-white/10 bg-slate-900 text-white'
                    }
                });
            @endif
        });
    </script>
</div>
@endsection