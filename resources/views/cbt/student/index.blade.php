@extends('layouts.student')

@section('content')
<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    /* Tambahan efek transisi halus untuk scroll */
    html { scroll-behavior: smooth; }
</style>

@php
    // 1. PERBAIKAN: Persiapkan data array di PHP agar bersih dan 100% aman
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

{{-- 2. PERBAIKAN: Gunakan tanda kutip TUNGGAL (') untuk x-data --}}
<div class="min-h-screen bg-slate-50/50 pb-20" x-data='{ 
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
    
    <!-- HEADER SECTION (TEMA BLUE-950 & CYAN) -->
    <div class="animate-enter relative bg-gradient-to-br from-blue-950 via-blue-900 to-blue-950 pb-28 pt-12 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[3rem] shadow-2xl shadow-blue-950/20">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-80 h-80 bg-cyan-500/20 rounded-full blur-[100px] -mr-20 -mt-20 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/20 rounded-full blur-[80px] -ml-10 -mb-10 pointer-events-none"></div>
        
        <div class="relative max-w-5xl mx-auto z-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full bg-cyan-500/20 border border-cyan-500/30 text-cyan-300 text-[10px] font-black uppercase tracking-widest backdrop-blur-md">
                            <i class="ph-fill ph-monitor-play"></i> CBT System
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-2">Ujian Online</h1>
                    <p class="text-cyan-100/80 text-sm md:text-base max-w-lg leading-relaxed font-medium">
                        Pilih jadwal ujian yang tersedia dan pastikan koneksi internet stabil sebelum memulai.
                    </p>
                </div>
                
                <!-- Statistik Real -->
                <div class="flex gap-4">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center min-w-[110px] shadow-lg">
                        <p class="text-[10px] text-cyan-200/70 font-bold uppercase tracking-widest mb-1">Tersedia</p>
                        <p class="text-3xl font-black text-cyan-400">
                            {{ isset($exams) ? $exams->filter(fn($e) => in_array($e->student_status, ['open', 'ongoing']))->count() : 0 }}
                        </p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center min-w-[110px] shadow-lg">
                        <p class="text-[10px] text-cyan-200/70 font-bold uppercase tracking-widest mb-1">Selesai</p>
                        <p class="text-3xl font-black text-emerald-400">
                            {{ isset($exams) ? $exams->where('student_status', 'finished')->count() : 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN LIST CONTAINER -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-20">
        
        <!-- ALERT ERROR -->
        @if(session('error'))
            <div class="animate-enter mb-6 bg-rose-50 border border-rose-100 text-rose-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-lg shadow-rose-900/5">
                <div class="bg-rose-100 p-2 rounded-xl text-rose-600"><i class="ph-fill ph-warning-circle text-xl"></i></div>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif
        
        @if(session('success'))
            <div class="animate-enter mb-6 bg-emerald-50 border border-emerald-100 text-emerald-800 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-lg shadow-emerald-900/5">
                <div class="bg-emerald-100 p-2 rounded-xl text-emerald-600"><i class="ph-fill ph-check-circle text-xl"></i></div>
                <span class="font-bold text-sm">{{ session('success') }}</span>
            </div>
        @endif

        @if(isset($exams) && $exams->isNotEmpty())
            <!-- TOOLBAR SEARCH & FILTER (Sticky) -->
            <div class="animate-enter sticky top-4 z-30 bg-white/80 backdrop-blur-xl p-3 sm:p-4 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100/50 mb-8 flex flex-col sm:flex-row gap-4 justify-between items-center transition-all">
                <!-- Tabs Filter -->
                <div class="flex p-1 bg-slate-100/50 rounded-xl gap-1 w-full sm:w-auto overflow-x-auto custom-scrollbar">
                    <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'bg-white shadow text-slate-800' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Semua</button>
                    <button @click="activeFilter = 'active'" :class="activeFilter === 'active' ? 'bg-white shadow text-cyan-600' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span> Tersedia</button>
                    <button @click="activeFilter = 'upcoming'" :class="activeFilter === 'upcoming' ? 'bg-white shadow text-amber-600' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Belum Mulai</button>
                    <button @click="activeFilter = 'finished'" :class="activeFilter === 'finished' ? 'bg-white shadow text-emerald-600' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Selesai</button>
                </div>

                <!-- Search Input -->
                <div class="relative w-full sm:w-72 group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-cyan-500 transition-colors">
                        <i class="ph-bold ph-magnifying-glass"></i>
                    </div>
                    <input x-model="searchQuery" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-white shadow-inner text-sm focus:bg-white focus:border-cyan-500 focus:ring-4 focus:ring-cyan-50 transition-all font-medium placeholder-slate-400" placeholder="Cari mapel atau judul...">
                </div>
            </div>
        @endif

        @if(!isset($exams) || $exams->isEmpty())
            <!-- STATE KOSONG (DATABASE BENAR-BENAR KOSONG) -->
            <div class="animate-enter bg-white rounded-[2.5rem] p-12 text-center border border-slate-200 shadow-xl shadow-slate-200/50" style="animation-delay: 100ms">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                    <i class="ph-duotone ph-desktop text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-800">Tidak Ada Ujian Aktif</h3>
                <p class="text-slate-500 mt-2 font-medium">Saat ini belum ada jadwal ujian yang tersedia untuk Anda.</p>
            </div>
        @else
            <!-- DAFTAR UJIAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($exams as $index => $examItem)
                    @php
                        $status = $examItem->student_status;
                        $statusLabel = 'Tersedia';
                        $statusClass = 'bg-cyan-50 text-cyan-600 border-cyan-100';
                        $filterCategory = in_array($status, ['open', 'ongoing']) ? 'active' : ($status == 'upcoming' ? 'upcoming' : $status);
                        
                        if($status == 'finished') {
                            $statusLabel = 'Selesai';
                            $statusClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                        } elseif($status == 'ongoing') {
                            $statusLabel = 'Sedang Dikerjakan';
                            $statusClass = 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse';
                        } elseif($status == 'upcoming') {
                            $statusLabel = 'Belum Mulai';
                            $statusClass = 'bg-slate-100 text-slate-500 border-slate-200';
                        }

                        // Logika Lulus/Tidak Lulus
                        $kkm = $examItem->passing_grade ?? 0;
                        $score = $examItem->student_score ?? 0;
                        $isPassed = $score >= $kkm;
                    @endphp

                    <!-- 3. PERBAIKAN: Sama seperti x-data, x-show dibungkus dengan single quote (') -->
                    <div x-show='(activeFilter === "all" || activeFilter === "{{ $filterCategory }}") && 
                                 (@json(strtolower($examItem->title)).includes(searchQuery.toLowerCase()) || 
                                  @json(strtolower($examItem->subject_name)).includes(searchQuery.toLowerCase()))'
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 transform scale-95"
                         x-transition:enter-end="opacity-100 transform scale-100"
                         x-transition:leave="transition ease-in duration-200"
                         x-transition:leave-start="opacity-100 transform scale-100"
                         x-transition:leave-end="opacity-0 transform scale-95"
                         class="animate-enter group bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-cyan-500/10 hover:border-cyan-200 transition-all duration-300 overflow-hidden flex flex-col h-full hover:-translate-y-1" style="animation-delay: {{ ($index + 1) * 50 }}ms">
                        
                        <!-- Header Card -->
                        <div class="p-6 md:p-7 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-cyan-50 to-blue-50 rounded-bl-[3rem] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                            
                            <div class="relative z-10 flex justify-between items-start mb-5">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 text-cyan-600 flex items-center justify-center shadow-md group-hover:bg-cyan-500 group-hover:text-white group-hover:border-cyan-500 transition-all duration-300">
                                    <i class="ph-duotone ph-exam text-2xl"></i>
                                </div>
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border shadow-sm {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            
                            <h3 class="relative z-10 text-lg md:text-xl font-bold text-slate-800 leading-tight group-hover:text-cyan-700 transition-colors mb-2 line-clamp-2" title="{{ $examItem->title }}">
                                {{ $examItem->title ?? 'Judul Ujian' }}
                            </h3>
                            <div class="relative z-10 flex items-center gap-2 text-xs font-bold text-slate-500 bg-slate-50 w-fit px-3 py-1.5 rounded-lg border border-slate-100">
                                <i class="ph-fill ph-chalkboard-teacher text-cyan-500"></i>
                                {{ $examItem->subject_name ?? 'Mata Pelajaran' }}
                            </div>
                        </div>

                        <!-- Info Detail -->
                        <div class="px-6 md:px-7 pb-6 flex-1">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Tanggal</p>
                                    <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <i class="ph-bold ph-calendar-blank text-blue-500"></i>
                                        {{ \Carbon\Carbon::parse($examItem->start_time)->format('d M Y') }}
                                    </p>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Durasi</p>
                                    <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <i class="ph-bold ph-timer text-amber-500"></i>
                                        {{ $examItem->duration_minutes }} Menit
                                    </p>
                                </div>
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Soal</p>
                                    <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <i class="ph-bold ph-list-numbers text-purple-500"></i>
                                        {{ $examItem->questions_count ?? 0 }} Butir
                                    </p>
                                </div>
                                
                                @if($status == 'finished')
                                    <div class="p-3 rounded-xl border {{ $isPassed ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100' }}">
                                        <p class="text-[10px] uppercase font-bold {{ $isPassed ? 'text-emerald-600' : 'text-rose-600' }} mb-1">
                                            Nilai Anda (KKM: {{ $kkm }})
                                        </p>
                                        <p class="text-sm font-black {{ $isPassed ? 'text-emerald-800' : 'text-rose-800' }} flex items-center gap-1.5">
                                            <i class="ph-fill {{ $isPassed ? 'ph-medal text-emerald-500' : 'ph-warning-circle text-rose-500' }}"></i>
                                            {{ $score }}
                                        </p>
                                    </div>
                                @else
                                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                        <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Target KKM</p>
                                        <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                            <i class="ph-bold ph-target text-emerald-500"></i>
                                            {{ $kkm }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Action -->
                        <div class="p-4 bg-slate-50/80 border-t border-slate-100" x-data="{ isNavigating: false }">
                            @if($status == 'finished')
                                <button disabled class="w-full py-3.5 rounded-xl bg-slate-200/50 text-slate-400 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="ph-fill ph-check-circle"></i> Selesai Dikerjakan
                                </button>
                            @elseif($status == 'upcoming')
                                <button disabled class="w-full py-3.5 rounded-xl bg-slate-100 text-slate-400 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2 border border-slate-200">
                                    <i class="ph-bold ph-lock-key"></i> Belum Dibuka
                                </button>
                            @elseif($status == 'ongoing')
                                <a href="{{ route('student.exam.run', $examItem->id) }}" @click="isNavigating = true" class="w-full py-3.5 rounded-xl bg-amber-500 text-white font-bold text-sm shadow-lg shadow-amber-500/20 hover:bg-amber-600 transition-all flex items-center justify-center gap-2 group/btn relative overflow-hidden">
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
                                <a href="{{ route('student.exam.show', $examItem->id) }}" @click="isNavigating = true" class="w-full py-3.5 rounded-xl bg-blue-950 text-white font-bold text-sm shadow-lg shadow-blue-950/20 hover:bg-cyan-600 transition-all flex items-center justify-center gap-2 group/btn relative overflow-hidden">
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
                                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent translate-x-[-100%] group-hover/btn:animate-[shimmer_1.5s_infinite]"></div>
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
            
            <!-- STATE KOSONG (JIKA HASIL PENCARIAN/FILTER = 0) -->
            <div x-show="!hasVisibleItems" x-cloak class="animate-enter bg-white rounded-[2.5rem] p-12 text-center border border-slate-200 mt-6 shadow-sm">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Ujian Tidak Ditemukan</h3>
                <p class="text-slate-500 text-sm mt-1">Coba gunakan filter atau kata kunci pencarian yang lain.</p>
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
</div>
@endsection