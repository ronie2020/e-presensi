<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-elevate-dark leading-tight">
            {{ __('Tugas & PR') }}
        </h2>
    </x-slot>

    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-dark bg-elevate-surface min-h-screen relative overflow-hidden pb-20">
        
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION ELEVATE --}}
            <div class="animate-enter relative rounded-[2rem] bg-gradient-to-r from-elevate-accent via-elevate-peach-light to-elevate-peach p-8 md:p-10 mb-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                {{-- Dekorasi Background --}}
                <div class="absolute -top-10 -left-10 w-56 h-56 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/40 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-xl"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <a href="{{ route('dashboard') }}" class="group/btn bg-white/60 hover:bg-white text-elevate-dark px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-md border border-white/60 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0 active:scale-95">
                            <i class="ph-bold ph-arrow-left text-lg group-hover/btn:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/60 border border-white/50 text-elevate-dark text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-md shadow-sm">
                            <i class="ph-bold ph-chalkboard-teacher"></i> Area Guru
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3 text-elevate-dark">
                            Manajemen Tugas
                        </h2>
                        <p class="text-elevate-dark/80 text-sm font-semibold max-w-lg leading-relaxed">
                            Buat tugas, kuis online, atau ulangan harian dan pantau hasil pengerjaan siswa secara real-time.
                        </p>
                    </div>
                    
                    {{-- Tombol Buat Tugas --}}
                    <a href="{{ route('lms.assignments.create') }}" class="w-full md:w-auto group/add bg-elevate-dark text-white px-7 py-4 rounded-2xl font-bold text-sm shadow-lg shadow-elevate-dark/30 hover:bg-elevate-primary transition-all duration-300 flex items-center justify-center gap-3 active:scale-95 border border-transparent">
                        <div class="bg-white/20 text-white w-8 h-8 rounded-xl flex items-center justify-center group-hover/add:bg-white group-hover/add:text-elevate-primary transition-colors">
                            <i class="ph-bold ph-plus"></i>
                        </div>
                        <span>Buat Tugas Baru</span>
                    </a>
                </div>
            </div>

            {{-- LIST TUGAS --}}
            @if($assignments->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($assignments as $index => $task)
                        @php
                            // Tentukan Icon & Warna Dasar Berdasarkan Tipe
                            $iconType = 'ph-file-text';
                            $labelType = 'Tugas File';
                            if($task->assignment_type == 'quiz') { $iconType = 'ph-brain'; $labelType = 'Kuis Online'; }
                            if($task->assignment_type == 'link') { $iconType = 'ph-link'; $labelType = 'Tugas Link'; }
                            
                            // Cek Status Deadline
                            $isExpired = now() > $task->deadline;

                            // LOGIKA TEMA WARNA BISA DISESUAIKAN (Pakai Standar Elevate Semantic)
                            $subjectName = strtolower($task->subject->name ?? 'umum');
                            $theme = ['bg' => 'bg-elevate-soft', 'text' => 'text-elevate-primary', 'border' => 'border-slate-200', 'ring' => 'hover:border-elevate-accent/50']; // Default Elevate Blue

                            if (str_contains($subjectName, 'indonesia') || str_contains($subjectName, 'inggris') || str_contains($subjectName, 'pkn')) {
                                $theme = ['bg' => 'bg-[#FDE7E9]', 'text' => 'text-[#D13438]', 'border' => 'border-[#F4C3C9]', 'ring' => 'hover:border-[#F4C3C9]']; // Elevate Red
                            } elseif (str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi') || str_contains($subjectName, 'pjok')) {
                                $theme = ['bg' => 'bg-[#DFF6DD]', 'text' => 'text-[#107C10]', 'border' => 'border-[#B7DFB9]', 'ring' => 'hover:border-[#B7DFB9]']; // Elevate Green
                            } elseif (str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah')) {
                                $theme = ['bg' => 'bg-[#FFEFD6]', 'text' => 'text-[#D83B01]', 'border' => 'border-[#FFD8A8]', 'ring' => 'hover:border-[#FFD8A8]']; // Elevate Orange
                            }
                        @endphp

                        <div class="animate-enter group relative bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 hover:shadow-elevate-accent/10 transition-all duration-300 flex flex-col h-full hover:-translate-y-1 {{ $theme['ring'] }} overflow-hidden" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                            
                            {{-- Inner Card --}}
                            <div class="bg-white p-6 md:p-8 h-full flex flex-col relative overflow-hidden">
                                
                                {{-- Background Dekoratif --}}
                                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full {{ $theme['bg'] }} opacity-50 group-hover:scale-150 transition-transform duration-700 pointer-events-none blur-xl"></div>

                                {{-- Header: Badge & Status --}}
                                <div class="flex justify-between items-start mb-5 relative z-10">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-3xl shadow-sm border {{ $theme['border'] }} {{ $theme['bg'] }} {{ $theme['text'] }} group-hover:scale-110 transition-transform duration-300">
                                        <i class="ph-duotone {{ $iconType }}"></i>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-1">
                                        @if($isExpired)
                                            <span class="bg-slate-100 text-slate-500 border border-slate-200 px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-sm">
                                                <i class="ph-bold ph-lock-key"></i> Ditutup
                                            </span>
                                        @else
                                            <span class="bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 animate-pulse shadow-sm">
                                                <i class="ph-bold ph-clock"></i> Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Judul & Mapel --}}
                                <div class="mb-5 relative z-10">
                                    <h3 class="font-black text-xl text-elevate-dark group-hover:text-elevate-primary transition-colors line-clamp-1" title="{{ $task->title }}">
                                        {{ $task->title }}
                                    </h3>
                                    <p class="text-sm font-bold text-slate-400 mt-1">{{ $task->subject->name }}</p>
                                </div>

                                {{-- Info Detail Grid --}}
                                <div class="grid grid-cols-2 gap-4 mb-6 relative z-10">
                                    {{-- Target Kelas --}}
                                    <div class="bg-elevate-soft/50 p-4 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1.5">Target</p>
                                        <div class="text-xs font-black text-elevate-dark flex items-center gap-1.5">
                                            <i class="ph-fill ph-users text-elevate-primary"></i>
                                            @if($task->is_bulk)
                                                Semua Kelas {{ $task->target_grade ?? '' }} ({{ $task->total_classes }})
                                            @else
                                                {{ $task->schoolClass->name ?? 'Semua' }}
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Deadline --}}
                                    <div class="bg-elevate-soft/50 p-4 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-elevate-primary uppercase tracking-widest mb-1.5">Deadline</p>
                                        <div class="text-xs font-black {{ $isExpired ? 'text-[#D13438]' : 'text-elevate-dark' }} flex items-center gap-1.5">
                                            <i class="ph-fill ph-calendar-blank {{ $isExpired ? 'text-[#D13438]' : 'text-slate-400' }}"></i>
                                            {{ $task->deadline->format('d M, H:i') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Statistik Pengumpulan --}}
                                <div class="mb-6 relative z-10">
                                    <div class="flex justify-between text-xs font-bold text-slate-500 mb-2">
                                        <span>Total Pengumpulan</span>
                                        <span class="text-elevate-primary">{{ $task->is_bulk ? $task->global_submissions_count : $task->submissions_count }} Siswa</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden shadow-inner">
                                        {{-- Progress bar --}}
                                        <div class="bg-elevate-primary h-2.5 rounded-full" style="width: 10%"></div>
                                    </div>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="pt-5 border-t border-slate-100 mt-auto flex items-center justify-between gap-3 relative z-10">
                                    
                                    {{-- Tombol Periksa (Utama) --}}
                                    <a href="{{ route('lms.assignments.submissions', $task->id) }}" class="flex-1 bg-elevate-dark hover:bg-elevate-primary text-white px-5 py-3 rounded-2xl font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 group/btn border border-transparent active:scale-95">
                                        <i class="ph-bold ph-list-checks text-lg text-elevate-accent"></i>
                                        <span>Periksa Jawaban</span>
                                    </a>

                                    {{-- Tombol Edit (Kuning/Orange) --}}
                                    <a href="{{ route('lms.assignments.edit', $task->id) }}" class="w-12 h-12 rounded-2xl bg-white border border-[#FFD8A8] text-[#D83B01] hover:bg-[#FFEFD6] transition-all flex items-center justify-center shadow-sm active:scale-95" title="Edit Tugas">
                                        <i class="ph-bold ph-pencil-simple text-xl"></i>
                                    </a>

                                    {{-- Tombol Hapus (Merah) --}}
                                    <form action="{{ route('lms.assignments.destroy', $task->id) }}" method="POST" class="form-delete-task">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-delete w-12 h-12 rounded-2xl bg-white border border-[#F4C3C9] text-[#D13438] hover:bg-[#FDE7E9] transition-all flex items-center justify-center shadow-sm active:scale-95" title="Hapus Tugas">
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
                <div class="animate-enter bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-16 flex flex-col items-center justify-center text-center group hover:border-elevate-primary transition-colors" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-elevate-soft rounded-full flex items-center justify-center text-elevate-primary mb-6 group-hover:bg-elevate-primary group-hover:text-white transition-all duration-500 border border-slate-100 shadow-sm">
                        <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                    </div>
                    <h3 class="font-black text-elevate-dark text-2xl mb-2">Belum Ada Tugas</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto font-medium leading-relaxed mb-8">
                        Anda belum membuat tugas apapun. Mulailah dengan membuat tugas baru untuk dikerjakan siswa.
                    </p>
                    <a href="{{ route('lms.assignments.create') }}" class="px-8 py-4 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-colors shadow-lg shadow-elevate-dark/30 transform flex items-center gap-2 active:scale-95 border border-transparent">
                        <i class="ph-bold ph-plus text-lg"></i> Buat Tugas Pertama
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
                        title: 'Hapus Tugas Ini?',
                        text: "Data nilai dan pengumpulan siswa akan ikut terhapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#D13438', 
                        cancelButtonColor: '#94a3b8', 
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                            title: 'text-xl font-black text-elevate-dark',
                            htmlContainer: 'text-slate-500 font-medium',
                            confirmButton: 'px-6 py-3 rounded-xl text-sm font-bold shadow-sm',
                            cancelButton: 'px-6 py-3 rounded-xl text-sm font-bold hover:bg-slate-100 text-slate-600'
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
                    customClass: {
                        popup: 'rounded-2xl border border-[#B7DFB9] bg-[#DFF6DD] text-[#107C10] shadow-md font-sans'
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>