<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-[#2c3f61] leading-tight">
            {{ __('Akademik & E-Rapor') }}
        </h2>
    </x-slot>

    {{-- X-DATA DIPERBARUI DENGAN FITUR PREVIEW EXCEL --}}
    <div class="py-8 sm:py-10 font-sans text-[#2c3f61]" 
         x-data="{ 
            inputMode: 'subject', 
            importMode: false,
            students: [],
            isLoadingStudents: false,
            
            // --- STATE UNTUK PREVIEW EXCEL ---
            previewModal: false,
            previewHeaders: [],
            previewRows: [],
            activeForm: null,
            fileName: '',
            totalDataRows: 0,
            isSubmittingImport: false,

            fetchStudents(classId) {
                if(!classId) { this.students = []; return; }
                this.isLoadingStudents = true;
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
            },

            // --- FUNGSI PREVIEW EXCEL ---
            previewFile(e, formElement) {
                const fileInput = formElement.querySelector('input[type=\'file\']');
                if (!fileInput || !fileInput.files.length) {
                    formElement.submit(); return;
                }
                
                const file = fileInput.files[0];
                this.fileName = file.name;
                this.activeForm = formElement;
                
                const reader = new FileReader();
                reader.onload = (event) => {
                    const data = new Uint8Array(event.target.result);
                    const workbook = XLSX.read(data, {type: 'array'});
                    const firstSheetName = workbook.SheetNames[0];
                    const worksheet = workbook.Sheets[firstSheetName];
                    
                    // Convert to JSON (Array of Arrays format to preserve headers)
                    const json = XLSX.utils.sheet_to_json(worksheet, {header: 1, defval: '-'});
                    
                    if(json.length > 0) {
                        this.previewHeaders = json[0];
                        // Ambil maksimal 5 baris pertama untuk preview
                        this.previewRows = json.slice(1, 6); 
                        this.totalDataRows = json.length - 1;
                        this.previewModal = true;
                    } else {
                        Swal.fire('Error', 'File Excel kosong atau format tidak sesuai', 'error');
                    }
                };
                reader.readAsArrayBuffer(file);
            },

            submitImport() {
                if(this.activeForm) {
                    this.isSubmittingImport = true;
                    this.activeForm.submit();
                }
            }
         }"> 
         
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            
            {{-- 1. HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-gradient-to-r from-[#56bbf1] via-[#e5eff5] to-[#f4d1c0] p-8 mb-10 text-[#2c3f61] shadow-xl shadow-[#56bbf1]/10 overflow-hidden border border-white/60">
                {{-- Abstract Shapes Ornaments --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-[#0d52a1]/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-[#f9a282]/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>

                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-4xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3">
                            Akademik & E-Rapor
                        </h1>
                        <p class="text-[#2c3f61]/80 text-sm font-medium leading-relaxed max-w-lg">
                            Pusat pengelolaan nilai siswa, rapor semester, dan arsip akademik sekolah secara terpadu.
                        </p>
                    </div>
                    
                    <div class="flex gap-3">
                        <div class="bg-white/70 backdrop-blur-md px-5 py-3 rounded-2xl border border-white text-center shadow-sm">
                            <span class="block text-2xl font-black text-[#2c3f61]">{{ count($classes) }}</span>
                            <span class="text-[10px] uppercase font-bold text-[#2c3f61]/60 tracking-wider">Kelas</span>
                        </div>
                        <div class="bg-white/70 backdrop-blur-md px-5 py-3 rounded-2xl border border-white text-center shadow-sm">
                            <span class="block text-2xl font-black text-[#2c3f61]">{{ count($subjects) }}</span>
                            <span class="text-[10px] uppercase font-bold text-[#2c3f61]/60 tracking-wider">Mapel</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ALERT NOTIFIKASI --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-100 p-1 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                        <span class="font-bold">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-rose-100 p-1 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                {{-- CARD 1: INPUT NILAI --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-[#2c3f61]/5 border border-slate-100 overflow-hidden relative group transition-all duration-300 flex flex-col h-full hover:border-[#56bbf1]/50">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#56bbf1] to-[#0d52a1]"></div>
                    
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-[#56bbf1]/10 text-[#0d52a1] flex items-center justify-center text-3xl shadow-sm">
                                    <i class="ph-duotone ph-pencil-simple-line"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-[#2c3f61]">Input Nilai</h2>
                                    <p class="text-sm font-medium text-slate-400">Entri data nilai harian & ujian.</p>
                                </div>
                            </div>
                        </div>

                        {{-- TABS SWITCHER --}}
                        <div class="bg-[#e5eff5]/50 p-1.5 rounded-xl flex mb-6 relative gap-1 overflow-x-auto custom-scrollbar">
                            <button @click="inputMode = 'subject'; importMode = false" 
                                    :class="inputMode === 'subject' ? 'bg-white text-[#0d52a1] shadow-sm border border-white' : 'text-[#2c3f61]/60 hover:text-[#2c3f61]'"
                                    class="flex-1 min-w-[100px] py-2.5 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 relative z-10 whitespace-nowrap">
                                <i class="ph-bold ph-books"></i> Per Mapel
                            </button>
                            <button @click="inputMode = 'student'; importMode = false" 
                                    :class="inputMode === 'student' ? 'bg-white text-[#0d52a1] shadow-sm border border-white' : 'text-[#2c3f61]/60 hover:text-[#2c3f61]'"
                                    class="flex-1 min-w-[100px] py-2.5 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 relative z-10 whitespace-nowrap">
                                <i class="ph-bold ph-student"></i> Per Siswa
                            </button>
                            <button @click="inputMode = 'leger'; importMode = true" 
                                    :class="inputMode === 'leger' ? 'bg-white text-[#0d52a1] shadow-sm border border-white' : 'text-[#2c3f61]/60 hover:text-[#2c3f61]'"
                                    class="flex-1 min-w-[120px] py-2.5 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 relative z-10 whitespace-nowrap">
                                <i class="ph-bold ph-microsoft-excel-logo text-[#0d52a1]"></i> Leger Kelas
                            </button>
                        </div>

                        {{-- AREA 1: PER MAPEL --}}
                        <div x-show="inputMode === 'subject'" x-transition>
                            <div class="flex items-center justify-between mb-4 px-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-[#2c3f61]/40">Metode Input</span>
                                <div class="flex bg-[#e5eff5]/50 rounded-lg p-1 border border-slate-100">
                                    <button @click="importMode = false" 
                                            :class="!importMode ? 'bg-white text-[#2c3f61] shadow-sm border border-white' : 'text-slate-400 hover:text-slate-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">Manual</button>
                                    <button @click="importMode = true" 
                                            :class="importMode ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-400 hover:text-slate-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all flex items-center gap-1">
                                            <i class="ph-bold ph-microsoft-excel-logo"></i> Excel
                                    </button>
                                </div>
                            </div>

                            {{-- FORM 1A: MANUAL --}}
                            <form x-show="!importMode" action="{{ route('grades.create') }}" method="GET" class="flex-1 flex flex-col gap-4">
                                <div class="bg-[#e5eff5]/30 p-1.5 rounded-2xl border border-slate-100 space-y-2">
                                    <select name="class_id" class="w-full rounded-xl border-transparent bg-white text-sm font-bold text-[#2c3f61] focus:ring-[#56bbf1] focus:border-[#56bbf1] py-3" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                    <select name="subject_id" class="w-full rounded-xl border-transparent bg-white text-sm font-bold text-[#2c3f61] focus:ring-[#56bbf1] focus:border-[#56bbf1] py-3" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($subjects as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                                    </select>
                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="academic_year" class="w-full rounded-xl border-transparent bg-white text-xs font-bold text-slate-600 py-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                            @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border-transparent bg-white text-xs font-bold text-slate-600 py-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                            <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-2 py-3.5 bg-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] transition flex items-center justify-center gap-2 shadow-lg shadow-[#2c3f61]/20 group">
                                    <span>Mulai Input</span> <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </form>

                            {{-- FORM 1B: IMPORT EXCEL (PERBAIKAN @submit.prevent & TAHUN/SEMESTER) --}}
                            <form x-show="importMode" @submit.prevent="previewFile($event, $el)" action="{{ route('grades.import') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-4" style="display: none;">
                                @csrf
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 flex gap-4 items-center">
                                    <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg"><i class="ph-fill ph-file-xls text-xl"></i></div>
                                    <div>
                                        <p class="text-xs text-emerald-800 font-bold">Import Nilai (Per Mapel)</p>
                                        <a href="{{ route('grades.template') }}" class="text-[10px] font-bold text-emerald-600 underline hover:text-emerald-800">Download Template Excel</a>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="class_id" class="w-full rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Kelas -</option>
                                            @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                        </select>
                                        <select name="subject_id" class="w-full rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Mapel -</option>
                                            @foreach($subjects as $s) <option value="{{ $s->id }}">{{ $s->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="academic_year" class="w-full rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Tahun Ajaran -</option>
                                            @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Semester -</option>
                                            <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>

                                    <div class="relative group">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-6 text-center group-hover:border-emerald-400 group-hover:bg-emerald-50/10 transition-all">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-300 group-hover:text-emerald-500 mb-2"></i>
                                            <p class="text-xs font-bold text-slate-500 group-hover:text-emerald-600">Klik untuk upload file Excel</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-upload"></i> Upload Data
                                </button>
                            </form>
                        </div>

                        {{-- AREA 2: PER SISWA --}}
                        <div x-show="inputMode === 'student'" x-transition style="display: none;">
                            <div class="flex items-center justify-between mb-4 px-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-[#2c3f61]/40">Metode Input</span>
                                <div class="flex bg-[#e5eff5]/50 rounded-lg p-1 border border-slate-100">
                                    <button @click="importMode = false" 
                                            :class="!importMode ? 'bg-white text-[#2c3f61] shadow-sm border border-white' : 'text-slate-400 hover:text-slate-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">Manual</button>
                                    <button @click="importMode = true" 
                                            :class="importMode ? 'bg-emerald-50 text-emerald-700 shadow-sm border border-emerald-100' : 'text-slate-400 hover:text-slate-600'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all flex items-center gap-1">
                                            <i class="ph-bold ph-microsoft-excel-logo"></i> Excel
                                    </button>
                                </div>
                            </div>

                            {{-- FORM 2A: MANUAL --}}
                            <form x-show="!importMode" action="{{ route('grades.create_by_student') }}" method="GET" class="flex-1 flex flex-col gap-4">
                                <div class="bg-[#56bbf1]/10 border border-[#56bbf1]/20 rounded-xl p-3 flex gap-3 items-start">
                                    <i class="ph-fill ph-info text-[#0d52a1] mt-0.5"></i>
                                    <p class="text-xs text-[#2c3f61] leading-relaxed font-medium">
                                        Pilih <strong>Kelas</strong> terlebih dahulu. Daftar Siswa akan muncul di halaman berikutnya.
                                    </p>
                                </div>
                                <div class="bg-[#e5eff5]/30 p-1.5 rounded-2xl border border-slate-100 space-y-2">
                                    <select name="class_id" class="w-full rounded-xl border-transparent bg-white text-sm font-bold text-[#2c3f61] focus:ring-[#56bbf1] focus:border-[#56bbf1] py-3" required>
                                        <option value="">-- Pilih Kelas --</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="academic_year" class="w-full rounded-xl border-transparent bg-white text-xs font-bold text-slate-600 py-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                            @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border-transparent bg-white text-xs font-bold text-slate-600 py-3 focus:ring-[#56bbf1] focus:border-[#56bbf1]">
                                            <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-2 py-3.5 bg-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] transition flex items-center justify-center gap-2 shadow-lg shadow-[#2c3f61]/20">
                                    <span>Lanjut Pilih Siswa</span> <i class="ph-bold ph-user-list"></i>
                                </button>
                            </form>

                            {{-- FORM 2B: IMPORT SISWA (PERBAIKAN @submit.prevent & TAHUN/SEMESTER) --}}
                            <form x-show="importMode" @submit.prevent="previewFile($event, $el)" action="{{ route('grades.import_student') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-4" style="display: none;">
                                @csrf
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 flex gap-4 items-center">
                                    <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg"><i class="ph-fill ph-file-xls text-xl"></i></div>
                                    <div>
                                        <p class="text-xs text-emerald-800 font-bold">Import Nilai (Per Siswa)</p>
                                        <a href="{{ route('grades.template_student') }}" class="text-[10px] font-bold text-emerald-600 underline hover:text-emerald-800">Download Template Siswa</a>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <select name="class_id" @change="fetchStudents($el.value)" class="w-full rounded-xl border-slate-200 text-sm font-bold text-[#2c3f61] focus:ring-emerald-500 focus:border-emerald-500" required>
                                        <option value="">- Pilih Kelas Dulu -</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                    <div class="relative">
                                        <select name="student_id" :disabled="students.length === 0" class="w-full rounded-xl border-slate-200 text-sm font-bold text-[#2c3f61] disabled:bg-slate-100 disabled:text-slate-400 focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Pilih Siswa -</option>
                                            <template x-for="student in students" :key="student.id">
                                                <option :value="student.id" x-text="student.name + ' (' + student.student_id + ')'"></option>
                                            </template>
                                        </select>
                                        <div x-show="isLoadingStudents" class="absolute right-8 top-1/2 -translate-y-1/2 text-emerald-500"><i class="ph-bold ph-spinner animate-spin"></i></div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="academic_year" class="w-full rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Tahun Ajaran -</option>
                                            @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Semester -</option>
                                            <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>

                                    <div class="relative group">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-6 text-center group-hover:border-emerald-400 group-hover:bg-emerald-50/10 transition-all">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-300 group-hover:text-emerald-500 mb-2"></i>
                                            <p class="text-xs font-bold text-slate-500 group-hover:text-emerald-600">Pilih File Excel Siswa</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-upload"></i> Upload Data
                                </button>
                            </form>
                        </div>

                        {{-- AREA 3: LEGER KELAS --}}
                        <div x-show="inputMode === 'leger'" x-transition style="display: none;">
                            
                            {{-- Form Khusus Download Template Leger --}}
                            <form action="{{ route('grades.template_leger') }}" method="GET" class="mb-4">
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-2xl p-4 flex gap-4 items-center">
                                    <div class="bg-emerald-100 text-emerald-600 p-2 rounded-lg"><i class="ph-fill ph-microsoft-excel-logo text-xl"></i></div>
                                    <div class="flex-1">
                                        <p class="text-xs text-emerald-800 font-bold">Format Leger Kelas</p>
                                        <p class="text-[10px] text-emerald-600 mt-0.5">Semua siswa & semua mapel dalam 1 file (Sesuai foto).</p>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2 mt-3">
                                    <select name="class_id" class="flex-1 rounded-xl border-slate-200 text-xs font-bold focus:ring-emerald-500 focus:border-emerald-500" required>
                                        <option value="">- Pilih Kelas -</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                    </select>
                                    <button type="submit" class="px-4 py-2 bg-[#2c3f61] text-white text-xs font-bold rounded-xl hover:bg-[#1c2940] transition whitespace-nowrap">
                                        <i class="ph-bold ph-download-simple"></i> Download Template
                                    </button>
                                </div>
                            </form>

                            <hr class="border-slate-100 mb-4">

                            {{-- Form Upload Leger (PERBAIKAN @submit.prevent) --}}
                            <form @submit.prevent="previewFile($event, $el)" action="{{ route('grades.import_leger') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-4">
                                @csrf
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="academic_year" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Tahun Ajaran -</option>
                                            @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white focus:ring-emerald-500 focus:border-emerald-500" required>
                                            <option value="">- Semester -</option>
                                            <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>
                                    <div class="relative group">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="bg-white border-2 border-dashed border-slate-200 rounded-xl p-6 text-center group-hover:border-emerald-400 group-hover:bg-emerald-50/10 transition-all">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-300 group-hover:text-emerald-500 mb-2"></i>
                                            <p class="text-xs font-bold text-slate-500 group-hover:text-emerald-600">Upload Leger Kelas yang sudah diisi</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-upload"></i> Upload Data Leger
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: CETAK RAPOR --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-[#2c3f61]/5 border border-slate-100 overflow-hidden relative group transition-all duration-300 flex flex-col h-full hover:border-[#f9a282]/50">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-[#f9a282] to-[#f4d1c0]"></div>
                    
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-[#f9a282]/10 text-[#f9a282] flex items-center justify-center text-3xl shadow-sm">
                                    <i class="ph-duotone ph-printer"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-[#2c3f61]">Cetak E-Rapor</h2>
                                    <p class="text-sm font-medium text-slate-400">Hasil belajar siswa.</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('grades.list') }}" method="GET" class="flex-1 flex flex-col gap-4">
                            <div class="bg-[#e5eff5]/30 p-4 rounded-2xl border border-slate-100 flex-1 flex flex-col justify-center">
                                <label class="text-xs font-bold text-slate-400 uppercase mb-2 block ml-1">Pilih Kelas</label>
                                <select name="class_id" class="w-full rounded-xl border-slate-200 text-sm font-bold text-[#2c3f61] focus:ring-[#f9a282] focus:border-[#f9a282] h-12" required>
                                    <option value="">-- Pilih Kelas --</option>
                                    @foreach($classes as $c) <option value="{{ $c->id }}">{{ $c->name }}</option> @endforeach
                                </select>
                                
                                <div class="mt-4 flex items-start gap-3 bg-white p-3 rounded-xl border border-slate-100 shadow-sm">
                                    <i class="ph-fill ph-info text-[#f9a282] mt-0.5"></i>
                                    <p class="text-xs text-slate-500 leading-relaxed">
                                        Sistem akan menampilkan daftar siswa dari kelas yang dipilih. Pastikan nilai sudah lengkap sebelum mencetak rapor.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Tahun</label>
                                    <select name="academic_year" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white focus:ring-[#f9a282] focus:border-[#f9a282]">
                                        @foreach($years as $y) <option value="{{ $y->name }}" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase mb-1 ml-1">Semester</label>
                                    <select name="semester" class="w-full rounded-xl border-slate-200 text-xs font-bold bg-white focus:ring-[#f9a282] focus:border-[#f9a282]">
                                        <option value="1" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                        <option value="2" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-2 py-3.5 bg-[#2c3f61] text-white font-bold rounded-xl hover:bg-[#1c2940] transition flex items-center justify-center gap-2 shadow-lg shadow-[#2c3f61]/20 group">
                                <i class="ph-bold ph-list-magnifying-glass text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Lihat Daftar Siswa</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- ======================================================= --}}
            {{-- MODAL PREVIEW EXCEL (BARU) --}}
            {{-- ======================================================= --}}
            <div x-show="previewModal" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                <!-- Backdrop -->
                <div x-show="previewModal" x-transition.opacity class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="previewModal = false"></div>
                
                <!-- Modal Box -->
                <div x-show="previewModal" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="relative w-full max-w-5xl bg-white rounded-[2.5rem] shadow-2xl overflow-hidden flex flex-col max-h-[90vh] border border-slate-100">
                    
                    <!-- Header Modal -->
                    <div class="p-6 md:p-8 bg-gradient-to-r from-[#e5eff5] to-white border-b border-slate-100 flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-[#0d52a1] text-white flex items-center justify-center text-3xl shadow-lg shadow-[#0d52a1]/20">
                                <i class="ph-fill ph-file-xls"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-[#2c3f61]">Pratinjau Data Excel</h3>
                                <p class="text-xs font-bold text-slate-500 mt-1 flex items-center gap-1">
                                    <i class="ph-bold ph-file-text"></i> <span x-text="fileName"></span>
                                </p>
                            </div>
                        </div>
                        <div class="bg-white/80 px-4 py-2 rounded-xl border border-slate-200 text-center shadow-sm">
                            <span class="block text-xl font-black text-[#2c3f61]" x-text="totalDataRows"></span>
                            <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Total Baris Data</span>
                        </div>
                    </div>

                    <!-- Body Modal (Table Preview) -->
                    <div class="p-6 md:p-8 overflow-auto bg-slate-50 flex-1 custom-scrollbar">
                        <div class="bg-white p-4 rounded-2xl border border-emerald-100 shadow-sm mb-4 flex items-start gap-3">
                            <i class="ph-fill ph-info text-emerald-500 text-lg mt-0.5"></i>
                            <p class="text-xs font-medium text-slate-600 leading-relaxed">
                                Menampilkan <strong>5 baris pertama</strong> dari data yang Anda unggah. Mohon pastikan struktur kolom (Header) dan isian datanya sudah sesuai dengan format template yang ditentukan sistem sebelum menekan tombol konfirmasi.
                            </p>
                        </div>

                        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white">
                            <table class="w-full text-left text-xs whitespace-nowrap">
                                <thead class="bg-slate-100 text-[#2c3f61]">
                                    <tr>
                                        <th class="px-4 py-3 font-black uppercase tracking-wider text-center w-10 border-b border-slate-200">#</th>
                                        <template x-for="(header, index) in previewHeaders" :key="index">
                                            <th class="px-4 py-3 font-black uppercase tracking-wider border-b border-slate-200 border-l border-slate-200" x-text="header || `Kolom ${index+1}`"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    <template x-for="(row, rowIndex) in previewRows" :key="rowIndex">
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-4 py-3 font-bold text-slate-400 text-center border-r border-slate-100" x-text="rowIndex + 1"></td>
                                            <template x-for="(colIndex) in previewHeaders.length" :key="colIndex">
                                                <td class="px-4 py-3 text-slate-600 border-r border-slate-100 font-mono" x-text="row[colIndex - 1] !== undefined ? row[colIndex - 1] : '-'"></td>
                                            </template>
                                        </tr>
                                    </template>
                                    <!-- Jika data lebih dari 5 baris -->
                                    <tr x-show="totalDataRows > 5">
                                        <td :colspan="previewHeaders.length + 1" class="px-4 py-4 text-center text-slate-400 font-bold bg-slate-50 italic">
                                            ... dan <span x-text="totalDataRows - 5"></span> baris data lainnya disembunyikan.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="p-6 md:p-8 bg-white border-t border-slate-100 flex justify-between items-center shrink-0">
                        <button type="button" @click="previewModal = false" class="px-6 py-3 rounded-xl border border-slate-200 text-slate-500 font-bold hover:bg-slate-50 hover:text-slate-700 transition">
                            Batal
                        </button>
                        <button type="button" @click="submitImport()" :disabled="isSubmittingImport" class="px-6 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition shadow-lg shadow-emerald-500/20 flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                            <i x-show="isSubmittingImport" style="display: none;" class="ph-bold ph-spinner animate-spin"></i>
                            <i x-show="!isSubmittingImport" class="ph-bold ph-check-circle"></i>
                            <span x-text="isSubmittingImport ? 'Mengunggah Data...' : 'Konfirmasi & Simpan'"></span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script Tambahan untuk membaca file Excel di Frontend --}}
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    @endpush
</x-app-layout>