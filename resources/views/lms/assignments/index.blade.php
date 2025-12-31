<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Tugas & PR') }}
        </h2>
    </x-slot>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl">📝</span> Manajemen Tugas
                        </h2>
                        <p class="text-blue-300 text-sm font-medium max-w-lg leading-relaxed">
                            Buat tugas, kuis online, atau ulangan harian dan pantau hasil pengerjaan siswa secara real-time.
                        </p>
                    </div>
                    
                    {{-- Tombol Buat Tugas --}}
                    <a href="{{ route('lms.assignments.create') }}" class="group bg-white text-blue-900 px-6 py-3.5 rounded-2xl font-bold text-sm shadow-lg hover:bg-blue-50 hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <i class="ph-bold ph-plus-circle text-xl group-hover:rotate-90 transition-transform duration-300"></i>
                        <span>Buat Tugas Baru</span>
                    </a>
                </div>
            </div>

            {{-- ALERT SESSION (Dihapus/Dikomentari karena diganti SweetAlert Toast di bawah) --}}
            {{-- 
            @if(session('success'))
               ... kode lama ...
            @endif 
            --}}

            {{-- LIST TUGAS (GRID SYSTEM) --}}
            @if($assignments->count() > 0)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($assignments as $task)
                        @php
                            // Tentukan Icon & Warna Berdasarkan Tipe
                            $icon = 'ph-file-text';
                            $color = 'blue';
                            if($task->assignment_type == 'quiz') { $icon = 'ph-brain'; $color = 'purple'; }
                            if($task->assignment_type == 'link') { $icon = 'ph-link'; $color = 'sky'; }
                            
                            // Cek Status Deadline
                            $isExpired = now() > $task->deadline;
                        @endphp

                        <div class="group relative bg-white border border-slate-100 rounded-[2rem] p-6 hover:shadow-xl hover:shadow-blue-900/5 hover:border-blue-200 transition-all duration-300 flex flex-col h-full">
                            
                            {{-- Badge Deadline di Pojok --}}
                            <div class="absolute top-5 right-5 z-10">
                                @if($isExpired)
                                    <span class="bg-slate-100 text-slate-500 border border-slate-200 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1">
                                        <i class="ph-bold ph-lock-key"></i> Ditutup
                                    </span>
                                @else
                                    <span class="bg-rose-50 text-rose-600 border border-rose-100 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider flex items-center gap-1 animate-pulse">
                                        <i class="ph-bold ph-clock"></i> Aktif
                                    </span>
                                @endif
                            </div>

                            {{-- Header Card --}}
                            <div class="flex items-start gap-4 mb-4">
                                <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shrink-0 transition-colors duration-300 bg-{{ $color }}-50 text-{{ $color }}-600 group-hover:bg-{{ $color }}-600 group-hover:text-white">
                                    <i class="ph-duotone {{ $icon }}"></i>
                                </div>
                                <div class="pr-20"> {{-- Padding right agar tidak nabrak badge --}}
                                    <h3 class="font-bold text-lg text-slate-800 group-hover:text-blue-700 transition-colors line-clamp-1">
                                        {{ $task->title }}
                                    </h3>
                                    <p class="text-sm text-slate-500 font-medium mt-0.5">{{ $task->subject->name }}</p>
                                </div>
                            </div>

                            {{-- Informasi Detail --}}
                            <div class="flex flex-wrap gap-2 mb-6">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-50 text-slate-600 border border-slate-100">
                                    <i class="ph-bold ph-users-three"></i> {{ $task->schoolClass->name ?? 'Semua Kelas' }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-50 text-slate-600 border border-slate-100">
                                    <i class="ph-bold ph-calendar-blank"></i> {{ $task->deadline->format('d M, H:i') }}
                                </span>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold bg-blue-50 text-blue-600 border border-blue-100">
                                    <i class="ph-bold ph-paper-plane-tilt"></i> {{ $task->submissions_count }} Kumpul
                                </span>
                            </div>

                            {{-- Footer Actions --}}
                            <div class="pt-4 border-t border-slate-50 mt-auto flex items-center justify-between gap-3">
                                <a href="{{ route('lms.assignments.submissions', $task->id) }}" class="flex-1 bg-slate-800 hover:bg-blue-600 text-white px-4 py-2.5 rounded-xl font-bold text-sm transition-all shadow-md flex items-center justify-center gap-2 group/btn">
                                    <i class="ph-bold ph-list-checks text-lg"></i>
                                    <span>Periksa / Nilai</span>
                                </a>

                                {{-- FORM HAPUS DENGAN SWEETALERT --}}
                                <form action="{{ route('lms.assignments.destroy', $task->id) }}" method="POST" class="form-delete-task">
                                    @csrf @method('DELETE')
                                    {{-- Hapus onsubmit, ganti type button, tambah class btn-delete --}}
                                    <button type="button" class="btn-delete w-11 h-11 rounded-xl bg-white border border-slate-200 text-rose-500 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-600 transition-all flex items-center justify-center shadow-sm" title="Hapus Tugas">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $assignments->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 flex flex-col items-center justify-center text-center">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center text-slate-300 mb-6 animate-pulse">
                        <i class="ph-duotone ph-clipboard-text text-5xl"></i>
                    </div>
                    <h3 class="font-black text-slate-800 text-xl mb-2">Belum Ada Tugas</h3>
                    <p class="text-slate-500 text-sm max-w-md mx-auto leading-relaxed mb-8">
                        Anda belum membuat tugas apapun. Mulailah dengan membuat tugas baru untuk dikerjakan siswa.
                    </p>
                    <a href="{{ route('lms.assignments.create') }}" class="px-8 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 hover:-translate-y-1 transform flex items-center gap-2">
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
                        confirmButtonColor: '#e11d48', // Rose-600
                        cancelButtonColor: '#64748b', // Slate-500
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        customClass: {
                            popup: 'rounded-[2rem]',
                            confirmButton: 'rounded-xl px-4 py-2 font-bold',
                            cancelButton: 'rounded-xl px-4 py-2 font-bold'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // 2. Notifikasi Toast Sukses (Pengganti Flash Message)
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
                        popup: 'rounded-xl border border-emerald-100 bg-white shadow-lg'
                    }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>