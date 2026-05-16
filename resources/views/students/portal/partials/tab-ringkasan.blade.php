{{-- 
    === LOGIKA TANGGAL & KALENDER === 
--}}
@php
    $date = \Carbon\Carbon::now();
    
    // --- PENGATURAN MANUAL TANGGAL HIJRIYAH ---
    $hijriOffset = -1; 

    // Default Fallback
    $hijriString = 'Tanggal Hijriyah';
    $hijriDateFull = '-';

    if(extension_loaded('intl')) {
        try {
            $fmt = new IntlDateFormatter(
                'id_ID@calendar=islamic', 
                IntlDateFormatter::FULL, 
                IntlDateFormatter::NONE, 
                'Asia/Jakarta', 
                IntlDateFormatter::TRADITIONAL
            );
            $hijriTimestamp = $date->copy()->addDays($hijriOffset)->getTimestamp();
            $hijriString = $fmt->format($hijriTimestamp);
            $hijriString = str_replace([' AH', ' H'], '', $hijriString);
            $parts = explode(',', $hijriString);
            $hijriDateFull = trim(end($parts)); 
            
        } catch (\Exception $e) {}
    }
@endphp

<div class="space-y-8 animate-in fade-in duration-500 font-sans">
    
    {{-- TAMBAHAN ZONE 0.5: ALERT VERIFIKASI DATA MANDIRI --}}
    @if(!$isAlumni && isset($student) && !$student->is_validated)
        <div class="flex flex-col sm:flex-row items-center gap-4 p-5 md:p-6 rounded-[2rem] bg-rose-50 dark:bg-rose-500/10 border border-rose-200 dark:border-rose-500/20 shadow-sm relative overflow-hidden group">
            <!-- Dekorasi Background -->
            <div class="absolute top-0 right-0 p-4 opacity-5 pointer-events-none group-hover:scale-110 transition-transform duration-500">
                <i class="ph-fill ph-identification-card text-8xl text-rose-600"></i>
            </div>
            
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-700 text-white flex items-center justify-center text-3xl shrink-0 shadow-lg shadow-rose-500/30 z-10 animate-pulse">
                <i class="ph-fill ph-shield-warning"></i>
            </div>
            
            <div class="flex-1 text-center sm:text-left z-10">
                <h4 class="font-black text-rose-900 dark:text-rose-100 text-base md:text-lg tracking-tight mb-0.5">Tindakan Diperlukan: Validasi Data Induk!</h4>
                <p class="text-rose-700 dark:text-rose-300 text-xs md:text-sm font-medium leading-snug max-w-2xl">
                    Sistem mendeteksi NIK dan NISN Anda belum dikonfirmasi. Hal ini wajib dilakukan secepatnya untuk keperluan kelancaran sinkronisasi data dengan server Dapodik Kemdikbud.
                </p>
            </div>
            
            <div class="shrink-0 w-full sm:w-auto z-10 mt-2 sm:mt-0">
                <a href="{{ route('students.verify') }}" class="w-full sm:w-auto px-6 py-3.5 bg-rose-600 hover:bg-rose-700 text-white text-sm font-black rounded-xl transition-all shadow-lg shadow-rose-600/30 flex items-center justify-center gap-2 transform active:scale-95">
                    <i class="ph-bold ph-check-circle text-lg"></i> Validasi Sekarang
                </a>
            </div>
        </div>
    @endif
    
    {{-- ZONE 0: CRITICAL PRIORITY ALERTS --}}
    @if(isset($priorityAlerts) && $priorityAlerts->isNotEmpty())
        <div class="space-y-3">
            @foreach($priorityAlerts as $alert)
                <div class="flex items-center gap-4 p-5 rounded-[2rem] bg-{{ $alert['color'] }}-50 dark:bg-{{ $alert['color'] }}-500/10 border border-{{ $alert['color'] }}-100 dark:border-{{ $alert['color'] }}-500/20 shadow-sm animate-pulse">
                    <div class="w-12 h-12 rounded-2xl bg-{{ $alert['color'] }}-500 text-white flex items-center justify-center text-2xl shrink-0 shadow-lg shadow-{{ $alert['color'] }}-500/20">
                        <i class="{{ $alert['icon'] }}"></i>
                    </div>
                    <div class="flex-1">
                        <h4 class="font-black text-{{ $alert['color'] }}-900 dark:text-{{ $alert['color'] }}-100 text-sm">{{ $alert['title'] }}</h4>
                        <p class="text-{{ $alert['color'] }}-700 dark:text-{{ $alert['color'] }}-300 text-xs font-medium">{{ $alert['message'] }}</p>
                    </div>
                    @if($alert['type'] == 'bk_schedule')
                        <button @click="updateTab('bk')" class="px-4 py-2 bg-{{ $alert['color'] }}-600 text-white text-[10px] font-black uppercase rounded-xl hover:bg-{{ $alert['color'] }}-700 transition-all">Detail</button>
                    @else
                        <button @click="updateTab('disiplin')" class="px-4 py-2 bg-{{ $alert['color'] }}-600 text-white text-[10px] font-black uppercase rounded-xl hover:bg-{{ $alert['color'] }}-700 transition-all">Pulihkan</button>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
    
   {{-- ZONE 1: CBT PRIORITY ALERTS --}}
    @if(isset($priorityExams) && $priorityExams->isNotEmpty())
        <div class="space-y-4">
            @foreach($priorityExams as $priorityExam)
                @php
                    $existingSession = \App\Models\CbtStudentExam::where('student_id', $student->id)
                                        ->where('cbt_exam_id', $priorityExam->id)
                                        ->first();
                    $isOngoing = $existingSession && $existingSession->status == 'ongoing';
                @endphp

                <div class="relative overflow-hidden rounded-[2.5rem] bg-elevate-dark p-8 text-white shadow-xl shadow-elevate-dark/20 lg:p-10 border border-elevate-primary/30">
                    <!-- Background Elevate Style -->
                    @if($loop->iteration % 2 == 0)
                        <div class="absolute top-0 right-0 -mr-20 -mt-20 h-80 w-80 rounded-full bg-elevate-accent blur-[100px] opacity-30 animate-pulse pointer-events-none"></div>
                    @else
                        <div class="absolute top-0 right-0 -mr-20 -mt-20 h-80 w-80 rounded-full bg-elevate-primary blur-[100px] opacity-40 animate-pulse pointer-events-none"></div>
                    @endif
                    <div class="absolute bottom-0 left-0 h-64 w-64 rounded-full bg-elevate-peach blur-[100px] opacity-10 pointer-events-none"></div>
                    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div>
                            <div class="flex items-center gap-3 mb-3">
                                <span class="inline-flex items-center gap-2 rounded-full bg-elevate-peach-dark/20 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-elevate-peach border border-elevate-peach/30 animate-pulse">
                                    <i class="ph-fill ph-broadcast"></i> Ujian Aktif
                                </span>
                                @if($isOngoing)
                                    <span class="inline-flex items-center gap-2 rounded-full bg-amber-500/20 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-amber-300 border border-amber-500/30">
                                        <i class="ph-fill ph-clock-countdown"></i> Lanjutkan
                                    </span>
                                @endif
                            </div>
                            
                            <h2 class="text-2xl md:text-3xl font-black leading-tight mb-2 text-white">
                                {{ $priorityExam->title }}
                            </h2>
                            
                            <div class="flex flex-wrap items-center gap-3 md:gap-4 text-sm font-medium text-slate-300 mt-4">
                                {{-- Mapel --}}
                                <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/5">
                                    <i class="ph-fill ph-book-open text-elevate-accent"></i> {{ $priorityExam->subject_name }}
                                </span>
                                
                                {{-- Hari & Tanggal --}}
                                @if(isset($priorityExam->start_time))
                                    <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/5" title="Tanggal Ujian">
                                        <i class="ph-fill ph-calendar-blank text-elevate-peach-light"></i> 
                                        {{ \Carbon\Carbon::parse($priorityExam->start_time)->translatedFormat('l, d M Y') }}
                                    </span>
                                    
                                    {{-- Jam Mulai & Selesai --}}
                                    <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/5" title="Waktu Ujian">
                                        <i class="ph-fill ph-clock text-emerald-400"></i> 
                                        {{ \Carbon\Carbon::parse($priorityExam->start_time)->format('H:i') }} 
                                        @if(isset($priorityExam->end_time))
                                            - {{ \Carbon\Carbon::parse($priorityExam->end_time)->format('H:i') }}
                                        @endif
                                        WIB
                                    </span>
                                @endif

                                {{-- Durasi --}}
                                <span class="flex items-center gap-1.5 bg-white/10 px-3 py-1.5 rounded-lg border border-white/5">
                                    <i class="ph-fill ph-timer text-elevate-peach"></i> {{ $priorityExam->duration_minutes }} Menit
                                </span>
                                
                                {{-- Tipe Ujian --}}
                                @if(isset($priorityExam->exam_type) && $priorityExam->exam_type == 'google_form')
                                    <span class="flex items-center gap-1.5 bg-emerald-500/20 px-3 py-1.5 rounded-lg border border-emerald-500/30 text-emerald-200">
                                        <i class="ph-bold ph-google-logo text-emerald-400"></i> Google Form
                                    </span>
                                @else
                                    <span class="flex items-center gap-1.5 bg-elevate-primary/40 px-3 py-1.5 rounded-lg border border-elevate-accent/30 text-elevate-soft">
                                        <i class="ph-bold ph-desktop text-elevate-accent"></i> CBT Internal
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="shrink-0">
                            @if($isOngoing)
                                <a href="{{ route('student.exam.run', $priorityExam->id) }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-elevate-peach px-8 py-4 text-sm font-bold text-elevate-dark transition-all hover:bg-elevate-peach-dark hover:text-white hover:scale-105 shadow-lg shadow-elevate-peach/30">
                                    <span class="relative z-10">Lanjutkan Mengerjakan</span>
                                    <i class="ph-bold ph-arrow-right relative z-10 transition-transform group-hover:translate-x-1"></i>
                                </a>
                            @else
                                <a href="{{ route('student.exam.show', $priorityExam->id) }}" class="group relative inline-flex items-center justify-center gap-2 overflow-hidden rounded-2xl bg-white px-8 py-4 text-sm font-bold text-elevate-dark transition-all hover:bg-elevate-soft hover:scale-105 shadow-lg">
                                    <span class="relative z-10">Masuk Ruang Ujian</span>
                                    <i class="ph-bold ph-sign-in relative z-10 transition-transform group-hover:translate-x-1"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

     {{-- ZONE 2: OPERATIONAL DASHBOARD --}}
    @if(!$isAlumni)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        {{-- A. JADWAL HARI INI --}}
        <div class="bg-white dark:bg-slate-800/80 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col h-full relative overflow-hidden group hover:border-elevate-accent/50 dark:hover:border-elevate-accent/30 transition-colors"> 
            
            <div class="flex flex-col sm:flex-row gap-6 h-full">
                {{-- SIDEBAR KIRI: KALENDER & QUICK ACTIONS --}}
                <div class="shrink-0 flex flex-col items-center sm:w-36">
                    
                    {{-- Visual Kalender (MASEHI - BESAR) --}}
                   <div class="w-full bg-white dark:bg-slate-800 rounded-2xl shadow-xl shadow-elevate-dark/5 border border-slate-100 dark:border-slate-700/50 overflow-hidden transform group-hover:rotate-1 transition-transform duration-500 mb-3">
                        <div class="bg-elevate-primary h-7 flex items-center justify-center relative">
                            <div class="absolute top-[-6px] w-2 h-2 rounded-full bg-elevate-dark border border-white z-20"></div> 
                            <span class="text-white font-black uppercase tracking-wider text-[8px] mt-1">KALENDER</span>
                        </div>
                        {{-- Bagian Tengah (Angka Masehi) --}}
                         <div class="bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-700/50 py-1.5 text-center px-1">
                            <span class="text-[9px] font-bold text-elevate-primary dark:text-elevate-accent uppercase block mb-0.5">
                                {{ $date->translatedFormat('l') }}
                            </span>
                            <span class="text-[8px] font-bold text-slate-400 dark:text-slate-500 block border-t border-slate-200 dark:border-slate-700/50 pt-0.5 mt-0.5">
                                <i class="ph-bold ph-moon-stars text-elevate-accent mr-0.5"></i> {{ $hijriDateFull }}
                            </span>
                        </div>
                        {{-- Bagian Bawah (Hari & Hijriyah Kecil) --}}
                        <div class="bg-slate-50 border-t border-slate-100 py-1.5 text-center px-1">
                            <span class="text-[9px] font-bold text-elevate-primary uppercase block mb-0.5">
                                {{ $date->translatedFormat('l') }}
                            </span>
                            <span class="text-[8px] font-bold text-slate-400 block border-t border-slate-200 pt-0.5 mt-0.5">
                                <i class="ph-bold ph-moon-stars text-elevate-accent mr-0.5"></i> {{ $hijriDateFull }}
                            </span>
                        </div>
                    </div>                    
                    {{-- Jam Digital --}}
                    <div class="text-center w-full mb-3">
                        <span class="inline-flex items-center justify-center gap-1 w-full px-2 py-1.5 rounded-lg bg-elevate-soft dark:bg-elevate-dark/50 text-elevate-primary dark:text-elevate-accent text-[10px] font-black border border-elevate-accent/30 dark:border-elevate-accent/20">
                            <i class="ph-bold ph-clock"></i> <span x-text="new Date().toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'})"></span> WIB
                        </span>
                    </div>

                    {{-- PANEL AKSES JURNAL (3 Tombol) --}}
                    <div class="w-full space-y-2">
                         <button @click="updateTab('ramadan_jurnal')" 
                                class="w-full py-2 rounded-xl bg-gradient-to-r from-elevate-primary to-elevate-dark hover:from-elevate-dark hover:to-elevate-primary text-white text-[10px] font-bold shadow-md shadow-elevate-primary/20 transition-all flex items-center justify-center gap-1.5 group/btn border border-elevate-dark/50 hover:-translate-y-0.5">
                            <i class="ph-fill ph-moon-stars text-xs text-elevate-peach"></i>
                            <span>{{ isset($todayRamadanLog) && $todayRamadanLog ? 'Lihat Ramadhan' : 'Jurnal Ramadhan' }}</span>
                        </button>

                        <button @click="updateTab('kebiasaan')" 
                                class="w-full py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 hover:border-elevate-accent dark:hover:border-elevate-accent hover:bg-elevate-soft dark:hover:bg-elevate-dark/30 text-elevate-dark dark:text-slate-300 hover:text-elevate-primary dark:hover:text-elevate-accent text-[10px] font-bold shadow-sm transition-all flex items-center justify-center gap-1.5 hover:-translate-y-0.5">
                            <i class="ph-fill ph-sun-horizon text-xs {{ isset($todayEntry) && $todayEntry ? 'text-elevate-primary' : 'text-slate-400' }}"></i>
                            <span>{{ isset($todayEntry) && $todayEntry ? 'Lihat Kebiasaan' : 'Isi 7 Kebiasaan' }}</span>
                        </button>

                        <button @click="updateTab('literasi_mandiri')" 
                                class="w-full py-2 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 hover:border-elevate-primary/50 dark:hover:border-elevate-primary/50 hover:bg-elevate-soft/50 dark:hover:bg-elevate-dark/30 text-elevate-dark dark:text-slate-300 hover:text-elevate-primary dark:hover:text-elevate-accent text-[10px] font-bold shadow-sm transition-all flex items-center justify-center gap-1.5 hover:-translate-y-0.5">
                            <i class="ph-fill ph-book-open text-xs text-elevate-primary"></i>
                            <span>Catat Literasi</span>
                        </button>
                    </div>
                </div>

                {{-- LIST JADWAL (KANAN) --}}
                <div class="flex-1 min-w-0 flex flex-col">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-bold text-elevate-dark dark:text-slate-100 flex items-center gap-2">
                            <i class="ph-fill ph-chalkboard-teacher text-elevate-primary text-lg"></i>
                            Jadwal Hari Ini
                        </h3>
                    </div>

                    <div class="flex-1 space-y-3 relative z-10 overflow-y-auto custom-scrollbar max-h-[350px] pr-1">
                        @forelse($todaysSchedule ?? [] as $schedule)
                            @php
                                $now = \Carbon\Carbon::now()->format('H:i:s');
                                $isActive = $now >= $schedule->start_time && $now <= $schedule->end_time;
                                $isPast = $now > $schedule->end_time;
                            @endphp
                            <div class="flex items-center gap-3 p-3 rounded-2xl transition-all border {{ $isActive ? 'bg-elevate-soft dark:bg-elevate-dark/30 border-elevate-accent/50 dark:border-elevate-accent shadow-sm' : ($isPast ? 'bg-slate-50 dark:bg-slate-800/50 border-transparent opacity-60' : 'bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-700/50 hover:border-elevate-accent/50 dark:hover:border-elevate-accent') }}">
                                {{-- Jam --}}
                                <div class="w-12 text-center shrink-0">
                                    <p class="text-[10px] font-black {{ $isActive ? 'text-elevate-primary dark:text-elevate-accent' : 'text-slate-700 dark:text-slate-300' }}">
                                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    </p>
                                    <div class="w-0.5 h-2 bg-slate-200 dark:bg-slate-600 mx-auto my-0.5"></div>
                                    <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500">
                                        {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    </p>
                                </div>
                                
                                {{-- Info Mapel --}}
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-xs truncate {{ $isActive ? 'text-elevate-dark dark:text-elevate-accent' : 'text-slate-800 dark:text-slate-200' }}">
                                        {{ $schedule->subject->name ?? 'Mapel Umum' }}
                                    </h4>
                                    <div class="flex items-center gap-1 text-[10px] text-slate-500 dark:text-slate-400 mt-0.5 truncate">
                                        <i class="ph-fill ph-user {{ $isActive ? 'text-elevate-primary' : 'text-slate-400' }}"></i>
                                        {{ Str::limit($schedule->teacher->name ?? '-', 15) }}
                                    </div>
                                </div>

                                {{-- Indikator Aktif --}}
                                @if($isActive)
                                    <div class="w-2 h-2 rounded-full bg-elevate-accent animate-pulse shadow-[0_0_8px_rgba(86,187,241,0.6)]"></div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-10 h-10 bg-elevate-soft dark:bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-2 text-elevate-primary/50 dark:text-slate-500">
                                    <i class="ph-duotone ph-coffee text-xl"></i>
                                </div>
                                <p class="text-xs font-bold text-slate-500 dark:text-slate-400">Tidak ada KBM.</p>
                            </div>
                        @endforelse
                    </div>
                    
                   <div class="mt-3 pt-3 border-t border-slate-50 dark:border-slate-700/50 text-center">
                        <button @click="updateTab('jadwal')" class="text-[10px] font-bold text-elevate-primary hover:text-elevate-dark transition flex items-center justify-center gap-1">
                            Lihat Jadwal Lengkap <i class="ph-bold ph-caret-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

       {{-- B. TUGAS PENDING (LMS) --}}
        <div class="bg-white dark:bg-slate-800/80 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700/50 shadow-sm flex flex-col h-full relative overflow-hidden group hover:border-elevate-peach-light/50 dark:hover:border-elevate-peach/30 transition-colors">
            <div class="absolute top-0 right-0 p-6 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity pointer-events-none">
                <i class="ph-duotone ph-clipboard-text text-8xl text-elevate-peach"></i>
            </div>

            <div class="flex justify-between items-center mb-6 relative z-10">
                <h3 class="font-bold text-elevate-dark dark:text-slate-100 flex items-center gap-3">
                    <span class="w-10 h-10 rounded-2xl bg-elevate-peach-light/30 text-elevate-peach-dark flex items-center justify-center border border-elevate-peach-light/50">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                    </span>
                    Tugas Belum Selesai
                </h3>
                @if(($pendingTasks->count() ?? 0) > 0)
                    <span class="px-2 py-1 rounded-lg bg-rose-50 text-rose-600 border border-rose-100 text-[10px] font-black uppercase tracking-wider">
                        {{ $pendingTasks->count() }} Pending
                    </span>
                @endif
            </div>

            <div class="flex-1 space-y-3 relative z-10">
                @forelse($pendingTasks ?? [] as $task)
                    @php
                        $deadline = \Carbon\Carbon::parse($task->deadline);
                        $isUrgent = $deadline->isToday() || $deadline->isPast();
                    @endphp
                    <div class="p-4 rounded-2xl border {{ $isUrgent ? 'bg-rose-50 dark:bg-rose-500/10 border-rose-100 dark:border-rose-500/20' : 'bg-white dark:bg-slate-800 border-slate-100 dark:border-slate-700/50' }} hover:shadow-md transition-all group/task">
                        <div class="flex justify-between items-start mb-2">
                            <span class="text-[10px] font-black uppercase tracking-wider {{ $isUrgent ? 'text-rose-600 dark:text-rose-400' : 'text-elevate-primary dark:text-elevate-accent' }}">
                                {{ $task->subject->name ?? 'Tugas' }}
                            </span>
                            <span class="text-[10px] font-bold flex items-center gap-1 {{ $isUrgent ? 'text-rose-500 dark:text-rose-400' : 'text-slate-400 dark:text-slate-500' }}">
                                <i class="ph-bold ph-clock"></i> {{ $deadline->diffForHumans() }}
                            </span>
                        </div>
                        <h4 class="font-bold text-elevate-dark dark:text-slate-200 text-sm mb-3 line-clamp-1 group-hover/task:text-elevate-peach-dark dark:group-hover/task:text-elevate-peach transition-colors">
                            {{ $task->title }}
                        </h4>
                        
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                @if($task->assignment_type == 'file_upload')
                                    <span class="text-[10px] font-bold text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-slate-700 px-2 py-0.5 rounded border border-slate-200 dark:border-slate-600">Upload</span>
                                @elseif($task->assignment_type == 'quiz')
                                    <span class="text-[10px] font-bold text-elevate-primary dark:text-elevate-accent bg-elevate-soft dark:bg-elevate-dark/30 px-2 py-0.5 rounded border border-elevate-accent/30 dark:border-elevate-accent/20">Kuis</span>
                                @else
                                    <span class="text-[10px] font-bold text-elevate-dark dark:text-elevate-accent bg-slate-50 dark:bg-elevate-dark/30 px-2 py-0.5 rounded border border-slate-200 dark:border-elevate-accent/20">Link</span>
                                @endif
                            </div>

                            <button @click="updateTab('lms')" class="px-4 py-1.5 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-[10px] font-bold text-elevate-dark dark:text-slate-300 hover:bg-elevate-peach hover:text-white dark:hover:text-white hover:border-elevate-peach transition-all shadow-sm flex items-center gap-1">
                                Kerjakan <i class="ph-bold ph-arrow-right"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-14 h-14 bg-elevate-soft rounded-full flex items-center justify-center mx-auto mb-3 text-elevate-primary">
                            <i class="ph-duotone ph-check-fat text-2xl"></i>
                        </div>
                        <p class="text-sm font-bold text-slate-500">Hebat! Semua tugas beres.</p>
                        <p class="text-xs text-slate-400">Tidak ada tanggungan tugas saat ini.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-4 pt-4 border-t border-slate-50 dark:border-slate-700/50 text-center relative z-10">
                <button @click="updateTab('lms')" class="text-xs font-bold text-elevate-peach-dark hover:text-elevate-peach transition flex items-center justify-center gap-1">
                    Lihat Semua Tugas <i class="ph-bold ph-caret-right"></i>
                </button>
            </div>
        </div>
    </div>
    @endif

  {{-- ZONE 2.5: AGENDA SEKOLAH --}}
    @if(isset($upcomingAgendas) && $upcomingAgendas->isNotEmpty() && !$isAlumni)
    <div class="bg-white dark:bg-slate-800/80 p-6 rounded-[2.5rem] border border-slate-100 dark:border-slate-700/50 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-elevate-dark dark:text-slate-100 flex items-center gap-3">
                <span class="w-10 h-10 rounded-2xl bg-elevate-soft text-elevate-primary flex items-center justify-center border border-elevate-accent/30">
                    <i class="ph-fill ph-calendar-star text-xl"></i>
                </span>
                Agenda Sekolah Terdekat
            </h3>
            <button @click="updateTab('jadwal'); setTimeout(() => { window.dispatchEvent(new Event('resize')); }, 100);" class="text-xs font-bold text-elevate-primary hover:text-elevate-dark transition flex items-center gap-1">
                Lihat Kalender <i class="ph-bold ph-caret-right"></i>
            </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach($upcomingAgendas as $agenda)
                @php
                    $start = \Carbon\Carbon::parse($agenda->start_date)->startOfDay();
                    $end = $agenda->end_date ? \Carbon\Carbon::parse($agenda->end_date)->endOfDay() : $start->copy()->endOfDay();
                    $isOngoing = \Carbon\Carbon::now()->between($start, $end);
                    
                    $bgIcon = 'bg-elevate-soft dark:bg-elevate-primary/40 text-elevate-primary dark:text-elevate-accent';
                    $bgBadge = 'bg-white dark:bg-elevate-dark/20 text-elevate-primary dark:text-elevate-accent border-elevate-accent/30 dark:border-elevate-accent/50';
                    $borderHover = 'hover:border-elevate-accent/50 dark:hover:border-elevate-primary/70';
                    $icon = 'ph-calendar-check';

                    if($agenda->type == 'libur' || $agenda->type == 'nasional') { 
                        $icon = 'ph-tent'; 
                        $bgIcon = 'bg-rose-100 dark:bg-rose-900/40 text-rose-600 dark:text-rose-400';
                        $bgBadge = 'bg-rose-50 dark:bg-rose-900/20 text-rose-600 dark:text-rose-400 border-rose-200 dark:border-rose-800';
                        $borderHover = 'hover:border-rose-200 dark:hover:border-rose-700';
                    } elseif($agenda->type == 'ujian') { 
                        $icon = 'ph-pencil-simple'; 
                        $bgIcon = 'bg-elevate-peach-light/30 dark:bg-elevate-peach-dark/40 text-elevate-peach-dark dark:text-elevate-peach';
                        $bgBadge = 'bg-white dark:bg-elevate-peach-dark/20 text-elevate-peach-dark dark:text-elevate-peach border-elevate-peach/30 dark:border-elevate-peach/50';
                        $borderHover = 'hover:border-elevate-peach/50 dark:hover:border-elevate-peach-dark/70';
                    }
                @endphp
                <div class="flex items-start gap-4 p-4 rounded-2xl border border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-slate-800/50 hover:bg-white dark:hover:bg-slate-800 hover:shadow-sm {{ $borderHover }} transition-all relative overflow-hidden">
                    <div class="w-12 h-12 rounded-xl {{ $bgIcon }} flex items-center justify-center text-xl shrink-0">
                        <i class="ph-duotone {{ $icon }}"></i>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <span class="text-[9px] font-black uppercase tracking-wider px-2 py-0.5 rounded {{ $bgBadge }} border">
                                {{ $agenda->type }}
                            </span>
                            @if($isOngoing)
                                <span class="text-[9px] font-black uppercase tracking-wider text-rose-500 animate-pulse flex items-center gap-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Sedang Berlangsung
                                </span>
                            @endif
                        </div>
                        <h4 class="font-bold text-elevate-dark dark:text-slate-200 text-sm mb-1 line-clamp-1" title="{{ $agenda->title }}">{{ $agenda->title }}</h4>
                        <p class="text-xs font-bold text-slate-500 dark:text-slate-400 flex items-center gap-1.5">
                            <i class="ph-bold ph-clock"></i> 
                            {{ \Carbon\Carbon::parse($agenda->start_date)->translatedFormat('d M Y') }}
                            @if($agenda->end_date && \Carbon\Carbon::parse($agenda->end_date)->toDateString() != \Carbon\Carbon::parse($agenda->start_date)->toDateString())
                                - {{ \Carbon\Carbon::parse($agenda->end_date)->translatedFormat('d M Y') }}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

     {{-- ZONE 3: STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        @if($isAlumni)
            <div class="md:col-span-3 bg-elevate-gradient-card dark:from-amber-900/20 dark:to-orange-900/20 border border-elevate-peach/20 dark:border-amber-800/50 rounded-[2.5rem] p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden shadow-sm">
                <div class="absolute top-0 right-0 w-48 h-48 bg-elevate-peach rounded-full mix-blend-multiply filter blur-3xl opacity-20 -mr-16 -mt-16 pointer-events-none"></div>
                <div class="w-24 h-24 bg-white dark:bg-slate-800 text-elevate-peach-dark rounded-full flex items-center justify-center text-5xl shrink-0 z-10 shadow-lg border-4 border-white dark:border-amber-900/50">
                    <i class="ph-duotone ph-graduation-cap"></i>
                </div>
                <div class="flex-1 text-center md:text-left z-10 w-full">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-elevate-peach-light/50 dark:bg-amber-900/40 text-elevate-peach-dark dark:text-amber-400 text-[10px] font-black uppercase tracking-widest mb-2 border border-elevate-peach/30">
                                Alumni Sekolah
                            </div>
                            <h3 class="text-2xl font-black text-elevate-dark dark:text-slate-100 mb-1">Selamat Mengabdi di Masyarakat!</h3>
                            <p class="text-slate-600 dark:text-slate-300 text-sm max-w-xl">
                                Siswa ini dinyatakan <strong>LULUS</strong> pada tahun {{ $student->graduation_year ?? \Carbon\Carbon::parse($student->graduated_date)->year }}. Tetap jaga nama baik almamater.
                            </p>
                        </div>
                        <div class="flex flex-wrap justify-center gap-3">
                            @if($student->alumniProfile)
                                <div class="flex flex-col items-end">
                                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wide">Status Saat Ini</span>
                                    <span class="font-black text-elevate-peach-dark dark:text-amber-400 text-lg">{{ $student->alumniProfile->activity_status }}</span>
                                </div>
                            @else
                                <a href="{{ Route::has('alumni.tracer') ? route('alumni.tracer') : '#' }}" class="group inline-flex items-center gap-2 px-6 py-3 bg-elevate-peach-dark hover:bg-elevate-peach text-white rounded-xl font-bold shadow-xl shadow-elevate-peach/30 transition-all">
                                    <i class="ph-bold ph-clipboard-text"></i> Isi Tracer Study
                                    <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- CARD KEHADIRAN --}}
            <div class="bg-white dark:bg-slate-800/80 p-6 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full hover:border-elevate-accent/30">
                <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition transform group-hover:scale-110 pointer-events-none">
                    <i class="ph-fill ph-chart-pie-slice text-9xl text-elevate-accent"></i>
                </div>
                
                <div class="relative z-10">
                    <div class="flex justify-between items-start mb-2">
                        <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                            <i class="ph-bold ph-calendar-check text-elevate-accent"></i> Kehadiran
                        </h3>
                        <div class="px-2 py-0.5 bg-slate-50 border border-slate-100 dark:bg-slate-700 rounded text-[10px] font-bold text-slate-500 dark:text-slate-400">Semester Ini</div>
                    </div>

                    <div class="flex items-baseline gap-2 mb-4">
                        @php 
                            $total_hari = ($hadir ?? 0) + ($sakit ?? 0) + ($izin ?? 0) + ($alpa ?? 0); 
                            $persen = $total_hari > 0 ? round(($hadir/$total_hari)*100) : 0; 
                        @endphp
                        <span class="text-5xl font-black text-elevate-dark dark:text-slate-100 tracking-tight">{{ $persen }}<span class="text-2xl text-slate-400 dark:text-slate-500">%</span></span>
                    </div>
                    
                    <div class="w-full bg-slate-100 dark:bg-slate-700 rounded-full h-3 mb-4 overflow-hidden flex">
                        <div class="h-full bg-elevate-accent" style="width: {{ $persen }}%"></div>
                        @php
                            $persenSakitIzin = $total_hari > 0 ? round((($sakit+$izin)/$total_hari)*100) : 0;
                        @endphp
                        <div class="h-full bg-elevate-primary/40" style="width: {{ $persenSakitIzin }}%"></div>
                    </div>

                    <div class="grid grid-cols-2 gap-2">
                        <div class="px-3 py-2 bg-elevate-soft dark:bg-elevate-dark/30 text-elevate-primary dark:text-elevate-accent border border-elevate-accent/20 rounded-xl text-xs font-bold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-elevate-accent"></div> 
                            Hadir: {{ $hadir ?? 0 }}
                        </div>
                        <div class="px-3 py-2 bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 border border-rose-100 dark:border-rose-800/50 rounded-xl text-xs font-bold flex items-center gap-2">
                            <div class="w-2 h-2 rounded-full bg-rose-500"></div> 
                            Alpa: {{ $alpa ?? 0 }}
                        </div>
                        <div class="px-3 py-2 bg-slate-50 dark:bg-slate-900/30 text-slate-700 dark:text-slate-400 border border-slate-100 dark:border-slate-800/50 rounded-xl text-xs font-bold flex items-center gap-2 col-span-2">
                            <div class="w-2 h-2 rounded-full bg-elevate-primary/40"></div> 
                            Sakit / Izin: {{ ($sakit ?? 0) + ($izin ?? 0) }}
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        {{-- CARD POIN KARAKTER --}}
        <div class="bg-white dark:bg-slate-800/80 p-6 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col h-full hover:border-elevate-peach/30">
            <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition transform group-hover:scale-110 pointer-events-none">
                <i class="ph-fill ph-star text-9xl text-elevate-peach"></i>
            </div>
            
            <div class="relative z-10 w-full flex flex-col h-full">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex flex-col">
                        <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1 mb-1">
                            <i class="ph-bold ph-medal text-elevate-peach"></i> Poin Karakter
                        </h3>
                        @php $streak = $login_streak ?? 5; @endphp
                        <span class="text-[10px] font-black text-elevate-peach-dark flex items-center gap-1">
                            <i class="ph-fill ph-fire text-elevate-peach animate-pulse"></i> {{ $streak }} Hari Streak!
                        </span>
                    </div>
                    
                    @php 
                        $behaviorScore = 200 - ($total_violation_points ?? 0) + ($total_merit_points ?? 0);
                        $scoreColor = $behaviorScore >= 180 ? 'text-elevate-primary dark:text-elevate-accent bg-elevate-soft dark:bg-elevate-dark/30 border-elevate-accent/30 dark:border-elevate-accent' : ($behaviorScore >= 150 ? 'text-elevate-peach-dark dark:text-elevate-peach bg-elevate-peach-light/30 dark:bg-elevate-peach-dark/30 border-elevate-peach/40 dark:border-elevate-peach' : 'text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/30 border-rose-100 dark:border-rose-800');
                        
                        $levelTitles = [
                            1 => 'Pemula',
                            2 => 'Pelajar Biasa',
                            3 => 'Pelajar Baik',
                            4 => 'Pelajar Teladan',
                            5 => 'Legend'
                        ];
                        
                        // Adaptasi color level menggunakan palet elevate
                        if ($behaviorScore >= 300) { $currentLevel = 5; $minScore = 300; $maxScore = 400; $colorLevel = 'from-elevate-dark to-elevate-primary'; }
                        elseif ($behaviorScore >= 250) { $currentLevel = 4; $minScore = 250; $maxScore = 300; $colorLevel = 'from-elevate-primary to-elevate-accent'; }
                        elseif ($behaviorScore >= 200) { $currentLevel = 3; $minScore = 200; $maxScore = 250; $colorLevel = 'from-elevate-accent to-emerald-400'; }
                        elseif ($behaviorScore >= 150) { $currentLevel = 2; $minScore = 150; $maxScore = 200; $colorLevel = 'from-elevate-peach to-elevate-peach-dark'; }
                        else { $currentLevel = 1; $minScore = 0; $maxScore = 150; $colorLevel = 'from-rose-400 to-rose-600'; }

                        $levelName = $levelTitles[$currentLevel];
                        $progress = $currentLevel < 5 ? (($behaviorScore - $minScore) / ($maxScore - $minScore)) * 100 : 100;
                    @endphp
                    <span class="px-2 py-1 rounded-lg text-xs font-black border {{ $scoreColor }} shadow-sm">
                        Skor: {{ $behaviorScore }}
                    </span>
                </div>

                <div class="mb-4 mt-2">
                    <div class="flex justify-between items-end mb-1.5">
                        <span class="text-sm font-black text-elevate-dark dark:text-slate-200 flex items-center gap-1.5">
                            <span class="w-5 h-5 rounded-full bg-gradient-to-br {{ $colorLevel }} text-white flex items-center justify-center text-[10px] shadow-sm">
                                {{ $currentLevel }}
                            </span>
                            {{ $levelName }}
                        </span>
                        @if($currentLevel < 5)
                            <span class="text-[9px] font-bold text-slate-400">
                                {{ $maxScore - $behaviorScore }} poin ke Lv {{ $currentLevel + 1 }}
                            </span>
                        @else
                            <span class="text-[9px] font-bold text-elevate-peach flex items-center gap-0.5">
                                <i class="ph-fill ph-crown"></i> Max Level
                            </span>
                        @endif
                    </div>
                    <div class="w-full h-2.5 bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden shadow-inner relative">
                        <div class="h-full rounded-full bg-gradient-to-r {{ $colorLevel }} relative transition-all duration-1000" style="width: {{ $progress }}%">
                            <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <div class="bg-elevate-soft/50 dark:bg-elevate-dark/20 p-3 rounded-2xl border border-elevate-accent/20 dark:border-elevate-accent/50 text-center flex flex-col justify-center hover:bg-elevate-soft dark:hover:bg-elevate-dark/40 transition-colors cursor-default">
                        <div class="w-7 h-7 rounded-full bg-elevate-accent/20 dark:bg-elevate-primary/50 text-elevate-primary dark:text-elevate-accent flex items-center justify-center mx-auto mb-1.5 text-sm shadow-sm">
                            <i class="ph-bold ph-plus"></i>
                        </div>
                        <p class="text-[9px] text-elevate-primary dark:text-elevate-accent font-bold uppercase tracking-wider">Prestasi</p>
                        <p class="text-xl font-black text-elevate-dark dark:text-white">{{ $total_merit_points ?? 0 }}</p>
                    </div>
                    <div class="bg-rose-50/50 dark:bg-rose-900/20 p-3 rounded-2xl border border-rose-100 dark:border-rose-800/50 text-center flex flex-col justify-center hover:bg-rose-50 dark:hover:bg-rose-900/40 transition-colors cursor-default">
                        <div class="w-7 h-7 rounded-full bg-rose-100 dark:bg-rose-800/50 text-rose-600 dark:text-rose-400 flex items-center justify-center mx-auto mb-1.5 text-sm shadow-sm">
                            <i class="ph-bold ph-minus"></i>
                        </div>
                        <p class="text-[9px] text-rose-600 dark:text-rose-400 font-bold uppercase tracking-wider">Pelanggaran</p>
                        <p class="text-xl font-black text-rose-700 dark:text-rose-300">{{ $total_violation_points ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CARD LITERASI --}}
        <div class="bg-white dark:bg-slate-800/80 p-6 rounded-[2.5rem] shadow-sm border border-slate-100 dark:border-slate-700/50 relative overflow-hidden group hover:shadow-lg transition-all duration-300 flex flex-col justify-between h-full hover:border-elevate-primary/30">
            <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:opacity-[0.08] transition transform group-hover:scale-110 pointer-events-none">
                <i class="ph-fill ph-books text-9xl text-elevate-primary"></i>
            </div>
            <div class="relative z-10">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-slate-400 text-xs font-bold uppercase tracking-wider flex items-center gap-1">
                        <i class="ph-bold ph-book-open text-elevate-primary"></i> Literasi
                    </h3>
                    <a href="{{ Route::has('student.library.index') ? route('student.library.index') : '#' }}" class="text-[10px] font-bold text-elevate-primary dark:text-elevate-accent hover:underline">
                        Lihat Katalog
                    </a>
                </div>

                @php
                    $ebookCount = isset($ebookHistory) ? $ebookHistory->count() : 0;
                    $totalLiterasi = ($library_visits ?? 0) + $ebookCount;
                @endphp

                <div class="flex items-baseline gap-2 mb-4">
                    <span class="text-5xl font-black text-elevate-dark dark:text-slate-100 tracking-tight">{{ $totalLiterasi }}</span>
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-bold bg-slate-50 border border-slate-100 dark:bg-slate-700 px-2 py-1 rounded-lg dark:border-slate-600">Aktivitas</span>
                </div>
                
                <div class="space-y-2">
                    <div class="flex justify-between items-center p-3 rounded-xl bg-elevate-soft/50 dark:bg-elevate-dark/30 border border-elevate-accent/20 dark:border-elevate-primary/50 hover:bg-elevate-soft dark:hover:bg-elevate-dark/50 transition-colors">
                        <span class="text-xs font-bold text-elevate-primary dark:text-elevate-accent flex items-center gap-2">
                            <i class="ph-bold ph-book"></i> Pinjam Fisik
                        </span>
                        <span class="font-black text-elevate-dark dark:text-white">{{ $library_visits ?? 0 }}</span>
                    </div>
                    <div class="flex justify-between items-center p-3 rounded-xl bg-white dark:bg-slate-800/30 border border-slate-200 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                        <span class="text-xs font-bold text-slate-600 dark:text-slate-400 flex items-center gap-2">
                            <i class="ph-bold ph-device-tablet-camera"></i> E-Book
                        </span>
                        <span class="font-black text-slate-800 dark:text-slate-300">{{ $ebookCount }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>