<x-app-layout>
    {{-- Load Library Tambahan --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-8 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative z-10 mb-8 sm:mb-10 print:hidden">
                <x-hero-section
                    badge="MODUL KESISWAAN"
                    badgeIcon="ph-fill ph-users-three"
                    showcaseIcon="ph-duotone ph-user-check"
                    showcaseTitle="Anggota Ekskul"
                    showcaseSubtitle="Daftar Partisipan">
                    <x-slot:title>
                        <span class="block text-slate-100">Peserta</span>
                        <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                            Ekstrakurikuler
                        </span>
                    </x-slot:title>
                    <x-slot:description>
                        Kelola data keanggotaan siswa. Tambahkan anggota baru, pantau partisipasi, dan cetak daftar hadir per kegiatan.
                    </x-slot:description>
                    <x-slot:chips>
                        <a href="{{ route('extracurriculars.index') }}" class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-[#56bbf1] hover:text-white text-xs font-semibold transition">
                            <i class="ph-bold ph-arrow-left"></i> Kembali ke Menu
                        </a>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                            <i class="ph-bold ph-user-plus text-emerald-400"></i> Tambah Anggota
                        </span>
                    </x-slot:chips>
                    <x-slot:cta>
                        @if($selectedEkskulId)
                            <button onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-3 rounded-2xl bg-white/10 hover:bg-white/20 text-white font-bold text-sm border border-white/15 transition-all shadow-sm active:scale-95">
                                <i class="ph-bold ph-printer text-lg text-[#56bbf1]"></i>
                                <span>Cetak Absensi</span>
                            </button>
                        @endif
                    </x-slot:cta>
                    <x-slot:showcaseStats>
                        @if($selectedEkskulId)
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                                <i class="ph-fill ph-user-check text-[#56bbf1] text-sm"></i>
                                <span class="text-xs font-bold text-slate-300">Anggota:</span>
                                <span class="text-sm font-black text-white font-mono">{{ $members->total() }}</span>
                            </div>
                        @else
                            @php $totalAll = $extracurriculars->sum('members_count'); @endphp
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                                <i class="ph-fill ph-users text-[#56bbf1] text-sm"></i>
                                <span class="text-xs font-bold text-slate-300">Total Partisipan:</span>
                                <span class="text-sm font-black text-white font-mono">{{ $totalAll }}</span>
                            </div>
                        @endif
                    </x-slot:showcaseStats>
                </x-hero-section>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                {{-- KOLOM KIRI (FILTER) --}}
                <div class="lg:col-span-1 space-y-6 print:hidden">
                    <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden sticky top-24">
                        <div class="p-6 bg-white/[0.02] border-b border-white/10">
                            <h3 class="font-black text-white text-lg flex items-center gap-2">
                                <i class="ph-fill ph-funnel text-[#56bbf1]"></i> Pilih Kegiatan
                            </h3>
                            <p class="text-xs text-slate-400 font-bold mt-1">Pilih ekskul untuk melihat anggota.</p>
                        </div>
                        <div class="p-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                            <form method="GET" action="{{ route('extracurriculars.members') }}">
                                <div class="space-y-2">
                                    @foreach($extracurriculars as $ekskul)
                                        <button type="submit" name="ekskul_id" value="{{ $ekskul->id }}" 
                                            class="w-full flex items-center justify-between p-3 rounded-2xl transition-all group {{ $selectedEkskulId == $ekskul->id ? 'bg-gradient-to-r from-[#0d52a1] to-[#56bbf1] text-white shadow-lg' : 'bg-[#021124]/80 hover:bg-white/10 text-slate-300 border border-white/10' }}">
                                            <div class="flex items-center gap-3 text-left overflow-hidden">
                                                <div class="w-10 h-10 shrink-0 rounded-xl flex items-center justify-center text-lg {{ $selectedEkskulId == $ekskul->id ? 'bg-white/20 text-white' : 'bg-[#56bbf1]/10 text-[#56bbf1] group-hover:bg-[#56bbf1] group-hover:text-white transition-colors' }}">
                                                    <i class="{{ $ekskul->icon && !Str::startsWith($ekskul->icon, 'storage') ? $ekskul->icon : 'ph-fill ph-star' }}"></i>
                                                </div>
                                                <span class="font-bold text-sm truncate pr-2">{{ $ekskul->name }}</span>
                                            </div>
                                            <span class="text-[10px] font-black px-2.5 py-1 rounded-lg shrink-0 {{ $selectedEkskulId == $ekskul->id ? 'bg-white/20 text-white' : 'bg-white/5 text-slate-400 border border-white/10' }}">
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
                        <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 p-6 md:p-8 print:hidden relative overflow-hidden group transition-colors">
                            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#0d52a1] via-sky-500 to-[#56bbf1]"></div>
                            
                            <div class="flex items-center gap-4 mb-6 pt-2">
                                <div class="w-12 h-12 bg-[#56bbf1]/10 text-[#56bbf1] rounded-2xl flex items-center justify-center text-2xl shadow-inner border border-[#56bbf1]/20">
                                    <i class="ph-duotone ph-user-plus"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-black text-white leading-none">Tambah Anggota</h3>
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
                                        <select id="filter-class" class="w-full pl-11 rounded-2xl border-white/15 bg-[#021124]/90 focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] text-sm font-bold text-white py-3.5 transition-all appearance-none cursor-pointer">
                                            <option value="" class="bg-[#021124] text-white">-- Pilih Kelas --</option>
                                            @foreach($classes as $c)
                                                <option value="{{ $c->id }}" class="bg-[#021124] text-white">{{ $c->name }}</option>
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
                                        <i class="ph-fill ph-info text-[#56bbf1]"></i> Hanya siswa yang BELUM masuk ekskul ini yang akan muncul.
                                    </p>
                                </div>
                                
                                <div class="flex justify-end pt-2">
                                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] text-white font-bold rounded-2xl hover:brightness-110 transition-all shadow-lg shadow-sky-950/50 text-sm flex items-center justify-center gap-2 transform active:scale-95 group/btn">
                                        <i class="ph-bold ph-plus-circle text-lg group-hover/btn:rotate-90 transition-transform"></i> Simpan Anggota
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Tabel Anggota -->
                        <div class="bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] shadow-2xl border border-white/10 overflow-hidden print:shadow-none print:border-none print:rounded-none print:bg-white">
                            <div class="p-6 border-b border-white/10 bg-white/[0.02] flex justify-between items-center print:border-b-2 print:border-black print:bg-white">
                                <div>
                                    <h3 class="font-black text-white text-lg flex items-center gap-2 print:text-black">
                                        <i class="ph-fill ph-users text-[#56bbf1] print:hidden"></i> Daftar Anggota Aktif
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
                                <span class="bg-[#021124]/80 border border-white/10 text-xs font-black px-3 py-1.5 rounded-xl text-sky-300 print:hidden shadow-sm">
                                    Total: {{ $members->total() }}
                                </span>
                            </div>
                            <div class="overflow-x-auto custom-scrollbar">
                                <table class="w-full text-left border-collapse text-sm text-slate-300">
                                    <thead class="bg-white/[0.03] text-xs font-bold text-slate-400 uppercase tracking-wider border-b border-white/10 print:bg-white print:text-black print:border-b-2 print:border-black">
                                        <tr>
                                            <th class="px-6 py-4 print:py-2 w-10">No</th>
                                            <th class="px-6 py-4 print:py-2">Identitas Siswa</th>
                                            <th class="px-6 py-4 print:py-2 text-center">Kelas</th>
                                            <th class="hidden print:table-cell px-6 py-4 border-l border-black text-center w-40">Paraf</th>
                                            <th class="px-6 py-4 text-right print:hidden">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-white/5 print:divide-slate-300">
                                        @forelse($members as $index => $member)
                                            <tr class="hover:bg-white/[0.03] transition-colors group">
                                                <td class="px-6 py-4 print:py-2 text-xs font-bold text-slate-400">
                                                    {{ $members->firstItem() + $index }}
                                                </td>
                                                <td class="px-6 py-4 print:py-2">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-10 h-10 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-[#56bbf1] font-black text-sm shadow-sm print:hidden group-hover:border-[#56bbf1]/30 transition-colors">
                                                            {{ substr($member->student->name, 0, 2) }}
                                                        </div>
                                                        <div>
                                                            <span class="font-bold text-white text-sm block group-hover:text-[#56bbf1] transition-colors print:text-black">{{ $member->student->name }}</span>
                                                            <span class="text-[10px] text-slate-400 font-mono font-bold bg-white/5 px-1.5 py-0.5 rounded border border-white/10 print:hidden">{{ $member->student->nis }}</span>
                                                            <span class="hidden print:inline text-xs">({{ $member->student->nis }})</span>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 print:py-2 text-center">
                                                    <span class="inline-flex px-3 py-1 bg-white/5 border border-white/10 rounded-xl text-xs font-bold text-slate-300 print:border-none print:bg-transparent print:p-0 print:text-black">
                                                        {{ $member->student->schoolClass->name ?? '-' }}
                                                    </span>
                                                </td>
                                                <td class="hidden print:table-cell border-l border-slate-300"></td>
                                                <td class="px-6 py-4 text-right print:hidden">
                                                    <form action="{{ route('extracurriculars.members.destroy', $member->id) }}" method="POST" class="delete-form inline-block">
                                                        @csrf @method('DELETE')
                                                        <button type="button" class="btn-delete w-9 h-9 rounded-xl flex items-center justify-center text-slate-400 hover:text-rose-400 hover:bg-rose-500/10 border border-white/10 hover:border-rose-500/30 transition-all shadow-sm" title="Keluarkan">
                                                            <i class="ph-bold ph-sign-out text-lg"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-6 py-16 text-center">
                                                    <div class="w-20 h-20 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400 border border-white/10">
                                                        <i class="ph-duotone ph-users-three text-4xl"></i>
                                                    </div>
                                                    <p class="text-sm font-bold text-white">Belum ada anggota terdaftar.</p>
                                                    <p class="text-xs text-slate-400 mt-1">Gunakan formulir di atas untuk menambahkan siswa.</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="p-6 border-t border-white/10 bg-white/[0.02] print:hidden">
                                {{ $members->links() }} 
                            </div>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="flex flex-col items-center justify-center h-80 bg-gradient-to-br from-[#031d3d]/90 via-[#021124]/95 to-[#031d3d]/90 backdrop-blur-xl rounded-[2.5rem] border border-white/10 text-center px-4 shadow-2xl">
                            <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mb-6 text-slate-400 shadow-inner border border-white/10">
                                <i class="ph-duotone ph-hand-pointing text-5xl"></i>
                            </div>
                            <h3 class="text-xl font-black text-white mb-2">Pilih Kegiatan Dahulu</h3>
                            <p class="text-sm text-slate-400 font-medium max-w-xs mx-auto leading-relaxed">Silakan pilih salah satu ekstrakurikuler di menu sebelah kiri untuk mulai mengelola anggotanya.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom Styling untuk TomSelect ke Tema Elevate Dark */
        .ts-control {
            border-radius: 1rem !important;
            padding: 0.875rem 1rem !important;
            font-weight: 700 !important;
            font-size: 0.875rem !important;
            background-color: rgba(2, 17, 36, 0.9) !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            color: #ffffff !important;
        }
        .ts-wrapper.focus .ts-control {
            border-color: #56bbf1 !important;
            box-shadow: 0 0 0 1px #56bbf1 !important;
            background-color: #031d3d !important;
        }
        .ts-dropdown {
            border-radius: 1rem !important;
            border-color: rgba(255, 255, 255, 0.15) !important;
            background-color: #031d3d !important;
            color: #ffffff !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5) !important;
        }
        .ts-dropdown .option {
            color: #ffffff !important;
        }
        .ts-dropdown .active {
            background-color: rgba(86, 187, 241, 0.2) !important;
            color: #56bbf1 !important;
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
                            return '<div class="py-2 px-3 hover:bg-white/10 transition-colors">' +
                                '<span class="font-bold text-white block text-sm">' + escape(data.name) + '</span>' +
                                '<span class="text-xs text-slate-400 font-mono">NIS: ' + escape(data.nis || '-') + '</span>' +
                            '</div>';
                        },
                        item: function(data, escape) {
                            return '<div title="' + escape(data.name) + '" class="font-bold text-sm text-white">' + escape(data.name) + '</div>';
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
                    background: '#031d3d', color: '#ffffff',
                    customClass: { popup: 'rounded-2xl shadow-2xl border border-white/10' }
                });
            @endif
            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Gagal!', text: "{{ session('error') }}", background: '#031d3d', color: '#ffffff' });
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
                        background: '#031d3d',
                        color: '#ffffff',
                        customClass: {
                            popup: 'rounded-[2.5rem] font-sans border border-white/10 shadow-2xl',
                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-950/50',
                            cancelButton: 'bg-white/10 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-white/20 transition-colors mx-2'
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