<x-app-layout>
    <div class="py-6 sm:py-10 font-sans text-elevate-dark relative overflow-hidden">
        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-elevate-gradient-main opacity-20 pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 relative z-10">
            <div class="relative rounded-[2rem] sm:rounded-[2.5rem] bg-elevate-gradient-main p-6 sm:p-10 text-elevate-dark shadow-xl shadow-elevate-accent/20 overflow-hidden border border-white/60 group">
                <div class="absolute inset-0 opacity-[0.03] bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] pointer-events-none mix-blend-overlay"></div>
                <div class="absolute -top-24 -right-24 w-80 h-80 bg-white/40 rounded-full blur-3xl pointer-events-none group-hover:bg-white/60 transition-all duration-700"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 sm:gap-8">
                    <div class="max-w-xl">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-elevate-soft border border-elevate-accent/30 text-elevate-primary text-[10px] font-bold uppercase tracking-widest mb-4 backdrop-blur-sm shadow-sm">
                            <i class="ph-fill ph-chalkboard-teacher"></i> Plotting Jadwal
                        </div>
                        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black tracking-tight mb-3 flex items-center gap-3 text-elevate-dark leading-tight">
                            Beban Mengajar
                        </h1>
                        <p class="text-elevate-dark/80 text-xs sm:text-sm font-semibold leading-relaxed">
                            Atur jam pelajaran masing-masing guru per kelas sebelum melakukan generate jadwal otomatis.
                        </p>
                    </div>

                    <div class="flex flex-row gap-3 w-full md:w-auto">
                        <div class="bg-white/60 backdrop-blur-md px-5 py-4 rounded-2xl border border-white/80 flex-1 md:flex-none text-center md:text-left shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-1.5 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-users text-lg"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider">Guru Di-plot</span>
                            </div>
                            <span class="block text-2xl font-black text-elevate-dark tracking-tight">
                                {{ $teachingLoads->unique('teacher_id')->count() }}
                            </span>
                        </div>
                        <div class="bg-white/60 backdrop-blur-md px-5 py-4 rounded-2xl border border-white/80 flex-1 md:flex-none text-center md:text-left shadow-sm">
                            <div class="flex items-center justify-center md:justify-start gap-1.5 mb-1 text-elevate-primary">
                                <i class="ph-duotone ph-clock text-lg"></i>
                                <span class="text-[9px] font-bold uppercase tracking-wider">Total JP</span>
                            </div>
                            <span class="block text-2xl font-black text-elevate-dark tracking-tight">
                                {{ $teachingLoads->sum('hours_per_week') }} <span class="text-xs font-bold text-elevate-dark/50">Jam</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Pesan Sukses / Error --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <span class="font-bold text-sm"><i class="ph-bold ph-check-circle mr-2"></i>{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-600"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if (session('error') || $errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                    <span class="font-bold text-sm"><i class="ph-bold ph-warning-circle mr-2"></i>{{ session('error') ?? $errors->first() }}</span>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-600"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-8 items-start">
                
                {{-- KIRI: FORM INPUT & UPLOAD --}}
                <div class="md:col-span-5 lg:col-span-4 space-y-6" x-data="{ tab: '{{ $errors->has('file') ? 'excel' : 'manual' }}' }">
                    <div class="flex bg-slate-100 rounded-xl p-1 shadow-inner border border-slate-200">
                        <button @click="tab = 'manual'" :class="tab === 'manual' ? 'bg-white shadow-sm text-elevate-primary font-black' : 'text-slate-500 font-bold hover:text-elevate-primary'" class="flex-1 py-2 text-xs rounded-lg transition-all flex justify-center items-center gap-2">
                            <i class="ph-bold ph-keyboard"></i> Manual
                        </button>
                        <button @click="tab = 'excel'" :class="tab === 'excel' ? 'bg-white shadow-sm text-emerald-600 font-black' : 'text-slate-500 font-bold hover:text-emerald-600'" class="flex-1 py-2 text-xs rounded-lg transition-all flex justify-center items-center gap-2">
                            <i class="ph-bold ph-file-xls"></i> Upload Excel
                        </button>
                    </div>

                    {{-- Form Manual (Tetap Sama) --}}
                    <div x-show="tab === 'manual'" x-transition class="bg-white p-5 lg:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-elevate-primary to-elevate-accent"></div>
                        <h3 class="text-lg font-black text-elevate-dark mb-5">Input Beban Baru</h3>
                        
                        <form action="{{ route('teaching-loads.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Pilih Guru</label>
                                <select name="teacher_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                                    <option value="">-- Pilih Guru --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Pilih Mata Pelajaran</label>
                                <select name="subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                                    <option value="">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Kelas</label>
                                    <select name="class_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                                        <option value="">-- Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Total JP</label>
                                    <input type="number" name="hours_per_week" min="1" max="10" required placeholder="Misal: 4" class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-3 mt-4 bg-elevate-primary text-white font-bold rounded-xl hover:bg-elevate-dark transition-all shadow-lg flex items-center justify-center gap-2 text-sm">
                                <i class="ph-bold ph-plus-circle"></i> Tambah Beban
                            </button>
                        </form>
                    </div>

                    {{-- Form Upload Excel (Tetap Sama) --}}
                    <div x-show="tab === 'excel'" x-transition style="display: none;" class="bg-white p-5 lg:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative">
                        {{-- ... (Kode Excel Form tetap dipertahankan sesuai file awal) ... --}}
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                        <h3 class="text-lg font-black text-elevate-dark mb-2">Import via Excel</h3>
                        <p class="text-xs font-medium text-slate-500 mb-5">Upload file template CSV/Excel untuk plotting massal.</p>
                        <div class="mb-5">
                            <a href="{{ route('teaching-loads.template') }}" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-4 py-2 rounded-xl transition-colors w-full justify-center">
                                <i class="ph-bold ph-download-simple"></i> Download Template
                            </a>
                        </div>
                        <form action="{{ route('teaching-loads.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ fileName: '' }">
                            @csrf
                            <div class="relative group cursor-pointer">
                                <input type="file" name="file" accept=".csv, .xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                <div class="w-full rounded-xl border-2 border-dashed border-slate-300 bg-slate-50 p-6 text-center transition-all flex flex-col items-center justify-center gap-2" :class="fileName ? 'border-emerald-400 bg-emerald-50' : 'group-hover:bg-slate-100 group-hover:border-emerald-400'">
                                    <i class="ph-duotone ph-upload-simple text-3xl text-slate-400 group-hover:text-emerald-500" x-show="!fileName"></i>
                                    <i class="ph-fill ph-file-excel text-3xl text-emerald-500" x-show="fileName" style="display: none;"></i>
                                    <span class="text-[10px] font-bold text-slate-500" x-show="!fileName">Pilih file Excel</span>
                                    <span class="text-[10px] font-bold text-emerald-600" x-show="fileName" x-text="fileName" style="display: none;"></span>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3 mt-4 bg-emerald-500 text-white font-bold rounded-xl hover:bg-emerald-600 transition-all shadow-lg text-sm">
                                <i class="ph-bold ph-upload-simple mr-2"></i> Upload Data
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KANAN: TABEL DATA + EDIT MODAL + MASS DELETE --}}
                <div class="md:col-span-7 lg:col-span-8 bg-white p-5 lg:p-6 rounded-[2rem] shadow-xl shadow-slate-200/50 border border-slate-100 relative overflow-hidden" 
                     x-data="{ 
                        selectedIds: [], 
                        selectAll: false,
                        editModalOpen: false,
                        editData: {},
                        toggleAll() {
                            if(this.selectAll) {
                                this.selectedIds = {{ json_encode($teachingLoads->pluck('id')) }};
                            } else {
                                this.selectedIds = [];
                            }
                        }
                     }">
                    
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-4">
                        <h3 class="text-lg font-black text-elevate-dark flex items-center gap-2">
                            <i class="ph-fill ph-list-dashes text-elevate-primary"></i> Daftar Beban Mengajar
                        </h3>

                        {{-- Tombol Hapus Massal (Hanya Tampil Jika Ada yang Dipilih & Hanya Admin) --}}
                       @if(auth()->check() && auth()->user()->hasRole('Admin'))
                        <div x-show="selectedIds.length > 0" x-transition class="flex items-center">
                            <form id="mass-delete-form" action="{{ route('teaching-loads.mass-destroy') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" x-bind:value="id">
                                </template>
                                <button type="button" onclick="confirmMassDelete()" class="bg-rose-500 hover:bg-rose-600 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md shadow-rose-500/20 transition-all flex items-center gap-2">
                                    <i class="ph-bold ph-trash"></i> Hapus <span x-text="selectedIds.length"></span> Data
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs font-bold text-elevate-primary uppercase border-b border-slate-100">
                                <tr>
                                    @if(auth()->check() && auth()->user()->hasRole('Admin'))
                                    <th class="px-4 py-3 rounded-tl-xl w-10">
                                        <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-slate-300 text-elevate-primary focus:ring-elevate-accent cursor-pointer">
                                    </th>
                                    @endif
                                    <th class="px-4 py-3 {{ (!auth()->check() || !auth()->user()->hasRole('Admin')) ? 'rounded-tl-xl' : '' }} whitespace-nowrap">Kelas</th>
                                    <th class="px-4 py-3 min-w-[150px]">Guru</th>
                                    <th class="px-4 py-3 min-w-[150px]">Mapel</th>
                                    <th class="px-4 py-3 text-center">JP</th>
                                    <th class="px-4 py-3 text-center rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($teachingLoads as $load)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    
                                    {{-- KOLOM CHECKBOX YANG SEBELUMNYA HILANG --}}
                                    @if(auth()->check() && auth()->user()->hasRole('Admin'))
                                    <td class="px-4 py-3">
                                        <input type="checkbox" value="{{ $load->id }}" x-model="selectedIds" class="rounded border-slate-300 text-elevate-primary focus:ring-elevate-accent cursor-pointer row-checkbox">
                                    </td>
                                    @endif
                                    
                                    <td class="px-4 py-3 font-black text-elevate-dark whitespace-nowrap">
                                        <span class="bg-elevate-soft text-elevate-primary px-2.5 py-1 rounded-lg border border-elevate-accent/20 text-xs">{{ $load->studentClass->name }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-bold text-slate-700 leading-tight">
                                        {{ $load->teacher->name }}
                                    </td>
                                    <td class="px-4 py-3 text-[11px] font-bold text-slate-500 leading-tight">
                                        {{ $load->subject->name }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-black text-elevate-dark">
                                        {{ $load->hours_per_week }}
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        @if(auth()->check() && auth()->user()->hasRole('Admin'))
                                        {{-- Tombol Edit --}}
                                        <button type="button" @click="editData = {{ json_encode($load) }}; editModalOpen = true" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-amber-50 text-amber-500 hover:bg-amber-500 hover:text-white transition-all shadow-sm mr-1">
                                            <i class="ph-bold ph-pencil-simple"></i>
                                        </button>
                                        @endif
                                        
                                        {{-- Tombol Delete Hapus Satuan --}}
                                        <form id="delete-form-{{ $load->id }}" action="{{ route('teaching-loads.destroy', $load->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $load->id }})" class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-50 text-rose-500 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (auth()->check() && auth()->user()->hasRole('Admin')) ? '6' : '5' }}" class="px-5 py-10 text-center">
                                        <i class="ph-duotone ph-folder-open text-4xl text-slate-300 mb-2"></i>
                                        <p class="text-sm font-bold text-slate-400">Belum ada beban mengajar yang diinput.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- MODAL EDIT (Hanya dirender jika Admin) --}}
                    @if(auth()->check() && auth()->user()->hasRole('Admin'))
                    <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            
                            {{-- Background Overlay --}}
                            <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" aria-hidden="true" @click="editModalOpen = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            {{-- Modal Content --}}
                            <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-slate-100">
                                <div class="bg-white px-6 pt-6 pb-6">
                                    <div class="flex justify-between items-center mb-5">
                                        <h3 class="text-lg font-black text-elevate-dark" id="modal-title">Edit Beban Mengajar</h3>
                                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-rose-500 transition-colors">
                                            <i class="ph-bold ph-x text-xl"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- Form Edit Dinamis --}}
                                    <form x-bind:action="'{{ url('teaching-loads') }}/' + editData.id" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Pilih Guru</label>
                                            <select name="teacher_id" x-model="editData.teacher_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Pilih Mata Pelajaran</label>
                                            <select name="subject_id" x-model="editData.subject_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Kelas</label>
                                                <select name="class_id" x-model="editData.class_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-elevate-primary uppercase mb-1.5 ml-1">Total JP</label>
                                                <input type="number" name="hours_per_week" x-model="editData.hours_per_week" min="1" max="10" required class="w-full rounded-xl border-slate-200 bg-slate-50 text-xs lg:text-sm font-bold focus:ring-elevate-accent outline-none py-2.5">
                                            </div>
                                        </div>
                                        
                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-colors text-sm">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-5 py-2.5 bg-amber-500 text-white font-bold rounded-xl hover:bg-amber-600 transition-colors shadow-lg shadow-amber-500/30 text-sm flex items-center gap-2">
                                                <i class="ph-bold ph-floppy-disk"></i> Simpan Perubahan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    {{-- SweetAlert2 Library & Custom Script --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Hapus Satuan
        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus Beban Mengajar?',
                text: "Yakin ingin menghapus data ini?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl w-[90%] max-w-md',
                    confirmButton: 'bg-rose-600 text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-1 sm:mx-2 shadow-lg shadow-rose-900/20 text-sm',
                    cancelButton: 'bg-slate-100 text-slate-600 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-1 sm:mx-2 text-sm'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('delete-form-' + id);
                    if (form) form.submit();
                }
            });
        }

        // Hapus Massal
        function confirmMassDelete() {
            Swal.fire({
                title: 'Hapus Data Terpilih?',
                text: "Semua data yang dicentang akan dihapus permanen.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Hapus Massal!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-[2rem] font-sans border-0 shadow-2xl w-[90%] max-w-md',
                    confirmButton: 'bg-rose-600 text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-1 sm:mx-2 shadow-lg shadow-rose-900/20 text-sm',
                    cancelButton: 'bg-slate-100 text-slate-600 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-1 sm:mx-2 text-sm'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('mass-delete-form').submit();
                }
            });
        }
    </script>
</x-app-layout>