<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Tugas & PR') }}
        </h2>
    </x-slot>

    {{-- CUSTOM STYLES FLUENT --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        .fluent-card { box-shadow: 0 1.6px 3.6px 0 rgba(0, 0, 0, 0.132), 0 0.3px 0.9px 0 rgba(0, 0, 0, 0.108); border: 1px solid rgba(0, 0, 0, 0.05); transition: all 0.3s ease; }
        .fluent-card:hover { box-shadow: 0 6.4px 14.4px 0 rgba(0, 0, 0, 0.132), 0 1.2px 3.6px 0 rgba(0, 0, 0, 0.108); transform: translateY(-2px); }
        .fluent-modal { box-shadow: 0 25.6px 57.6px 0 rgba(0, 0, 0, 0.22), 0 4.8px 14.4px 0 rgba(0, 0, 0, 0.18); border: 1px solid rgba(0, 0, 0, 0.05); }
    </style>

    <div class="py-6 md:py-10 font-sans text-slate-800 pb-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION ELEVATE --}}
            <div class="animate-enter relative rounded-xl bg-gradient-to-br from-[#25D0FF] via-[#5295FF] to-[#FFC9B9] p-8 md:p-10 mb-10 text-[#2A3B52] shadow-[0_10px_40px_-10px_rgba(37,208,255,0.4)] overflow-hidden border border-white/40 group">
                {{-- Dekorasi Background --}}
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/30 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/40 transition-all duration-700"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-white/20 rounded-full blur-[80px] pointer-events-none group-hover:bg-white/30 transition-all duration-700"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div class="text-center md:text-left">
                        <a href="{{ route('dashboard') }}" class="group bg-white/40 hover:bg-white/60 text-[#2A3B52] px-5 py-3 rounded-xl font-bold text-sm backdrop-blur-sm border border-white/50 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-lg group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-white/40 border border-white/50 text-[#2A3B52] text-[10px] font-black uppercase tracking-widest mb-3 backdrop-blur-md shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Area Guru
                        </div>
                        <h2 class="text-3xl md:text-4xl font-black tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3 text-[#2A3B52]">
                            Manajemen Tugas
                        </h2>
                        <p class="text-[#2A3B52]/80 text-sm md:text-base font-medium max-w-lg leading-relaxed">
                            Buat tugas, kuis online, atau ulangan harian dan pantau hasil pengerjaan siswa secara real-time.
                        </p>
                    </div>
                    
                    {{-- Tombol Buat Tugas --}}
                    <a href="{{ route('lms.assignments.create') }}" class="w-full md:w-auto group bg-[#2A3B52] text-white px-7 py-4 rounded-xl font-bold text-sm shadow-md hover:bg-[#182436] transition-all duration-300 flex items-center justify-center gap-3 active:scale-95 border border-transparent">
                        <div class="bg-white/20 text-white w-8 h-8 rounded-lg flex items-center justify-center group-hover:bg-white group-hover:text-[#2A3B52] transition-colors">
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
                            $theme = ['bg' => 'bg-[#F3F9FD]', 'text' => 'text-[#5295FF]', 'border' => 'border-[#D0E7F8]', 'ring' => 'ring-[#D0E7F8]']; // Default Elevate Blue

                            if (str_contains($subjectName, 'indonesia') || str_contains($subjectName, 'inggris') || str_contains($subjectName, 'pkn')) {
                                $theme = ['bg' => 'bg-[#FDE7E9]', 'text' => 'text-[#D13438]', 'border' => 'border-[#F4C3C9]', 'ring' => 'ring-[#F4C3C9]']; // Elevate Red
                            } elseif (str_contains($subjectName, 'ipa') || str_contains($subjectName, 'biologi') || str_contains($subjectName, 'pjok')) {
                                $theme = ['bg' => 'bg-[#DFF6DD]', 'text' => 'text-[#107C10]', 'border' => 'border-[#B7DFB9]', 'ring' => 'ring-[#B7DFB9]']; // Elevate Green
                            } elseif (str_contains($subjectName, 'ips') || str_contains($subjectName, 'sejarah')) {
                                $theme = ['bg' => 'bg-[#FFEFD6]', 'text' => 'text-[#D83B01]', 'border' => 'border-[#FFD8A8]', 'ring' => 'ring-[#FFD8A8]']; // Elevate Orange
                            }
                        @endphp

                        <div class="animate-enter group relative bg-white rounded-xl p-1 shadow-sm hover:shadow-md transition-all duration-300 flex flex-col h-full hover:-translate-y-1 hover:border-transparent hover:ring-2 {{ $theme['ring'] }} fluent-card" style="animation-delay: {{ ($index + 1) * 100 }}ms">
                            
                            {{-- Inner Card --}}
                            <div class="bg-white rounded-lg p-6 h-full flex flex-col relative overflow-hidden">
                                
                                {{-- Background Dekoratif --}}
                                <div class="absolute -right-10 -top-10 w-32 h-32 rounded-full {{ $theme['bg'] }} opacity-50 group-hover:scale-150 transition-transform duration-500 pointer-events-none"></div>

                                {{-- Header: Badge & Status --}}
                                <div class="flex justify-between items-start mb-4 relative z-10">
                                    <div class="w-14 h-14 rounded-xl flex items-center justify-center text-3xl shadow-sm border {{ $theme['border'] }} {{ $theme['bg'] }} {{ $theme['text'] }} group-hover:scale-110 transition-transform duration-300">
                                        <i class="ph-duotone {{ $iconType }}"></i>
                                    </div>
                                    
                                    <div class="flex flex-col items-end gap-1">
                                        @if($isExpired)
                                            <span class="bg-slate-100 text-slate-500 border border-slate-200 px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-wider flex items-center gap-1 shadow-sm">
                                                <i class="ph-bold ph-lock-key"></i> Ditutup
                                            </span>
                                        @else
                                            <span class="bg-[#DFF6DD] text-[#107C10] border border-[#B7DFB9] px-3 py-1 rounded-md text-[10px] font-black uppercase tracking-wider flex items-center gap-1 animate-pulse shadow-sm">
                                                <i class="ph-bold ph-clock"></i> Aktif
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Judul & Mapel --}}
                                <div class="mb-4 relative z-10">
                                    <h3 class="font-bold text-lg text-[#2A3B52] group-hover:text-[#5295FF] transition-colors line-clamp-1" title="{{ $task->title }}">
                                        {{ $task->title }}
                                    </h3>
                                    <p class="text-sm font-bold text-slate-400 mt-0.5">{{ $task->subject->name }}</p>
                                </div>

                                {{-- Info Detail Grid --}}
                                <div class="grid grid-cols-2 gap-3 mb-6 relative z-10">
                                    {{-- Target Kelas --}}
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Target</p>
                                        <div class="text-xs font-bold text-[#2A3B52] flex items-center gap-1.5">
                                            <i class="ph-fill ph-users text-[#5295FF]"></i>
                                            @if($task->is_bulk)
                                                Semua Kelas {{ $task->target_grade ?? '' }} ({{ $task->total_classes }})
                                            @else
                                                {{ $task->schoolClass->name ?? 'Semua' }}
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Deadline --}}
                                    <div class="bg-slate-50 p-3 rounded-xl border border-slate-100">
                                        <p class="text-[10px] font-bold text-slate-400 uppercase mb-1">Deadline</p>
                                        <div class="text-xs font-bold {{ $isExpired ? 'text-[#D13438]' : 'text-[#2A3B52]' }} flex items-center gap-1.5">
                                            <i class="ph-fill ph-calendar-blank {{ $isExpired ? 'text-[#D13438]' : 'text-slate-400' }}"></i>
                                            {{ $task->deadline->format('d M, H:i') }}
                                        </div>
                                    </div>
                                </div>

                                {{-- Statistik Pengumpulan --}}
                                <div class="mb-6 relative z-10">
                                    <div class="flex justify-between text-xs font-bold text-slate-500 mb-2">
                                        <span>Total Pengumpulan</span>
                                        <span class="text-[#5295FF]">{{ $task->is_bulk ? $task->global_submissions_count : $task->submissions_count }} Siswa</span>
                                    </div>
                                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden shadow-inner">
                                        {{-- Progress bar --}}
                                        <div class="bg-[#5295FF] h-2 rounded-full" style="width: 10%"></div>
                                    </div>
                                </div>

                                {{-- Footer Actions --}}
                                <div class="pt-4 border-t border-slate-100 mt-auto flex items-center justify-between gap-2 relative z-10">
                                    
                                    {{-- Tombol Periksa (Utama) --}}
                                    <a href="{{ route('lms.assignments.submissions', $task->id) }}" class="flex-1 bg-[#2A3B52] hover:bg-[#182436] text-white px-4 py-2.5 rounded-lg font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 group/btn border border-transparent active:scale-95">
                                        <i class="ph-bold ph-list-checks text-lg text-[#5295FF]"></i>
                                        <span>Periksa</span>
                                    </a>

                                    {{-- Tombol Edit (Kuning/Orange) --}}
                                    <a href="{{ route('lms.assignments.edit', $task->id) }}" class="w-10 h-10 rounded-lg bg-white border border-[#FFD8A8] text-[#D83B01] hover:bg-[#FFEFD6] transition-all flex items-center justify-center shadow-sm active:scale-95" title="Edit Tugas">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </a>

                                    {{-- Tombol Hapus (Merah) --}}
                                    <form action="{{ route('lms.assignments.destroy', $task->id) }}" method="POST" class="form-delete-task">
                                        @csrf @method('DELETE')
                                        <button type="button" class="btn-delete w-10 h-10 rounded-lg bg-white border border-[#F4C3C9] text-[#D13438] hover:bg-[#FDE7E9] transition-all flex items-center justify-center shadow-sm active:scale-95" title="Hapus Tugas">
                                            <i class="ph-bold ph-trash text-lg"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-10 animate-enter px-4" style="animation-delay: 500ms">
                    {{ $assignments->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="animate-enter bg-white rounded-xl border-2 border-dashed border-slate-200 p-12 flex flex-col items-center justify-center text-center group hover:border-[#5295FF] transition-colors fluent-card" style="animation-delay: 200ms">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6 group-hover:bg-[#F3F9FD] group-hover:text-[#5295FF] transition-all duration-500 border border-slate-100 group-hover:border-[#D0E7F8]">
                        <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                    </div>
                    <h3 class="font-black text-[#2A3B52] text-xl mb-2">Belum Ada Tugas</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed mb-8">
                        Anda belum membuat tugas apapun. Mulailah dengan membuat tugas baru untuk dikerjakan siswa.
                    </p>
                    <a href="{{ route('lms.assignments.create') }}" class="px-8 py-3.5 bg-[#2A3B52] text-white font-bold rounded-xl hover:bg-[#182436] transition shadow-md hover:-translate-y-1 transform flex items-center gap-2 active:scale-95 border border-transparent">
                        <i class="ph-bold ph-plus"></i> Buat Tugas Pertama
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
                            popup: 'rounded-xl fluent-modal font-sans border-0',
                            title: 'text-xl font-bold text-[#2A3B52]',
                            htmlContainer: 'text-slate-500',
                            confirmButton: 'px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm',
                            cancelButton: 'px-6 py-2.5 rounded-xl text-sm font-bold hover:bg-slate-100 text-slate-600'
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
                        popup: 'rounded-xl border border-[#B7DFB9] bg-[#DFF6DD] text-[#107C10] shadow-md font-sans'
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>