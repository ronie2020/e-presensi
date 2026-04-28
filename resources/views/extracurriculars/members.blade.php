<x-app-layout>
    {{-- Load Library Tambahan --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-10 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60 print:hidden">
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-8">
                    
                    {{-- Left Text --}}
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-2 mb-2">
                             <a href="{{ route('extracurriculars.index') }}" class="text-xs font-bold text-elevate-primary hover:text-elevate-dark transition flex items-center gap-1 bg-white/60 px-3 py-1 rounded-full border border-white backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-arrow-left"></i> Kembali
                            </a>
                            <span class="text-[10px] font-bold text-elevate-dark/70 uppercase tracking-wider bg-white/50 px-3 py-1 rounded-full border border-white/60 backdrop-blur-sm shadow-sm">Modul Kesiswaan</span>
                        </div>
                        <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Peserta Ekstrakurikuler
                        </h1>
                        <p class="text-elevate-dark/80 text-sm md:text-base font-medium leading-relaxed max-w-lg">
                            Kelola data keanggotaan siswa. Tambahkan anggota baru, pantau partisipasi, dan cetak daftar hadir per kegiatan.
                        </p>

                        {{-- Action Button in Hero --}}
                        @if($selectedEkskulId)
                            <div class="mt-8 flex gap-3">
                                <button onclick="window.print()" class="group bg-white text-elevate-dark px-5 py-3 rounded-2xl font-bold text-sm transition-all hover:bg-slate-50 flex items-center gap-2 shadow-lg shadow-elevate-dark/5 border border-white active:scale-95">
                                    <div class="w-7 h-7 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <i class="ph-bold ph-printer text-sm"></i>
                                    </div>
                                    <span>Cetak Absensi</span>
                                </button>
                            </div>
                        @endif
                    </div>
                    
                    {{-- Right Stats Cards --}}
                    <div class="flex flex-row md:flex-col lg:flex-row gap-4 w-full md:w-auto">
                        @if($selectedEkskulId)
                            <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white flex-1 md:flex-none min-w-[160px] text-center md:text-left shadow-sm">
                                <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                    <i class="ph-duotone ph-user-check text-xl"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Anggota Aktif</span>
                                </div>
                                <span class="block text-4xl font-black text-elevate-dark tracking-tight">{{ $members->total() }}</span>
                                <span class="text-[10px] text-elevate-dark/70 font-bold block mt-1 truncate max-w-[140px]">{{ $extracurriculars->find($selectedEkskulId)->name }}</span>
                            </div>
                        @else
                            <div class="bg-white/60 backdrop-blur-md px-6 py-5 rounded-[2rem] border border-white flex-1 md:flex-none min-w-[160px] text-center md:text-left shadow-sm">
                                <div class="flex items-center justify-center md:justify-start gap-2 mb-1 text-elevate-primary">
                                    <i class="ph-duotone ph-users text-xl"></i>
                                    <span class="text-[10px] font-bold uppercase tracking-wider">Total Partisipan</span>
                                </div>
                                @php
                                    $totalAll = $extracurriculars->sum('members_count');
                                @endphp
                                <span class="block text-4xl font-black text-elevate-dark tracking-tight">{{ $totalAll }}</span>
                                <span class="text-[10px] text-elevate-dark/70 font-bold block mt-1">Semua Kegiatan</span>
                            </div>
                        @endif
                    </div>

                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI (FILTER) --}}
                <div class="lg:col-span-1 space-y-6 print:hidden">
                    <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                        <div class="p-6 bg-slate-50/50 border-b border-slate-50">
                            <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                                <i class="ph-fill ph-funnel text-elevate-primary"></i> Pilih Kegiatan
                            </h3>
                            <p class="text-xs text-slate-400 font-bold mt-1">Pilih ekskul untuk melihat anggota.</p>
                        </div>
                        <div class="p-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <form method="GET" action="{{ route('extracurriculars.members') }}">
                                <div class="space-y-2">
                                    @foreach($extracurriculars as $ekskul)
                                        <button type="submit" name="ekskul_id" value="{{ $ekskul->id }}" 
                                            class="w-full flex items-center justify-between p-3 rounded-2xl transition-all group {{ $selectedEkskulId == $ekskul->id ? 'bg-elevate-dark text-white shadow-lg shadow-elevate-dark/20' : 'bg-white hover:bg-slate-50 text-slate-600 border border-slate-100 hover:border-elevate-accent/30' }}">
                                            <div class="flex items-center gap-3 text-left overflow-hidden">
                                                <div class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center text-lg {{ $selectedEkskulId == $ekskul->id ? 'bg-white/20 text-white' : 'bg-elevate-accent/10 text-elevate-primary group-hover:bg-elevate-primary group-hover:text-white transition-colors' }}">
                                                    <i class="{{ $ekskul->icon && !Str::startsWith($ekskul->icon, 'storage') ? $ekskul->icon : 'ph-fill ph-star' }}"></i>
                                                </div>
                                                <span class="font-bold text-sm truncate pr-2">{{ $ekskul->name }}</span>
                                            </div>
                                            <span class="text-[10px] font-black px-2.5 py-1 rounded-lg shrink-0 {{ $selectedEkskulId == $ekskul->id ? 'bg-white text-elevate-dark' : 'bg-slate-100 text-slate-500 group-hover:bg-elevate-accent/20 group-hover:text-elevate-primary transition-colors' }}">
                                                {{ $ekskul->members_count }}
                                            </span>
                                        </button>
                                    @endforeach
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN (DATA) --}}
                <div class="lg:col-span-2 space-y-6">
                    @if($selectedEkskulId)
                        <!-- Form Tambah Anggota -->
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 p-8 print:hidden relative overflow-hidden group hover:border-elevate-accent/30 transition-colors">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-accent to-elevate-primary"></div>
                            
                            <div class="flex items-center gap-4 mb-6 pt-2">
                                <div class="w-12 h-12 bg-elevate-accent/10 text-elevate-primary rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-elevate-accent/20">
                                    <i class="ph-duotone ph-user-plus"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-elevate-dark leading-none">Tambah Anggota</h3>
                                    <p class="text-xs text-slate-400 font-bold mt-1 uppercase tracking-wider">Input Siswa Baru</p>
                                </div>
                            </div>
                            
                            <form action="{{ route('extracurriculars.members.store') }}" method="POST" class="flex flex-col gap-5">
                                @csrf
                                <input type="hidden" name="extracurricular_id" value="{{ $selectedEkskulId }}">
                                
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Langkah 1: Pilih Kelas</label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                            <i class="ph-bold ph-chalkboard-teacher"></i>
                                        </div>
                                        <select id="filter-class" class="w-full pl-11 rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-elevate-primary focus:ring-elevate-primary text-sm font-bold text-elevate-dark py-3.5 transition-all appearance-none cursor-pointer">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                    </div>
                                </div>

                                <div class="relative w-full">
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Langkah 2: Pilih Siswa</label>
                                    <select id="select-students" name="student_ids[]" multiple placeholder="Pilih kelas terlebih dahulu..." autocomplete="off" disabled class="rounded-2xl">
                                    </select>
                                    <p class="text-[10px] text-slate-400 mt-2 font-bold flex items-center gap-1">
                                        <i class="ph-fill ph-info text-elevate-primary"></i> Hanya siswa yang BELUM masuk ekskul ini yang akan muncul.
                                    </p>
                                </div>
                                
                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-elevate-dark text-white font-bold rounded-2xl hover:bg-elevate-primary transition-all shadow-lg shadow-elevate-dark/20 text-sm flex items-center justify-center gap-2 transform active:scale-95 group/btn">
                                        <i class="ph-bold ph-plus-circle text-lg group-hover/btn:rotate-90 transition-transform"></i> Simpan Anggota
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tabel Anggota -->
                        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden print:shadow-none print:border-none print:rounded-none">
                            <div class="p-6 border-b border-slate-50 bg-slate-50/30 flex justify-between items-center print:border-b-2 print:border-black print:bg-white">
                                <div>
                                    <h3 class="font-black text-elevate-dark text-lg flex items-center gap-2">
                                        <i class="ph-fill ph-users text-elevate-primary print:hidden"></i> Daftar Anggota Aktif
                                    </h3>
                                    <p class="hidden print:block text-sm font-bold text-slate-700 mt-1 uppercase tracking-wide">
                                        Kegiatan: {{ $extracurriculars->find($selectedEkskulId)->name }}
                                    </p>
                                    @if($members->total() > 0)
                                        <p class="text-xs text-slate-400 font-bold mt-1 print:hidden">
                                            Menampilkan {{ $members->firstItem() }}-{{ $members->lastItem() }} dari {{ $members->total() }} siswa
                                        </p>
                                    @endif
                                </div>
                                <span class="bg-white border border-slate-200 text-xs font-black px-3 py-1.5 rounded-xl text-slate-600 print:hidden shadow-sm">
                                    Total: {{ $members->total() }}
                                </span>
                            </div>
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse">
                                    <thead class="bg-slate-50/50 text-xs font-bold text-slate-400 uppercase tracking-wider print:bg-white print:text-black print:border-b-2 print:border-black">
                                        <tr>
                                            <th class="px-6 py-4 print:py-2 w-10">No</th>
                                            <th class="px-6 py-4 print:py-2">Identitas Siswa</th>
                                            <th class="px-6 py-4 print:py-2 text-center">Kelas</th>
                                            <th class="hidden print:table-cell px-6 py-4 border-l border-black text-center w-40">Paraf</th>
                                            <th class="px-6 py-4 text-right print:hidden">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-50 print:divide-slate-300">
                                        @forelse($members as $index => $member)
                                            <tr class="hover:bg-slate-50/80 transition-colors group">
                                                <td class="px-6 py-4 print:py-2 text-xs font-bold text-slate-400">
                                                    {{ $members->firstItem() + $index }}
                                                </td>
                                                <td class="px-6 py-4 print:py-2">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-elevate-primary font-black text-sm shadow-sm print:hidden group-hover:border-elevate-primary/30 transition-colors">
                                                            {{ substr($member->student->name, 0, 2) }}
                                                        </div>
                                                        <div>
                                                            <span class="font-bold text-elevate-dark text-sm block group-hover:text-elevate-primary transition-colors">{{ $member->student->name }}</span>
                                                            <span class="text-[10px] text-slate-400 font-mono font-bold bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 print:hidden">{{ $member->student->nis }}</span>
                                                            <span class="hidden print:inline text-xs">({{ $member->student->nis }})</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 print:py-2 text-center">
                                                    <span class="inline-flex px-3 py-1 bg-slate-50 border border-slate-100 rounded-xl text-xs font-bold text-slate-600 print:border-none print:bg-transparent print:p-0">
                                                        {{ $member->student->schoolClass->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="hidden print:table-cell border-l border-slate-300"></td>
                                                <td class="px-6 py-4 text-right print:hidden">
                                                    <form action="{{ route('extracurriculars.members.destroy', $member->id) }}" method="POST" class="delete-form inline-block">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn-delete w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all shadow-sm" title="Keluarkan">
                                                            <i class="ph-bold ph-sign-out text-lg"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-16 text-center">
                                                    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 border border-slate-100">
                                                        <i class="ph-duotone ph-users-three text-4xl"></i>
                                                    </div>
                                                    <p class="text-sm font-bold text-slate-500">Belum ada anggota terdaftar.</p>
                                                    <p class="text-xs text-slate-400 mt-1">Gunakan formulir di atas untuk menambahkan siswa.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-6 border-t border-slate-50 bg-slate-50/30 print:hidden">
                                {{ $members->links() }} 
                            </div>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="flex flex-col items-center justify-center h-80 bg-white rounded-[2.5rem] border border-slate-100 text-center px-4 shadow-sm">
                            <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-6 text-slate-300 shadow-inner border border-slate-100">
                                <i class="ph-duotone ph-hand-pointing text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-elevate-dark mb-2">Pilih Kegiatan Dahulu</h3>
                            <p class="text-sm text-slate-400 font-medium max-w-xs mx-auto leading-relaxed">Silakan pilih salah satu ekstrakurikuler di menu sebelah kiri untuk mulai mengelola anggotanya.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Styling untuk TomSelect ke Tema Elevate */
        .ts-control {
            border-radius: 1rem !important;
            padding: 0.875rem 1rem !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            background-color: #f8fafc !important;
            border-color: #e2e8f0 !important;
            color: #1e293b !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #3b5889 !important;
            box-shadow: 0 0 0 1px #3b5889 !important;
            background-color: #fff !important;
        }
        .ts-dropdown {
            border-radius: 1rem !important;
            border-color: #e2e8f0 !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. SETUP DATA SISWA ---
            @php
                $studentsData = $students->map(function($s) {
                    return [
                        'id' => $s->id,
                        'name' => $s->name,
                        'nis' => $s->nis,
                        'class_id' => $s->class_id, 
                        'class_name' => optional($s->schoolClass)->name ?? '-'
                    ];
                })->values();
            @endphp

            const allStudents = @json($studentsData);

            // --- 2. SETUP TOM SELECT ---
            let studentSelect;
            if(document.getElementById('select-students')) {
                studentSelect = new TomSelect('#select-students', {
                    create: false,
                    sortField: { field: "text", direction: "asc" },
                    placeholder: "Pilih kelas terlebih dahulu...",
                    plugins: ['dropdown_input', 'remove_button'],
                    maxOptions: null,
                    valueField: 'id',
                    labelField: 'name',
                    searchField: ['name', 'nis'],
                    render: {
                        option: function(data, escape) {
                            return '<div class="py-2 px-3 hover:bg-slate-50 transition-colors">' +
                                '<span class="font-bold text-elevate-dark block text-sm">' + escape(data.name) + '</span>' +
                                '<span class="text-xs text-slate-400 font-mono">NIS: ' + escape(data.nis || '-') + '</span>' +
                            '</div>';
                        },
                        item: function(data, escape) {
                            return '<div title="' + escape(data.name) + '" class="font-bold text-sm">' + escape(data.name) + '</div>';
                        }
                    }
                });
            }

            // --- 3. LOGIKA FILTER KELAS ---
            const classFilter = document.getElementById('filter-class');
            if(classFilter && studentSelect) {
                classFilter.addEventListener('change', function() {
                    const selectedClassId = this.value;
                    
                    studentSelect.clear();
                    studentSelect.clearOptions();

                    if(selectedClassId) {
                        const filteredStudents = allStudents.filter(s => s.class_id == selectedClassId);
                        
                        filteredStudents.forEach(s => {
                            studentSelect.addOption(s);
                        });

                        studentSelect.settings.placeholder = "Pilih siswa (Total: " + filteredStudents.length + ")";
                        studentSelect.enable();
                        studentSelect.refreshOptions(false); 
                    } else {
                        studentSelect.settings.placeholder = "Pilih kelas terlebih dahulu...";
                        studentSelect.disable();
                    }
                    studentSelect.sync();
                });
            }

            // --- 4. SWEETALERT ---
            @if(session('success'))
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}",
                    timer: 3000, showConfirmButton: false, toast: true, position: 'top-end',
                    customClass: { popup: 'rounded-2xl shadow-lg border border-slate-100' }
                });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}" });
            @endif

            // --- 5. DELETE CONFIRM ---
            document.querySelectorAll('.btn-delete').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-form');
                    Swal.fire({
                        title: 'Keluarkan Siswa?', 
                        text: "Siswa akan dihapus dari daftar anggota ekskul ini.",
                        icon: 'warning', 
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48', 
                        cancelButtonColor: '#94a3b8',
                        confirmButtonText: 'Ya, Keluarkan!', 
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        customClass: {
                            popup: 'rounded-[2.5rem] font-sans border-0 shadow-2xl',
                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                            cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</x-app-layout>