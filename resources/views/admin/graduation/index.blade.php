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
                    
                    {{-- Text Content --}}
                    <div class="max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-500/20 border border-blue-400/30 text-blue-200 text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm">
                            <i class="ph-fill ph-graduation-cap"></i> Akademik & Kelulusan
                        </div>
                        <h1 class="text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-white leading-tight">
                            Manajemen Kelulusan
                        </h1>
                        <p class="text-blue-100/80 text-sm md:text-base font-medium leading-relaxed">
                            Kelola status kelulusan siswa, nilai rata-rata, nomor SKL, dan jadwal pengumuman secara terpusat.
                        </p>
                    </div>
                    
                    {{-- Stats Grid (4 Cards) --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 w-full xl:w-auto">
                        {{-- Total Siswa --}}
                        <div class="bg-white/10 backdrop-blur-md px-4 py-4 rounded-2xl border border-white/10 text-center hover:bg-white/15 transition-colors">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-blue-200 block mb-1">Total Siswa</span>
                            <span class="text-2xl font-black text-white">{{ $students->total() }}</span>
                        </div>
                        
                        {{-- Lulus --}}
                        <div class="bg-emerald-500/20 backdrop-blur-md px-4 py-4 rounded-2xl border border-emerald-400/20 text-center hover:bg-emerald-500/30 transition-colors">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-200 block mb-1">Lulus</span>
                            <span class="text-2xl font-black text-emerald-100">{{ \App\Models\Graduation::where('status', 'LULUS')->count() }}</span>
                        </div>

                        {{-- Tidak Lulus --}}
                        <div class="bg-rose-500/20 backdrop-blur-md px-4 py-4 rounded-2xl border border-rose-400/20 text-center hover:bg-rose-500/30 transition-colors">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-rose-200 block mb-1">Tidak Lulus</span>
                            <span class="text-2xl font-black text-rose-100">{{ \App\Models\Graduation::where('status', 'TIDAK LULUS')->count() }}</span>
                        </div>

                        {{-- Ditunda --}}
                        <div class="bg-amber-500/20 backdrop-blur-md px-4 py-4 rounded-2xl border border-amber-400/20 text-center hover:bg-amber-500/30 transition-colors">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-amber-200 block mb-1">Ditunda</span>
                            <span class="text-2xl font-black text-amber-100">{{ \App\Models\Graduation::where('status', 'DITUNDA')->count() }}</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        {{-- Main Content Container --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Messages --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[2rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-emerald-100 rounded-full text-emerald-600"><i class="ph-bold ph-check-circle text-xl"></i></div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-2"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-[2rem] flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3 px-2">
                        <div class="p-2 bg-rose-100 rounded-full text-rose-600"><i class="ph-bold ph-warning-circle text-xl"></i></div>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600 p-2"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            {{-- SECTION: KONTROL PANEL (Jadwal & Aksi) --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                {{-- 1. PENGATURAN JADWAL --}}
                @php
                    $sampleSchedule = \App\Models\Graduation::whereNotNull('announcement_date')->orderBy('updated_at', 'desc')->value('announcement_date');
                    $scheduleCarbon = $sampleSchedule ? \Carbon\Carbon::parse($sampleSchedule) : null;
                    $isSet = $scheduleCarbon != null;
                    $isPast = $isSet && \Carbon\Carbon::now()->greaterThanOrEqualTo($scheduleCarbon);
                @endphp
                <div class="lg:col-span-2 bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden p-8">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-indigo-500 to-purple-600"></div>
                    
                    <div class="flex flex-col md:flex-row gap-8 items-start">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-indigo-100">
                                    <i class="ph-duotone ph-calendar-check"></i>
                                </div>
                                <h3 class="text-lg font-black text-slate-800">Jadwal Pengumuman</h3>
                            </div>
                            
                            <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200">
                                @if($isSet)
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="relative flex h-3 w-3">
                                          <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $isPast ? 'bg-emerald-400' : 'bg-blue-400' }} opacity-75"></span>
                                          <span class="relative inline-flex rounded-full h-3 w-3 {{ $isPast ? 'bg-emerald-500' : 'bg-blue-500' }}"></span>
                                        </span>
                                        <span class="text-xs font-black uppercase tracking-wider {{ $isPast ? 'text-emerald-600' : 'text-blue-600' }}">
                                            {{ $isPast ? 'Sudah Dibuka' : 'Terjadwal' }}
                                        </span>
                                    </div>
                                    <p class="text-slate-600 text-sm font-medium">
                                        Pengumuman diset pada: <br>
                                        <strong class="text-slate-900 text-lg">{{ $scheduleCarbon->isoFormat('D MMMM Y, HH:mm') }} WIB</strong>
                                    </p>
                                @else
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="h-3 w-3 rounded-full bg-amber-400"></span>
                                        <span class="text-xs font-black uppercase tracking-wider text-amber-600">Belum Diatur</span>
                                    </div>
                                    <p class="text-slate-400 text-sm italic">Siswa belum dapat melihat hasil kelulusan.</p>
                                @endif
                            </div>
                        </div>

                        <div class="w-full md:w-1/2">
                            <form action="{{ route('admin.graduation.set_date') }}" method="POST" class="space-y-3">
                                @csrf
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider">Atur Waktu Serentak</label>
                                <div class="relative group">
                                    <i class="ph-bold ph-clock absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors"></i>
                                    <input type="datetime-local" name="global_date" required 
                                           value="{{ $isSet ? $scheduleCarbon->format('Y-m-d\TH:i') : '' }}"
                                           class="block w-full pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-white focus:border-indigo-500 focus:ring-indigo-500 text-sm font-bold text-slate-700 shadow-sm">
                                </div>
                                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/20 transition-all text-sm flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-floppy-disk"></i> Simpan Jadwal
                                </button>
                                <p class="text-[10px] text-slate-400 text-center">*Berlaku untuk semua siswa Kelas 9</p>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- 2. AKSI CEPAT --}}
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden p-8 flex flex-col justify-center gap-4">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                    <h3 class="text-lg font-black text-slate-800 mb-2 flex items-center gap-2">
                        <i class="ph-duotone ph-lightning text-amber-500"></i> Aksi Cepat
                    </h3>
                    
                    <button onclick="openModal('modalGenerate')" class="group w-full py-4 px-6 bg-slate-50 hover:bg-slate-800 border border-slate-200 hover:border-slate-800 rounded-2xl transition-all duration-300 flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-slate-600 group-hover:text-slate-800 transition-colors">
                            <i class="ph-duotone ph-magic-wand text-xl"></i>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm font-black text-slate-700 group-hover:text-white">Auto Generate SKL</span>
                            <span class="block text-[10px] text-slate-400 group-hover:text-slate-400">Buat nomor otomatis</span>
                        </div>
                    </button>

                    <button onclick="openModal('modalImport')" class="group w-full py-4 px-6 bg-emerald-50 hover:bg-emerald-600 border border-emerald-100 hover:border-emerald-600 rounded-2xl transition-all duration-300 flex items-center gap-4">
                        <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-600 transition-colors">
                            <i class="ph-duotone ph-file-csv text-xl"></i>
                        </div>
                        <div class="text-left">
                            <span class="block text-sm font-black text-emerald-800 group-hover:text-white">Import Nilai (CSV)</span>
                            <span class="block text-[10px] text-emerald-600/70 group-hover:text-emerald-100">Upload data massal</span>
                        </div>
                    </button>
                </div>
            </div>

            {{-- SECTION: TABEL DATA SISWA --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative min-h-[600px] flex flex-col">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                {{-- Toolbar --}}
                <div class="p-8 border-b border-slate-50 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl shadow-sm border border-blue-100">
                            <i class="ph-duotone ph-student"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-slate-800">Data Kelulusan</h3>
                            <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Edit data per siswa atau massal</p>
                        </div>
                    </div>

                    <form method="GET" class="flex flex-col sm:flex-row gap-3 w-full md:w-auto">
                        <select name="class_id" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-600 focus:ring-blue-500 focus:border-blue-500 py-2.5">
                            <option value="">-- Semua Kelas --</option>
                            @foreach($classes as $cls)
                                <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>
                        <div class="relative">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" placeholder="Cari Nama / NISN..." value="{{ request('search') }}" 
                                   class="pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 text-sm font-bold text-slate-600 focus:ring-blue-500 focus:border-blue-500 w-full sm:w-64">
                        </div>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2.5 rounded-xl font-bold shadow-lg shadow-blue-500/20 hover:bg-blue-700 transition-all">
                            Filter
                        </button>
                    </form>
                </div>

                {{-- Form Bulk Update --}}
                <form action="{{ route('admin.graduation.bulk_update') }}" method="POST" class="flex-1 flex flex-col">
                    @csrf
                    <div class="overflow-x-auto flex-1 custom-scrollbar">
                        <table class="w-full text-sm text-left text-slate-600">
                            <thead class="text-xs font-bold text-slate-400 uppercase bg-slate-50/80 sticky top-0 backdrop-blur-sm z-10">
                                <tr>
                                    <th class="px-6 py-4">Identitas Siswa</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Nilai Rata-rata</th>
                                    <th class="px-6 py-4 text-center">Nomor SKL</th>
                                    <th class="px-6 py-4 text-center">Waktu Pengumuman</th>
                                    <th class="px-6 py-4 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($students as $student)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    {{-- Kolom Identitas --}}
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 text-slate-500 flex items-center justify-center text-xs font-black border border-slate-200">
                                                {{ substr($student->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-700 text-sm">{{ $student->name }}</div>
                                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                                                    {{ $student->student_id }} <span class="mx-1">•</span> {{ $student->schoolClass->name ?? '-' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- Status Dropdown --}}
                                    <td class="px-6 py-4 text-center">
                                        <select name="students[{{ $student->id }}][status]" id="status_{{ $student->id }}" 
                                                class="text-xs font-bold rounded-lg border-slate-200 bg-white focus:ring-blue-500 focus:border-blue-500 py-1.5 px-2 cursor-pointer shadow-sm
                                                {{ ($student->graduation->status ?? '') == 'LULUS' ? 'text-emerald-600 bg-emerald-50 border-emerald-200' : '' }}
                                                {{ ($student->graduation->status ?? '') == 'TIDAK LULUS' ? 'text-rose-600 bg-rose-50 border-rose-200' : '' }}
                                                {{ ($student->graduation->status ?? '') == 'DITUNDA' ? 'text-amber-600 bg-amber-50 border-amber-200' : '' }}">
                                            <option value="LULUS" {{ ($student->graduation->status ?? '') == 'LULUS' ? 'selected' : '' }}>LULUS</option>
                                            <option value="TIDAK LULUS" {{ ($student->graduation->status ?? '') == 'TIDAK LULUS' ? 'selected' : '' }}>TIDAK LULUS</option>
                                            <option value="DITUNDA" {{ ($student->graduation->status ?? '') == 'DITUNDA' ? 'selected' : '' }}>DITUNDA</option>
                                        </select>
                                    </td>
                                    
                                    {{-- Input Nilai --}}
                                    <td class="px-6 py-4 text-center">
                                        <input type="number" step="0.01" name="students[{{ $student->id }}][average_score]" id="score_{{ $student->id }}" 
                                               value="{{ $student->graduation->average_score ?? 0 }}" 
                                               class="w-20 text-center text-xs font-bold border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white transition-all">
                                    </td>
                                    
                                    {{-- Input No SKL --}}
                                    <td class="px-6 py-4 text-center">
                                        <input type="text" name="students[{{ $student->id }}][skl_number]" id="skl_{{ $student->id }}" 
                                               value="{{ $student->graduation->skl_number ?? '' }}" 
                                               placeholder="Nomor SKL..."
                                               class="w-40 text-xs font-bold border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white transition-all text-center">
                                    </td>
                                    
                                    {{-- Input Waktu Personal --}}
                                    <td class="px-6 py-4 text-center">
                                        <input type="datetime-local" name="students[{{ $student->id }}][announcement_date]" id="date_{{ $student->id }}" 
                                               value="{{ isset($student->graduation->announcement_date) ? \Carbon\Carbon::parse($student->graduation->announcement_date)->format('Y-m-d\TH:i') : '' }}" 
                                               class="w-full text-xs font-medium border-slate-200 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-slate-50 focus:bg-white text-slate-500">
                                    </td>

                                    {{-- Tombol Save Row --}}
                                    <td class="px-6 py-4 text-center">
                                        <div class="relative flex items-center justify-center gap-2">
                                            <button type="button" onclick="saveRow({{ $student->id }})" 
                                                    class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-100 transition-all flex items-center justify-center shadow-sm" title="Simpan Baris Ini">
                                                <i class="ph-bold ph-floppy-disk"></i>
                                            </button>
                                            <span id="msg_{{ $student->id }}" class="hidden absolute top-full mt-1 left-1/2 -translate-x-1/2 text-[10px] font-black text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded shadow-sm whitespace-nowrap z-20">
                                                Tersimpan!
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Sticky Footer Pagination & Save All --}}
                    <div class="p-6 border-t border-slate-100 bg-white flex flex-col md:flex-row justify-between items-center gap-4 sticky bottom-0 z-20 shadow-[0_-5px_20px_rgba(0,0,0,0.02)]">
                        <div class="w-full md:w-auto">
                            {{ $students->links() }}
                        </div>
                        <button type="submit" class="w-full md:w-auto py-3 px-8 bg-blue-900 text-white font-bold rounded-2xl hover:bg-blue-800 transition-all shadow-lg shadow-blue-900/20 flex items-center justify-center gap-2 transform active:scale-[0.98]">
                            <i class="ph-bold ph-floppy-disk-back"></i>
                            Simpan Perubahan (Massal)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- MODAL 1: IMPORT CSV --}}
    <div id="modalImport" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('modalImport')"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all border border-slate-100">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-500 to-teal-500"></div>
                
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                            <i class="ph-duotone ph-file-csv"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Import Nilai</h3>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Format CSV: NISN, Status, Nilai</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.graduation.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="bg-slate-50 p-6 rounded-2xl border border-slate-200 border-dashed text-center hover:bg-white hover:border-emerald-400 transition-colors group">
                            <i class="ph-duotone ph-upload-simple text-4xl text-slate-300 group-hover:text-emerald-500 mb-2 transition-colors"></i>
                            <div class="space-y-2">
                                <label class="block text-sm font-bold text-slate-600 cursor-pointer hover:text-emerald-600">
                                    <span>Pilih File CSV</span>
                                    <input type="file" name="file" accept=".csv, .txt" class="hidden">
                                </label>
                                <p class="text-xs text-slate-400">atau drag & drop file ke sini</p>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <a href="{{ route('admin.graduation.template') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 flex items-center gap-1">
                                <i class="ph-bold ph-download-simple"></i> Download Template
                            </a>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" onclick="closeModal('modalImport')" class="flex-1 py-3 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-3 bg-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 hover:bg-emerald-700 transition-colors">
                                Import Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL 2: AUTO GENERATE SKL --}}
    <div id="modalGenerate" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" onclick="closeModal('modalGenerate')"></div>
        
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="relative bg-white rounded-[2.5rem] shadow-2xl max-w-lg w-full overflow-hidden transform transition-all border border-slate-100">
                <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-600 to-indigo-600"></div>
                
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm">
                            <i class="ph-duotone ph-magic-wand"></i>
                        </div>
                        <div>
                            <h3 class="text-xl font-black text-slate-800">Auto Generate SKL</h3>
                            <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Otomatisasi Nomor Surat</p>
                        </div>
                    </div>

                    <form action="{{ route('admin.graduation.auto_generate') }}" method="POST" class="space-y-5">
                        @csrf
                        <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-sm text-blue-800 font-medium">
                            <p>Sistem akan membuat nomor SKL berurutan berdasarkan abjad nama siswa.</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Format Nomor</label>
                            <input type="text" name="format" value="421.3/{no}/SMP.03/{year}" class="w-full px-4 py-3 rounded-xl border-slate-200 bg-slate-50 font-mono text-sm text-slate-700 font-bold focus:ring-blue-500 focus:border-blue-500" required>
                            <p class="text-[10px] text-slate-400 mt-1 ml-1">Variabel: <code class="bg-slate-100 px-1 rounded text-slate-600">{no}</code> (Urut), <code class="bg-slate-100 px-1 rounded text-slate-600">{year}</code> (Tahun)</p>
                        </div>

                        <div>
                            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Nomor Mulai</label>
                            <input type="number" name="start_number" value="1" class="w-24 px-4 py-3 rounded-xl border-slate-200 bg-slate-50 font-bold text-slate-700 focus:ring-blue-500 focus:border-blue-500" required>
                        </div>

                        <div class="flex gap-3 pt-4">
                            <button type="button" onclick="closeModal('modalGenerate')" class="flex-1 py-3 bg-white text-slate-600 font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 py-3 bg-blue-900 text-white font-bold rounded-xl shadow-lg shadow-blue-900/30 hover:bg-blue-800 transition-colors">
                                Generate
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.remove('hidden');
        }
        function closeModal(id) {
            document.getElementById(id).classList.add('hidden');
        }

        // FUNGSI SIMPAN AJAX PER BARIS
        function saveRow(studentId) {
            const status = document.getElementById('status_' + studentId).value;
            const score = document.getElementById('score_' + studentId).value;
            const skl = document.getElementById('skl_' + studentId).value;
            const date = document.getElementById('date_' + studentId).value;
            
            // Efek Loading pada tombol
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