@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-slate-950 text-slate-100 p-4 md:p-6 lg:p-8">
    
    <!-- HEADER -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-slate-900/60 backdrop-blur-xl p-6 rounded-3xl border border-white/10 shadow-2xl">
        <div>
            <div class="flex items-center gap-2 text-sky-400 font-semibold text-xs uppercase tracking-widest mb-1">
                <i class="ph-bold ph-chart-line-up text-base"></i>
                <span>E-Learning Management System</span>
            </div>
            <h1 class="text-2xl md:text-3xl font-black text-white tracking-tight">Monitoring Progres Belajar Siswa</h1>
            <p class="text-sm text-slate-400 mt-1">Pantau keterbacaan materi, pengumpulan tugas, dan aktivitas belajar siswa secara real-time.</p>
        </div>

        <!-- FILTER FORM -->
        <form method="GET" action="{{ route('lms.monitoring') }}" class="flex flex-wrap items-center gap-3 bg-slate-950/80 p-2.5 rounded-2xl border border-white/10">
            <div>
                <select name="class_id" onchange="this.form.submit()" class="bg-slate-900 text-white text-xs font-semibold rounded-xl border border-white/15 px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:outline-none">
                    @foreach($classes as $c)
                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                            Kelas: {{ $c->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <select name="subject_id" onchange="this.form.submit()" class="bg-slate-900 text-white text-xs font-semibold rounded-xl border border-white/15 px-3 py-2 focus:ring-2 focus:ring-sky-500 focus:outline-none">
                    @foreach($subjects as $s)
                        <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>
                            Mapel: {{ $s->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold text-xs rounded-xl shadow-lg shadow-sky-500/20 transition-all">
                <i class="ph-bold ph-funnel mr-1"></i> Filter
            </button>
        </form>
    </div>

    <!-- SUMMARY STATS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <!-- Card 1: Total Siswa -->
        <div class="bg-slate-900/40 backdrop-blur-md p-5 rounded-2xl border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-slate-400 block mb-1">Total Siswa</span>
                <span class="text-3xl font-black text-white">{{ $totalStudentsCount }}</span>
                <span class="text-[11px] text-slate-400 block mt-1">Dalam Kelas Terpilih</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-sky-500/10 border border-sky-500/20 text-sky-400 flex items-center justify-center text-2xl">
                <i class="ph-bold ph-users-three"></i>
            </div>
        </div>

        <!-- Card 2: Rata-rata Progres Kelas -->
        <div class="bg-slate-900/40 backdrop-blur-md p-5 rounded-2xl border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-slate-400 block mb-1">Rata-rata Progres Kelas</span>
                <span class="text-3xl font-black text-emerald-400">{{ $avgProgress }}%</span>
                <span class="text-[11px] text-slate-400 block mt-1">{{ $materialsCount }} Materi & {{ $assignmentsCount }} Tugas</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl">
                <i class="ph-bold ph-chart-pie font-bold"></i>
            </div>
        </div>

        <!-- Card 3: Siswa Tuntas 100% -->
        <div class="bg-slate-900/40 backdrop-blur-md p-5 rounded-2xl border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-slate-400 block mb-1">Siswa Tuntas (100%)</span>
                <span class="text-3xl font-black text-blue-400">{{ $completedStudentsCount }}</span>
                <span class="text-[11px] text-slate-400 block mt-1">Membaca & Mengerjakan Semua</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-2xl">
                <i class="ph-bold ph-check-circle"></i>
            </div>
        </div>

        <!-- Card 4: Membutuhkan Perhatian -->
        <div class="bg-slate-900/40 backdrop-blur-md p-5 rounded-2xl border border-white/10 flex items-center justify-between">
            <div>
                <span class="text-xs font-medium text-slate-400 block mb-1">Perlu Perhatian (< 50%)</span>
                <span class="text-3xl font-black text-rose-400">{{ $warningStudentsCount }}</span>
                <span class="text-[11px] text-slate-400 block mt-1">Belum tuntas / belum mulai</span>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center text-2xl">
                <i class="ph-bold ph-warning"></i>
            </div>
        </div>
    </div>

    <!-- TABLE AREA -->
    <div class="bg-slate-900/50 backdrop-blur-xl rounded-3xl border border-white/10 overflow-hidden shadow-2xl">
        <div class="p-6 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-lg text-white">Daftar Progres Siswa</h3>
                <p class="text-xs text-slate-400">Rincian status baca materi dan pengumpulan tugas per siswa.</p>
            </div>
            <div class="flex items-center gap-2">
                <span class="px-3 py-1 bg-slate-800 rounded-full border border-white/10 text-slate-300 text-xs font-medium">
                    Total Item: <strong class="text-white">{{ $materialsCount + $assignmentsCount }}</strong>
                </span>
            </div>
        </div>

        @if($studentsData->isEmpty())
            <div class="p-12 text-center text-slate-400">
                <i class="ph-bold ph-user-minus text-4xl mb-3 text-slate-500 block"></i>
                <p class="text-base font-semibold">Tidak ada siswa ditemukan di kelas terpilih.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-950/60 border-b border-white/10 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                            <th class="py-4 px-6">No</th>
                            <th class="py-4 px-6">Nama Siswa</th>
                            <th class="py-4 px-6">Materi Dibaca</th>
                            <th class="py-4 px-6">Tugas Dikumpul</th>
                            <th class="py-4 px-6">Progres Belajar</th>
                            <th class="py-4 px-6 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5 text-sm">
                        @foreach($studentsData as $idx => $row)
                            <tr class="hover:bg-white/[0.02] transition-colors">
                                <td class="py-4 px-6 text-slate-400 font-mono text-xs">{{ $idx + 1 }}</td>
                                <td class="py-4 px-6">
                                    <div class="font-semibold text-white">{{ $row['student']->name }}</div>
                                    <div class="text-xs text-slate-400 font-mono">NISN: {{ $row['student']->nisn ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-sky-400">{{ $row['read_materials'] }}</span>
                                        <span class="text-slate-500 text-xs">/ {{ $materialsCount }} materi</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-2">
                                        <span class="font-bold text-emerald-400">{{ $row['submitted_assignments'] }}</span>
                                        <span class="text-slate-500 text-xs">/ {{ $assignmentsCount }} tugas</span>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="w-full max-w-xs">
                                        <div class="flex justify-between items-center text-xs mb-1">
                                            <span class="font-bold text-slate-200">{{ $row['progress_percent'] }}%</span>
                                            <span class="text-[11px] text-slate-400">{{ $row['read_materials'] + $row['submitted_assignments'] }} dari {{ $row['total_items'] }} selesai</span>
                                        </div>
                                        <div class="w-full h-2 rounded-full bg-slate-800 overflow-hidden border border-white/5">
                                            <div class="h-full bg-gradient-to-r {{ $row['progress_percent'] >= 100 ? 'from-emerald-500 to-teal-400' : ($row['progress_percent'] >= 50 ? 'from-sky-500 to-blue-400' : 'from-amber-500 to-rose-500') }} transition-all duration-500"
                                                 style="width: {{ $row['progress_percent'] }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold border backdrop-blur-md {{ $row['badge_class'] }}">
                                        @if($row['progress_percent'] >= 100)
                                            <i class="ph-bold ph-check-circle"></i>
                                        @elseif($row['progress_percent'] >= 50)
                                            <i class="ph-bold ph-clock"></i>
                                        @else
                                            <i class="ph-bold ph-warning-circle"></i>
                                        @endif
                                        {{ $row['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
