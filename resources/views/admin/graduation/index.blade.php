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

    <div class="py-8 sm:py-10 font-sans text-white bg-elevate-surface relative overflow-hidden min-h-screen">
        {{-- Efek Latar Belakang Halus --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>
        
        {{-- HERO SECTION (UNIFIED ELEVATE DARK GLASS - AQUALIFE & E-LEARNING) --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
            <div class="animate-enter relative rounded-[2.5rem] bg-gradient-to-br from-[#0d52a1]/85 via-[#031d3d]/90 to-[#021124]/95 p-8 md:p-10 text-white shadow-2xl shadow-[#0d52a1]/25 border border-white/20 backdrop-blur-2xl overflow-hidden group">
                
                {{-- Specular Top Rim Light (Ref 2) --}}
                <div class="absolute inset-0 rounded-[2.5rem] pointer-events-none border-t border-l border-white/30"></div>
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>

                {{-- Ambient Radiant Glow Orbs (Ref 1 & 2) --}}
                <div class="absolute -top-16 -right-16 w-80 h-80 bg-[#56bbf1]/20 rounded-full blur-[100px] pointer-events-none"></div>
                <div class="absolute -bottom-16 -left-16 w-72 h-72 bg-[#0d52a1]/30 rounded-full blur-[90px] pointer-events-none"></div>
                
                <div class="relative z-10 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-8">
                    
                    {{-- KIRI: Judul, Intro, Chips & Tombol --}}
                    <div class="space-y-4 max-w-2xl">
                        <div class="flex flex-wrap items-center gap-2">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-white/10 hover:bg-white/20 text-slate-300 hover:text-white border border-white/15 text-xs font-bold transition-all shadow-sm">
                                <i class="ph-bold ph-arrow-left text-sky-400"></i>
                                <span>Dashboard</span>
                            </a>
                            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 border border-white/20 text-sky-300 text-xs font-bold uppercase tracking-wider backdrop-blur-md shadow-sm">
                                <i class="ph-fill ph-graduation-cap text-sky-400"></i> Manajemen Kelulusan
                            </div>
                        </div>

                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-black tracking-tight leading-snug sm:leading-snug md:leading-normal text-white">
                            <span class="block text-slate-100">Kelulusan &</span>
                            <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">SKL Digital Terpadu</span>
                        </h1>
                        <p class="text-slate-300 text-sm sm:text-base font-medium leading-relaxed">
                            Kelola status kelulusan siswa tingkat akhir, generate Surat Keterangan Lulus (SKL) otomatis, cetak dokumen PDF, dan publikasikan jadwal pengumuman.
                        </p>

                        {{-- Feature Highlight Chips (Ref 1 & 2) --}}
                        <div class="flex flex-wrap items-center gap-2.5 pt-1">
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Penomoran SKL Otomatis
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Jadwal Publikasi Terjadwal
                            </div>
                            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-xl bg-white/5 border border-white/10 text-slate-200 text-xs font-semibold backdrop-blur-sm shadow-sm">
                                <i class="ph-bold ph-check text-sky-400"></i> Migrasi Status Alumni
                            </div>
                        </div>

                        {{-- GLOBAL ACTIONS (Ref 2 Dual & Multi Button Style) --}}
                        <div class="flex flex-wrap items-center gap-3 pt-2">
                            <!-- Tombol Generate Nomor SKL Massal -->
                            <button onclick="document.getElementById('modalSkl').showModal()" class="group bg-gradient-to-r from-[#0d52a1] via-sky-600 to-[#56bbf1] hover:from-sky-600 hover:to-sky-400 text-white shadow-lg shadow-sky-600/25 border border-white/20 px-5 py-3.5 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 active:scale-95">
                                <i class="ph-bold ph-list-numbers text-lg"></i>
                                <span>Set No. SKL Auto</span>
                                <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                            </button>

                            <!-- Tombol Set Tanggal -->
                            <button onclick="document.getElementById('modalGlobalDate').showModal()" class="bg-white/10 hover:bg-white/20 border border-white/15 hover:border-emerald-400/40 text-white px-4 py-3.5 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-sm backdrop-blur-md active:scale-95">
                                <i class="ph-bold ph-calendar-check text-base text-emerald-400"></i>
                                <span>Set Tanggal</span>
                            </button>
                            
                            <!-- Tombol Import -->
                            <button onclick="document.getElementById('modalImport').showModal()" class="bg-white/10 hover:bg-white/20 border border-white/15 hover:border-amber-400/40 text-white px-4 py-3.5 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-sm backdrop-blur-md active:scale-95">
                                <i class="ph-bold ph-file-csv text-base text-amber-400"></i>
                                <span>Import CSV</span>
                            </button>

                            <!-- Tombol Pengaturan SKL -->
                            <button onclick="document.getElementById('modalSettings').showModal()" class="bg-white/10 hover:bg-white/20 border border-white/15 text-white px-4 py-3.5 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-sm backdrop-blur-md active:scale-95">
                                <i class="ph-bold ph-gear text-base text-slate-300"></i>
                                <span>Pengaturan</span>
                            </button>

                            <!-- Tombol Pindahkan ke Alumni -->
                            <form action="{{ route('admin.graduation.process_alumni') }}" method="POST" id="formProcessAlumni" class="w-full sm:w-auto">
                                @csrf
                                <button type="button" onclick="confirmAlumniProcess()" class="w-full sm:w-auto bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 border border-emerald-400/30 text-white shadow-lg shadow-emerald-600/25 px-5 py-3.5 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 active:scale-95">
                                    <i class="ph-bold ph-users-three text-lg"></i>
                                    <span>Pindahkan ke Alumni</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- KANAN: GLOWING FOCAL SHOWCASE (Ref 1 Glowing Ring & Floating Bubbles) --}}
                    <div class="relative flex items-center justify-center shrink-0 w-full xl:w-auto mt-4 xl:mt-0">
                        {{-- Multi-Layer Luminous Outer Ring --}}
                        <div class="absolute w-56 h-56 rounded-full border border-sky-400/20 bg-sky-500/5 blur-sm pointer-events-none"></div>
                        <div class="absolute w-48 h-48 rounded-full border border-sky-300/30 shadow-[0_0_40px_rgba(86,187,241,0.2)] pointer-events-none"></div>

                        {{-- Floating Micro Bubbles (Ref 1) --}}
                        <div class="absolute -top-2 -right-1 z-20 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-emerald-300 text-[10px] font-bold shadow-lg flex items-center gap-1.5">
                            <i class="ph-bold ph-certificate text-emerald-400"></i>
                            SKL Valid
                        </div>
                        <div class="absolute -bottom-2 -left-2 z-20 px-3 py-1 rounded-full bg-white/10 backdrop-blur-md border border-white/20 text-sky-300 text-[10px] font-bold shadow-lg flex items-center gap-1.5">
                            <i class="ph-bold ph-seal-check text-sky-400"></i>
                            Tingkat Akhir
                        </div>

                        {{-- Core Showcase Card --}}
                        <div class="relative z-10 bg-[#031d3d]/90 backdrop-blur-xl border border-white/20 p-6 sm:p-7 rounded-[2.5rem] shadow-2xl flex flex-col items-center justify-center text-center min-w-[240px] group-hover:border-sky-400/40 transition-all">
                            <div class="w-16 h-16 rounded-2xl bg-white/10 border border-white/20 flex items-center justify-center text-3xl text-sky-400 shadow-inner mb-3">
                                <i class="ph-duotone ph-graduation-cap"></i>
                            </div>
                            <div class="text-3xl font-black text-white leading-none mb-1">{{ $students->total() }}</div>
                            <div class="text-[10px] uppercase font-bold text-slate-400 tracking-wider">Total Siswa Kelas 9</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FILTER & SEARCH BAR --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6 animate-enter relative z-10" style="animation-delay: 100ms;">
            <div class="bg-[#031d3d]/90 backdrop-blur-md p-4 rounded-[1.5rem] shadow-xl border border-white/10 flex flex-col sm:flex-row gap-4 justify-between items-center">
                
                {{-- Filter Kelas --}}
                <form method="GET" class="w-full sm:w-auto flex items-center gap-2">
                    <div class="relative w-full sm:w-64 group">
                        <i class="ph-bold ph-chalkboard-teacher absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sky-400 transition-colors"></i>
                        <select name="class_id" onchange="this.form.submit()" class="w-full pl-11 pr-10 py-3 rounded-xl border border-white/15 bg-[#021124]/80 text-sm font-bold text-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent appearance-none cursor-pointer transition-all shadow-sm outline-none">
                            <option value="" class="bg-[#031d3d] text-white">Semua Kelas 9</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }} class="bg-[#031d3d] text-white">{{ $class->name }}</option>
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
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-sky-400 transition-colors"></i>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Siswa / NISN..." 
                           class="w-full sm:w-72 pl-11 pr-4 py-3 rounded-xl border border-white/15 bg-[#021124]/80 text-sm font-bold text-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent transition-all shadow-sm placeholder:font-medium placeholder:text-slate-500 outline-none">
                </form>
            </div>
        </div>

        {{-- MAIN CONTENT: TABEL SISWA --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-enter relative z-10" style="animation-delay: 200ms;">
            <form action="{{ route('admin.graduation.bulk_update') }}" method="POST" id="bulkForm">
                @csrf
                <div class="bg-[#031d3d]/90 backdrop-blur-md rounded-[2.5rem] shadow-xl border border-white/10 overflow-hidden">
                    
                    {{-- Table Header Bar --}}
                    <div class="px-6 py-5 border-b border-white/10 bg-white/5 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <div class="flex items-center gap-2">
                            <span class="bg-sky-500/20 border border-sky-400/30 text-sky-300 px-4 py-1.5 rounded-xl text-xs font-bold shadow-sm">
                                Total: {{ $students->total() }} Siswa
                            </span>
                        </div>
                        <button type="submit" class="bg-sky-600 hover:bg-sky-500 text-white border border-sky-400/30 px-5 py-2.5 rounded-xl text-xs font-bold shadow-lg shadow-sky-600/20 transition-all flex items-center gap-2 active:scale-95 group">
                            <i class="ph-bold ph-floppy-disk group-hover:scale-110 transition-transform"></i> Simpan Perubahan Masal
                        </button>
                    </div>

                    {{-- Table Body --}}
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-[#021124]/90 backdrop-blur-sm border-b border-white/10 text-xs uppercase font-bold text-sky-300 tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Identitas Siswa</th>
                                    <th class="px-6 py-4">Status Kelulusan</th>
                                    <th class="px-6 py-4 text-center">Nilai Rata-rata</th>
                                    <th class="px-6 py-4">No. SKL</th>
                                    <th class="px-6 py-4 text-center border-l border-white/10">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5 text-sm">
                                @forelse($students as $student)
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-xl bg-white/10 border border-white/15 flex items-center justify-center text-sky-400 font-bold text-xs shrink-0 overflow-hidden shadow-sm group-hover:border-sky-400/40 transition-colors">
                                                @if($student->photo_path)
                                                    <img src="{{ asset('storage/'.$student->photo_path) }}" class="w-full h-full object-cover">
                                                @else
                                                    {{ substr($student->name, 0, 2) }}
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-bold text-white group-hover:text-sky-300 transition-colors">{{ $student->name }}</div>
                                                <div class="text-[11px] font-mono text-slate-400 font-medium mt-0.5 flex items-center gap-1">
                                                    <i class="ph-bold ph-identification-card text-sky-400"></i> {{ $student->student_id }} <span class="mx-1 text-slate-600">•</span> <i class="ph-fill ph-chalkboard text-slate-400"></i> {{ $student->schoolClass->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                        @if($student->status == 'graduated')
                                            <span class="mt-2 inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-500/20 border border-emerald-500/30 text-emerald-400 text-[10px] font-bold rounded-lg shadow-sm">
                                                <i class="ph-fill ph-check-circle"></i> Sudah Alumni
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <select name="students[{{ $student->id }}][status]" class="w-36 py-2 px-3 rounded-xl text-xs font-bold border border-white/15 bg-[#021124]/80 text-white focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent cursor-pointer shadow-sm outline-none transition-all
                                            {{ $student->graduation?->status == 'LULUS' ? '!bg-emerald-950/40 !text-emerald-400 !border-emerald-500/40' : '' }}
                                            {{ $student->graduation?->status == 'TIDAK LULUS' ? '!bg-rose-950/40 !text-rose-400 !border-rose-500/40' : '' }}">
                                            <option value="DITUNDA" {{ ($student->graduation?->status ?? 'DITUNDA') == 'DITUNDA' ? 'selected' : '' }} class="bg-[#031d3d] text-amber-300">⏳ Ditunda</option>
                                            <option value="LULUS" {{ ($student->graduation?->status ?? '') == 'LULUS' ? 'selected' : '' }} class="bg-[#031d3d] text-emerald-400">✅ Lulus</option>
                                            <option value="TIDAK LULUS" {{ ($student->graduation?->status ?? '') == 'TIDAK LULUS' ? 'selected' : '' }} class="bg-[#031d3d] text-rose-400">❌ Tidak Lulus</option>
                                        </select>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.01" name="students[{{ $student->id }}][average_score]" value="{{ $student->graduation?->average_score }}" 
                                            class="w-24 text-center py-2 rounded-xl border border-white/15 text-xs font-bold focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent bg-[#021124]/80 text-white transition-colors shadow-sm outline-none placeholder:text-slate-500" placeholder="0.00">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="students[{{ $student->id }}][skl_number]" value="{{ $student->graduation?->skl_number }}" 
                                            class="w-48 py-2 px-3 rounded-xl border border-white/15 text-xs font-medium focus:ring-4 focus:ring-elevate-accent/20 focus:border-elevate-accent bg-[#021124]/80 text-white transition-colors shadow-sm outline-none placeholder:text-slate-500" placeholder="Kosong = Format Default">
                                        
                                        {{-- Hidden date field untuk menjaga tanggal pengumuman per siswa jika ada --}}
                                        <input type="hidden" name="students[{{ $student->id }}][announcement_date]" value="{{ $student->graduation?->announcement_date }}">
                                    </td>
                                    <td class="px-6 py-4 text-center border-l border-white/10">
                                        <div class="flex items-center justify-center gap-2">
                                            <button type="button" onclick="saveSingle('{{ $student->id }}')" class="w-9 h-9 flex items-center justify-center rounded-xl bg-white/10 hover:bg-sky-500/20 text-slate-300 hover:text-sky-300 border border-white/15 hover:border-sky-400/40 transition-all shadow-sm" title="Simpan Baris Ini">
                                                <i class="ph-bold ph-check text-base"></i>
                                            </button>
                                            {{-- Tombol Cetak PDF --}}
                                            @if($student->graduation?->status == 'LULUS')
                                                <a href="{{ route('graduation.print', $student->id) }}" target="_blank" class="w-9 h-9 flex items-center justify-center rounded-xl bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-400 border border-emerald-500/30 transition-all shadow-sm" title="Cetak SKL">
                                                    <i class="ph-bold ph-printer text-base"></i>
                                                </a>
                                            @endif
                                        </div>
                                        <span id="msg_{{ $student->id }}" class="text-[10px] text-emerald-400 font-bold hidden animate-pulse mt-1 block">Tersimpan!</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-16 text-center text-slate-400">
                                        <div class="flex flex-col items-center justify-center gap-3">
                                            <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center shadow-inner">
                                                <i class="ph-duotone ph-student text-3xl text-sky-400"></i>
                                            </div>
                                            <span class="font-bold text-sm text-white">Tidak ada data siswa kelas 9 ditemukan.</span>
                                            <span class="text-xs text-slate-400">Silakan sesuaikan filter pencarian.</span>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Pagination --}}
                    <div class="px-6 py-4 border-t border-white/10 bg-white/5">
                        {{ $students->links() }}
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================================= --}}
    {{-- MODAL-MODAL DENGAN GAYA ELEVATE DARK GLASSMORPHISM --}}
    {{-- ========================================================= --}}

    {{-- MODAL SET NOMOR SKL AUTO --}}
    <dialog id="modalSkl" class="rounded-[2.5rem] p-0 w-full max-w-md bg-transparent shadow-2xl border border-white/20 backdrop-blur-xl">
        <form action="{{ route('admin.graduation.bulk_skl') }}" method="POST" class="bg-[#031d3d] p-8 rounded-[2.5rem] border border-white/15 text-white">
            @csrf
            <div class="flex items-center gap-3 mb-6 border-b border-white/10 pb-4">
                <div class="w-10 h-10 rounded-xl bg-sky-500/20 text-sky-400 border border-sky-400/30 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-list-numbers"></i>
                </div>
                <h3 class="text-lg font-black text-white">Generate SKL Massal</h3>
            </div>
            
            <div class="p-4 mb-6 rounded-2xl bg-sky-500/10 border border-sky-400/20 flex gap-3 items-start shadow-sm">
                <i class="ph-fill ph-info text-xl text-sky-400 mt-0.5 shrink-0"></i>
                <div class="text-xs text-slate-300 leading-relaxed font-medium">
                    Gunakan tag <b class="text-sky-300 font-mono px-1.5 py-0.5 bg-white/10 border border-white/15 rounded shadow-sm">{urut}</b> agar sistem membuat urutan otomatis (001, 002, dst) ke database.<br><br>
                    Contoh: <br><b class="font-mono text-white">421.3/{urut}/SMP.03/2026</b>
                </div>
            </div>

            @if(request('class_id')) <input type="hidden" name="class_filter" value="{{ request('class_id') }}"> @endif
            
            <div class="mb-5">
                <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-widest mb-2 ml-1">Format Nomor Surat</label>
                <input type="text" name="skl_format" required class="w-full py-3 px-4 rounded-xl border border-white/15 font-bold text-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 bg-[#021124]/80 transition-colors shadow-sm outline-none" placeholder="Cth: 421.3/{urut}/SMP.03/2026">
            </div>

            <div class="mb-8">
                <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-widest mb-2 ml-1">Mulai Dari Urutan Ke</label>
                <input type="number" name="start_number" value="1" min="1" class="w-full py-3 px-4 rounded-xl border border-white/15 font-bold text-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 bg-[#021124]/80 transition-colors shadow-sm outline-none">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-300 bg-white/10 hover:bg-white/20 border border-white/10 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-600 text-white text-xs font-bold hover:bg-sky-500 border border-sky-400/30 shadow-lg shadow-sky-600/20 transition-all flex items-center gap-2 active:scale-95 group">
                    <i class="ph-bold ph-database group-hover:scale-110 transition-transform"></i> Generate
                </button>
            </div>
        </form>
    </dialog>

    {{-- MODAL GLOBAL DATE --}}
    <dialog id="modalGlobalDate" class="rounded-[2.5rem] p-0 w-full max-w-md bg-transparent shadow-2xl border border-white/20 backdrop-blur-xl">
        <form action="{{ route('admin.graduation.set_date') }}" method="POST" class="bg-[#031d3d] p-8 rounded-[2.5rem] border border-white/15 text-white">
            @csrf
            <div class="flex items-center gap-3 mb-6 border-b border-white/10 pb-4">
                <div class="w-10 h-10 rounded-xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-calendar-check"></i>
                </div>
                <h3 class="text-lg font-black text-white">Set Tanggal Pengumuman</h3>
            </div>
            
            <div class="p-4 mb-6 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 flex gap-3 items-start shadow-sm">
                <i class="ph-fill ph-info text-xl text-emerald-400 mt-0.5 shrink-0"></i>
                <div class="text-xs text-slate-300 leading-relaxed font-medium">
                    Mengatur jadwal di sini akan otomatis membuat seluruh siswa di tabel menjadi berstatus <span class="bg-emerald-500/20 text-emerald-400 px-1.5 py-0.5 rounded border border-emerald-500/30 font-bold text-[10px]">LULUS</span>. Jika ada yang tidak lulus, Anda dapat mengubahnya secara manual setelah ini.
                </div>
            </div>

            @if(request('class_id')) <input type="hidden" name="class_filter" value="{{ request('class_id') }}"> @endif
            <div class="mb-8">
                <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-widest mb-2 ml-1">Tanggal & Jam Publikasi</label>
                <input type="datetime-local" name="global_date" required class="w-full py-3 px-4 rounded-xl border border-white/15 font-bold text-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 bg-[#021124]/80 transition-colors shadow-sm cursor-pointer outline-none">
            </div>
            
            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-300 bg-white/10 hover:bg-white/20 border border-white/10 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-500 border border-emerald-500/30 shadow-lg shadow-emerald-600/20 transition-all flex items-center gap-2 active:scale-95 group">
                    <i class="ph-bold ph-floppy-disk group-hover:scale-110 transition-transform"></i> Simpan Jadwal
                </button>
            </div>
        </form>
    </dialog>

    {{-- MODAL IMPORT --}}
    <dialog id="modalImport" class="rounded-[2.5rem] p-0 w-full max-w-md bg-transparent shadow-2xl border border-white/20 backdrop-blur-xl">
        <form action="{{ route('admin.graduation.import') }}" method="POST" enctype="multipart/form-data" class="bg-[#031d3d] p-8 rounded-[2.5rem] border border-white/15 text-white">
            @csrf
            <div class="flex items-center gap-3 mb-4 border-b border-white/10 pb-4">
                <div class="w-10 h-10 rounded-xl bg-amber-500/20 text-amber-400 border border-amber-500/30 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-file-csv"></i>
                </div>
                <h3 class="text-lg font-black text-white">Import Data CSV</h3>
            </div>
            
            <p class="text-xs text-slate-300 mb-6 font-medium bg-white/5 p-3 rounded-2xl border border-white/10">Format Kolom: <b class="text-sky-300">NISN</b>, <b class="text-emerald-400">STATUS</b> (LULUS/TIDAK LULUS), <b class="text-amber-300">NILAI</b></p>
            
            <div class="mb-8">
                <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-widest mb-2 ml-1">Pilih File CSV</label>
                <input type="file" name="file" accept=".csv" required class="w-full text-sm text-slate-300 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-sky-500/20 file:text-sky-300 hover:file:bg-sky-500/30 file:transition-colors file:cursor-pointer border border-white/15 rounded-xl bg-[#021124]/80 p-1.5 shadow-sm outline-none">
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-300 bg-white/10 hover:bg-white/20 border border-white/10 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-amber-500 text-white text-xs font-bold hover:bg-amber-400 border border-amber-400/30 shadow-lg shadow-amber-500/20 transition-all flex items-center gap-2 active:scale-95 group">
                    <i class="ph-bold ph-upload-simple group-hover:-translate-y-1 transition-transform"></i> Upload Data
                </button>
            </div>
        </form>
    </dialog>

    {{-- MODAL PENGATURAN SKL --}}
    <dialog id="modalSettings" class="rounded-[2.5rem] p-0 w-full max-w-md bg-transparent shadow-2xl border border-white/20 backdrop-blur-xl">
        <form action="{{ route('admin.graduation.save_settings') }}" method="POST" class="bg-[#031d3d] p-8 rounded-[2.5rem] border border-white/15 text-white">
            @csrf
            <div class="flex items-center gap-3 mb-6 border-b border-white/10 pb-4">
                <div class="w-10 h-10 rounded-xl bg-white/10 text-sky-400 border border-white/15 flex items-center justify-center text-lg shadow-sm">
                    <i class="ph-bold ph-gear"></i>
                </div>
                <h3 class="text-lg font-black text-white">Pengaturan Cetak SKL</h3>
            </div>
            
            <div class="space-y-5 mb-8">
                <div>
                    <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-widest mb-2 ml-1">Format Default Nomor Surat</label>
                    <input type="text" name="letter_number" value="{{ $settings['letter_number'] ?? '' }}" required class="w-full py-3 px-4 rounded-xl border border-white/15 text-sm font-bold text-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 bg-[#021124]/80 shadow-sm transition-all outline-none" placeholder="Contoh: 421.3/ ... /SMP.03/2026">
                    <p class="text-[10px] text-amber-300 font-medium mt-2 bg-amber-500/10 p-2.5 rounded-xl border border-amber-500/20">
                        *Hanya dipakai sebagai fallback statis jika No. SKL siswa kosong. Untuk auto-increment, gunakan fitur <b>Set No. SKL (Auto)</b>.
                    </p>
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-widest mb-2 ml-1">Nama Kepala Sekolah</label>
                    <input type="text" name="principal_name" value="{{ $settings['principal_name'] ?? '' }}" required class="w-full py-3 px-4 rounded-xl border border-white/15 text-sm font-bold text-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 bg-[#021124]/80 shadow-sm transition-all outline-none" placeholder="Nama beserta gelar">
                </div>
                <div>
                    <label class="block text-[10px] font-bold text-sky-300 uppercase tracking-widest mb-2 ml-1">NIP Kepala Sekolah</label>
                    <input type="text" name="principal_nip" value="{{ $settings['principal_nip'] ?? '' }}" required class="w-full py-3 px-4 rounded-xl border border-white/15 text-sm font-bold text-white focus:border-elevate-accent focus:ring-4 focus:ring-elevate-accent/20 bg-[#021124]/80 shadow-sm transition-all outline-none" placeholder="NIP Kepala Sekolah">
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" onclick="this.closest('dialog').close()" class="px-5 py-2.5 rounded-xl text-xs font-bold text-slate-300 bg-white/10 hover:bg-white/20 border border-white/10 transition-colors">Batal</button>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-sky-600 text-white text-xs font-bold hover:bg-sky-500 border border-sky-400/30 shadow-lg shadow-sky-600/20 transition-all flex items-center gap-2 active:scale-95 group">
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
                    confirmButtonColor: '#0d52a1',
                    customClass: { popup: 'rounded-[2rem] border border-white/15 bg-[#031d3d] text-white shadow-2xl font-sans', confirmButton: 'rounded-xl font-bold px-6 py-2.5 bg-sky-600 text-white' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#e11d48',
                    customClass: { popup: 'rounded-[2rem] border border-white/15 bg-[#031d3d] text-white shadow-2xl font-sans', confirmButton: 'rounded-xl font-bold px-6 py-2.5 bg-rose-600 text-white' }
                });
            @endif
        });

        // SweetAlert untuk konfirmasi pindah Alumni
        function confirmAlumniProcess() {
            Swal.fire({
                title: 'Pindahkan ke Alumni?',
                html: '<span class="text-slate-300">Siswa dengan status <b class="text-emerald-400">LULUS</b> akan dipindahkan menjadi <b>ALUMNI</b>.<br><br><ul class="text-left text-xs text-slate-400 list-disc pl-5 mt-2 font-medium"><li>Akun dikeluarkan dari kelas aktif.</li><li>Login diarahkan ke Dashboard Alumni.</li></ul></span>',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#10b981',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Pindahkan!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: { 
                    popup: 'rounded-[2.5rem] shadow-2xl border border-white/15 bg-[#031d3d] text-white font-sans',
                    confirmButton: 'bg-emerald-600 text-white rounded-xl font-bold px-6 py-3 hover:bg-emerald-500 transition-colors mx-2 shadow-lg shadow-emerald-600/30 border border-emerald-500/30',
                    cancelButton: 'bg-white/10 text-slate-300 rounded-xl font-bold px-6 py-3 hover:bg-white/20 transition-colors mx-2 border border-white/10'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses Data...',
                        text: 'Mohon tunggu sebentar',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); },
                        customClass: { popup: 'rounded-[2rem] border border-white/15 bg-[#031d3d] text-white font-sans' }
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
            btn.innerHTML = '<i class="ph-bold ph-spinner animate-spin text-base text-sky-400"></i>'; 
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
                btn.innerHTML = '<i class="ph-bold ph-warning text-rose-400 text-base"></i>';
                btn.disabled = false;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal Menyimpan',
                    text: 'Terjadi kesalahan saat menghubungi server.',
                    confirmButtonColor: '#e11d48',
                    customClass: { popup: 'rounded-[2rem] font-sans border border-white/15 bg-[#031d3d] text-white shadow-xl', confirmButton: 'rounded-xl font-bold px-6 py-2.5 bg-rose-600 text-white' }
                });
            });
        }
    </script>
</x-app-layout>