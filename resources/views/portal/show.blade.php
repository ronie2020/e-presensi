@extends('layouts.public')

@section('content')
<!-- X-DATA: Menangani Tab & Resize Chart -->
<div class="w-full max-w-6xl mx-auto pb-20" 
     x-data="{ 
        activeTab: new URLSearchParams(window.location.search).get('tab') || 'ringkasan',
        updateTab(val) {
            this.activeTab = val;
            const url = new URL(window.location);
            url.searchParams.set('tab', val);
            window.history.pushState({}, '', url);
            // Trigger resize chart jika tab chart dibuka
            if(val === 'akademik' || val === 'kehadiran') {
                setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 100);
            }
        }
     }">
    
    <!-- HEADER PROFIL -->
    <div class="bg-white rounded-[2rem] shadow-xl overflow-hidden mb-6 border border-gray-100 relative group">
        <!-- Background Banner -->
        <div class="absolute top-0 left-0 w-full h-52 z-0 overflow-hidden bg-slate-900">
            <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1523050854058-8df90110c9f1?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80')] bg-cover bg-center opacity-20 mix-blend-overlay"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-slate-900 via-blue-900/80 to-slate-900"></div>
            <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')]"></div>
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-blue-600 rounded-full mix-blend-overlay filter blur-[80px] opacity-20 -mr-20 -mt-20"></div>
            <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-indigo-600 rounded-full mix-blend-overlay filter blur-[80px] opacity-20 -ml-20 -mb-20"></div>
        </div>
        
        <!-- Content Container -->
        <div class="relative z-10 px-6 sm:px-10 pt-28 pb-6 flex flex-col md:flex-row items-end md:items-end text-center md:text-left gap-6">
            <!-- Foto Profil -->
            <div class="relative group shrink-0 mx-auto md:mx-0 -mb-2">
                <div class="w-36 h-36 rounded-full bg-white p-1 shadow-2xl relative z-10 transform group-hover:scale-105 transition-transform duration-300 ring-4 ring-white/20 backdrop-blur-sm">
                    <div class="w-full h-full rounded-full bg-gray-100 flex items-center justify-center overflow-hidden border-2 border-white relative">
                        @if($student->photo_path)
                            <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-5xl font-black text-slate-400 select-none">
                                {{ substr(trim($student->name), 0, 1) }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="absolute bottom-1 right-1 z-20 bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-full border-2 border-white shadow-sm flex items-center gap-1.5">
                    <div class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></div> AKTIF
                </div>
            </div>
            
            <!-- Detail Siswa -->
            <div class="flex-1 min-w-0 w-full md:pb-3">
                <h1 class="text-3xl sm:text-4xl font-black text-white tracking-tight leading-tight mb-3 break-words capitalize drop-shadow-lg">
                    {{ strtolower($student->name) }}
                </h1>
                <div class="flex flex-wrap justify-center md:justify-start gap-2 text-sm font-medium">
                    <span class="flex items-center bg-blue-600 px-4 py-1.5 rounded-full text-white shadow-lg shadow-blue-900/30 border border-blue-500 transition hover:bg-blue-500 hover:scale-105">
                        <i class="ph-fill ph-chalkboard-teacher mr-2 text-lg text-blue-200"></i>
                        <span>Kelas <strong class="font-bold text-white">{{ $student->schoolClass->name ?? 'Unassigned' }}</strong></span>
                    </span>
                    <span x-data="{ copied: false }" 
                          @click="navigator.clipboard.writeText('{{ $student->student_id }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                          class="flex items-center bg-blue-600 px-4 py-1.5 rounded-full text-white shadow-lg shadow-blue-900/30 border border-blue-500 font-mono transition hover:bg-blue-500 hover:scale-105 cursor-pointer select-none relative" 
                          title="Klik untuk salin">
                        <i class="ph-fill mr-2 text-lg text-blue-200" :class="copied ? 'ph-check' : 'ph-identification-card'"></i>
                        <span x-text="copied ? 'Tersalin!' : '{{ $student->student_id }}'"></span>
                    </span>
                </div>
            </div>

            <!-- Action Button -->
            <div class="w-full md:w-auto flex flex-col sm:flex-row gap-2 mt-4 md:mt-0 md:pb-4">
                <button onclick="window.print()" class="flex-1 sm:flex-none justify-center inline-flex items-center px-5 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-sm font-bold text-white hover:bg-white hover:text-slate-900 transition-all shadow-lg">
                    <i class="ph-bold ph-printer mr-2"></i> Cetak
                </button>
                <a href="{{ route('portal.index') }}" class="flex-1 sm:flex-none justify-center inline-flex items-center px-5 py-2.5 bg-white/10 backdrop-blur-md border border-white/20 rounded-xl text-sm font-bold text-white hover:bg-white hover:text-slate-900 transition-all shadow-lg">
                    <i class="ph-bold ph-magnifying-glass mr-2"></i> Cari Lain
                </a>
            </div>
        </div>
    </div>

    <!-- NAVIGATION TABS -->
    <div class="mb-8 sticky top-4 z-40 transition-all duration-300" id="sticky-nav">
        <div class="bg-white/90 backdrop-blur-xl p-1.5 rounded-2xl shadow-lg border border-gray-100/50 relative group">
            <div class="absolute right-0 top-0 bottom-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none md:hidden z-10 rounded-r-2xl"></div>
            <div class="overflow-x-auto custom-scrollbar flex items-center gap-1 min-w-max pb-0.5 md:pb-0 scroll-smooth px-1">
                @php
                    $tabs = [
                        'ringkasan' => ['icon' => 'squares-four', 'label' => 'Ringkasan'],
                        'kbm' => ['icon' => 'chalkboard-teacher', 'label' => 'Jurnal KBM'],
                        'akademik' => ['icon' => 'exam', 'label' => 'Akademik'],
                        'kehadiran' => ['icon' => 'calendar-check', 'label' => 'Kehadiran'],
                        'keagamaan' => ['icon' => 'book-open-text', 'label' => 'Keagamaan'],
                        'disiplin' => ['icon' => 'warning-circle', 'label' => 'Disiplin'],
                        'prestasi' => ['icon' => 'trophy', 'label' => 'Prestasi'],
                        'perpustakaan' => ['icon' => 'books', 'label' => 'Pustaka'],
                    ];
                @endphp

                @foreach($tabs as $key => $tab)
                    <button @click="updateTab('{{ $key }}')" 
                        :class="activeTab === '{{ $key }}' ? 'bg-slate-900 text-white shadow-lg shadow-slate-300 transform scale-100' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700'"
                        class="px-4 py-2.5 rounded-xl text-sm font-bold transition-all duration-300 flex items-center gap-2 whitespace-nowrap outline-none focus:ring-2 focus:ring-slate-200">
                        <i class="ph-bold ph-{{ $tab['icon'] }} text-lg"></i> {{ $tab['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- CONTENT AREAS -->
    <div class="min-h-[400px]">
        
        <!-- 1. TAB RINGKASAN -->
        <div x-show="activeTab === 'ringkasan'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card Kehadiran -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-chart-pie-slice text-9xl text-blue-500"></i></div>
                    <div class="relative z-10">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Persentase Kehadiran</h3>
                        <div class="flex items-baseline gap-2 mb-4">
                            @php $total_hari = ($hadir ?? 0) + ($sakit ?? 0) + ($izin ?? 0) + ($alpa ?? 0); $persen = $total_hari > 0 ? round(($hadir/$total_hari)*100) : 0; @endphp
                            <span class="text-5xl font-black text-slate-800">{{ $persen }}<span class="text-2xl text-slate-400">%</span></span>
                        </div>
                        <div class="flex gap-2">
                            <span class="px-3 py-1.5 bg-green-50 text-green-700 border border-green-100 rounded-lg text-xs font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Hadir: {{ $hadir ?? 0 }}</span>
                            <span class="px-3 py-1.5 bg-rose-50 text-rose-700 border border-rose-100 rounded-lg text-xs font-bold flex items-center gap-1"><div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div> Alpa: {{ $alpa ?? 0 }}</span>
                        </div>
                    </div>
                </div>
                
                <!-- Card Poin (Updated) -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-star text-9xl text-yellow-500"></i></div>
                    <div class="relative z-10">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-4">Poin Karakter</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-green-50/50 p-3 rounded-xl border border-green-100/50">
                                <p class="text-[10px] text-green-600 font-bold mb-1 uppercase">Kebaikan</p>
                                <p class="text-3xl font-black text-green-600">+{{ $total_merit_points ?? 0 }}</p>
                            </div>
                            <div class="bg-rose-50/50 p-3 rounded-xl border border-rose-100/50">
                                <p class="text-[10px] text-rose-600 font-bold mb-1 uppercase">Pelanggaran</p>
                                <p class="text-3xl font-black text-rose-600">-{{ $total_violation_points ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Literasi -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-lg transition-all duration-300">
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110"><i class="ph-fill ph-books text-9xl text-purple-500"></i></div>
                    <div class="relative z-10">
                        <h3 class="text-gray-500 text-xs font-bold uppercase tracking-wider mb-2">Literasi</h3>
                        <div class="flex items-baseline gap-2">
                            <span class="text-5xl font-black text-slate-800">{{ $library_visits ?? 0 }}</span>
                            <span class="text-sm text-slate-400 font-bold bg-slate-100 px-2 py-1 rounded-md">Kunjungan</span>
                        </div>
                        <p class="mt-4 text-sm text-slate-500 font-medium">"Buku adalah jendela dunia."</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 2. TAB JURNAL KBM -->
        <div x-show="activeTab === 'kbm'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 gap-6">
                @if(isset($teaching_journals) && count($teaching_journals) > 0)
                    @foreach($teaching_journals as $journal)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-4">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="px-2.5 py-1 rounded-lg bg-blue-50 text-blue-700 text-xs font-bold uppercase tracking-wide border border-blue-100">
                                            {{ $journal->schedule->subject->name ?? 'Mapel' }}
                                        </span>
                                        <span class="text-xs text-slate-400 font-bold flex items-center gap-1">
                                            <i class="ph-fill ph-clock"></i>
                                            {{ \Carbon\Carbon::parse($journal->started_at)->format('H:i') }}
                                        </span>
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-800">{{ $journal->topic ?? 'Tanpa Topik' }}</h3>
                                    <p class="text-sm text-slate-500">Pengajar: {{ $journal->schedule->teacher->name ?? 'Guru' }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-black text-slate-200">{{ \Carbon\Carbon::parse($journal->date)->format('d') }}</p>
                                    <p class="text-xs font-bold text-slate-400 uppercase">{{ \Carbon\Carbon::parse($journal->date)->format('M Y') }}</p>
                                </div>
                            </div>
                            <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100">
                                <p class="text-xs font-bold text-slate-400 uppercase mb-2">Aktivitas / Tugas:</p>
                                <p class="text-slate-700 text-sm leading-relaxed whitespace-pre-line">{{ $journal->activities ?? '-' }}</p>
                            </div>
                            <div class="flex justify-between items-center pt-2 border-t border-gray-50">
                                <div>
                                    @if($journal->reference_link)
                                        <a href="{{ $journal->reference_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 text-sm font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                            <i class="ph-bold ph-link"></i> Buka Materi / Link
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Tidak ada link referensi</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-12 bg-white rounded-3xl border border-dashed border-gray-200">
                        <i class="ph-duotone ph-notebook text-4xl text-slate-300 mb-3"></i>
                        <p class="text-slate-500 font-medium">Belum ada riwayat pembelajaran.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 3. TAB AKADEMIK -->
        <div x-show="activeTab === 'akademik'" x-cloak x-transition:enter="transition ease-out duration-300">
            @if($academic_record)
                <!-- Grafik -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 mb-6 relative overflow-hidden">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="font-bold text-slate-800 text-lg flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600"><i class="ph-fill ph-chart-bar"></i></div>
                            Grafik Capaian Kompetensi
                        </h3>
                    </div>
                    <div class="h-72 w-full relative">
                        <canvas id="academicChart"></canvas>
                    </div>
                </div>

                <!-- Tabel Nilai -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-blue-50/50 to-white flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <h3 class="font-bold text-slate-800 text-lg">Rincian Nilai Rapor</h3>
                            <p class="text-sm text-slate-500 mt-1">Laporan hasil belajar siswa.</p>
                        </div>
                        <div class="flex gap-3 text-xs font-bold">
                             <div class="px-4 py-2 bg-white border border-blue-100 rounded-xl text-blue-700 shadow-sm">
                                TA: {{ $academic_record->academic_year }}
                             </div>
                             <div class="px-4 py-2 bg-white border border-blue-100 rounded-xl text-blue-700 shadow-sm">
                                SM: {{ $academic_record->semester }}
                             </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/50 text-xs font-bold text-slate-400 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 rounded-tl-2xl">Mata Pelajaran</th>
                                    <th class="px-6 py-4 text-center">Nilai</th>
                                    <th class="px-6 py-4 text-center">Predikat</th>
                                    <th class="px-6 py-4 hidden md:table-cell rounded-tr-2xl">Deskripsi Capaian</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50 text-sm">
                                @foreach($academic_record->items as $item)
                                    <tr class="hover:bg-slate-50/80 transition-colors group">
                                        <td class="px-6 py-4 font-bold text-slate-700">{{ $item->subject->name ?? 'Mapel Dihapus' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-block font-black text-slate-700 text-lg">{{ $item->score }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php 
                                                $gradeColor = match($item->predicate) { 
                                                    'A' => 'bg-emerald-100 text-emerald-700 ring-emerald-200', 
                                                    'B' => 'bg-blue-100 text-blue-700 ring-blue-200', 
                                                    'C' => 'bg-amber-100 text-amber-700 ring-amber-200', 
                                                    default => 'bg-rose-100 text-rose-700 ring-rose-200' 
                                                }; 
                                            @endphp
                                            <span class="px-3 py-1 rounded-lg text-xs font-bold ring-1 {{ $gradeColor }}">{{ $item->predicate }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 hidden md:table-cell max-w-sm leading-relaxed text-xs">
                                            {{Str::limit($item->description, 100) ?? '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-3xl border-2 border-dashed border-slate-200 p-16 text-center group hover:border-blue-300 transition-colors">
                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 group-hover:bg-blue-50 transition-colors">
                        <i class="ph-duotone ph-exam text-4xl text-slate-300 group-hover:text-blue-400 transition-colors"></i>
                    </div>
                    <h3 class="font-bold text-slate-800 text-lg">Belum Ada Data Nilai</h3>
                    <p class="text-slate-500 text-sm mt-2 max-w-xs mx-auto">Data nilai akademik untuk semester ini belum tersedia atau belum dipublikasikan.</p>
                </div>
            @endif
        </div>

        <!-- 4. TAB KEHADIRAN -->
        <div x-show="activeTab === 'kehadiran'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- GRAFIK DONUT -->
                <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-100 lg:col-span-1 flex flex-col justify-center items-center relative">
                    <h3 class="font-bold text-slate-800 mb-2 flex items-center gap-2 self-start w-full border-b border-gray-50 pb-4">
                        <i class="ph-fill ph-chart-pie-slice text-blue-600"></i> Statistik Kehadiran
                    </h3>
                    <div class="h-56 w-full relative mt-2">
                        <canvas id="attendanceChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pt-4">
                            <span class="text-[10px] text-gray-400 uppercase font-bold tracking-widest">TOTAL HARI</span>
                            <span class="text-4xl font-black text-slate-800">{{ ($attendanceChart['hadir'] ?? 0) + ($attendanceChart['sakit'] ?? 0) + ($attendanceChart['izin'] ?? 0) + ($attendanceChart['alpa'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- KARTU STATISTIK GRID -->
                <div class="lg:col-span-2 grid grid-cols-2 gap-4">
                    <div class="bg-gradient-to-br from-emerald-50 to-white p-5 rounded-2xl border border-emerald-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
                        <div class="text-4xl font-black text-emerald-600 mb-1">{{ $hadir ?? 0 }}</div>
                        <div class="text-xs font-bold text-emerald-600/70 uppercase tracking-widest">Hadir</div>
                    </div>
                    <div class="bg-gradient-to-br from-blue-50 to-white p-5 rounded-2xl border border-blue-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
                        <div class="text-4xl font-black text-blue-600 mb-1">{{ $sakit ?? 0 }}</div>
                        <div class="text-xs font-bold text-blue-600/70 uppercase tracking-widest">Sakit</div>
                    </div>
                    <div class="bg-gradient-to-br from-amber-50 to-white p-5 rounded-2xl border border-amber-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
                        <div class="text-4xl font-black text-amber-600 mb-1">{{ $izin ?? 0 }}</div>
                        <div class="text-xs font-bold text-amber-600/70 uppercase tracking-widest">Izin</div>
                    </div>
                    <div class="bg-gradient-to-br from-rose-50 to-white p-5 rounded-2xl border border-rose-100 flex flex-col justify-center text-center h-full hover:shadow-md transition-shadow">
                        <div class="text-4xl font-black text-rose-600 mb-1">{{ $alpa ?? 0 }}</div>
                        <div class="text-xs font-bold text-rose-600/70 uppercase tracking-widest">Alpa</div>
                    </div>
                </div>
            </div>

            <!-- TABEL RIWAYAT -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="font-bold text-slate-800">Riwayat Kehadiran Terakhir</h3>
                    <button class="text-xs font-bold text-blue-600 hover:text-blue-700">Lihat Semua</button>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($attendance_history as $log)
                    <div class="px-6 py-4 flex flex-col sm:flex-row items-start sm:items-center justify-between hover:bg-slate-50 transition gap-3">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-lg
                                {{ ($log->status == 'Hadir') ? 'bg-emerald-100 text-emerald-600' : 
                                   (($log->status == 'Sakit') ? 'bg-blue-100 text-blue-600' : 
                                   (($log->status == 'Izin') ? 'bg-amber-100 text-amber-600' : 'bg-rose-100 text-rose-600')) }}">
                                <i class="ph-fill 
                                    {{ ($log->status == 'Hadir') ? 'ph-check' : 
                                       (($log->status == 'Sakit') ? 'ph-thermometer' : 
                                       (($log->status == 'Izin') ? 'ph-file-text' : 'ph-x')) }}"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($log->attendance_date)->translatedFormat('l, d F Y') }}</p>
                                <p class="text-xs text-slate-500 font-mono">
                                    IN: <span class="font-bold text-slate-700">{{ $log->time_in ? \Carbon\Carbon::parse($log->time_in)->format('H:i') : '--:--' }}</span>
                                    <span class="mx-1 text-slate-300">|</span>
                                    OUT: <span class="font-bold text-slate-700">{{ $log->time_out ? \Carbon\Carbon::parse($log->time_out)->format('H:i') : '--:--' }}</span>
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase ring-1 ring-inset
                             {{ ($log->status == 'Hadir' || $log->status == 'Masuk' || $log->status == 'Terlambat') ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 
                                ($log->status == 'Sakit' ? 'bg-blue-50 text-blue-700 ring-blue-200' : 
                                ($log->status == 'Izin' ? 'bg-amber-50 text-amber-700 ring-amber-200' : 'bg-rose-50 text-rose-700 ring-rose-200')) }}">
                             {{ $log->status }}
                        </span>
                    </div>
                    @empty
                    <div class="p-8 text-center text-slate-400">Belum ada data kehadiran bulan ini.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- 5. TAB DISIPLIN (REKAP PELANGGARAN) -->
        <div x-show="activeTab === 'disiplin'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Summary Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-rose-100 sticky top-24">
                        <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-3xl mb-4">
                            <i class="ph-duotone ph-warning-circle"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Catatan Indisipliner</h3>
                        <p class="text-sm text-slate-500 mb-6">Rekapitulasi pelanggaran tata tertib sekolah.</p>
                        
                        <div class="bg-rose-50 rounded-2xl p-5 border border-rose-100 text-center">
                            <p class="text-xs font-bold text-rose-500 uppercase tracking-widest mb-1">Total Poin</p>
                            <p class="text-5xl font-black text-rose-600">{{ $total_violation_points ?? 0 }}</p>
                            <p class="text-xs text-rose-400 mt-2 font-medium">Semakin kecil semakin baik</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[300px]">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-slate-800">Riwayat Pelanggaran</h3>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @if(isset($violations) && count($violations) > 0)
                                @foreach($violations as $record)
                                <div class="p-6 hover:bg-rose-50/30 transition-colors group flex gap-4 items-start">
                                    <div class="flex-shrink-0 w-16 text-center">
                                        <div class="text-2xl font-black text-slate-300 group-hover:text-rose-400 transition-colors">
                                            {{ \Carbon\Carbon::parse($record->date)->format('d') }}
                                        </div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase">
                                            {{ \Carbon\Carbon::parse($record->date)->format('M Y') }}
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-slate-800 text-lg">{{ $record->disciplineType->name ?? 'Pelanggaran Dihapus' }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-rose-100 text-rose-700">
                                                -{{ $record->disciplineType->point_value ?? 0 }} Poin
                                            </span>
                                        </div>
                                        @if($record->notes)
                                            <div class="mt-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100 italic">
                                                "{{ $record->notes }}"
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="p-12 text-center">
                                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="ph-duotone ph-shield-check text-4xl text-emerald-400"></i>
                                    </div>
                                    <h3 class="font-bold text-slate-800">Siswa Teladan!</h3>
                                    <p class="text-slate-500 text-sm mt-1">Tidak ada catatan pelanggaran yang tercatat.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6. TAB PRESTASI (REKAP KEBAIKAN) -->
        <div x-show="activeTab === 'prestasi'" x-cloak x-transition:enter="transition ease-out duration-300">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Summary Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-emerald-100 sticky top-24">
                        <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-3xl mb-4">
                            <i class="ph-duotone ph-trophy"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Catatan Prestasi</h3>
                        <p class="text-sm text-slate-500 mb-6">Rekapitulasi kebaikan dan pencapaian.</p>
                        
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-2xl p-5 border border-emerald-100 text-center">
                            <p class="text-xs font-bold text-emerald-600 uppercase tracking-widest mb-1">Total Poin</p>
                            <p class="text-5xl font-black text-emerald-600">+{{ $total_merit_points ?? 0 }}</p>
                            <p class="text-xs text-emerald-500 mt-2 font-medium">Terus tingkatkan kebaikanmu!</p>
                        </div>
                    </div>
                </div>

                <!-- Timeline List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[300px]">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50">
                            <h3 class="font-bold text-slate-800">Jejak Kebaikan</h3>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @if(isset($achievements) && count($achievements) > 0)
                                @foreach($achievements as $record)
                                <div class="p-6 hover:bg-emerald-50/30 transition-colors group flex gap-4 items-start">
                                    <div class="flex-shrink-0 w-16 text-center">
                                        <div class="text-2xl font-black text-slate-300 group-hover:text-emerald-500 transition-colors">
                                            {{ \Carbon\Carbon::parse($record->date)->format('d') }}
                                        </div>
                                        <div class="text-[10px] font-bold text-slate-400 uppercase">
                                            {{ \Carbon\Carbon::parse($record->date)->format('M Y') }}
                                        </div>
                                    </div>
                                    <div class="flex-grow">
                                        <div class="flex justify-between items-start">
                                            <h4 class="font-bold text-slate-800 text-lg">{{ $record->disciplineType->name ?? 'Data Dihapus' }}</h4>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold bg-emerald-100 text-emerald-700">
                                                +{{ $record->disciplineType->point_value ?? 0 }} Poin
                                            </span>
                                        </div>
                                        @if($record->notes)
                                            <div class="mt-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-xl border border-slate-100">
                                                {{ $record->notes }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="p-12 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="ph-duotone ph-star text-4xl text-slate-300"></i>
                                    </div>
                                    <h3 class="font-bold text-slate-800">Ayo Berprestasi!</h3>
                                    <p class="text-slate-500 text-sm mt-1">Belum ada catatan kebaikan yang tercatat semester ini.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 7. TAB PUSTAKA (LIBRARY) -->
        <div x-show="activeTab === 'perpustakaan'" x-cloak x-transition:enter="transition ease-out duration-300">
             <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Stat Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white p-6 rounded-3xl shadow-sm border border-indigo-100 sticky top-24">
                        <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center text-3xl mb-4">
                            <i class="ph-duotone ph-books"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-1">Perpustakaan</h3>
                        <p class="text-sm text-slate-500 mb-6">Riwayat literasi dan peminjaman buku.</p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <span class="text-xs font-bold text-slate-500 uppercase">Total Bacaan</span>
                                <span class="text-xl font-black text-slate-800">{{ $library_visits ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-indigo-50 rounded-xl">
                                <span class="text-xs font-bold text-indigo-600 uppercase">Sedang Dipinjam</span>
                                <span class="text-xl font-black text-indigo-700">
                                    {{ collect($library_history ?? [])->where('status', 'Dipinjam')->count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Book List -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden min-h-[300px]">
                        <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                            <h3 class="font-bold text-slate-800">Riwayat Peminjaman</h3>
                            <button class="text-xs font-bold text-blue-600 hover:underline">Katalog Buku</button>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @if(isset($library_history) && count($library_history) > 0)
                                @foreach($library_history as $book)
                                <div class="p-5 hover:bg-indigo-50/30 transition-colors flex items-center gap-4">
                                    <div class="w-12 h-16 bg-slate-200 rounded flex-shrink-0 flex items-center justify-center text-slate-400 shadow-sm">
                                        <i class="ph-fill ph-book-open text-2xl"></i>
                                    </div>
                                    <div class="flex-grow">
                                        <h4 class="font-bold text-slate-800 line-clamp-1">{{ $book->title }}</h4>
                                        <div class="flex items-center gap-3 mt-1 text-xs text-slate-500">
                                            <span class="flex items-center gap-1">
                                                <i class="ph-bold ph-calendar-blank"></i> 
                                                Pinjam: {{ \Carbon\Carbon::parse($book->borrow_date)->format('d M Y') }}
                                            </span>
                                            @if($book->return_date)
                                                <span class="flex items-center gap-1">
                                                    <i class="ph-bold ph-check-circle"></i> 
                                                    Kembali: {{ \Carbon\Carbon::parse($book->return_date)->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div>
                                        @if($book->status == 'Dipinjam')
                                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-bold uppercase tracking-wide">Dipinjam</span>
                                        @else
                                            <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[10px] font-bold uppercase tracking-wide">Kembali</span>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="p-12 text-center">
                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                        <i class="ph-duotone ph-book-bookmark text-4xl text-slate-300"></i>
                                    </div>
                                    <h3 class="font-bold text-slate-800">Ayo Membaca!</h3>
                                    <p class="text-slate-500 text-sm mt-1">Kunjungi perpustakaan dan pinjam buku pertamamu.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
             </div>
        </div>

        <!-- 8. TAB KEAGAMAAN -->
        <div x-show="activeTab === 'keagamaan'" x-cloak>
             <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-3xl border border-teal-100 shadow-sm flex items-center gap-6 group hover:border-teal-200 transition-colors">
                    <div class="w-16 h-16 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform"><i class="ph-duotone ph-sun-horizon text-3xl"></i></div>
                    <div><h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sholat Dhuha</h4><p class="text-4xl font-black text-slate-800">{{ $sholat_dhuha ?? 0 }} <span class="text-sm font-bold text-slate-400">Kali</span></p></div>
                </div>
                <div class="bg-white p-6 rounded-3xl border border-orange-100 shadow-sm flex items-center gap-6 group hover:border-orange-200 transition-colors">
                    <div class="w-16 h-16 rounded-2xl bg-orange-50 flex items-center justify-center text-orange-600 group-hover:scale-110 transition-transform"><i class="ph-duotone ph-clock-afternoon text-3xl"></i></div>
                    <div><h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Sholat Dhuhur</h4><p class="text-4xl font-black text-slate-800">{{ $sholat_dhuhur ?? 0 }} <span class="text-sm font-bold text-slate-400">Kali</span></p></div>
                </div>
             </div>
        </div>
        
    </div>
</div>

<style>
    .custom-scrollbar::-webkit-scrollbar { height: 0px; background: transparent; }
    .custom-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    [x-cloak] { display: none !important; }
</style>

<!-- SCRIPTS: Chart Logic -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init Academic Chart
        const academicCtx = document.getElementById('academicChart');
        if (academicCtx && typeof Chart !== 'undefined') {
            const labels = @json($chartData['labels'] ?? []);
            const scores = @json($chartData['scores'] ?? []);
            new Chart(academicCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nilai Akhir',
                        data: scores,
                        backgroundColor: 'rgba(37, 99, 235, 0.2)', 
                        borderColor: 'rgba(37, 99, 235, 1)',
                        borderWidth: 2,
                        borderRadius: 6,
                        barThickness: 20, 
                        maxBarThickness: 30
                    }]
                },
                options: {
                    indexAxis: 'y', 
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9', borderDash: [4, 4] }, border: { display: false } },
                        y: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: 'bold' } } }
                    }
                }
            });
        }

        // Init Attendance Chart
        const attendCtx = document.getElementById('attendanceChart');
        if (attendCtx && typeof Chart !== 'undefined') {
            const data = {
                hadir: {{ $attendanceChart['hadir'] ?? 0 }},
                sakit: {{ $attendanceChart['sakit'] ?? 0 }},
                izin: {{ $attendanceChart['izin'] ?? 0 }},
                alpa: {{ $attendanceChart['alpa'] ?? 0 }}
            };
            
            new Chart(attendCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Hadir', 'Sakit', 'Izin', 'Alpa'],
                    datasets: [{
                        data: [data.hadir, data.sakit, data.izin, data.alpa],
                        backgroundColor: ['#10b981', '#3b82f6', '#f59e0b', '#ef4444'],
                        borderWidth: 0,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { 
                            position: 'bottom', 
                            labels: { usePointStyle: true, padding: 20, font: { family: 'inherit', size: 11, weight: 'bold' } } 
                        },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    }
                }
            });
        }
    });
</script>
@endsection