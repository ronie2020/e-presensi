<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Akademik & E-Rapor') }}
        </h2>
    </x-slot>

    {{-- X-DATA DIPERBARUI DENGAN FITUR PREVIEW EXCEL --}}
    <div class="py-8 sm:py-10 font-sans min-h-screen text-slate-100 bg-[#020b18] relative overflow-hidden pb-20" 
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
         
        {{-- Ambient Glow Effects --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-sky-500/10 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- HERO SECTION --}}
            <x-hero-section
                badge="Sistem Rapor Digital"
                badgeIcon="ph-pencil-line"
                title="Akademik & E-Rapor"
                titleHighlight="Sekolah Terpadu"
                description="Pusat pengelolaan nilai siswa, asesmen capaian pembelajaran, rapor semester, dan arsip akademik sekolah terpadu."
                :chips="[
                    ['icon' => 'ph-chalkboard', 'label' => count($classes) . ' Rombel Kelas'],
                    ['icon' => 'ph-book-bookmark', 'label' => count($subjects) . ' Mata Pelajaran'],
                    ['icon' => 'ph-file-xls', 'label' => 'Import/Export Excel']
                ]"
                heroIcon="ph-pencil-line"
                :showcaseNumber="count($classes)"
                showcaseLabel="Total Rombel"
                statusOrb="Semester Aktif"
                statusColor="sky"
                ctaSecondaryText="Dashboard Utama"
                ctaSecondaryHref="{{ route('dashboard') }}"
                ctaSecondaryIcon="ph-arrow-left"
            >
                <x-slot:showcaseStats>
                    <div class="grid grid-cols-2 gap-3 w-full text-center">
                        <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/15">
                            <span class="block text-xl sm:text-2xl font-black text-white leading-tight">{{ count($classes) }}</span>
                            <span class="text-[9px] uppercase font-bold text-sky-200 tracking-wider">Kelas</span>
                        </div>
                        <div class="bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/15">
                            <span class="block text-xl sm:text-2xl font-black text-[#56bbf1] leading-tight">{{ count($subjects) }}</span>
                            <span class="text-[9px] uppercase font-bold text-sky-200 tracking-wider">Mapel</span>
                        </div>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>

            {{-- ALERT NOTIFIKASI --}}
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-check-circle text-xl"></i>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-emerald-500/20 p-1.5 rounded-lg transition text-emerald-400"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-rose-500/10 border border-rose-500/20 text-rose-400 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <i class="ph-fill ph-warning-circle text-xl"></i>
                        <span class="font-bold text-sm">{{ session('error') }}</span>
                    </div>
                    <button @click="show = false" class="hover:bg-rose-500/20 p-1.5 rounded-lg transition text-rose-400"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                
                {{-- CARD 1: INPUT NILAI --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden relative group transition-all duration-300 flex flex-col h-full hover:border-sky-400/40">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-400 via-blue-500 to-[#0d52a1]"></div>
                    
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-sky-500/10 border border-sky-400/20 text-sky-400 flex items-center justify-center text-3xl shadow-inner">
                                    <i class="ph-duotone ph-pencil-simple-line"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-white">Input Nilai</h2>
                                    <p class="text-sm font-medium text-slate-400">Entri data nilai harian & ujian.</p>
                                </div>
                            </div>
                        </div>

                        {{-- TABS SWITCHER --}}
                        <div class="bg-slate-900/80 p-1.5 rounded-xl border border-white/10 flex mb-6 relative gap-1 overflow-x-auto custom-scrollbar">
                            <button @click="inputMode = 'subject'; importMode = false" 
                                    :class="inputMode === 'subject' ? 'bg-sky-500/20 text-sky-300 border border-sky-400/30 shadow-sm' : 'text-slate-400 hover:text-white'"
                                    class="flex-1 min-w-[100px] py-2.5 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 relative z-10 whitespace-nowrap">
                                <i class="ph-bold ph-books"></i> Per Mapel
                            </button>
                            <button @click="inputMode = 'student'; importMode = false" 
                                    :class="inputMode === 'student' ? 'bg-sky-500/20 text-sky-300 border border-sky-400/30 shadow-sm' : 'text-slate-400 hover:text-white'"
                                    class="flex-1 min-w-[100px] py-2.5 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 relative z-10 whitespace-nowrap">
                                <i class="ph-bold ph-student"></i> Per Siswa
                            </button>
                            <button @click="inputMode = 'leger'; importMode = true" 
                                    :class="inputMode === 'leger' ? 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 shadow-sm' : 'text-slate-400 hover:text-white'"
                                    class="flex-1 min-w-[120px] py-2.5 text-xs font-bold rounded-lg transition-all flex items-center justify-center gap-2 relative z-10 whitespace-nowrap">
                                <i class="ph-bold ph-microsoft-excel-logo text-emerald-400"></i> Leger Kelas
                            </button>
                        </div>

                        {{-- AREA 1: PER MAPEL --}}
                        <div x-show="inputMode === 'subject'" x-transition>
                            <div class="flex items-center justify-between mb-4 px-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-sky-400/80">Metode Input</span>
                                <div class="flex bg-slate-900/60 rounded-lg p-1 border border-white/10">
                                    <button @click="importMode = false" 
                                            :class="!importMode ? 'bg-sky-500/20 text-sky-300 border border-sky-400/30 shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">Manual</button>
                                    <button @click="importMode = true" 
                                            :class="importMode ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all flex items-center gap-1">
                                            <i class="ph-bold ph-microsoft-excel-logo"></i> Excel
                                    </button>
                                </div>
                            </div>

                            {{-- FORM 1A: MANUAL --}}
                            <form x-show="!importMode" action="{{ route('grades.create') }}" method="GET" class="flex-1 flex flex-col gap-4">
                                <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/10 space-y-3">
                                    <select name="class_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-sm font-bold text-white focus:ring-sky-400 focus:border-sky-400 py-3 [color-scheme:dark]" required>
                                        <option value="" class="bg-slate-900 text-white">-- Pilih Kelas --</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}" class="bg-slate-900 text-white">{{ $c->name }}</option> @endforeach
                                    </select>
                                    <select name="subject_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-sm font-bold text-white focus:ring-sky-400 focus:border-sky-400 py-3 [color-scheme:dark]" required>
                                        <option value="" class="bg-slate-900 text-white">-- Pilih Mapel --</option>
                                        @foreach($subjects as $s) <option value="{{ $s->id }}" class="bg-slate-900 text-white">{{ $s->name }}</option> @endforeach
                                    </select>
                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="academic_year" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 py-3 focus:ring-sky-400 focus:border-sky-400 [color-scheme:dark]">
                                            @foreach($years as $y) <option value="{{ $y->name }}" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 py-3 focus:ring-sky-400 focus:border-sky-400 [color-scheme:dark]">
                                            <option value="1" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-2 py-3.5 bg-gradient-to-r from-sky-400 to-[#0d52a1] text-white font-bold rounded-xl hover:from-sky-300 hover:to-sky-700 transition flex items-center justify-center gap-2 shadow-lg shadow-sky-500/20 group">
                                    <span>Mulai Input</span> <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                                </button>
                            </form>

                            {{-- FORM 1B: IMPORT EXCEL --}}
                            <form x-show="importMode" @submit.prevent="previewFile($event, $el)" action="{{ route('grades.import') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-4" style="display: none;">
                                @csrf
                                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl p-4 flex gap-4 items-center">
                                    <div class="bg-emerald-500/20 text-emerald-400 p-2.5 rounded-xl border border-emerald-500/30"><i class="ph-fill ph-file-xls text-xl"></i></div>
                                    <div>
                                        <p class="text-xs text-emerald-300 font-bold">Import Nilai (Per Mapel)</p>
                                        <a href="{{ route('grades.template') }}" class="text-[10px] font-bold text-emerald-400 underline hover:text-emerald-200 transition">Download Template Excel</a>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="class_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Kelas -</option>
                                            @foreach($classes as $c) <option value="{{ $c->id }}" class="bg-slate-900 text-white">{{ $c->name }}</option> @endforeach
                                        </select>
                                        <select name="subject_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Mapel -</option>
                                            @foreach($subjects as $s) <option value="{{ $s->id }}" class="bg-slate-900 text-white">{{ $s->name }}</option> @endforeach
                                        </select>
                                    </div>
                                    
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="academic_year" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Tahun Ajaran -</option>
                                            @foreach($years as $y) <option value="{{ $y->name }}" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Semester -</option>
                                            <option value="1" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>

                                    <div class="relative group">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="bg-slate-900/60 border-2 border-dashed border-white/15 rounded-xl p-6 text-center group-hover:border-emerald-400 group-hover:bg-emerald-500/5 transition-all">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-400 group-hover:text-emerald-400 mb-2"></i>
                                            <p class="text-xs font-bold text-slate-400 group-hover:text-emerald-300">Klik untuk upload file Excel</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-400 hover:to-teal-500 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-upload"></i> Upload Data
                                </button>
                            </form>
                        </div>

                        {{-- AREA 2: PER SISWA --}}
                        <div x-show="inputMode === 'student'" x-transition style="display: none;">
                            <div class="flex items-center justify-between mb-4 px-1">
                                <span class="text-[10px] font-black uppercase tracking-widest text-sky-400/80">Metode Input</span>
                                <div class="flex bg-slate-900/60 rounded-lg p-1 border border-white/10">
                                    <button @click="importMode = false" 
                                            :class="!importMode ? 'bg-sky-500/20 text-sky-300 border border-sky-400/30 shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all">Manual</button>
                                    <button @click="importMode = true" 
                                            :class="importMode ? 'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-400 hover:text-slate-200'"
                                            class="px-3 py-1 text-[10px] font-bold rounded-md transition-all flex items-center gap-1">
                                            <i class="ph-bold ph-microsoft-excel-logo"></i> Excel
                                    </button>
                                </div>
                            </div>

                            {{-- FORM 2A: MANUAL --}}
                            <form x-show="!importMode" action="{{ route('grades.create_by_student') }}" method="GET" class="flex-1 flex flex-col gap-4">
                                <div class="bg-sky-500/10 border border-sky-400/20 rounded-xl p-3 flex gap-3 items-start text-sky-300">
                                    <i class="ph-fill ph-info text-sky-400 mt-0.5"></i>
                                    <p class="text-xs leading-relaxed font-medium">
                                        Pilih <strong>Kelas</strong> terlebih dahulu. Daftar Siswa akan muncul di halaman berikutnya.
                                    </p>
                                </div>
                                <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/10 space-y-3">
                                    <select name="class_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-sm font-bold text-white focus:ring-sky-400 focus:border-sky-400 py-3 [color-scheme:dark]" required>
                                        <option value="" class="bg-slate-900 text-white">-- Pilih Kelas --</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}" class="bg-slate-900 text-white">{{ $c->name }}</option> @endforeach
                                    </select>
                                    <div class="grid grid-cols-2 gap-2">
                                        <select name="academic_year" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 py-3 focus:ring-sky-400 focus:border-sky-400 [color-scheme:dark]">
                                            @foreach($years as $y) <option value="{{ $y->name }}" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 py-3 focus:ring-sky-400 focus:border-sky-400 [color-scheme:dark]">
                                            <option value="1" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>
                                </div>
                                <button type="submit" class="w-full mt-2 py-3.5 bg-gradient-to-r from-sky-400 to-[#0d52a1] text-white font-bold rounded-xl hover:from-sky-300 hover:to-sky-700 transition flex items-center justify-center gap-2 shadow-lg shadow-sky-500/20">
                                    <span>Lanjut Pilih Siswa</span> <i class="ph-bold ph-user-list"></i>
                                </button>
                            </form>

                            {{-- FORM 2B: IMPORT SISWA --}}
                            <form x-show="importMode" @submit.prevent="previewFile($event, $el)" action="{{ route('grades.import_student') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-4" style="display: none;">
                                @csrf
                                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl p-4 flex gap-4 items-center">
                                    <div class="bg-emerald-500/20 text-emerald-400 p-2.5 rounded-xl border border-emerald-500/30"><i class="ph-fill ph-file-xls text-xl"></i></div>
                                    <div>
                                        <p class="text-xs text-emerald-300 font-bold">Import Nilai (Per Siswa)</p>
                                        <a href="{{ route('grades.template_student') }}" class="text-[10px] font-bold text-emerald-400 underline hover:text-emerald-200 transition">Download Template Siswa</a>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <select name="class_id" @change="fetchStudents($el.value)" class="w-full rounded-xl border border-white/10 bg-slate-900 text-sm font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                        <option value="" class="bg-slate-900 text-white">- Pilih Kelas Dulu -</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}" class="bg-slate-900 text-white">{{ $c->name }}</option> @endforeach
                                    </select>
                                    <div class="relative">
                                        <select name="student_id" :disabled="students.length === 0" class="w-full rounded-xl border border-white/10 bg-slate-900 text-sm font-bold text-white disabled:bg-slate-950 disabled:text-slate-600 focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Pilih Siswa -</option>
                                            <template x-for="student in students" :key="student.id">
                                                <option :value="student.id" x-text="student.name + ' (' + student.student_id + ')'" class="bg-slate-900 text-white"></option>
                                            </template>
                                        </select>
                                        <div x-show="isLoadingStudents" class="absolute right-8 top-1/2 -translate-y-1/2 text-emerald-400"><i class="ph-bold ph-spinner animate-spin"></i></div>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="academic_year" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Tahun Ajaran -</option>
                                            @foreach($years as $y) <option value="{{ $y->name }}" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Semester -</option>
                                            <option value="1" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>

                                    <div class="relative group">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="bg-slate-900/60 border-2 border-dashed border-white/15 rounded-xl p-6 text-center group-hover:border-emerald-400 group-hover:bg-emerald-500/5 transition-all">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-400 group-hover:text-emerald-400 mb-2"></i>
                                            <p class="text-xs font-bold text-slate-400 group-hover:text-emerald-300">Pilih File Excel Siswa</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-400 hover:to-teal-500 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-upload"></i> Upload Data
                                </button>
                            </form>
                        </div>

                        {{-- AREA 3: LEGER KELAS --}}
                        <div x-show="inputMode === 'leger'" x-transition style="display: none;">
                            
                            {{-- Form Khusus Download Template Leger --}}
                            <form action="{{ route('grades.template_leger') }}" method="GET" class="mb-4">
                                <div class="bg-emerald-500/10 border border-emerald-500/20 text-emerald-300 rounded-2xl p-4 flex gap-4 items-center">
                                    <div class="bg-emerald-500/20 text-emerald-400 p-2.5 rounded-xl border border-emerald-500/30"><i class="ph-fill ph-microsoft-excel-logo text-xl"></i></div>
                                    <div class="flex-1">
                                        <p class="text-xs text-emerald-300 font-bold">Format Leger Kelas</p>
                                        <p class="text-[10px] text-emerald-400 mt-0.5">Semua siswa & semua mapel dalam 1 file (Format Terpadu).</p>
                                    </div>
                                </div>
                                
                                <div class="flex gap-2 mt-3">
                                    <select name="class_id" class="flex-1 rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-white focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                        <option value="" class="bg-slate-900 text-white">- Pilih Kelas -</option>
                                        @foreach($classes as $c) <option value="{{ $c->id }}" class="bg-slate-900 text-white">{{ $c->name }}</option> @endforeach
                                    </select>
                                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-sky-400 to-[#0d52a1] text-white text-xs font-bold rounded-xl hover:from-sky-300 hover:to-sky-700 transition whitespace-nowrap shadow-md">
                                        <i class="ph-bold ph-download-simple"></i> Download Template
                                    </button>
                                </div>
                            </form>

                            <hr class="border-white/10 mb-4">

                            {{-- Form Upload Leger --}}
                            <form @submit.prevent="previewFile($event, $el)" action="{{ route('grades.import_leger') }}" method="POST" enctype="multipart/form-data" class="flex-1 flex flex-col gap-4">
                                @csrf
                                <div class="space-y-3">
                                    <div class="grid grid-cols-2 gap-3">
                                        <select name="academic_year" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Tahun Ajaran -</option>
                                            @foreach($years as $y) <option value="{{ $y->name }}" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                        </select>
                                        <select name="semester" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 focus:ring-emerald-500 focus:border-emerald-500 [color-scheme:dark]" required>
                                            <option value="" class="bg-slate-900 text-white">- Semester -</option>
                                            <option value="1" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                            <option value="2" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>
                                    <div class="relative group">
                                        <input type="file" name="file" accept=".xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" required>
                                        <div class="bg-slate-900/60 border-2 border-dashed border-white/15 rounded-xl p-6 text-center group-hover:border-emerald-400 group-hover:bg-emerald-500/5 transition-all">
                                            <i class="ph-duotone ph-upload-simple text-3xl text-slate-400 group-hover:text-emerald-400 mb-2"></i>
                                            <p class="text-xs font-bold text-slate-400 group-hover:text-emerald-300">Upload Leger Kelas yang sudah diisi</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-400 hover:to-teal-500 transition shadow-lg shadow-emerald-500/20 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-upload"></i> Upload Data Leger
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- CARD 2: CETAK RAPOR --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden relative group transition-all duration-300 flex flex-col h-full hover:border-amber-400/40">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-amber-400 via-orange-500 to-amber-600"></div>
                    
                    <div class="p-8 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 rounded-2xl bg-amber-500/10 border border-amber-400/20 text-amber-400 flex items-center justify-center text-3xl shadow-inner">
                                    <i class="ph-duotone ph-printer"></i>
                                </div>
                                <div>
                                    <h2 class="text-xl font-black text-white">Cetak E-Rapor</h2>
                                    <p class="text-sm font-medium text-slate-400">Hasil belajar siswa.</p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('grades.list') }}" method="GET" class="flex-1 flex flex-col gap-4">
                            <div class="bg-slate-900/60 p-4 rounded-2xl border border-white/10 flex-1 flex flex-col justify-center">
                                <label class="text-xs font-bold text-sky-400 uppercase tracking-wider mb-2 block ml-1">Pilih Kelas</label>
                                <select name="class_id" class="w-full rounded-xl border border-white/10 bg-slate-900 text-sm font-bold text-white focus:ring-amber-400 focus:border-amber-400 h-12 [color-scheme:dark]" required>
                                    <option value="" class="bg-slate-900 text-white">-- Pilih Kelas --</option>
                                    @foreach($classes as $c) <option value="{{ $c->id }}" class="bg-slate-900 text-white">{{ $c->name }}</option> @endforeach
                                </select>
                                
                                <div class="mt-4 flex items-start gap-3 bg-amber-500/10 border border-amber-400/20 p-3.5 rounded-xl shadow-sm text-amber-300">
                                    <i class="ph-fill ph-info text-amber-400 mt-0.5 text-base shrink-0"></i>
                                    <p class="text-xs leading-relaxed">
                                        Sistem akan menampilkan daftar siswa dari kelas yang dipilih. Pastikan nilai sudah lengkap sebelum mencetak rapor.
                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1 block">Tahun</label>
                                    <select name="academic_year" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 focus:ring-amber-400 focus:border-amber-400 [color-scheme:dark]">
                                        @foreach($years as $y) <option value="{{ $y->name }}" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->name == $y->name) ? 'selected' : '' }}>{{ $y->name }}</option> @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1 ml-1 block">Semester</label>
                                    <select name="semester" class="w-full rounded-xl border border-white/10 bg-slate-900 text-xs font-bold text-slate-300 focus:ring-amber-400 focus:border-amber-400 [color-scheme:dark]">
                                        <option value="1" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Ganjil') ? 'selected' : '' }}>Ganjil</option>
                                        <option value="2" class="bg-slate-900 text-white" {{ ($activeYear && $activeYear->semester == 'Genap') ? 'selected' : '' }}>Genap</option>
                                    </select>
                                </div>
                            </div>

                            <button type="submit" class="w-full mt-2 py-3.5 bg-gradient-to-r from-amber-500 to-orange-600 text-white font-bold rounded-xl hover:from-amber-400 hover:to-orange-500 transition flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20 group">
                                <i class="ph-bold ph-list-magnifying-glass text-lg group-hover:scale-110 transition-transform"></i> 
                                <span>Lihat Daftar Siswa</span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>

        {{-- ======================================================= --}}
        {{-- MODAL PREVIEW EXCEL (DARK GLASS THEME) --}}
        {{-- ======================================================= --}}
        <template x-teleport="body">
            <div x-show="previewModal" style="display: none;" class="fixed inset-0 z-[9999] flex items-center justify-center p-4 sm:p-6 font-sans text-slate-100">
                <!-- Backdrop -->
                <div x-show="previewModal" x-transition.opacity class="absolute inset-0 bg-slate-950/80 backdrop-blur-md" @click="previewModal = false"></div>
                
                <!-- Modal Box -->
                <div x-show="previewModal" 
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-8 scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-200 transform"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-8 scale-95"
                     class="relative w-full max-w-5xl min-w-0 bg-gradient-to-b from-[#031d3d] via-[#021124] to-[#020b18] rounded-[2.5rem] shadow-2xl flex flex-col max-h-[90vh] overflow-hidden border border-white/15">
                    
                    <!-- Header Modal -->
                    <div class="p-6 md:p-8 bg-slate-900/80 border-b border-white/10 flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 flex items-center justify-center text-3xl shadow-lg shadow-emerald-500/10">
                                <i class="ph-fill ph-file-xls"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-white">Pratinjau Data Excel</h3>
                                <p class="text-xs font-bold text-slate-400 mt-1 flex items-center gap-1">
                                    <i class="ph-bold ph-file-text text-sky-400"></i> <span x-text="fileName"></span>
                                </p>
                            </div>
                        </div>
                        <div class="bg-slate-900 px-4 py-2 rounded-xl border border-white/10 text-center shadow-sm">
                            <span class="block text-xl font-black text-sky-400" x-text="totalDataRows"></span>
                            <span class="text-[9px] uppercase font-bold text-slate-400 tracking-wider">Total Baris Data</span>
                        </div>
                    </div>

                    <!-- Body Modal (Table Preview) -->
                    <div class="p-6 md:p-8 overflow-y-auto overflow-x-hidden bg-slate-950/40 flex-1 min-h-0 min-w-0 w-full custom-scrollbar">
                        <div class="bg-emerald-500/10 p-4 rounded-2xl border border-emerald-500/20 shadow-sm mb-4 flex items-start gap-3 text-emerald-300">
                            <i class="ph-fill ph-info text-emerald-400 text-lg mt-0.5 shrink-0"></i>
                            <p class="text-xs font-medium leading-relaxed">
                                Menampilkan <strong>5 baris pertama</strong> dari data yang Anda unggah. Mohon pastikan struktur kolom (Header) dan isian datanya sudah sesuai dengan format template yang ditentukan sistem sebelum menekan tombol konfirmasi.
                            </p>
                        </div>

                        <!-- Table Wrapper -->
                        <div class="overflow-x-auto rounded-xl border border-white/10 bg-slate-900/80 custom-scrollbar pb-2 w-full">
                            <table class="w-full text-left text-xs whitespace-nowrap min-w-max">
                                <thead class="bg-slate-900 text-slate-300">
                                    <tr>
                                        <th class="px-4 py-3 font-black uppercase tracking-wider text-center w-10 border-b border-white/10">#</th>
                                        <template x-for="(header, index) in previewHeaders" :key="index">
                                            <th class="px-4 py-3 font-black uppercase tracking-wider border-b border-white/10 border-l border-white/10 text-sky-300" x-text="header || `Kolom ${index+1}`"></th>
                                        </template>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-slate-300">
                                    <template x-for="(row, rowIndex) in previewRows" :key="rowIndex">
                                        <tr class="hover:bg-white/5 transition-colors">
                                            <td class="px-4 py-3 font-bold text-slate-400 text-center border-r border-white/5" x-text="rowIndex + 1"></td>
                                            <template x-for="(colIndex) in previewHeaders.length" :key="colIndex">
                                                <td class="px-4 py-3 text-slate-300 border-r border-white/5 font-mono" x-text="row[colIndex - 1] !== undefined ? row[colIndex - 1] : '-'"></td>
                                            </template>
                                        </tr>
                                    </template>
                                    <!-- Jika data lebih dari 5 baris -->
                                    <tr x-show="totalDataRows > 5">
                                        <td :colspan="previewHeaders.length + 1" class="px-4 py-4 text-center text-slate-400 font-bold bg-slate-950/40 italic border-t border-white/10">
                                            ... dan <span x-text="totalDataRows - 5" class="text-sky-400"></span> baris data lainnya disembunyikan.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Footer Modal -->
                    <div class="p-6 md:p-8 bg-slate-900/90 border-t border-white/10 flex justify-between items-center shrink-0">
                        <button type="button" @click="previewModal = false" class="px-6 py-3 rounded-xl border border-white/10 text-slate-300 font-bold hover:bg-white/10 transition">
                            Batal
                        </button>
                        <button type="button" @click="submitImport()" :disabled="isSubmittingImport" class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-bold rounded-xl hover:from-emerald-400 hover:to-teal-500 transition shadow-lg shadow-emerald-500/20 flex items-center gap-2 disabled:opacity-70 disabled:cursor-not-allowed">
                            <i x-show="isSubmittingImport" style="display: none;" class="ph-bold ph-spinner animate-spin"></i>
                            <i x-show="!isSubmittingImport" class="ph-bold ph-check-circle"></i>
                            <span x-text="isSubmittingImport ? 'Mengunggah Data...' : 'Konfirmasi & Simpan'"></span>
                        </button>
                    </div>
                </div>
            </div>
        </template>

    </div>

    {{-- Script Tambahan untuk membaca file Excel di Frontend --}}
    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    @endpush
</x-app-layout>