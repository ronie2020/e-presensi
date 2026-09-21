<x-app-layout>
    <div class="py-6 sm:py-10 font-sans text-slate-100 bg-[#020b18] min-h-screen relative overflow-hidden">
        {{-- Efek Latar Belakang --}}
        <div class="absolute top-0 left-0 w-full h-[400px] bg-gradient-to-r from-sky-600/20 via-blue-600/10 to-transparent pointer-events-none -z-10 blur-3xl"></div>

        {{-- HERO SECTION --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8 sm:mb-10 relative z-10">
            <x-hero-section
                badge="PLOTTING JADWAL"
                badgeIcon="ph-fill ph-chalkboard-teacher"
                showcaseIcon="ph-duotone ph-briefcase"
                showcaseTitle="Beban Mengajar"
                showcaseSubtitle="Distribusi JP Guru">
                <x-slot:title>
                    <span class="block text-slate-100">Distribusi & Plotting</span>
                    <span class="block mt-1 sm:mt-1.5 text-transparent bg-clip-text bg-gradient-to-r from-[#56bbf1] via-sky-200 to-[#38bdf8]">
                        Beban Mengajar Guru
                    </span>
                </x-slot:title>
                <x-slot:description>
                    Atur alokasi jam pelajaran masing-masing guru per rombel/kelas sebelum melakukan generate jadwal otomatis.
                </x-slot:description>
                <x-slot:chips>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-hourglass-high text-sky-400"></i> Jam Per Minggu (JP)
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-user-check text-emerald-400"></i> Validasi Guru
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-800/80 border border-slate-700/60 text-slate-300 text-xs font-semibold">
                        <i class="ph-bold ph-cpu text-cyan-400"></i> Generator Ready
                    </span>
                </x-slot:chips>
                <x-slot:showcaseStats>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-slate-700/80 backdrop-blur-md">
                        <i class="ph-fill ph-users text-sky-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Guru:</span>
                        <span class="text-sm font-black text-white font-mono">{{ $totalTeachers ?? $teachingLoads->unique('teacher_id')->count() }}</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-2xl bg-slate-900/80 border border-emerald-500/30 backdrop-blur-md">
                        <i class="ph-fill ph-clock text-emerald-400 text-sm"></i>
                        <span class="text-xs font-bold text-slate-300">Total:</span>
                        <span class="text-sm font-black text-emerald-400 font-mono">{{ $totalHours ?? $teachingLoads->sum('hours_per_week') }} JP</span>
                    </div>
                </x-slot:showcaseStats>
            </x-hero-section>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            {{-- Pesan Sukses / Error --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-950/60 border border-emerald-500/30 text-emerald-300 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                    <span class="font-bold text-sm"><i class="ph-bold ph-check-circle text-emerald-400 mr-2"></i>{{ session('success') }}</span>
                    <button @click="show = false" class="text-emerald-400 hover:text-emerald-300"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif
            @if (session('error') || $errors->any())
                <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-950/60 border border-rose-500/30 text-rose-300 rounded-2xl flex items-center justify-between shadow-lg backdrop-blur-md">
                    <span class="font-bold text-sm"><i class="ph-bold ph-warning-circle text-rose-400 mr-2"></i>{{ session('error') ?? $errors->first() }}</span>
                    <button @click="show = false" class="text-rose-400 hover:text-rose-300"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-12 gap-6 lg:gap-8 items-start">
                
                {{-- KIRI: FORM INPUT & UPLOAD --}}
                <div class="md:col-span-5 lg:col-span-4 space-y-6" x-data="{ tab: '{{ $errors->has('file') ? 'excel' : 'manual' }}' }">
                    <div class="flex bg-slate-900/80 rounded-xl p-1 shadow-inner border border-white/10">
                        <button @click="tab = 'manual'" :class="tab === 'manual' ? 'bg-[#0d52a1] shadow-sm text-white font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-xs rounded-lg transition-all flex justify-center items-center gap-2">
                            <i class="ph-bold ph-keyboard"></i> Manual
                        </button>
                        <button @click="tab = 'excel'" :class="tab === 'excel' ? 'bg-[#0d52a1] shadow-sm text-white font-black' : 'text-slate-400 font-bold hover:text-white'" class="flex-1 py-2 text-xs rounded-lg transition-all flex justify-center items-center gap-2">
                            <i class="ph-bold ph-file-xls"></i> Upload Excel
                        </button>
                    </div>

                    {{-- Form Manual --}}
                    <div x-show="tab === 'manual'" x-transition class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 lg:p-6 rounded-[2rem] shadow-2xl border border-white/10 relative text-white backdrop-blur-xl">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-sky-500 to-blue-600"></div>
                        <h3 class="text-lg font-black text-white mb-5">Input Beban Baru</h3>
                        
                        <form action="{{ route('teaching-loads.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-[10px] font-bold text-sky-400 uppercase mb-1.5 ml-1">Pilih Guru</label>
                                <select name="teacher_id" required class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-xs lg:text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] outline-none py-2.5 [color-scheme:dark]">
                                    <option value="" class="bg-slate-900 text-slate-400">-- Pilih Guru --</option>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" class="bg-slate-900 text-white">{{ $teacher->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-sky-400 uppercase mb-1.5 ml-1">Pilih Mata Pelajaran</label>
                                <select name="subject_id" required class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-xs lg:text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] outline-none py-2.5 [color-scheme:dark]">
                                    <option value="" class="bg-slate-900 text-slate-400">-- Pilih Mapel --</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" class="bg-slate-900 text-white">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase mb-1.5 ml-1">Kelas</label>
                                    <select name="class_id" required class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-xs lg:text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] outline-none py-2.5 [color-scheme:dark]">
                                        <option value="" class="bg-slate-900 text-slate-400">-- Kelas --</option>
                                        @foreach($classes as $class)
                                            <option value="{{ $class->id }}" class="bg-slate-900 text-white">{{ $class->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-bold text-sky-400 uppercase mb-1.5 ml-1">Total JP</label>
                                    <input type="number" name="hours_per_week" min="1" max="10" required placeholder="Misal: 4" class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-xs lg:text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] outline-none py-2.5 placeholder-slate-500">
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full py-3 mt-4 bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white font-bold rounded-xl shadow-lg transition-all flex items-center justify-center gap-2 text-sm border border-white/10">
                                <i class="ph-bold ph-plus-circle"></i> Tambah Beban
                            </button>
                        </form>
                    </div>

                    {{-- Form Upload Excel --}}
                    <div x-show="tab === 'excel'" x-transition style="display: none;" class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 lg:p-6 rounded-[2rem] shadow-2xl border border-white/10 relative text-white backdrop-blur-xl">
                        <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-emerald-400 to-emerald-600"></div>
                        <h3 class="text-lg font-black text-white mb-2">Import via Excel</h3>
                        <p class="text-xs font-medium text-slate-400 mb-5">Upload file template CSV/Excel untuk plotting massal.</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mb-5">
                            <a href="{{ route('teaching-loads.template') }}" class="inline-flex items-center gap-2 text-xs font-bold text-emerald-300 bg-emerald-950/60 hover:bg-emerald-900/60 border border-emerald-500/30 px-3 py-2.5 rounded-xl transition-colors w-full justify-center shadow-sm">
                                <i class="ph-bold ph-download-simple"></i> Download Template
                            </a>
                            <a href="{{ route('teaching-loads.export') }}" class="inline-flex items-center gap-2 text-xs font-bold text-sky-300 bg-sky-950/60 hover:bg-sky-900/60 border border-sky-500/30 px-3 py-2.5 rounded-xl transition-colors w-full justify-center shadow-sm">
                                <i class="ph-bold ph-file-arrow-down"></i> Export Data (.xlsx)
                            </a>
                        </div>
                        <form action="{{ route('teaching-loads.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ fileName: '' }">
                            @csrf
                            <div class="relative group cursor-pointer">
                                <input type="file" name="file" accept=".csv, .xlsx, .xls" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" @change="fileName = $event.target.files[0] ? $event.target.files[0].name : ''">
                                <div class="w-full rounded-xl border-2 border-dashed border-white/20 bg-slate-900/80 p-6 text-center transition-all flex flex-col items-center justify-center gap-2" :class="fileName ? 'border-emerald-400 bg-emerald-950/40' : 'group-hover:bg-slate-800 group-hover:border-emerald-400'">
                                    <i class="ph-duotone ph-upload-simple text-3xl text-slate-400 group-hover:text-emerald-400" x-show="!fileName"></i>
                                    <i class="ph-fill ph-file-excel text-3xl text-emerald-400" x-show="fileName" style="display: none;"></i>
                                    <span class="text-[10px] font-bold text-slate-400" x-show="!fileName">Pilih file Excel</span>
                                    <span class="text-[10px] font-bold text-emerald-300" x-show="fileName" x-text="fileName" style="display: none;"></span>
                                </div>
                            </div>
                            <button type="submit" class="w-full py-3 mt-4 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold rounded-xl shadow-lg transition-all text-sm border border-white/10">
                                <i class="ph-bold ph-upload-simple mr-2"></i> Upload Data
                            </button>
                        </form>
                    </div>
                </div>

                {{-- KANAN: TABEL DATA + EDIT MODAL + MASS DELETE --}}
                <div class="md:col-span-7 lg:col-span-8 bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] p-5 lg:p-6 rounded-[2rem] shadow-2xl border border-white/10 relative overflow-hidden backdrop-blur-xl text-white" 
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
                        <h3 class="text-lg font-black text-white flex items-center gap-2">
                            <i class="ph-fill ph-list-dashes text-sky-400"></i> Daftar Beban Mengajar
                        </h3>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('teaching-loads.export') }}" class="px-4 py-2 bg-sky-950/60 hover:bg-sky-900/60 text-sky-300 border border-sky-500/30 rounded-xl text-xs font-bold transition-all shadow-sm flex items-center gap-2">
                                <i class="ph-bold ph-file-arrow-down"></i> Export Excel
                            </a>

                            {{-- Tombol Hapus Massal --}}
                            @if(auth()->check() && auth()->user()->hasRole('Admin'))
                            <div x-show="selectedIds.length > 0" x-transition class="flex items-center">
                                <form id="mass-delete-form" action="{{ route('teaching-loads.mass-destroy') }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <template x-for="id in selectedIds" :key="id">
                                        <input type="hidden" name="ids[]" x-bind:value="id">
                                    </template>
                                    <button type="button" onclick="confirmMassDelete()" class="bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-md border border-rose-400/30 transition-all flex items-center gap-2">
                                        <i class="ph-bold ph-trash"></i> Hapus <span x-text="selectedIds.length"></span> Data
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-900/80 text-xs font-bold text-sky-400 uppercase border-b border-white/10">
                                <tr>
                                    @if(auth()->check() && auth()->user()->hasRole('Admin'))
                                    <th class="px-4 py-3 rounded-tl-xl w-10">
                                        <input type="checkbox" x-model="selectAll" @change="toggleAll()" class="rounded border-white/20 bg-slate-900 text-sky-500 focus:ring-sky-500 cursor-pointer">
                                    </th>
                                    @endif
                                    <th class="px-4 py-3 {{ (!auth()->check() || !auth()->user()->hasRole('Admin')) ? 'rounded-tl-xl' : '' }} whitespace-nowrap">Kelas</th>
                                    <th class="px-4 py-3 min-w-[150px]">Guru</th>
                                    <th class="px-4 py-3 min-w-[150px]">Mapel</th>
                                    <th class="px-4 py-3 text-center">JP</th>
                                    <th class="px-4 py-3 text-center rounded-tr-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($teachingLoads as $load)
                                <tr class="hover:bg-slate-900/40 transition-colors">
                                    
                                    @if(auth()->check() && auth()->user()->hasRole('Admin'))
                                    <td class="px-4 py-3">
                                        <input type="checkbox" value="{{ $load->id }}" x-model="selectedIds" class="rounded border-white/20 bg-slate-900 text-sky-500 focus:ring-sky-500 cursor-pointer row-checkbox">
                                    </td>
                                    @endif
                                    
                                    <td class="px-4 py-3 font-black text-white whitespace-nowrap">
                                        <span class="bg-sky-500/20 text-sky-300 px-2.5 py-1 rounded-lg border border-sky-500/30 text-xs">{{ $load->studentClass->name }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-xs font-bold text-slate-200 leading-tight">
                                        {{ $load->teacher->name }}
                                    </td>
                                    <td class="px-4 py-3 text-[11px] font-bold text-slate-400 leading-tight">
                                        {{ $load->subject->name }}
                                    </td>
                                    <td class="px-4 py-3 text-center font-black text-white">
                                        {{ $load->hours_per_week }}
                                    </td>
                                    <td class="px-4 py-3 text-center whitespace-nowrap">
                                        @if(auth()->check() && auth()->user()->hasRole('Admin'))
                                        {{-- Tombol Edit --}}
                                        <button type="button" @click="editData = {{ json_encode($load) }}; editModalOpen = true" class="w-7 h-7 inline-flex items-center justify-center rounded-lg bg-amber-500/20 text-amber-300 border border-amber-500/30 hover:bg-amber-500 hover:text-white transition-all shadow-sm mr-1">
                                            <i class="ph-bold ph-pencil-simple"></i>
                                        </button>
                                        @endif
                                        
                                        {{-- Tombol Delete Hapus Satuan --}}
                                        <form id="delete-form-{{ $load->id }}" action="{{ route('teaching-loads.destroy', $load->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $load->id }})" class="w-7 h-7 flex items-center justify-center rounded-lg bg-rose-500/20 text-rose-400 border border-rose-500/30 hover:bg-rose-500 hover:text-white transition-all shadow-sm">
                                                <i class="ph-bold ph-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="{{ (auth()->check() && auth()->user()->hasRole('Admin')) ? '6' : '5' }}" class="px-5 py-10 text-center">
                                        <i class="ph-duotone ph-folder-open text-4xl text-slate-500 mb-2"></i>
                                        <p class="text-sm font-bold text-slate-400">Belum ada beban mengajar yang diinput.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Pagination --}}
                    @if($teachingLoads instanceof \Illuminate\Contracts\Pagination\Paginator && $teachingLoads->hasPages())
                    <div class="mt-6 pt-5 border-t border-white/10">
                        {{ $teachingLoads->links() }}
                    </div>
                    @endif

                    {{-- MODAL EDIT (Hanya dirender jika Admin) --}}
                    @if(auth()->check() && auth()->user()->hasRole('Admin'))
                    <div x-show="editModalOpen" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            
                            {{-- Background Overlay --}}
                            <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-[#021124]/85 backdrop-blur-md transition-opacity" aria-hidden="true" @click="editModalOpen = false"></div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            {{-- Modal Content --}}
                            <div x-show="editModalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-[#021124] rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full border border-white/10 text-white">
                                <div class="p-6 md:p-8">
                                    <div class="flex justify-between items-center mb-5">
                                        <h3 class="text-lg font-black text-white" id="modal-title">Edit Beban Mengajar</h3>
                                        <button @click="editModalOpen = false" class="text-slate-400 hover:text-rose-400 transition-colors">
                                            <i class="ph-bold ph-x text-xl"></i>
                                        </button>
                                    </div>
                                    
                                    {{-- Form Edit Dinamis --}}
                                    <form x-bind:action="'{{ url('teaching-loads') }}/' + editData.id" method="POST" class="space-y-4">
                                        @csrf
                                        @method('PUT')
                                        <div>
                                            <label class="block text-[10px] font-bold text-sky-400 uppercase mb-1.5 ml-1">Pilih Guru</label>
                                            <select name="teacher_id" x-model="editData.teacher_id" required class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-xs lg:text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] outline-none py-2.5 [color-scheme:dark]">
                                                @foreach($teachers as $teacher)
                                                    <option value="{{ $teacher->id }}" class="bg-slate-900 text-white">{{ $teacher->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-[10px] font-bold text-sky-400 uppercase mb-1.5 ml-1">Pilih Mata Pelajaran</label>
                                            <select name="subject_id" x-model="editData.subject_id" required class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-xs lg:text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] outline-none py-2.5 [color-scheme:dark]">
                                                @foreach($subjects as $subject)
                                                    <option value="{{ $subject->id }}" class="bg-slate-900 text-white">{{ $subject->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3">
                                            <div>
                                                <label class="block text-[10px] font-bold text-sky-400 uppercase mb-1.5 ml-1">Kelas</label>
                                                <select name="class_id" x-model="editData.class_id" required class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-xs lg:text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] outline-none py-2.5 [color-scheme:dark]">
                                                    @foreach($classes as $class)
                                                        <option value="{{ $class->id }}" class="bg-slate-900 text-white">{{ $class->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-bold text-sky-400 uppercase mb-1.5 ml-1">Total JP</label>
                                                <input type="number" name="hours_per_week" x-model="editData.hours_per_week" min="1" max="10" required class="w-full rounded-xl border border-white/10 bg-slate-900/80 text-xs lg:text-sm font-bold text-white focus:border-[#56bbf1] focus:ring-1 focus:ring-[#56bbf1] outline-none py-2.5">
                                            </div>
                                        </div>
                                        
                                        <div class="mt-6 flex justify-end gap-3">
                                            <button type="button" @click="editModalOpen = false" class="px-5 py-2.5 bg-slate-800 text-slate-300 font-bold rounded-xl hover:bg-slate-700 transition-colors text-sm border border-white/10">
                                                Batal
                                            </button>
                                            <button type="submit" class="px-5 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl transition-colors shadow-lg shadow-amber-500/20 text-sm flex items-center gap-2">
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
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#021124',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl w-[90%] max-w-md',
                    confirmButton: 'bg-rose-600 text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-1 sm:mx-2 shadow-lg shadow-rose-900/20 text-sm',
                    cancelButton: 'bg-slate-800 text-slate-300 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-1 sm:mx-2 text-sm border border-white/10'
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
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Hapus Massal!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#021124',
                color: '#fff',
                customClass: {
                    popup: 'rounded-[2rem] font-sans border border-white/10 bg-[#021124] text-white shadow-2xl w-[90%] max-w-md',
                    confirmButton: 'bg-rose-600 text-white px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-1 sm:mx-2 shadow-lg shadow-rose-900/20 text-sm',
                    cancelButton: 'bg-slate-800 text-slate-300 px-5 sm:px-6 py-2.5 sm:py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-1 sm:mx-2 text-sm border border-white/10'
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