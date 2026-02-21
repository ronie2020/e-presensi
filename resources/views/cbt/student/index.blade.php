@extends('layouts.student')

@section('content')
<style>
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
</style>

{{-- PENGEMBANGAN: Tambahkan x-data AlpineJS di container utama untuk state Pencarian & Filter --}}
<div class="min-h-screen bg-slate-50/50 pb-20" x-data="{ searchQuery: '', activeFilter: 'all' }">
    
    <!-- HEADER SECTION -->
    <div class="animate-enter relative bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 pb-24 pt-12 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[3rem] shadow-2xl shadow-slate-900/20">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-80 h-80 bg-rose-500/10 rounded-full blur-[100px] -mr-20 -mt-20 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-blue-500/10 rounded-full blur-[80px] -ml-10 -mb-10 pointer-events-none"></div>
        
        <div class="relative max-w-5xl mx-auto z-10">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-300 text-[10px] font-black uppercase tracking-widest backdrop-blur-md">
                            <i class="ph-fill ph-monitor-play"></i> CBT System
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-2">Ujian Online</h1>
                    <p class="text-slate-400 text-sm md:text-base max-w-lg leading-relaxed font-medium">
                        Pilih jadwal ujian yang tersedia dan pastikan koneksi internet stabil sebelum memulai.
                    </p>
                </div>
                
                <!-- Statistik Real -->
                <div class="flex gap-4">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center min-w-[110px] shadow-lg">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Tersedia</p>
                        <p class="text-3xl font-black text-yellow-400">
                            {{ isset($exams) ? $exams->filter(fn($e) => in_array($e->student_status, ['open', 'ongoing']))->count() : 0 }}
                        </p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-5 rounded-2xl text-center min-w-[110px] shadow-lg">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-1">Selesai</p>
                        <p class="text-3xl font-black text-white">
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
            <!-- PENGEMBANGAN: TOOLBAR SEARCH & FILTER -->
            <div class="animate-enter bg-white p-4 rounded-[2rem] shadow-lg shadow-slate-200/50 border border-slate-100 mb-8 flex flex-col sm:flex-row gap-4 justify-between items-center">
                <!-- Tabs Filter -->
                <div class="flex p-1 bg-slate-100 rounded-xl gap-1 w-full sm:w-auto overflow-x-auto">
                    <button @click="activeFilter = 'all'" :class="activeFilter === 'all' ? 'bg-white shadow-sm text-slate-800' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Semua</button>
                    <button @click="activeFilter = 'active'" :class="activeFilter === 'active' ? 'bg-white shadow-sm text-blue-600' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Tersedia</button>
                    <button @click="activeFilter = 'finished'" :class="activeFilter === 'finished' ? 'bg-white shadow-sm text-emerald-600' : 'text-slate-500 hover:text-slate-700'" class="px-4 py-2 rounded-lg text-xs font-bold transition-all whitespace-nowrap">Selesai</button>
                </div>

                <!-- Search Input -->
                <div class="relative w-full sm:w-72 group">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                        <i class="ph-bold ph-magnifying-glass"></i>
                    </div>
                    <input x-model="searchQuery" type="text" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm focus:bg-white focus:border-blue-500 focus:ring-4 focus:ring-blue-50 transition-all font-medium placeholder-slate-400" placeholder="Cari pelajaran atau ujian...">
                </div>
            </div>
        @endif

        @if(!isset($exams) || $exams->isEmpty())
            <!-- STATE KOSONG -->
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
                        $statusClass = 'bg-blue-50 text-blue-600 border-blue-100';
                        $filterCategory = in_array($status, ['open', 'ongoing']) ? 'active' : $status;
                        
                        if($status == 'finished') {
                            $statusLabel = 'Selesai';
                            $statusClass = 'bg-emerald-50 text-emerald-600 border-emerald-100';
                        } elseif($status == 'ongoing') {
                            $statusLabel = 'Sedang Dikerjakan';
                            $statusClass = 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse';
                        } elseif($status == 'upcoming') {
                            $statusLabel = 'Belum Mulai';
                            $statusClass = 'bg-slate-100 text-slate-500 border-slate-200';
                            $filterCategory = 'upcoming';
                        }
                    @endphp

                    <!-- PENGEMBANGAN: Implementasi x-show AlpineJS untuk filter dan pencarian -->
                    <div x-show="(activeFilter === 'all' || activeFilter === '{{ $filterCategory }}') && 
                                 ('{{ strtolower(addslashes($examItem->title)) }}'.includes(searchQuery.toLowerCase()) || 
                                  '{{ strtolower(addslashes($examItem->subject_name)) }}'.includes(searchQuery.toLowerCase()))"
                         x-transition.duration.300ms
                         class="animate-enter group bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-2xl hover:shadow-slate-200/50 hover:border-blue-200 transition-all duration-300 overflow-hidden flex flex-col h-full hover:-translate-y-1" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                        
                        <!-- Header Card -->
                        <div class="p-6 md:p-7 relative overflow-hidden">
                            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-bl-[3rem] -mr-4 -mt-4 transition-transform group-hover:scale-110"></div>
                            
                            <div class="relative z-10 flex justify-between items-start mb-5">
                                <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 text-blue-600 flex items-center justify-center shadow-md group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-all duration-300">
                                    <i class="ph-duotone ph-exam text-2xl"></i>
                                </div>
                                <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wide border shadow-sm {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            
                            <h3 class="relative z-10 text-lg md:text-xl font-bold text-slate-800 leading-tight group-hover:text-blue-700 transition-colors mb-2 line-clamp-2">
                                {{ $examItem->title ?? 'Judul Ujian' }}
                            </h3>
                            <div class="relative z-10 flex items-center gap-2 text-xs font-bold text-slate-500 bg-slate-50 w-fit px-3 py-1.5 rounded-lg border border-slate-100">
                                <i class="ph-fill ph-chalkboard-teacher text-blue-500"></i>
                                {{ $examItem->subject_name ?? 'Mata Pelajaran' }}
                            </div>
                        </div>

                        <!-- Info Detail -->
                        <div class="px-6 md:px-7 pb-6 flex-1">
                            <div class="grid grid-cols-2 gap-3">
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">Tanggal</p>
                                    <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <i class="ph-bold ph-calendar-blank text-rose-500"></i>
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
                                <div class="p-3 rounded-xl bg-emerald-50 border border-emerald-100">
                                    <p class="text-[10px] uppercase font-bold text-emerald-600 mb-1">Nilai Anda</p>
                                    <p class="text-xs font-bold text-emerald-800 flex items-center gap-1.5">
                                        <i class="ph-fill ph-medal text-emerald-500"></i>
                                        {{ $examItem->student_score ?? 0 }}
                                    </p>
                                </div>
                                @else
                                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                                    <p class="text-[10px] uppercase font-bold text-slate-400 mb-1">KKM</p>
                                    <p class="text-xs font-bold text-slate-700 flex items-center gap-1.5">
                                        <i class="ph-bold ph-target text-emerald-500"></i>
                                        {{ $examItem->passing_grade ?? 75 }}
                                    </p>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Footer Action (Ditambahkan AlpineJS state onClick loading effect) -->
                        <div class="p-4 bg-slate-50 border-t border-slate-100" x-data="{ isNavigating: false }">
                            @if($status == 'finished')
                                <button disabled class="w-full py-3.5 rounded-xl bg-slate-200 text-slate-400 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="ph-fill ph-check-circle"></i> Sudah Dikerjakan
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
                                <a href="{{ route('student.exam.show', $examItem->id) }}" @click="isNavigating = true" class="w-full py-3.5 rounded-xl bg-slate-900 text-white font-bold text-sm shadow-lg shadow-slate-900/20 hover:bg-blue-600 transition-all flex items-center justify-center gap-2 group/btn relative overflow-hidden">
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
            
            <!-- State Kosong jika hasil pencarian tidak ditemukan -->
            <div x-show="!document.querySelectorAll('[x-show]:not([style*=\'display: none\'])').length" x-cloak class="animate-enter bg-white rounded-[2.5rem] p-12 text-center border border-slate-200 mt-6 shadow-sm">
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                    <i class="ph-duotone ph-magnifying-glass text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800">Ujian Tidak Ditemukan</h3>
                <p class="text-slate-500 text-sm mt-1">Coba gunakan kata kunci pencarian yang lain.</p>
            </div>
        @endif
    </div>
</div>
@endsection