@extends('layouts.student')

@section('content')
<div class="min-h-screen bg-slate-50/50">
    
    <!-- HEADER SECTION -->
    <div class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 pb-20 pt-10 px-4 sm:px-6 lg:px-8 overflow-hidden rounded-b-[2.5rem] shadow-2xl shadow-blue-900/20">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-rose-500 opacity-10 rounded-full blur-3xl -mr-16 -mt-16"></div>
        
        <div class="relative max-w-5xl mx-auto">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="px-3 py-1 rounded-full bg-rose-900/50 border border-rose-700/50 text-rose-300 text-[10px] font-bold uppercase tracking-wider">
                            Computer Based Test
                        </span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight">Ujian Online</h1>
                    <p class="text-slate-300 mt-2 text-sm max-w-lg">
                        Silakan pilih ujian yang tersedia di bawah ini. Pastikan Anda memiliki koneksi internet yang stabil.
                    </p>
                </div>
                
                <!-- Statistik Ringkas -->
                <div class="flex gap-3">
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px]">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Tersedia</p>
                        <!-- Menggunakan optional chaining ($exams ?? collect()) untuk mencegah error jika variabel null -->
                        <p class="text-2xl font-black text-yellow-400">{{ isset($exams) ? $exams->where('status', 'active')->count() : 0 }}</p>
                    </div>
                    <div class="bg-white/5 backdrop-blur-md border border-white/10 p-4 rounded-2xl text-center min-w-[100px]">
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Selesai</p>
                        <p class="text-2xl font-black text-white">{{ isset($exams) ? $exams->where('status', 'finished')->count() : 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN LIST CONTAINER -->
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 -mt-12 relative z-10 pb-20">
        
        <!-- ALERT ERROR -->
        @if(session('error'))
            <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm animate-pulse">
                <i class="ph-fill ph-warning-circle text-xl text-rose-600"></i>
                <span class="font-bold text-sm">{{ session('error') }}</span>
            </div>
        @endif

        @if(!isset($exams) || $exams->isEmpty())
            <!-- STATE KOSONG -->
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-200 shadow-sm">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="ph-duotone ph-desktop text-5xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-700">Tidak Ada Ujian Aktif</h3>
                <p class="text-slate-500 mt-2">Saat ini belum ada jadwal ujian yang tersedia untuk Anda.</p>
            </div>
        @else
            <!-- DAFTAR UJIAN -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($exams as $examItem)
                    @php
                        // Logika Status (Sesuaikan dengan field DB Anda)
                        $isActive = true; 
                        $isFinished = false; 
                    @endphp

                    <div class="group bg-white rounded-2xl border border-slate-200 shadow-sm hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-300 transition-all duration-300 overflow-hidden flex flex-col">
                        
                        <!-- Header Card -->
                        <div class="p-6 border-b border-slate-50 bg-gradient-to-r from-white to-slate-50">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100 group-hover:bg-blue-600 group-hover:text-yellow-400 group-hover:border-blue-600 transition-colors">
                                    <i class="ph-duotone ph-exam text-2xl"></i>
                                </div>
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase tracking-wide border
                                    {{ $isActive ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-slate-100 text-slate-500 border-slate-200' }}">
                                    {{ $isActive ? 'Sedang Aktif' : 'Ditutup' }}
                                </span>
                            </div>
                            
                            <!-- Menggunakan $examItem (bukan $exam) agar tidak bentrok -->
                            <h3 class="text-lg font-bold text-slate-800 leading-tight group-hover:text-blue-700 transition-colors">
                                {{ $examItem->title ?? 'Judul Ujian' }}
                            </h3>
                            <p class="text-xs text-slate-500 font-medium mt-1 flex items-center gap-1">
                                <i class="ph-fill ph-chalkboard-teacher"></i>
                                {{-- FIX: Menggunakan subject_name (string) bukan relasi --}}
                                {{ $examItem->subject_name ?? 'Mata Pelajaran' }}
                            </p>
                        </div>

                        <!-- Info Detail -->
                        <div class="p-6 pt-4 flex-1">
                            <div class="grid grid-cols-2 gap-4 text-xs font-medium text-slate-500 mb-6">
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-calendar-blank text-blue-500"></i>
                                    <span>{{ \Carbon\Carbon::parse($examItem->start_time)->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-clock text-blue-500"></i>
                                    <span>{{ $examItem->duration }} Menit</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-list-numbers text-blue-500"></i>
                                    {{-- FIX: Menggunakan query langsung agar jumlah soal selalu muncul --}}
                                    <span>{{ $examItem->questions_count ?? 0 }} Soal</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="ph-bold ph-percent text-blue-500"></i>
                                    <span>KKM: {{ $examItem->passing_grade ?? 75 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Footer Action -->
                        <div class="p-4 bg-slate-50 border-t border-slate-100">
                            @if($isFinished)
                                <button disabled class="w-full py-3 rounded-xl bg-slate-200 text-slate-400 font-bold text-sm cursor-not-allowed flex items-center justify-center gap-2">
                                    <i class="ph-fill ph-check-circle"></i> Sudah Dikerjakan
                                </button>
                            @else
                                <a href="{{ route('student.exam.show', $examItem->id) }}" class="w-full py-3 rounded-xl bg-blue-900 text-white font-bold text-sm shadow-lg shadow-blue-900/20 hover:bg-blue-800 transition-all flex items-center justify-center gap-2 group/btn">
                                    <span>Masuk Ruang Ujian</span>
                                    <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-1 transition-transform text-yellow-400"></i>
                                </a>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection