<x-app-layout>
    {{-- X-DATA DIPERBARUI: Menambahkan state 'students' dan fungsi fetchStudents --}}
    <div class="py-10" 
         x-data="{ 
            inputMode: 'subject', 
            importMode: false,
            students: [],
            isLoadingStudents: false,

            fetchStudents(classId) {
                if(!classId) { this.students = []; return; }
                this.isLoadingStudents = true;
                // Fetch ke route yang sudah kita buat di web.php
                fetch(`{{ url('/grades/students') }}/${classId}`)
                    .then(res => res.json())
                    .then(data => {
                        this.students = data;
                        this.isLoadingStudents = false;
                    })
                    .catch(err => {
                        console.error(err);
                        this.isLoadingStudents = false;
                    });
            }
         }"> 
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Header --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-violet-100 text-violet-600 mb-4 shadow-sm animate-bounce-slow">
                    <i class="ph-duotone ph-exam text-4xl"></i>
                </div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight">Akademik & E-Rapor</h1>
                <p class="text-slate-500 mt-2 text-lg">Kelola nilai dan cetak hasil belajar siswa.</p>
            </div>

            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
                
                {{-- CARD 1: INPUT NILAI --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-violet-500/5 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-violet-500 to-fuchsia-500"></div>
                    
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-violet-50 text-violet-600 flex items-center justify-center text-2xl">
                                    <i class="ph-duotone ph-pencil-simple-line"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-bold text-slate-800">Input Nilai</h2>
                                    <p class="text-sm text-slate-400">Entri data nilai siswa.</p>
                                </div>
                            </div>
                        </div>

                        {{-- TABS SWITCHER --}}
                        <div class="bg-slate-100 p-1 rounded-xl flex mb-6">
                            <button @click="inputMode = 'subject'; importMode = false" 
                                    :class="inputMode === 'subject' ? 'bg-white text-violet-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="flex-1 py-2 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-books"></i> Per Mapel
                            </button>
                            <button @click="inputMode = 'student'; importMode = false" 
                                    :class="inputMode === 'student' ? 'bg-white text-violet-600 shadow-sm' : 'text-slate-500 hover:text-slate-700'"
                                    class="flex-1 py-2 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2">
                                <i class="ph-bold ph-student"></i> Per Siswa
                            </button>
                        </div>

                        {{-- =================================================== --}}
                        {{-- AREA 1: PER MAPEL --}}
                        {{-- =================================================== --}}
                        <div x-show="inputMode === 'subject'" x-transition>
                            
                            {{-- SUB-SWITCHER: Manual vs Excel --}}
                            <div class="flex items-center justify-between mb-4 px-1">
                                <span class="text-xs font-bold uppercase text-slate-400">Metode Input:</span>
                                <div class="flex bg-violet-50 rounded-lg p-0.5 border border-violet-100">
                                    <button @click="importMode = false" 
                                            :class="!importMode ? 'bg-white text-violet-700 shadow-sm' : 'text-violet-400 hover:text-violet-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">Manual</button>
                                    <button @click="importMode = true" 
                                            :class="importMode ? 'bg-white text-emerald-700 shadow-sm' : 'text-violet-400 hover:text-violet-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all flex items-center gap-1">
                                            <i class="ph-bold ph-microsoft-excel-logo"></i> Excel
                                    </button>
                                </div>
                            </div>

                            {{-- FORM 1A: MANUAL --}}
                            <form x-show="!importMode" action="{{ route('grades.create') }}" method="GET" class="flex-1 flex flex-col">
                                <div class="space-y-4 flex-1">
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-violet-200 transition">
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Kelas</label>
                                        <select name="class_id" class="w-full rounded-lg border-slate-200 text-sm font-bold text-slate-700 focus:ring-violet-500 cursor-pointer" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-violet-200 transition">
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Mata Pelajaran</label>
                                        <select name="subject_id" class="w-full rounded-lg border-slate-200 text-sm font-bold text-slate-700 focus:ring-violet-500 cursor-pointer" required>
                                            <option value="">-- Pilih Mapel --</option>
                                            @foreach($subjects as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Tahun</label>
                                            <select name="academic_year" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                                @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Semester</label>
                                            <select name="semester" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                                <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil (1)</option>
                                                <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap (2)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-6 py-3 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 transition flex items-center justify-center gap-2 shadow-lg shadow-violet-500/20">
                                    <span>Input Manual</span> <i class="ph-bold ph-arrow-right"></i>
                                </button>
                            </form>

                            {{-- FORM 1B: IMPORT EXCEL --}}
                            <form x-show="importMode" action="{{ route('grades.import') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col" style="display: none;">
                                @csrf
                                <div class="space-y-4 flex-1">
                                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 flex gap-3 items-start">
                                        <i class="ph-fill ph-file-xls text-emerald-500 mt-0.5 text-xl"></i>
                                        <div>
                                            <p class="text-xs text-emerald-800 leading-relaxed font-bold mb-1">Import Excel (Per Mapel)</p>
                                            <a href="{{ route('grades.template') }}" class="text-[10px] font-bold text-emerald-700 underline block">Download Template Mapel</a>
                                        </div>
                                    </div>
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-emerald-200 transition">
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Konfigurasi</label>
                                        <div class="grid grid-cols-2 gap-3 mb-3">
                                            <select name="class_id" class="w-full rounded-lg border-slate-200 text-xs font-bold text-slate-700" required>
                                                <option value="">- Kelas -</option>
                                                @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                            </select>
                                            <select name="subject_id" class="w-full rounded-lg border-slate-200 text-xs font-bold text-slate-700" required>
                                                <option value="">- Mapel -</option>
                                                @foreach($subjects as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <select name="academic_year" class="w-full rounded-lg border-slate-200 text-[10px] font-bold bg-white">
                                                @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                            </select>
                                            <select name="semester" class="w-full rounded-lg border-slate-200 text-[10px] font-bold bg-white">
                                                <option value="1">Ganjil</option>
                                                <option value="2">Genap</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="bg-white p-4 rounded-xl border-2 border-dashed border-slate-300 hover:border-emerald-400 hover:bg-emerald-50/30 transition text-center group cursor-pointer relative">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <i class="ph-duotone ph-upload-simple text-3xl text-slate-300 group-hover:text-emerald-500 mb-2 transition-colors"></i>
                                        <p class="text-xs text-slate-500 font-bold group-hover:text-emerald-600">Pilih File Excel Mapel</p>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                                    <i class="ph-bold ph-upload"></i> <span>Upload Mapel</span>
                                </button>
                            </form>
                        </div>

                        {{-- =================================================== --}}
                        {{-- AREA 2: PER SISWA --}}
                        {{-- =================================================== --}}
                        <div x-show="inputMode === 'student'" x-transition style="display: none;">
                            
                            {{-- SUB-SWITCHER: Manual vs Excel (Sama seperti Mapel) --}}
                            <div class="flex items-center justify-between mb-4 px-1">
                                <span class="text-xs font-bold uppercase text-slate-400">Metode Input:</span>
                                <div class="flex bg-fuchsia-50 rounded-lg p-0.5 border border-fuchsia-100">
                                    <button @click="importMode = false" 
                                            :class="!importMode ? 'bg-white text-fuchsia-700 shadow-sm' : 'text-fuchsia-400 hover:text-fuchsia-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">Manual</button>
                                    <button @click="importMode = true" 
                                            :class="importMode ? 'bg-white text-emerald-700 shadow-sm' : 'text-fuchsia-400 hover:text-fuchsia-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all flex items-center gap-1">
                                            <i class="ph-bold ph-microsoft-excel-logo"></i> Excel
                                    </button>
                                </div>
                            </div>

                            {{-- FORM 2A: MANUAL --}}
                            <form x-show="!importMode" action="{{ route('grades.create_by_student') }}" method="GET" class="flex-1 flex flex-col">
                                <div class="bg-fuchsia-50 border border-fuchsia-100 rounded-xl p-3 mb-4 flex gap-3 items-start">
                                    <i class="ph-fill ph-info text-fuchsia-500 mt-0.5"></i>
                                    <p class="text-xs text-fuchsia-800 leading-relaxed">
                                        Pilih <strong>Kelas</strong> terlebih dahulu. Daftar Siswa muncul di halaman berikutnya.
                                    </p>
                                </div>

                                <div class="space-y-4 flex-1">
                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-fuchsia-200 transition">
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Kelas</label>
                                        <select name="class_id" class="w-full rounded-lg border-slate-200 text-sm font-bold text-slate-700 focus:ring-fuchsia-500 cursor-pointer" required>
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Tahun</label>
                                            <select name="academic_year" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                                @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Semester</label>
                                            <select name="semester" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                                <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil (1)</option>
                                                <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap (2)</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-6 py-3 bg-fuchsia-600 text-white font-bold rounded-xl hover:bg-fuchsia-700 transition flex items-center justify-center gap-2 shadow-lg shadow-fuchsia-500/20">
                                    <span>Lanjut Pilih Siswa</span> <i class="ph-bold ph-user-list"></i>
                                </button>
                            </form>

                            {{-- FORM 2B: IMPORT EXCEL PER SISWA (BARU) --}}
                            <form x-show="importMode" action="{{ route('grades.import_student') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col" style="display: none;">
                                @csrf
                                <div class="space-y-4 flex-1">
                                    <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-3 flex gap-3 items-start">
                                        <i class="ph-fill ph-file-xls text-emerald-500 mt-0.5 text-xl"></i>
                                        <div>
                                            <p class="text-xs text-emerald-800 leading-relaxed font-bold mb-1">Import Excel (Per Siswa)</p>
                                            <a href="{{ route('grades.template_student') }}" class="text-[10px] font-bold text-emerald-700 underline block">Download Template Siswa</a>
                                        </div>
                                    </div>

                                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-emerald-200 transition">
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Pilih Siswa</label>
                                        
                                        {{-- SELECT KELAS (Memicu AJAX) --}}
                                        <div class="mb-3">
                                            <select name="class_id" 
                                                    @change="fetchStudents($el.value)"
                                                    class="w-full rounded-lg border-slate-200 text-xs font-bold text-slate-700 mb-1" required>
                                                <option value="">- Pilih Kelas Dulu -</option>
                                                @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                            </select>
                                        </div>

                                        {{-- SELECT SISWA (Hasil AJAX) --}}
                                        <div class="mb-3 relative">
                                            <select name="student_id" 
                                                    :disabled="students.length === 0"
                                                    class="w-full rounded-lg border-slate-200 text-xs font-bold text-slate-700 disabled:bg-slate-100 disabled:text-slate-400" required>
                                                <option value="">- Pilih Siswa -</option>
                                                <template x-for="student in students" :key="student.id">
                                                    <option :value="student.id" x-text="student.name + ' (' + student.student_id + ')'"></option>
                                                </template>
                                            </select>
                                            {{-- Loading Indicator --}}
                                            <div x-show="isLoadingStudents" class="absolute right-8 top-1/2 -translate-y-1/2">
                                                <i class="ph-bold ph-spinner animate-spin text-emerald-500"></i>
                                            </div>
                                        </div>

                                        <div class="grid grid-cols-2 gap-3">
                                            <select name="academic_year" class="w-full rounded-lg border-slate-200 text-[10px] font-bold bg-white">
                                                @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                            </select>
                                            <select name="semester" class="w-full rounded-lg border-slate-200 text-[10px] font-bold bg-white">
                                                <option value="1">Ganjil</option>
                                                <option value="2">Genap</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="bg-white p-4 rounded-xl border-2 border-dashed border-slate-300 hover:border-emerald-400 hover:bg-emerald-50/30 transition text-center group cursor-pointer relative">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <i class="ph-duotone ph-upload-simple text-3xl text-slate-300 group-hover:text-emerald-500 mb-2 transition-colors"></i>
                                        <p class="text-xs text-slate-500 font-bold group-hover:text-emerald-600">Pilih File Excel Siswa</p>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/20">
                                    <i class="ph-bold ph-upload"></i> <span>Upload Siswa</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: CETAK RAPOR (TETAP SAMA) --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-blue-500/5 border border-slate-100 overflow-hidden relative group hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-blue-500 to-cyan-500"></div>
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">
                                <i class="ph-duotone ph-printer"></i>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-slate-800">Cetak E-Rapor</h2>
                                <p class="text-sm text-slate-400">Lihat hasil & cetak dokumen.</p>
                            </div>
                        </div>

                        <form action="{{ route('grades.list') }}" method="GET" class="flex-1 flex flex-col">
                            <div class="space-y-4 flex-1">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100 focus-within:ring-2 ring-blue-200 transition">
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-2 block">Kelas</label>
                                    <select name="class_id" class="w-full rounded-lg border-slate-200 text-sm font-bold text-slate-700 focus:ring-blue-500 cursor-pointer" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                </div>
                                
                                <div class="bg-blue-50/50 rounded-xl border border-blue-100 p-4 flex items-center gap-3">
                                    <i class="ph-fill ph-info text-blue-400"></i>
                                    <span class="text-xs text-blue-600 font-medium leading-tight">Pilih kelas di atas untuk melihat daftar siswa yang siap dicetak rapornya.</span>
                                </div>

                                <div class="grid grid-cols-2 gap-4 mt-auto">
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Tahun</label>
                                        <select name="academic_year" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                            @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-xs font-bold text-slate-400 uppercase mb-1 block">Semester</label>
                                        <select name="semester" class="w-full rounded-lg border-slate-200 text-xs font-bold bg-slate-50">
                                            <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil (1)</option>
                                            <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap (2)</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="w-full mt-6 py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
                                <i class="ph-bold ph-list-magnifying-glass"></i> <span>Lihat Siswa</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>