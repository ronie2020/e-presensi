<x-app-layout>
    {{-- CUSTOM STYLES & ELEVATE THEME --}}
    <style>
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        .animate-enter { opacity: 0; animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
        
        dialog::backdrop {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(4px);
        }
    </style>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

    <div class="py-8 sm:py-10 font-sans text-elevate-text bg-slate-50 min-h-screen">
        
        {{-- HERO SECTION & STATS (TEMA MICROSOFT ELEVATE) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
            <div class="animate-enter relative rounded-[2rem] bg-elevate-gradient-main p-8 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60 group">
                
                {{-- Background Decorations --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-8">
                    <div class="space-y-3">
                        <a href="{{ route('dashboard') }}" class="group bg-white hover:bg-slate-50 text-[#2A3B52] px-5 py-2.5 rounded-xl font-bold text-sm border border-slate-100 transition-all flex items-center gap-2 shadow-sm hover:shadow-md w-fit mb-4 mx-auto xl:mx-0">
                            <i class="ph-bold ph-arrow-left text-m group-hover:-translate-x-1 transition-transform"></i>
                            <span>Kembali ke Dashboard</span>
                        </a>
                        <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white border border-slate-100 text-[#2A3B52] text-xs font-bold uppercase tracking-widest backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-graduation-cap text-[#5295FF]"></i> Manajemen Akademik
                        </div>
                        <h1 class="text-3xl sm:text-5xl font-black tracking-tight leading-tight text-[#2A3B52]">
                            Kelulusan & <span class="text-[#2A3B52]">SKL Digital</span>
                        </h1>
                        <p class="text-[#2A3B52]/80 text-sm sm:text-base max-w-xl font-medium leading-relaxed">
                            Kelola status kelulusan siswa tingkat akhir, generate Surat Keterangan Lulus (SKL), dan publikasi pengumuman secara terpusat.
                        </p>
                    </div>

                    {{-- GLOBAL ACTIONS --}}
                    <div class="flex flex-wrap gap-3 w-full xl:w-auto">
                        <!-- Tombol Set Tanggal -->
                        <button onclick="document.getElementById('modalGlobalDate').showModal()" class="flex-1 xl:flex-none bg-white hover:bg-slate-50 border border-slate-100 text-[#2A3B52] px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                            <i class="ph-bold ph-calendar-check text-lg text-[#107C10]"></i>
                            <span>Set Tanggal</span>
                        </button>
                        
                        <!-- Tombol Import -->
                        <button onclick="document.getElementById('modalImport').showModal()" class="flex-1 xl:flex-none bg-white hover:bg-slate-50 border border-slate-100 text-[#2A3B52] px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                            <i class="ph-bold ph-file-csv text-lg text-[#D83B01]"></i>
                            <span>Import CSV</span>
                        </button>

                        <!-- Tombol Generate Nomor SKL Massal -->
                        <button onclick="document.getElementById('modalSkl').showModal()" class="flex-1 xl:flex-none bg-[#5295FF] hover:bg-[#3b7ee6] text-white shadow-md hover:shadow-lg px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2">
                            <i class="ph-bold ph-list-numbers text-lg"></i>
                            <span>Set No. SKL (Auto)</span>
                        </button>

                        <!-- Tombol Pengaturan SKL -->
                        <button onclick="document.getElementById('modalSettings').showModal()" class="flex-1 xl:flex-none bg-white hover:bg-slate-50 border border-slate-100 text-[#2A3B52] px-5 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 shadow-md hover:shadow-lg">
                            <i class="ph-bold ph-gear text-lg text-[#2A3B52]"></i>
                            <span>Pengaturan SKL</span>
                        </button>

                        <!-- Tombol Pindahkan ke Alumni -->
                        <form action="{{ route('admin.graduation.process_alumni') }}" method="POST" id="formProcessAlumni" class="w-full xl:w-auto">
                            @csrf
                            <button type="button" onclick="confirmAlumniProcess()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white shadow-md px-6 py-3 rounded-xl font-bold text-sm transition-all flex items-center justify-center gap-2 active:scale-95">
                                <i class="ph-bold ph-users-three text-lg"></i>
                                <span>Pindahkan ke Alumni</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER & SEARCH BAR --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 animate-enter" style="animation-delay: 100ms;">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
                
                {{-- Filter Kelas --}}
                <form method="GET" class="w-full sm:w-auto flex items-center gap-2">
                    <div class="relative w-full sm:w-64 group">
                        <i class="ph-bold ph-chalkboard-teacher absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                        <select name="class_id" onchange="this.form.submit()" class="w-full pl-11 pr-10 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary appearance-none cursor-pointer transition-all shadow-sm">
                            <option value="">Semua Kelas 9</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                            @endforeach
                        </select>
                        <i class="ph-bold ph-caret-down absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    </div>
                </form>

                {{-- Search --}}
                <form method="GET" class="w-full sm:w-auto relative group">
                    @if(request('class_id')) 
                        <input type="hidden" name="class_id" value="{{ request('class_id') }}"> 
                    @endif
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-elevate-primary transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa / NISN..." 
                           class="w-full sm:w-72 pl-11 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary transition-all shadow-sm placeholder:font-medium placeholder:text-slate-400">
                </form>
            </div>
        </div>

        {{-- MAIN CONTENT: TABEL SISWA --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-enter" style="animation-delay: 200ms;">
            <form action="{{ route('admin.graduation.bulk_update') }}" method="POST" id="bulkForm">
                @csrf
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
                    
                    {{-- Table Header --}}
                    <div class="px-6 py-5 border-b border-slate-100 bg-white flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="bg-elevate-accent/10 border border-elevate-accent/20 text-elevate-primary px-4 py-1.5 rounded-lg text-xs font-bold shadow-sm">Total: {{ $students->total() }} Siswa</span>
                        </div>
                        <button type="submit" class="bg-elevate-dark hover:bg-elevate-primary text-white px-5 py-2.5 rounded-lg text-xs font-bold shadow-sm transition-all flex items-center gap-2 active:scale-95 group">
                            <i class="ph-bold ph-floppy-disk group-hover:scale-110 transition-transform"></i> Simpan Perubahan Masal
                        </button>
                    </div>

                    {{-- Table Body --}}
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/80 text-xs uppercase font-bold text-slate-500 tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Identitas Siswa</th>
                                    <th class="px-6 py-4">Status Kelulusan</th>
                                    <th class="px-6 py-4 text-center">Nilai Rata-rata</th>
                                    <th class="px-6 py-4">No. SKL</th>
                                    <th class="px-6 py-4 text-center border-l border-slate-100 bg-white">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($students as $student)
                                <tr class="hover:bg-slate-50/50 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-500 font-bold text-xs shrink-0 overflow-hidden shadow-sm group-hover:border-elevate-primary transition-colors">
                                                @if($student->photo_path)
                                                    <img src="{{ asset('storage/'.$student->photo_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($student->name, 0, 2) }}
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-elevate-dark group-hover:text-elevate-primary transition-colors">{{ $student->name }}</div>
                                                <div class="text-[11px] font-mono text-slate-400 font-medium mt-0.5 flex items-center gap-1">
                                                    <i class="ph-bold ph-identification-card"></i> {{ $student->student_id }} <span class="mx-1">•</span> <i class="ph-fill ph-chalkboard text-slate-300"></i> {{ $student->schoolClass->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($student->status == 'graduated')
                                            <span class="mt-2 inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-bold rounded-md">
                                                <i class="ph-fill ph-check-circle"></i> Sudah Alumni
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="students[{{ $student->id }}][status]" class="w-36 py-2 px-3 rounded-lg text-xs font-bold border-slate-200 focus:ring-elevate-primary focus:border-elevate-primary cursor-pointer shadow-sm text-elevate-dark
                                            {{ $student->graduation?->status == 'LULUS' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : '' }}
                                            {{ $student->graduation?->status == 'TIDAK LULUS' ? 'bg-rose-50 text-rose-600 border-rose-100' : '' }}">
                                            <option value="DITUNDA" {{ ($student->graduation?->status ?? 'DITUNDA') == 'DITUNDA' ? 'selected' : '' }}>⏳ Ditunda</option>
                                            <option value="LULUS" {{ ($student->graduation?->status ?? '') == 'LULUS' ? 'selected' : '' }}>✅ Lulus</option>
                                            <option value="TIDAK LULUS" {{ ($student->graduation?->status ?? '') == 'TIDAK LULUS' ? 'selected' : '' }}>❌ Tidak Lulus</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.01" name="students[{{ $student->id }}][average_score]" value="{{ $student->graduation?->average_score }}" 
                                            class="w-24 text-center py-2 rounded-lg border-slate-200 text-xs font-bold focus:ring-elevate-primary focus:border-elevate-primary bg-slate-50 focus:bg-white transition-colors shadow-sm text-elevate-dark" placeholder="0.00">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="students[{{ $student->id }}][skl_number]" value="{{ $student->graduation?->skl_number }}" 
                                            class="w-48 py-2 rounded-lg border-slate-200 text-xs font-medium focus:ring-elevate-primary focus:border-elevate-primary bg-slate-50 focus:bg-white transition-colors shadow-sm text-elevate-dark" placeholder="Kosong = Format Default">
                                        
                                        {{-- Hidden date field untuk menjaga tanggal pengumuman per siswa jika ada --}}
                                        <input type="hidden" name="students[{{ $student->id }}][announcement_date]" value="{{ $student->graduation?->announcement_date }}">
                                    </td>
                                    <td class="px-6 py-4 text-center bg-white border-l border-slate-100">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="saveSingle('{{ $student->id }}')" class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-elevate-accent/10 hover:text-elevate-primary border border-transparent hover:border-elevate-accent/20 transition-all" title="Simpan Baris Ini">
                                                <i class="ph-bold ph-check text-lg"></i>
                                            </button>
                                            {{-- Tombol Cetak PDF --}}
                                            @if($student->graduation?->status == 'LULUS')
                                                <a href="{{ route('graduation.print', $student->id) }}" target="_blank" class="w-8 h-8 flex items-center justify-center rounded-lg text-emerald-600 hover:bg-emerald-50 border border-transparent hover:border-emerald-200 transition-all" title="Cetak SKL">
                                                    <i class="ph-bold ph-printer text-lg"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <span id="msg_{{ $student->id }}" class="text-[10px] text-emerald-600 font-bold hidden animate-pulse mt-1">Tersimpan!</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div class="w-16 h-16 bg-slate-50 border border-slate-100 rounded-2xl flex items-center justify-center shadow-sm">
                                                <i class="ph-duotone ph-student text-3xl"></i>
                                            </div>
                                            <span class="font-bold text-sm text-elevate-dark">Tidak ada data siswa kelas 9 ditemukan.</span>
                                            <span class="text-xs">Silakan sesuaikan filter pencarian.</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-slate-100 bg-white">
                        {{ $students->links() }}
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- MODAL-MODAL DENGAN GAYA MICROSOFT ELEVATE --}}
    {{-- ========================================================= --}}

    {{-- MODAL SET NOMOR SKL AUTO --}}
    <dialog id="modalSkl" class="rounded-[2.5rem] p-0 w-full max-w-md bg-transparent shadow-2xl border border-white/20 backdrop-blur-md">
        <form action="{{ route('admin.graduation.bulk_skl') }}" method="POST" class="bg-white p-8 rounded-[2.5rem]">
            @csrf
            <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-elevate-accent/10 text-elevate-primary border border-elevate-accent/20 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-list-numbers"></i>
                </div>
                <h3 class="text-lg font-black text-elevate-dark">Generate SKL Massal</h3>
            </div>
            
            <div class="p-4 mb-6 rounded-xl bg-elevate-accent/10 border border-elevate-accent/20 flex gap-3 items-start shadow-sm">
                <i class="ph-fill ph-info text-xl text-elevate-primary mt-0.5"></i>
                <div class="text-xs text-elevate-dark leading-relaxed font-medium">
                    Gunakan tag <b class="text-elevate-primary font-mono px-1.5 py-0.5 bg-white border border-elevate-accent/20 rounded shadow-sm">{urut}</b> agar sistem membuat urutan otomatis (001, 002, dst) ke database.<br><br>
                    Contoh: <br><b class="font-mono text-elevate-dark">421.3/{urut}/SMP.03/2026</b>
                </div>
            </div>

            @if(request('class_id')) <input type="hidden" name="class_filter" value="{{ request('class_id') }}"> @endif
            
            <div class="mb-5">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Format Nomor Surat</label>
                <input type="text" name="skl_format" required class="w-full py-3 px-4 rounded-xl border-slate-200 font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary bg-slate-50 focus:bg-white transition-colors shadow-sm" placeholder="Cth: 421.3/{urut}/SMP.03/2026">
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Mulai Dari Urutan Ke</label>
                <input type="number" name="start_number" value="1" min="1" class="w-full py-3 px-4 rounded-xl border-slate-200 font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary bg-slate-50 focus:bg-white transition-colors shadow-sm">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-elevate-primary text-white text-xs font-bold hover:bg-elevate-dark shadow-sm transition-all flex items-center gap-2 active:scale-95 group">
                    <i class="ph-bold ph-database group-hover:scale-110 transition-transform"></i> Generate
                </button>
            </div>
        </form>
    </dialog>

    {{-- MODAL GLOBAL DATE --}}
    <dialog id="modalGlobalDate" class="rounded-[2.5rem] p-0 w-full max-w-md bg-transparent shadow-2xl border border-white/20 backdrop-blur-md">
        <form action="{{ route('admin.graduation.set_date') }}" method="POST" class="bg-white p-8 rounded-[2.5rem]">
            @csrf
            <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-calendar-check"></i>
                </div>
                <h3 class="text-lg font-black text-elevate-dark">Set Tanggal Pengumuman</h3>
            </div>
            
            <div class="p-4 mb-6 rounded-xl bg-emerald-50 border border-emerald-100 flex gap-3 items-start shadow-sm">
                <i class="ph-fill ph-info text-xl text-emerald-600 mt-0.5"></i>
                <div class="text-xs text-elevate-dark leading-relaxed font-medium">
                    Mengatur jadwal di sini akan otomatis membuat seluruh siswa di tabel menjadi berstatus <span class="bg-white text-emerald-600 px-1.5 py-0.5 rounded border border-emerald-200 font-bold text-[10px]">LULUS</span>. Jika ada yang tidak lulus, Anda dapat mengubahnya secara manual setelah ini.
                </div>
            </div>

            @if(request('class_id')) <input type="hidden" name="class_filter" value="{{ request('class_id') }}"> @endif
            <div class="mb-8">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Tanggal & Jam Publikasi</label>
                <input type="datetime-local" name="global_date" required class="w-full py-3 px-4 rounded-xl border-slate-200 font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary bg-slate-50 focus:bg-white transition-colors shadow-sm cursor-pointer">
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 shadow-sm transition-all flex items-center gap-2 active:scale-95 group">
                    <i class="ph-bold ph-floppy-disk group-hover:scale-110 transition-transform"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </dialog>

    {{-- MODAL IMPORT --}}
    <dialog id="modalImport" class="rounded-[2.5rem] p-0 w-full max-w-md bg-transparent shadow-2xl border border-white/20 backdrop-blur-md">
        <form action="{{ route('admin.graduation.import') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-[2.5rem]">
            @csrf
            <div class="flex items-center gap-3 mb-4 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-500 border border-amber-100 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-file-csv"></i>
                </div>
                <h3 class="text-lg font-black text-elevate-dark">Import Data CSV</h3>
            </div>
            
            <p class="text-xs text-slate-500 mb-6 font-medium bg-slate-50 p-3 rounded-xl border border-slate-100">Format Kolom: <b class="text-elevate-dark">NISN</b>, <b class="text-elevate-dark">STATUS</b> (LULUS/TIDAK LULUS), <b class="text-elevate-dark">NILAI</b></p>
            
            <div class="mb-8">
                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Pilih File CSV</label>
                <input type="file" name="file" accept=".csv" required class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-elevate-accent/10 file:text-elevate-primary hover:file:bg-elevate-accent/20 file:transition-colors file:cursor-pointer border border-slate-200 rounded-xl bg-slate-50 p-1 shadow-sm">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 text-white text-xs font-bold hover:bg-amber-600 shadow-sm transition-all flex items-center gap-2 active:scale-95 group">
                    <i class="ph-bold ph-upload-simple group-hover:-translate-y-1 transition-transform"></i> Upload Data
                </button>
            </div>
        </form>
    </dialog>

    {{-- MODAL PENGATURAN SKL --}}
    <dialog id="modalSettings" class="rounded-[2.5rem] p-0 w-full max-w-md bg-transparent shadow-2xl border border-white/20 backdrop-blur-md">
        <form action="{{ route('admin.graduation.save_settings') }}" method="POST" class="bg-white p-8 rounded-[2.5rem]">
            @csrf
            <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                <div class="w-10 h-10 rounded-xl bg-slate-100 text-elevate-dark border border-slate-200 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-gear"></i>
                </div>
                <h3 class="text-lg font-black text-elevate-dark">Pengaturan Cetak SKL</h3>
            </div>
            
            <div class="space-y-5 mb-8">
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Format Default Nomor Surat</label>
                    <input type="text" name="letter_number" value="{{ $settings['letter_number'] ?? '' }}" required class="w-full py-3 px-4 rounded-xl border-slate-200 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary shadow-sm transition-all" placeholder="Contoh: 421.3/ ... /SMP.03/2026">
                    <p class="text-[10px] text-amber-600 font-medium mt-2 bg-amber-50 p-2.5 rounded-xl border border-amber-100">
                        *Hanya dipakai sebagai fallback statis jika No. SKL siswa kosong. Untuk auto-increment, gunakan fitur <b>Set No. SKL (Auto)</b>.
                    </p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Nama Kepala Sekolah</label>
                    <input type="text" name="principal_name" value="{{ $settings['principal_name'] ?? '' }}" required class="w-full py-3 px-4 rounded-xl border-slate-200 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary shadow-sm transition-all" placeholder="Nama beserta gelar">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">NIP Kepala Sekolah</label>
                    <input type="text" name="principal_nip" value="{{ $settings['principal_nip'] ?? '' }}" required class="w-full py-3 px-4 rounded-xl border-slate-200 text-sm font-bold text-elevate-dark focus:border-elevate-primary focus:ring-elevate-primary shadow-sm transition-all" placeholder="NIP Kepala Sekolah">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-500 bg-slate-100 hover:bg-slate-200 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-elevate-dark text-white text-xs font-bold hover:bg-elevate-primary shadow-sm transition-all flex items-center gap-2 active:scale-95 group">
                    <i class="ph-bold ph-floppy-disk group-hover:scale-110 transition-transform"></i> Simpan Pengaturan
                </button>
            </div>
        </form>
    </dialog>

    {{-- SCRIPTS --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#3b5889',
                    customClass: { popup: 'rounded-3xl border border-slate-100 shadow-xl font-sans', confirmButton: 'rounded-xl font-bold px-6 py-2.5' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#e11d48',
                    customClass: { popup: 'rounded-3xl border border-slate-100 shadow-xl font-sans', confirmButton: 'rounded-xl font-bold px-6 py-2.5' }
                });
            @endif
        });

        // SweetAlert untuk konfirmasi pindah Alumni
        function confirmAlumniProcess() {
            Swal.fire({
                title: 'Pindahkan ke Alumni?',
                html: 'Siswa dengan status <b class="text-emerald-600">LULUS</b> akan dipindahkan menjadi <b>ALUMNI</b>.<br><br><ul class="text-left text-xs text-slate-500 list-disc pl-5 mt-2 font-medium"><li>Akun dikeluarkan dari kelas aktif.</li><li>Login diarahkan ke Dashboard Alumni.</li></ul>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981', // emerald
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Pindahkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { 
                    popup: 'rounded-[2.5rem] shadow-2xl border-0 font-sans',
                    confirmButton: 'bg-emerald-600 text-white rounded-xl font-bold px-6 py-3 hover:bg-emerald-700 transition-colors mx-2 shadow-lg shadow-emerald-600/20',
                    cancelButton: 'bg-slate-100 text-slate-600 rounded-xl font-bold px-6 py-3 hover:bg-slate-200 transition-colors mx-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses Data...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-3xl font-sans' }
                    });
                    document.getElementById('formProcessAlumni').submit();
                }
            });
        }

        // Fungsi Simpan Satuan (AJAX Fetch)
        function saveSingle(studentId) {
            const row = document.querySelector(`input[name="students[${studentId}][average_score]"]`).closest('tr');
            const status = row.querySelector(`select[name="students[${studentId}][status]"]`).value;
            const score = row.querySelector(`input[name="students[${studentId}][average_score]"]`).value;
            const skl = row.querySelector(`input[name="students[${studentId}][skl_number]"]`).value;
            const date = row.querySelector(`input[name="students[${studentId}][announcement_date]"]`).value;

            const btn = event.currentTarget;
            const originalIcon = btn.innerHTML;
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin text-lg"></i>'; 
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
            .then(response => {
                if (!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                btn.innerHTML = originalIcon;
                btn.disabled = false;
                const msg = document.getElementById('msg_' + studentId);
                msg.classList.remove('hidden');
                setTimeout(() => msg.classList.add('hidden'), 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                btn.innerHTML = '<i class="ph-bold ph-warning text-rose-500 text-lg"></i>';
                btn.disabled = false;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: 'Terjadi kesalahan saat menghubungi server.',
                    confirmButtonColor: '#e11d48',
                    customClass: { popup: 'rounded-[2rem] font-sans border border-slate-100 shadow-xl', confirmButton: 'rounded-xl font-bold px-6 py-2.5' }
                });
            });
        }
    </script>
</x-app-layout>