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
                        @endphp

                        <div class="animate-enter group relative bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-white/10 hover:border-sky-400/50 backdrop-blur-xl transition-all duration-300 flex flex-col h-full hover:-translate-y-1 overflow-hidden" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                            
                            {{-- Inner Card --}}
                            <div class="p-6 md:p-8 h-full flex flex-col relative overflow-hidden">
                                
                                {{-- Background Dekoratif --}}
                                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full bg-sky-500/10 opacity-30 group-hover:scale-150 transition-transform duration-700 pointer-events-none blur-xl"></div>

                                {{-- Header: Badge & Status --}}
                                <div class="flex justify-between items-start mb-5 relative z-10">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm border border-white/10 bg-slate-900/80 text-sky-400 group-hover:scale-110 transition-transform duration-300">
                                        <i class="ph-duotone {{ $iconType }}"></i>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-1">
                                        @if($isExpired)
                                            <span class="bg-slate-800 text-slate-400 border border-white/10 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm">
                                                <i class="ph-bold ph-lock-key"></i> Ditutup
                                            </span>
                                        @else
                                            <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 animate-pulse shadow-sm">
                                                <i class="ph-bold ph-clock"></i> Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Judul & Mapel --}}
                                <div class="mb-5 relative z-10">
                                    <h3 class="font-black text-xl text-white group-hover:text-sky-300 transition-colors line-clamp-1" title="{{ $task->title }}">
                                        {{ $task->title }}
                                    </h3>
                                    <p class="text-sm font-bold text-slate-400 mt-1">{{ $task->subject->name }}</p>
                                </div>

                                {{-- Info Detail Grid --}}
                                <div class="grid grid-cols-2 gap-4 mb-6 relative z-10">
                                    {{-- Target Kelas --}}
                                    <div class="bg-slate-900/60 p-4 rounded-xl border border-white/10">
                                        <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-1.5">Target</p>
                                        <div class="text-xs font-black text-slate-200 flex items-center gap-1.5">
                                            <i class="ph-fill ph-users text-sky-400"></i>
                                            @if($task->is_bulk)
                                                Semua Kelas {{ $task->target_grade ?? '' }} ({{ $task->total_classes }})
                                            @else
                                                {{ $task->schoolClass->name ?? 'Semua' }}
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Deadline --}}
                                    <div class="bg-slate-900/60 p-4 rounded-xl border border-white/10">
                                        <p class="text-[10px] font-bold text-sky-400 uppercase tracking-widest mb-1.5">Deadline</p>
                                        <div class="text-xs font-black {{ $isExpired ? 'text-rose-400' : 'text-slate-200' }} flex items-center gap-1.5">
                                            <i class="ph-fill ph-calendar-blank {{ $isExpired ? 'text-rose-400' : 'text-slate-400' }}"></i>
                                            {{ $task->deadline->format('d M, H:i') }}
                                        </div>
                                    </div>
                                </div>

                               {{-- Statistik Pengumpulan --}}
                                <div class="mb-6 relative z-10">
                                    <div class="flex justify-between text-xs font-bold text-slate-400 mb-2">
                                        <span>Total Pengumpulan</span>
                                        <span class="text-sky-400">{{ $task->is_bulk ? $task->global_submissions_count : $task->submissions_count }} Siswa</span>
                                    </div>
                                    <div class="w-full bg-slate-900 rounded-full h-2.5 overflow-hidden shadow-inner border border-white/5">
                                        <div class="bg-gradient-to-r from-sky-400 to-blue-600 h-2.5 rounded-full" style="width: 10%"></div>
                                    </div>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="pt-5 border-t border-white/10 mt-auto flex flex-wrap items-center justify-between gap-2 relative z-10">
                                    {{-- Tombol Periksa (Utama) --}}
                                    <a href="{{ route('lms.assignments.submissions', $task->id) }}" class="flex-1 bg-gradient-to-r from-sky-400 to-blue-600 hover:from-sky-300 hover:to-blue-500 text-slate-950 font-bold px-3 py-3 rounded-2xl text-sm transition-all shadow-md shadow-sky-500/20 flex items-center justify-center gap-2 active:scale-95" title="Periksa Jawaban Siswa">
                                        <i class="ph-bold ph-list-checks text-lg"></i>
                                        <span class="hidden sm:inline">Periksa</span>
                                    </a>

                                    {{-- Tombol Preview --}}
                                    <a href="{{ route('lms.preview.player', ['subject' => $task->subject_id, 'class' => $task->class_id]) }}" target="_blank" class="w-12 h-12 shrink-0 rounded-2xl bg-slate-900/80 text-slate-300 hover:text-white hover:bg-slate-800 transition-all flex items-center justify-center border border-white/10 hover:border-sky-400/40 shadow-sm active:scale-95" title="Preview Tampilan Siswa">
                                        <i class="ph-bold ph-presentation-chart text-xl"></i>
                                    </a>

                                    {{-- Tombol Edit --}}
                                    <a href="{{ route('lms.assignments.edit', $task->id) }}" class="w-12 h-12 shrink-0 rounded-2xl bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-500/30 transition-all flex items-center justify-center shadow-sm active:scale-95" title="Edit Latihan">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </a>

                                    {{-- Tombol Hapus --}}
                                    <form action="{{ route('lms.assignments.destroy', $task->id) }}" method="POST" class="form-delete-task shrink-0 m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-delete w-12 h-12 rounded-2xl bg-rose-500/20 text-rose-300 border border-rose-500/30 hover:bg-rose-500/30 transition-all flex items-center justify-center shadow-sm active:scale-95" title="Hapus Latihan">
                                            <i class="ph-bold ph-trash text-xl"></i>
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