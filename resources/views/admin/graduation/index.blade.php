<x-app-layout>
    {{-- 
        X-DATA CONTEXT:
        State 'search' untuk interaksi UI sederhana.
    --}}
    <div class="py-8 sm:py-10 font-sans text-slate-800">
        
        {{-- HERO SECTION & STATS --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-10">
            <div class="relative rounded-[2.5rem] bg-gray-900 bg-gradient-to-br from-blue-950 via-blue-900 to-slate-900 p-8 sm:p-10 text-white shadow-2xl shadow-blue-900/40 overflow-hidden border border-white/10 group">
                
                {{-- Background Decorations --}}
                <div class="absolute inset-0 opacity-20 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none"></div>
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-500/20 rounded-full blur-3xl pointer-events-none group-hover:bg-emerald-500/30 transition-all duration-700"></div>
                <div class="absolute bottom-0 right-20 w-64 h-64 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-8">
                    <div class="space-y-2">
                        <a href="{{ route('dashboard') }}" class="group bg-white/10 hover:bg-white/20 text-white px-5 py-3 rounded-2xl font-bold text-sm backdrop-blur-sm border border-white/10 transition-all flex items-center gap-2 shadow-sm w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-m group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-300 text-xs font-bold uppercase tracking-widest backdrop-blur-sm">
                            <i class="ph-fill ph-graduation-cap"></i> Manajemen Akademik
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-black tracking-tight leading-tight">
                            Kelulusan & <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-200 to-emerald-200">SKL Digital</span>
                        </h1>
                        <p class="text-blue-200/80 text-sm sm:text-base max-w-xl font-medium">
                            Kelola status kelulusan siswa tingkat akhir, generate SKL, dan publikasi pengumuman secara terpusat.
                        </p>
                    </div>

                    {{-- GLOBAL ACTIONS --}}
                    <div class="flex flex-wrap gap-3 w-full xl:w-auto">
                        <!-- Tombol Set Tanggal -->
                        <button onclick="document.getElementById('modalGlobalDate').showModal()" class="flex-1 xl:flex-none btn-secondary bg-white/10 hover:bg-white/20 border-white/10 text-white backdrop-blur-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-calendar-check text-lg"></i>
                            <span>Set Tanggal</span>
                        </button>
                        
                        <!-- Tombol Import -->
                        <button onclick="document.getElementById('modalImport').showModal()" class="flex-1 xl:flex-none btn-secondary bg-white/10 hover:bg-white/20 border-white/10 text-white backdrop-blur-md px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-file-csv text-lg"></i>
                            <span>Import CSV</span>
                        </button>

                        <!-- [BARU] Tombol Pindahkan ke Alumni -->
                        <form action="{{ route('admin.graduation.process_alumni') }}" method="POST" onsubmit="return confirm('PERINGATAN: \n\nSiswa dengan status LULUS akan dipindahkan menjadi ALUMNI.\n- Akun mereka akan dikeluarkan dari kelas.\n- Login mereka akan diarahkan ke Dashboard Alumni.\n\nLanjutkan?');">
                            @csrf
                            <button type="submit" class="w-full xl:w-auto btn-primary bg-emerald-500 hover:bg-emerald-400 text-white shadow-lg shadow-emerald-900/20 px-6 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 border border-emerald-400/50">
                                <i class="ph-bold ph-users-three text-lg"></i>
                                <span>Pindahkan ke Alumni</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER & SEARCH BAR --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-col sm:flex-row gap-4 justify-between items-center">
                
                {{-- Filter Kelas --}}
                <form method="GET" class="w-full sm:w-auto flex items-center gap-2">
                    <div class="relative w-full sm:w-64">
                        <i class="ph-bold ph-chalkboard-teacher absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <select name="class_id" onchange="this.form.submit()" class="w-full pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500 appearance-none cursor-pointer">
                            <option value="">Semua Kelas 9</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </form>

                {{-- Search --}}
                <form method="GET" class="w-full sm:w-auto relative">
                    @if(request('class_id')) 
                        <input type="hidden" name="class_id" value="{{ request('class_id') }}"> 
                    @endif
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa / NISN..." 
                           class="w-full sm:w-72 pl-10 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold focus:ring-blue-500 focus:border-blue-500 transition-all">
                </form>
            </div>
        </div>

        {{-- MAIN CONTENT: TABEL SISWA --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form action="{{ route('admin.graduation.bulk_update') }}" method="POST" id="bulkForm">
                @csrf
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/60 border border-slate-100 overflow-hidden">
                    
                    {{-- Table Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-lg text-xs font-bold">Total: {{ $students->total() }} Siswa</span>
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-xs font-bold shadow-lg shadow-blue-500/30 transition flex items-center gap-2">
                            <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan Masal
                        </button>
                    </div>

                    {{-- Table Body --}}
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 text-xs uppercase font-bold text-slate-400 tracking-wider">
                                <tr>
                                    <th class="px-6 py-4 rounded-tl-2xl">Identitas Siswa</th>
                                    <th class="px-6 py-4">Status Kelulusan</th>
                                    <th class="px-6 py-4 text-center">Nilai Rata-rata</th>
                                    <th class="px-6 py-4">No. SKL</th>
                                    <th class="px-6 py-4 rounded-tr-2xl text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($students as $student)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0 overflow-hidden">
                                                @if($student->photo_path)
                                                    <img src="{{ asset('storage/'.$student->photo_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($student->name, 0, 2) }}
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-slate-800 group-hover:text-blue-600 transition">{{ $student->name }}</div>
                                                <div class="text-xs text-slate-400 font-medium">{{ $student->student_id }} &bull; {{ $student->schoolClass->name ?? '-' }}</div>
                                            </div>
                                        </div>
                                        @if($student->status == 'graduated')
                                            <span class="mt-1 inline-block px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[10px] font-bold rounded">Sudah Alumni</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="students[{{ $student->id }}][status]" class="w-32 py-1.5 px-3 rounded-lg text-xs font-bold border-slate-200 focus:ring-blue-500 focus:border-blue-500 cursor-pointer
                                            {{ $student->graduation?->status == 'LULUS' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' : '' }}
                                            {{ $student->graduation?->status == 'TIDAK LULUS' ? 'bg-rose-50 text-rose-700 border-rose-200' : '' }}">
                                            <option value="DITUNDA" {{ ($student->graduation?->status ?? 'DITUNDA') == 'DITUNDA' ? 'selected' : '' }}>Ditunda</option>
                                            <option value="LULUS" {{ ($student->graduation?->status ?? '') == 'LULUS' ? 'selected' : '' }}>Lulus</option>
                                            <option value="TIDAK LULUS" {{ ($student->graduation?->status ?? '') == 'TIDAK LULUS' ? 'selected' : '' }}>Tidak Lulus</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.01" name="students[{{ $student->id }}][average_score]" value="{{ $student->graduation?->average_score }}" 
                                            class="w-20 text-center py-1.5 rounded-lg border-slate-200 text-xs font-bold focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white transition" placeholder="0.00">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="students[{{ $student->id }}][skl_number]" value="{{ $student->graduation?->skl_number }}" 
                                            class="w-32 py-1.5 rounded-lg border-slate-200 text-xs font-medium focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white transition" placeholder="No. SKL">
                                        
                                        {{-- Hidden date field untuk menjaga tanggal pengumuman per siswa jika ada --}}
                                        <input type="hidden" name="students[{{ $student->id }}][announcement_date]" value="{{ $student->graduation?->announcement_date }}">
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="saveSingle('{{ $student->id }}')" class="p-2 rounded-lg text-slate-400 hover:bg-blue-50 hover:text-blue-600 transition" title="Simpan Baris Ini">
                                                <i class="ph-bold ph-check"></i>
                                            </button>
                                            {{-- Tombol Cetak PDF --}}
                                            @if($student->graduation?->status == 'LULUS')
                                                <a href="{{ route('graduation.print', $student->id) }}" target="_blank" class="p-2 rounded-lg text-emerald-500 hover:bg-emerald-50 transition" title="Cetak SKL">
                                                    <i class="ph-bold ph-printer"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <span id="msg_{{ $student->id }}" class="text-[10px] text-emerald-600 font-bold hidden animate-pulse">Tersimpan!</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-2">
                                            <i class="ph-duotone ph-student text-3xl"></i>
                                            <span class="font-medium">Tidak ada data siswa kelas 9 ditemukan.</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50">
                        {{ $students->links() }}
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL IMPORT & SET DATE (Masih sama, disembunyikan untuk ringkas) --}}
    <dialog id="modalGlobalDate" class="modal rounded-3xl p-0 backdrop:bg-slate-900/50">
        <form action="{{ route('admin.graduation.set_date') }}" method="POST" class="bg-white p-8 w-full max-w-md rounded-3xl shadow-2xl">
            @csrf
            <h3 class="text-lg font-bold mb-4">Set Tanggal Pengumuman Serentak</h3>
            @if(request('class_id')) <input type="hidden" name="class_filter" value="{{ request('class_id') }}"> @endif
            <div class="mb-6">
                <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Tanggal & Jam Buka</label>
                <input type="datetime-local" name="global_date" required class="w-full rounded-xl border-slate-200 font-bold">
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700">Simpan</button>
            </div>
        </form>
    </dialog>

    <dialog id="modalImport" class="modal rounded-3xl p-0 backdrop:bg-slate-900/50">
        <form action="{{ route('admin.graduation.import') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 w-full max-w-md rounded-3xl shadow-2xl">
            @csrf
            <h3 class="text-lg font-bold mb-2">Import Data CSV</h3>
            <p class="text-xs text-slate-500 mb-6">Format: NISN, STATUS (LULUS/TIDAK), NILAI</p>
            <input type="file" name="file" accept=".csv" required class="w-full mb-6 text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-bold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
            <div class="flex justify-end gap-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-4 py-2 rounded-xl text-sm font-bold text-slate-500 hover:bg-slate-50">Batal</button>
                <button type="submit" class="px-6 py-2 rounded-xl bg-blue-600 text-white text-sm font-bold hover:bg-blue-700">Upload</button>
            </div>
        </form>
    </dialog>

    <script>
        function saveSingle(studentId) {
            const row = document.querySelector(`input[name="students[${studentId}][average_score]"]`).closest('tr');
            const status = row.querySelector(`select[name="students[${studentId}][status]"]`).value;
            const score = row.querySelector(`input[name="students[${studentId}][average_score]"]`).value;
            const skl = row.querySelector(`input[name="students[${studentId}][skl_number]"]`).value;
            const date = row.querySelector(`input[name="students[${studentId}][announcement_date]"]`).value;

            const btn = event.currentTarget;
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin"></i>'; 
            btn.disabled = true;

            fetch("{{ route('admin.graduation.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    student_id: studentId,
                    status: status,
                    average_score: score,
                    skl_number: skl,
                    announcement_date: date
                })
            })
            .then(response => response.json())
            .then(data => {
                btn.innerHTML = originalIcon;
                btn.disabled = false;
                const msg = document.getElementById('msg_' + studentId);
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = '<i class="ph-bold ph-warning text-rose-500"></i>';
                btn.disabled = false;
                alert('Gagal menyimpan.');
            });
        }
    </script>
</x-app-layout>