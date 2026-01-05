<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Rekap Nilai Siswa') }}
        </h2>
    </x-slot>

    <div class="py-8 font-sans text-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative rounded-[2rem] bg-gray-900 bg-gradient-to-br from-slate-900 via-blue-900 to-blue-800 p-8 mb-8 text-white shadow-xl shadow-blue-900/30 overflow-hidden border border-white/10">
                {{-- Dekorasi Latar --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-blue-400/10 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            <span class="text-4xl"></span> Rekap Nilai
                        </h2>
                        <p class="text-blue-300 text-sm font-medium max-w-lg leading-relaxed">
                            Pantau perkembangan nilai tugas, kuis, dan ulangan harian siswa dalam satu tampilan matriks terpadu.
                        </p>
                    </div>

                    {{-- Tombol Aksi (Export/Print) --}}
                    @if($selectedClassId && $selectedSubjectId && !$assignments->isEmpty())
                        <div class="flex flex-wrap justify-center gap-3">
                            {{-- Tambahkan class 'btn-export' untuk trigger SweetAlert --}}
                            <a href="{{ route('lms.grades.export', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId]) }}" target="_blank" class="btn-export px-5 py-3 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl shadow-lg transition-all flex items-center gap-2 group">
                                <i class="ph-bold ph-microsoft-excel-logo text-xl"></i>
                                <span>Export Excel</span>
                            </a>
                            <a href="{{ route('lms.grades.print', ['class_id' => $selectedClassId, 'subject_id' => $selectedSubjectId]) }}" target="_blank" class="btn-print px-5 py-3 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl shadow-lg backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2">
                                <i class="ph-bold ph-printer text-xl"></i>
                                <span>Cetak</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- CARD FILTER --}}
            <div class="bg-white p-6 rounded-[2rem] border border-slate-100 shadow-sm mb-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-slate-50 rounded-bl-full -mr-10 -mt-10 opacity-50 pointer-events-none"></div>
                
                {{-- Tambahkan ID 'filterForm' untuk script --}}
                <form action="{{ route('lms.grades.index') }}" method="GET" class="relative z-10" id="filterForm">
                    <div class="flex flex-col md:flex-row gap-5 items-end">
                        
                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Kelas</label>
                            <div class="relative">
                                <select name="class_id" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 appearance-none" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}" {{ $selectedClassId == $c->id ? 'selected' : '' }}>
                                            {{ $c->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>

                        <div class="flex-1 w-full">
                            <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Mata Pelajaran</label>
                            <div class="relative">
                                <select name="subject_id" class="w-full rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 h-12 px-4 appearance-none" required>
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $s)
                                        <option value="{{ $s->id }}" {{ $selectedSubjectId == $s->id ? 'selected' : '' }}>
                                            {{ $s->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-500"><i class="ph-bold ph-caret-down"></i></div>
                            </div>
                        </div>

                        <div class="w-full md:w-auto">
                            <button type="submit" class="w-full md:w-auto px-8 py-3 bg-blue-900 text-white font-bold rounded-xl hover:bg-slate-900 transition-all shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 h-12">
                                <i class="ph-bold ph-magnifying-glass text-lg"></i>
                                <span>Tampilkan</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- TABEL NILAI --}}
            @if($selectedClassId && $selectedSubjectId)
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    @if($assignments->isEmpty())
                        {{-- Empty State Assignments --}}
                        <div class="p-16 text-center flex flex-col items-center">
                            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mb-6 animate-pulse">
                                <i class="ph-duotone ph-clipboard-text text-4xl text-slate-300"></i>
                            </div>
                            <h3 class="text-xl font-black text-slate-800 mb-2">Belum Ada Tugas</h3>
                            <p class="text-slate-500 text-sm max-w-sm mx-auto leading-relaxed">
                                Belum ada tugas atau kuis yang dibuat untuk kelas dan mata pelajaran ini. Silakan buat tugas terlebih dahulu.
                            </p>
                            <a href="{{ route('lms.assignments.create') }}" class="mt-6 px-6 py-2.5 bg-blue-50 text-blue-600 font-bold rounded-xl hover:bg-blue-100 transition flex items-center gap-2">
                                <i class="ph-bold ph-plus"></i> Buat Tugas
                            </a>
                        </div>
                    @else
                        {{-- Data Table --}}
                        <div class="overflow-x-auto custom-scrollbar pb-2">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-slate-50 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                                    <tr>
                                        <th class="px-4 py-4 border-b border-slate-200 sticky left-0 bg-slate-50 z-20 w-12 text-center border-r border-slate-100">No</th>
                                        <th class="px-4 py-4 border-b border-slate-200 sticky left-12 bg-slate-50 z-20 min-w-[220px] border-r border-slate-100 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)]">Nama Siswa</th>
                                        
                                        <!-- Loop Judul Tugas (Kolom) -->
                                        @foreach($assignments as $task)
                                            <th class="px-4 py-4 border-b border-slate-200 text-center min-w-[110px] group relative border-r border-slate-100 hover:bg-blue-50/50 transition-colors">
                                                <div class="flex flex-col items-center gap-1">
                                                    <span class="block truncate w-24 cursor-help font-bold text-blue-600" title="{{ $task->title }}">
                                                        {{ Str::limit($task->title, 10) }}
                                                    </span>
                                                    <div class="flex items-center gap-1">
                                                        <span class="text-[9px] text-slate-400 font-medium bg-white border border-slate-100 px-1.5 rounded">{{ $task->created_at->format('d/m') }}</span>
                                                        <span class="text-[9px] px-1.5 py-0.5 rounded text-white font-bold uppercase {{ $task->assignment_type == 'quiz' ? 'bg-purple-400' : 'bg-blue-400' }}">
                                                            {{ $task->assignment_type == 'quiz' ? 'Kuis' : 'Tugas' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </th>
                                        @endforeach

                                        <th class="px-4 py-4 border-b border-slate-200 text-center bg-blue-50/50 text-blue-800 min-w-[90px] border-r border-slate-100">Total</th>
                                        <th class="px-4 py-4 border-b border-slate-200 text-center bg-emerald-50/50 text-emerald-800 min-w-[90px]">Rata-rata</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 bg-white">
                                    @foreach($students as $index => $student)
                                        @php
                                            $totalScore = 0;
                                            $countScore = 0;
                                        @endphp
                                        <tr class="hover:bg-slate-50 transition-colors group">
                                            {{-- Kolom No (Sticky) --}}
                                            <td class="px-4 py-3 border-r border-slate-50 sticky left-0 bg-white group-hover:bg-slate-50 transition-colors text-center text-slate-400 font-medium z-10">
                                                {{ $index + 1 }}
                                            </td>
                                            
                                            {{-- Kolom Nama (Sticky) --}}
                                            <td class="px-4 py-3 border-r border-slate-100 sticky left-12 bg-white group-hover:bg-slate-50 transition-colors z-10 shadow-[4px_0_12px_-4px_rgba(0,0,0,0.05)]">
                                                <div class="flex flex-col">
                                                    <span class="font-bold text-slate-700 text-sm">{{ $student->name }}</span>
                                                    <span class="text-[10px] text-slate-400 font-mono">{{ $student->nisn }}</span>
                                                </div>
                                            </td>

                                            <!-- Loop Nilai per Tugas -->
                                            @foreach($assignments as $task)
                                                @php
                                                    $score = $gradeBook[$student->id][$task->id] ?? null;
                                                    if ($score !== null) {
                                                        $totalScore += $score;
                                                        $countScore++;
                                                    }
                                                @endphp
                                                <td class="px-4 py-3 text-center border-r border-slate-50">
                                                    @if($score !== null)
                                                        <span class="inline-flex w-10 h-8 items-center justify-center rounded-lg text-xs font-bold border 
                                                            {{ $score < 70 ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-emerald-50 text-emerald-600 border-emerald-100' }}">
                                                            {{ $score }}
                                                        </span>
                                                    @else
                                                        <span class="text-slate-300 text-lg">-</span>
                                                    @endif
                                                </td>
                                            @endforeach

                                            {{-- Kolom Total --}}
                                            <td class="px-4 py-3 text-center border-r border-slate-100 bg-blue-50/30 font-bold text-blue-700">
                                                {{ $totalScore }}
                                            </td>
                                            
                                            {{-- Kolom Rata-rata --}}
                                            @php
                                                $average = $countScore > 0 ? round($totalScore / $countScore, 1) : 0;
                                                $avgColor = $average < 70 ? 'text-rose-600' : 'text-emerald-600';
                                            @endphp
                                            <td class="px-4 py-3 text-center bg-emerald-50/30 font-black {{ $avgColor }}">
                                                {{ $average }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            @else
                {{-- Empty State Filter --}}
                <div class="text-center py-20 bg-white rounded-[2rem] border-2 border-dashed border-slate-200">
                    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300">
                        <i class="ph-duotone ph-faders text-5xl"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-800 mb-2">Silakan Pilih Filter</h3>
                    <p class="text-slate-500 max-w-sm mx-auto">Pilih <b>Kelas</b> dan <b>Mata Pelajaran</b> pada formulir di atas untuk menampilkan data nilai siswa.</p>
                </div>
            @endif

        </div>
    </div>

    {{-- SCRIPT TAMBAHAN UNTUK UX --}}
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Loading saat Filter Form dikirim
            const filterForm = document.getElementById('filterForm');
            if(filterForm) {
                filterForm.addEventListener('submit', function(e) {
                    // Validasi manual sederhana agar loading hanya muncul jika form valid
                    const selects = this.querySelectorAll('select');
                    let isValid = true;
                    selects.forEach(s => { if(s.value === '') isValid = false; });

                    if(isValid) {
                        Swal.fire({
                            title: 'Sedang Memuat Data...',
                            text: 'Mohon tunggu sebentar.',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            customClass: {
                                popup: 'rounded-[1.5rem]',
                            }
                        });
                    }
                });
            }

            // 2. Notifikasi Toast saat klik Export Excel
            const btnExport = document.querySelector('.btn-export');
            if(btnExport) {
                btnExport.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Mendownload Excel...',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'rounded-xl'
                        }
                    });
                });
            }

            // 3. Notifikasi Toast saat klik Print
            const btnPrint = document.querySelector('.btn-print');
            if(btnPrint) {
                btnPrint.addEventListener('click', function() {
                    Swal.fire({
                        icon: 'info',
                        title: 'Membuka PDF...',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'rounded-xl'
                        }
                    });
                });
            }

            // 4. Handle Pesan Session (Jika ada error/success dari controller)
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    customClass: { popup: 'rounded-[1.5rem]' }
                });
            @endif
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    customClass: { popup: 'rounded-[1.5rem]' }
                });
            @endif
        });
    </script>
    @endpush
</x-app-layout>