<x-app-layout>
    {{-- Load Library Tambahan --}}
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <div class="py-6 sm:py-8">
        
        {{-- Header --}}
        <div class="mb-8 px-4 sm:px-0 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                    <i class="ph-duotone ph-users-three text-blue-600"></i> Peserta Ekstrakurikuler
                </h1>
                <p class="text-slate-500 mt-2 text-lg">
                    Kelola keanggotaan siswa dalam setiap kegiatan.
                </p>
            </div>
            
            {{-- Tombol Cetak --}}
            @if($selectedEkskulId)
            <div>
                <button onclick="window.print()" class="bg-white border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold py-2 px-4 rounded-xl shadow-sm flex items-center gap-2 transition">
                    <i class="ph-bold ph-printer"></i> Cetak Absensi
                </button>
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0 items-start">
            
            {{-- KOLOM KIRI (FILTER) --}}
            <div class="lg:col-span-1 space-y-6 print:hidden">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden sticky top-24">
                    <div class="p-6 bg-slate-50 border-b border-slate-100">
                        <h3 class="font-bold text-slate-800">Pilih Kegiatan</h3>
                        <p class="text-xs text-slate-500">Pilih ekskul untuk melihat anggota.</p>
                    </div>
                    <div class="p-4 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <form method="GET" action="{{ route('extracurriculars.members') }}">
                            <div class="space-y-2">
                                @foreach($extracurriculars as $ekskul)
                                    <button type="submit" name="ekskul_id" value="{{ $ekskul->id }}" 
                                        class="w-full flex items-center justify-between p-3 rounded-xl transition-all {{ $selectedEkskulId == $ekskul->id ? 'bg-blue-600 text-white shadow-md shadow-blue-500/30' : 'bg-white hover:bg-slate-50 text-slate-600 border border-slate-100' }}">
                                        <div class="flex items-center gap-3 text-left">
                                            <div class="w-8 h-8 shrink-0 rounded-lg flex items-center justify-center {{ $selectedEkskulId == $ekskul->id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
                                                <i class="{{ $ekskul->icon && !Str::startsWith($ekskul->icon, 'storage') ? $ekskul->icon : 'ph-fill ph-star' }}"></i>
                                            </div>
                                            <span class="font-bold text-sm line-clamp-2">{{ $ekskul->name }}</span>
                                        </div>
                                        <span class="text-xs font-bold px-2 py-1 rounded-full shrink-0 {{ $selectedEkskulId == $ekskul->id ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">
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
                    <!-- Form Tambah Anggota (FILTER KELAS -> SISWA) -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 p-6 print:hidden">
                        <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                            <i class="ph-bold ph-user-plus text-blue-500"></i> Tambah Anggota
                        </h3>
                        
                        <form action="{{ route('extracurriculars.members.store') }}" method="POST" class="flex flex-col gap-4">
                            @csrf
                            <input type="hidden" name="extracurricular_id" value="{{ $selectedEkskulId }}">
                            
                            {{-- 1. FILTER KELAS --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Langkah 1: Pilih Kelas</label>
                                <select id="filter-class" class="w-full rounded-xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 text-sm font-bold text-slate-700">
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- 2. PILIH SISWA (TomSelect) --}}
                            <div class="relative w-full">
                                <label class="block text-xs font-bold text-slate-500 uppercase tracking-wide mb-1.5">Langkah 2: Pilih Siswa</label>
                                <select id="select-students" name="student_ids[]" multiple placeholder="Pilih kelas terlebih dahulu..." autocomplete="off" disabled>
                                    {{-- Option akan diisi oleh Javascript --}}
                                </select>
                                <p class="text-[10px] text-slate-400 mt-1 italic">* Hanya siswa yang BELUM masuk ekskul ini yang akan muncul.</p>
                            </div>
                            
                            <div class="flex justify-end pt-2">
                                <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-500/20 text-sm flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-plus"></i> Simpan Anggota
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Tabel Anggota -->
                    <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden print:shadow-none print:border-none">
                        <div class="p-6 border-b border-slate-50 flex justify-between items-center print:border-b-2 print:border-black">
                            <div>
                                <h3 class="font-bold text-slate-800 text-lg">Daftar Anggota Aktif</h3>
                                <p class="hidden print:block text-sm text-slate-500 mt-1">
                                    Kegiatan: {{ $extracurriculars->find($selectedEkskulId)->name }}
                                </p>
                                @if($members->total() > 0)
                                    <p class="text-xs text-slate-400 mt-1 print:hidden">
                                        Menampilkan {{ $members->firstItem() }}-{{ $members->lastItem() }} dari {{ $members->total() }} siswa
                                    </p>
                                @endif
                            </div>
                            <span class="bg-slate-100 text-xs font-bold px-3 py-1 rounded-full text-slate-500 print:hidden">
                                Total {{ $members->total() }}
                            </span>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 text-xs font-bold text-slate-400 uppercase print:bg-white print:text-black print:border-b">
                                    <tr>
                                        <th class="px-6 py-4 print:py-2 w-10">No</th>
                                        <th class="px-6 py-4 print:py-2">Siswa</th>
                                        <th class="px-6 py-4 print:py-2 text-center">Kelas</th>
                                        <th class="hidden print:table-cell px-6 py-4 border-l border-black text-center w-32">Paraf</th>
                                        <th class="px-6 py-4 text-right print:hidden">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 print:divide-slate-200">
                                    @forelse($members as $index => $member)
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-6 py-4 print:py-2 text-xs font-bold text-slate-500">
                                                {{ $members->firstItem() + $index }}
                                            </td>
                                            <td class="px-6 py-4 print:py-2">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-xs shadow-sm print:hidden">
                                                        {{ substr($member->student->name, 0, 2) }}
                                                    </div>
                                                    <div>
                                                        <span class="font-bold text-slate-700 text-sm block">{{ $member->student->name }}</span>
                                                        <span class="text-[10px] text-slate-400 font-mono print:hidden">{{ $member->student->nis }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 print:py-2 text-center">
                                                <span class="inline-flex px-2 py-1 bg-slate-100 border border-slate-200 rounded text-xs font-bold text-slate-600 print:border-none print:bg-transparent">
                                                    {{ $member->student->schoolClass->name ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="hidden print:table-cell border-l border-slate-300"></td>
                                            <td class="px-6 py-4 text-right print:hidden">
                                                <form action="{{ route('extracurriculars.members.destroy', $member->id) }}" method="POST" class="delete-form">
                                                    @csrf @method('DELETE')
                                                    <button type="button" class="btn-delete text-slate-300 hover:text-rose-600 transition-colors p-2 rounded-lg hover:bg-rose-50" title="Keluarkan">
                                                        <i class="ph-bold ph-sign-out text-lg"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-300">
                                                    <i class="ph-duotone ph-users text-3xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-600">Belum ada anggota.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4 border-t border-slate-50 print:hidden">
                            {{ $members->links() }} 
                        </div>
                    </div>
                @else
                    {{-- Empty State --}}
                    <div class="flex flex-col items-center justify-center h-64 bg-slate-50 rounded-3xl border-2 border-dashed border-slate-200 text-center px-4">
                        <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mb-4 text-slate-300 shadow-sm">
                            <i class="ph-duotone ph-hand-pointing text-3xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-600">Pilih Kegiatan Dahulu</h3>
                        <p class="text-sm text-slate-400 max-w-xs mt-1">Silakan pilih salah satu ekstrakurikuler di menu sebelah kiri untuk mengelola anggotanya.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // --- 1. SETUP DATA SISWA (Dari Laravel ke JS) ---
            // Kita siapkan datanya di PHP block terpisah agar aman dari parsing error
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

            // Ambil data yang sudah clean ke variable JS
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
                            return '<div>' +
                                '<span class="font-bold text-slate-700">' + escape(data.name) + '</span>' +
                                '<span class="text-xs text-slate-400 ml-2">(' + escape(data.nis || '-') + ')</span>' +
                            '</div>';
                        },
                        item: function(data, escape) {
                            return '<div>' + escape(data.name) + '</div>';
                        }
                    }
                });
            }

            // --- 3. LOGIKA FILTER KELAS ---
            const classFilter = document.getElementById('filter-class');
            if(classFilter && studentSelect) {
                classFilter.addEventListener('change', function() {
                    const selectedClassId = this.value;
                    
                    // Reset Pilihan & Kosongkan Dropdown Siswa
                    studentSelect.clear();
                    studentSelect.clearOptions();

                    if(selectedClassId) {
                        // Filter siswa berdasarkan ID Kelas yang dipilih
                        const filteredStudents = allStudents.filter(s => s.class_id == selectedClassId);
                        
                        // Masukkan siswa yang lolos filter ke TomSelect
                        filteredStudents.forEach(s => {
                            studentSelect.addOption(s);
                        });

                        // Update Placeholder & Enable
                        studentSelect.settings.placeholder = "Pilih siswa (Total: " + filteredStudents.length + ")";
                        studentSelect.enable();
                        studentSelect.refreshOptions(false); // Refresh UI
                    } else {
                        // Jika kelas di-unselect (kembali ke default)
                        studentSelect.settings.placeholder = "Pilih kelas terlebih dahulu...";
                        studentSelect.disable();
                    }
                    
                    // Force update placeholder text di UI
                    studentSelect.sync();
                });
            }

            // --- 4. SWEETALERT ---
            @if(session('success'))
                Swal.fire({
                    icon: 'success', title: 'Berhasil!', text: "{{ session('success') }}",
                    timer: 3000, showConfirmButton: false, toast: true, position: 'top-end'
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
                        title: 'Keluarkan Siswa?', text: "Siswa akan dihapus dari daftar anggota ekskul ini.",
                        icon: 'warning', showCancelButton: true,
                        confirmButtonColor: '#e11d48', cancelButtonColor: '#64748b',
                        confirmButtonText: 'Ya, Keluarkan!', cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) form.submit();
                    });
                });
            });
        });
    </script>
</x-app-layout>