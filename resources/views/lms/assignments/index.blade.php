<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Tugas & Latihan') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-b from-[#0d52a1]/20 via-[#031d3d]/10 to-transparent opacity-30 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Asesmen & Tugas Mandiri"
                badgeIcon="ph-pencil-simple"
                title="Tugas & Latihan"
                titleHighlight="Siswa Terpadu"
                description="Buat tugas, latihan soal, kuis interaktif, serta pantau hasil penyerahan dan penilaian siswa secara real-time."
                :chips="[
                    ['icon' => 'ph-clipboard-text', 'label' => 'Kuis & Tugas Online'],
                    ['icon' => 'ph-clock', 'label' => 'Batas Waktu Fleksibel'],
                    ['icon' => 'ph-check-circle', 'label' => 'Koreksi & Nilai Terpadu']
                ]"
                heroIcon="ph-pencil-simple"
                :showcaseNumber="$assignments->total() ?? count($assignments)"
                showcaseLabel="Total Tugas"
                showcaseSubtitle="Kuis & Penugasan"
                statusOrb="Sistem Aktif"
                statusColor="emerald"
            >
                <x-slot:cta>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('lms.assignments.create') }}" class="px-5 py-2.5 bg-gradient-to-r from-sky-400 to-[#0d52a1] hover:from-sky-300 hover:to-sky-700 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-sky-500/25 hover:shadow-sky-500/40 hover:scale-[1.02] transition-all flex items-center gap-2 border border-white/20 active:scale-95">
                            <i class="ph-bold ph-plus text-base"></i> Buat Latihan Baru
                        </a>
                        <a href="{{ route('lms.grades.index') }}" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-chart-bar text-base text-sky-400"></i> Rekap Nilai
                        </a>
                        <a href="{{ route('dashboard') }}" class="px-4 py-2.5 bg-white/10 hover:bg-white/20 text-slate-200 hover:text-white font-bold text-xs rounded-xl border border-white/15 backdrop-blur-md hover:scale-[1.02] transition-all flex items-center gap-1.5">
                            <i class="ph-bold ph-arrow-left text-sm text-sky-400"></i> Dashboard
                        </a>
                    </div>
                </x-slot:cta>
            </x-hero-section>

            {{-- INFO ALUR BELAJAR (PRO-TIP UNTUK GURU) --}}
            <div class="animate-enter mb-10 bg-sky-500/10 border border-sky-400/20 p-5 rounded-[2rem] flex flex-col md:flex-row items-start md:items-center gap-4 shadow-sm backdrop-blur-md" style="animation-delay: 50ms">
                <div class="w-12 h-12 bg-sky-500/20 text-sky-300 rounded-2xl shrink-0 flex items-center justify-center text-2xl border border-sky-400/30">
                    <i class="ph-duotone ph-info"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-black text-sky-200 mb-1">Pro-Tip: Urutan Belajar Siswa</h3>
                    <p class="text-xs font-medium text-slate-300 leading-relaxed">
                        Tugas/Latihan yang Anda buat akan otomatis digabungkan dengan Materi menjadi sebuah alur belajar yang runtut. Gunakan tombol <b class="text-sky-300">Preview</b> <i class="ph-bold ph-presentation-chart"></i> pada masing-masing tugas untuk melihat tampilannya di sisi siswa.
                    </p>
                </div>
            </div>

            {{-- LIST TUGAS --}}
            @if($assignments->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($assignments as $index => $task)
                        @php
                            $iconType = 'ph-file-text';
                            $labelType = 'Tugas File';
                            if($task->assignment_type == 'quiz') { $iconType = 'ph-brain'; $labelType = 'Kuis Online'; }
                            if($task->assignment_type == 'link') { $iconType = 'ph-link'; $labelType = 'Tugas Link'; }
                            
                            $isExpired = now() > $task->deadline;

                            $assignSubName = strtolower($task->subject->name ?? '');
                            $assignCover = match(true) {
                                $task->assignment_type === 'quiz' => ['bg' => 'from-violet-600 via-purple-800 to-slate-900', 'icon' => 'ph-brain'],
                                $task->assignment_type === 'link' => ['bg' => 'from-rose-500 via-pink-700 to-slate-900', 'icon' => 'ph-link'],
                                str_contains($assignSubName, 'matematika') => ['bg' => 'from-blue-600 via-indigo-700 to-slate-900', 'icon' => 'ph-calculator'],
                                str_contains($assignSubName, 'ipa') || str_contains($assignSubName, 'biologi') || str_contains($assignSubName, 'fisika') || str_contains($assignSubName, 'kimia') => ['bg' => 'from-emerald-600 via-teal-700 to-slate-900', 'icon' => 'ph-flask'],
                                str_contains($assignSubName, 'inggris') || str_contains($assignSubName, 'indonesia') || str_contains($assignSubName, 'bahasa') => ['bg' => 'from-purple-600 via-indigo-800 to-slate-900', 'icon' => 'ph-translate'],
                                str_contains($assignSubName, 'informatika') || str_contains($assignSubName, 'tik') => ['bg' => 'from-sky-500 via-blue-800 to-slate-950', 'icon' => 'ph-code'],
                                str_contains($assignSubName, 'ips') || str_contains($assignSubName, 'sejarah') => ['bg' => 'from-amber-600 via-orange-700 to-slate-950', 'icon' => 'ph-compass'],
                                str_contains($assignSubName, 'agama') => ['bg' => 'from-teal-600 via-cyan-700 to-slate-900', 'icon' => 'ph-hands-praying'],
                                default => ['bg' => 'from-sky-600 via-indigo-800 to-slate-900', 'icon' => 'ph-file-text']
                            };
                        @endphp

                        <div class="animate-enter group relative bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-white/10 hover:border-sky-400/50 backdrop-blur-xl transition-all duration-300 flex flex-col h-full hover:-translate-y-1 overflow-hidden" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                            
                            {{-- COVER THUMBNAIL --}}
                            <div class="relative h-40 w-full overflow-hidden shrink-0 bg-slate-900">
                                @if($task->cover_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($task->cover_image))
                                    <img src="{{ asset('storage/' . $task->cover_image) }}" alt="{{ $task->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-[#021124] via-transparent to-black/30"></div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br {{ $assignCover['bg'] }} flex flex-col justify-between p-4 group-hover:scale-105 transition-transform duration-500 relative">
                                        <div class="absolute -right-6 -bottom-6 text-white/[0.07] pointer-events-none select-none" style="font-size:9rem">
                                            <i class="ph-duotone {{ $assignCover['icon'] }}"></i>
                                        </div>
                                        <div class="absolute inset-0 bg-gradient-to-t from-[#021124] via-transparent to-transparent pointer-events-none"></div>
                                    </div>
                                @endif

                                {{-- Top Badges Overlay --}}
                                <div class="absolute top-3 left-3 right-3 z-10 flex items-center justify-between gap-2 pointer-events-none">
                                    <span class="px-2.5 py-1 rounded-lg bg-[#021124]/85 backdrop-blur-md text-sky-300 border border-white/20 text-[10px] font-black uppercase tracking-wider shadow-md truncate max-w-[170px]">
                                        <i class="ph-fill ph-book-bookmark text-sky-400 mr-1"></i>{{ $task->subject->name ?? 'Mata Pelajaran' }}
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-900/85 backdrop-blur-md border border-white/20 text-slate-200 text-[10px] font-bold shadow-md flex items-center gap-1.5 shrink-0">
                                        <i class="ph-bold {{ $iconType }} text-sky-400"></i>
                                        {{ $labelType }}
                                    </span>
                                </div>

                                {{-- Bottom Badges Overlay --}}
                                <div class="absolute bottom-2.5 left-3 right-3 z-10 flex items-center justify-between gap-2 pointer-events-none">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-md bg-black/60 backdrop-blur-md text-slate-300 text-[10px] font-semibold border border-white/10 truncate max-w-[180px]">
                                        <i class="ph-fill ph-users text-sky-400 shrink-0"></i>
                                        <span class="truncate">
                                            @if($task->is_bulk)
                                                Semua Kelas {{ $task->target_grade ?? '' }} ({{ $task->total_classes }})
                                            @else
                                                {{ $task->schoolClass->name ?? 'Semua' }}
                                            @endif
                                        </span>
                                    </span>

                                    @if($isExpired)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-slate-900/80 backdrop-blur-md border border-slate-400/20 text-slate-300 text-[9px] font-black shadow-sm shrink-0">
                                            <i class="ph-bold ph-lock-key"></i> Ditutup
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md bg-emerald-900/80 backdrop-blur-md border border-emerald-400/30 text-emerald-300 text-[9px] font-black animate-pulse shadow-sm shrink-0">
                                            <i class="ph-bold ph-clock"></i> Aktif
                                        </span>
                                    @endif
                                </div>
                            </div>

                            {{-- Inner Card Body --}}
                            <div class="p-4 sm:p-5 flex flex-col flex-grow relative overflow-hidden">
                                
                                {{-- Background Dekoratif --}}
                                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full bg-sky-500/10 opacity-30 pointer-events-none blur-xl"></div>

                                {{-- Judul & Mapel --}}
                                <div class="mb-3 relative z-10">
                                    <h3 class="font-bold text-base text-white group-hover:text-sky-300 transition-colors line-clamp-1" title="{{ $task->title }}">
                                        {{ $task->title }}
                                    </h3>
                                    <p class="text-xs font-semibold text-slate-400 mt-0.5">{{ $task->subject->name }}</p>
                                </div>

                                {{-- Info Detail Grid --}}
                                <div class="grid grid-cols-2 gap-2.5 mb-3.5 relative z-10">
                                    {{-- Target Kelas --}}
                                    <div class="bg-slate-900/60 px-3 py-2 rounded-xl border border-white/10">
                                        <p class="text-[9px] font-bold text-sky-400 uppercase tracking-widest mb-0.5">Target</p>
                                        <div class="text-xs font-bold text-slate-200 flex items-center gap-1.5 truncate">
                                            <i class="ph-fill ph-users text-sky-400 shrink-0"></i>
                                            <span class="truncate">
                                                @if($task->is_bulk)
                                                    Semua Kelas {{ $task->target_grade ?? '' }} ({{ $task->total_classes }})
                                                @else
                                                    {{ $task->schoolClass->name ?? 'Semua' }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Deadline --}}
                                    <div class="bg-slate-900/60 px-3 py-2 rounded-xl border border-white/10">
                                        <p class="text-[9px] font-bold text-sky-400 uppercase tracking-widest mb-0.5">Deadline</p>
                                        <div class="text-xs font-bold {{ $isExpired ? 'text-rose-400' : 'text-slate-200' }} flex items-center gap-1.5 truncate">
                                            <i class="ph-fill ph-calendar-blank {{ $isExpired ? 'text-rose-400' : 'text-slate-400' }} shrink-0"></i>
                                            <span class="truncate">{{ $task->deadline->format('d M, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Statistik Pengumpulan --}}
                                <div class="mb-3.5 relative z-10">
                                    <div class="flex justify-between text-[11px] font-semibold text-slate-400 mb-1.5">
                                        <span>Total Pengumpulan</span>
                                        <span class="text-sky-400 font-bold">{{ $task->is_bulk ? $task->global_submissions_count : $task->submissions_count }} Siswa</span>
                                    </div>
                                    <div class="w-full bg-slate-900 rounded-full h-2 overflow-hidden shadow-inner border border-white/5">
                                        <div class="bg-gradient-to-r from-sky-400 to-blue-600 h-2 rounded-full" style="width: 10%"></div>
                                    </div>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="pt-3 border-t border-white/10 mt-auto flex items-center justify-between gap-2 relative z-10">
                                    {{-- Tombol Periksa (Utama) --}}
                                    <a href="{{ route('lms.assignments.submissions', $task->id) }}" class="flex-1 bg-gradient-to-r from-sky-400 to-blue-600 hover:from-sky-300 hover:to-blue-500 text-slate-950 font-bold px-3 py-2 rounded-xl text-xs transition-all shadow-md shadow-sky-500/20 flex items-center justify-center gap-1.5 active:scale-95" title="Periksa Jawaban Siswa">
                                        <i class="ph-bold ph-list-checks text-base"></i>
                                        <span>Periksa</span>
                                    </a>

                                    {{-- Tombol Preview --}}
                                    <a href="{{ route('lms.preview.player', ['subject' => $task->subject_id, 'class' => $task->class_id]) }}" target="_blank" class="w-8 h-8 shrink-0 rounded-lg bg-slate-900/80 text-slate-300 hover:text-white hover:bg-slate-800 transition-all flex items-center justify-center border border-white/10 hover:border-sky-400/40 shadow-sm active:scale-95" title="Preview Tampilan Siswa">
                                        <i class="ph-bold ph-presentation-chart text-base"></i>
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('lms.assignments.edit', $task->id) }}" class="w-8 h-8 shrink-0 rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-500/30 transition-all flex items-center justify-center shadow-sm active:scale-95" title="Edit Latihan">
                                        <i class="ph-bold ph-pencil-simple text-base"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('lms.assignments.destroy', $task->id) }}" method="POST" class="form-delete-task shrink-0 m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-delete w-8 h-8 rounded-lg bg-rose-500/20 text-rose-300 border border-rose-500/30 hover:bg-rose-500/30 transition-all flex items-center justify-center shadow-sm active:scale-95" title="Hapus Latihan">
                                            <i class="ph-bold ph-trash text-base"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10 animate-enter flex justify-center" style="animation-delay: 500ms">
                    {{ $assignments->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="animate-enter bg-gradient-to-b from-[#031d3d]/60 via-[#021124]/70 to-[#020b18] rounded-[2.5rem] border-2 border-dashed border-white/15 p-16 flex flex-col items-center justify-center text-center group hover:border-sky-400/40 transition-colors" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-slate-900 rounded-full flex items-center justify-center text-sky-400 mb-6 group-hover:bg-sky-500 group-hover:text-slate-950 transition-all duration-500 border border-white/10 shadow-sm">
                        <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                    </div>
                    <h3 class="font-black text-white text-2xl mb-2">Belum Ada Latihan</h3>
                    <p class="text-slate-400 text-sm max-w-md mx-auto font-medium leading-relaxed mb-8">
                        Anda belum membuat tugas atau latihan apapun. Mulailah dengan membuat latihan baru untuk dikerjakan siswa.
                    </p>
                    <a href="{{ route('lms.assignments.create') }}" class="px-8 py-4 bg-gradient-to-r from-sky-400 to-blue-600 text-slate-950 font-bold rounded-2xl hover:from-sky-300 hover:to-blue-500 transition-colors shadow-lg shadow-sky-500/20 transform flex items-center gap-2 active:scale-95">
                        <i class="ph-bold ph-plus text-lg"></i> Buat Latihan Pertama
                    </a>
                </div>
            @endif

        </div>
    </div>

    {{-- SCRIPT SWEETALERT --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. Tombol Hapus dengan Konfirmasi
            const deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('.form-delete-task');
                    
                    Swal.fire({
                        title: 'Hapus Latihan Ini?',
                        text: "Data nilai dan pengumpulan siswa akan ikut terhapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#f43f5e', 
                        cancelButtonColor: '#475569', 
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        background: '#021124',
                        color: '#fff',
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl',
                            title: 'text-xl font-black text-white',
                            htmlContainer: 'text-slate-400 font-medium',
                            confirmButton: 'px-6 py-3 rounded-xl text-sm font-bold shadow-sm',
                            cancelButton: 'px-6 py-3 rounded-xl text-sm font-bold hover:bg-slate-800 text-slate-300'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // 2. Notifikasi Toast Sukses
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: '#021124',
                    color: '#fff',
                    customClass: {
                        popup: 'rounded-2xl border border-emerald-500/30 bg-[#021124] text-emerald-400 shadow-md font-sans'
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>