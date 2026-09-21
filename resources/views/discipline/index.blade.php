<x-app-layout>
    {{-- LIBRARY PENDUKUNG --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Tambahan TomSelect untuk Pencarian Dropdown -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    
    <style>
        /* Style untuk area kamera agar responsif */
        #reader video {
            object-fit: cover;
            width: 100% !important;
            height: 100% !important;
            border-radius: 1rem;
        }
        #reader { width: 100%; }
        
        /* Hilangkan scrollbar default pada tabel agar bersih */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

        /* Customisasi TomSelect agar sesuai dengan Elevate Dark Glass Theme */
        .ts-control { border-radius: 1rem !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; background-color: #0f172a !important; padding: 0.875rem 1rem !important; font-size: 0.875rem !important; font-weight: 700 !important; color: #ffffff !important;}
        .ts-control.focus { border-color: #f43f5e !important; box-shadow: none !important; background-color: #0f172a !important;}
        #student_select_merit-ts-control.focus { border-color: #10b981 !important; }
        .ts-dropdown { border-radius: 1rem !important; overflow: hidden !important; border: 1px solid rgba(255, 255, 255, 0.1) !important; font-size: 0.875rem !important; font-weight: 500 !important; background-color: #0f172a !important; color: #ffffff !important; }
        .ts-dropdown .option { color: #f8fafc !important; }
        .ts-dropdown .option.active, .ts-dropdown .option:hover { background-color: #1e293b !important; color: #ffffff !important; }
        .ts-control input { color: #ffffff !important; }
    </style>

    <div class="min-h-screen bg-[#020b18] text-slate-100 relative overflow-hidden py-8 sm:py-10 font-sans">
        
        {{-- Efek Latar Belakang Glowing --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-blue-600/15 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-1/3 right-10 w-96 h-96 bg-rose-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute top-2/3 left-10 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <x-hero-section
                badge="Kesiswaan & Tata Tertib"
                badgeIcon="warning-circle"
                title="Monitoring &"
                titleHighlight="Catatan Kedisiplinan"
                description="Kelola poin siswa, pantau klasemen pelanggaran, dan lihat rekapitulasi per kelas dalam satu dashboard terpadu."
                :chips="['Pencatatan Poin Pelanggaran', 'Pantauan Siswa Berisiko', 'Amnesti & Pembinaan']"
                :showcaseIcon="'shield-check'"
                showcaseLabel="Sistem Tata Tertib"
                showcaseStatus="Aktif Terpantau"
                :showcaseBubbles="[
                    ['icon' => 'warning', 'label' => 'Real-time Log', 'pos' => '-top-2 -right-2'],
                    ['icon' => 'scales', 'label' => 'Aturan Poin', 'pos' => '-bottom-2 -left-2']
                ]"
            >
                <x-slot:actions>
                    <a href="{{ route('discipline.analytics') }}" class="group bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white shadow-lg shadow-sky-500/25 border border-sky-400/30 px-5 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 active:scale-95">
                        <i class="ph-bold ph-chart-line-up text-base"></i>
                        <span>Statistik & Analitik</span>
                        <i class="ph-bold ph-arrow-up-right group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform"></i>
                    </a>
                    <a href="{{ route('discipline-types.index') }}" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white px-5 py-3 rounded-2xl font-bold text-xs sm:text-sm transition-all flex items-center justify-center gap-2 shadow-sm backdrop-blur-md active:scale-95">
                        <i class="ph-bold ph-gear text-base text-sky-400"></i>
                        <span>Atur Poin Pelanggaran</span>
                    </a>
                </x-slot:actions>
            </x-hero-section>

            {{-- Pesan Flash Sukses --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-emerald-950/40 border border-emerald-500/30 text-emerald-300 rounded-2xl flex items-center justify-between shadow-sm animate-in slide-in-from-top-2 backdrop-blur-md">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 border border-emerald-500/30"><i class="ph-bold ph-check"></i></div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="p-2 hover:bg-white/5 rounded-lg transition text-slate-400 hover:text-white"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <!-- BAGIAN 1: FORM INPUT (GRID 2 KOLOM) -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                
                <!-- KIRI: Form Pelanggaran -->
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-rose-500/30 overflow-visible relative group hover:border-rose-500/50 transition-all duration-300 backdrop-blur-xl">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-rose-500 rounded-t-[2rem]"></div>
                    <div class="p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-rose-500/20 text-rose-400 rounded-xl flex items-center justify-center text-2xl shadow-sm border border-rose-500/30 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-warning-octagon"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-white">Input Pelanggaran</h3>
                                <p class="text-xs font-bold text-rose-400 uppercase tracking-wider">Kurangi Poin (-)</p>
                            </div>
                        </div>

                        <form action="{{ route('discipline.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            
                            {{-- PILIH SISWA --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <select name="student_id" id="student_select_violation" required placeholder="Ketik nama atau kelas siswa...">
                                            <option value="">-- Cari / Pilih Nama Siswa --</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}" 
                                                        data-nis="{{ $student->nis ?? '' }}" 
                                                        data-nisn="{{ $student->nisn ?? '' }}"
                                                        data-student-id="{{ $student->student_id ?? '' }}"
                                                        data-rfid="{{ $student->rfid_id ?? '' }}"
                                                        data-class="{{ $student->schoolClass->name ?? '' }}">
                                                    {{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" onclick="startScanner('student_select_violation')" class="shrink-0 bg-slate-900 border border-white/10 text-sky-400 w-[52px] h-[52px] rounded-xl hover:bg-slate-800 transition-colors shadow-lg flex items-center justify-center" title="Scan QR Code">
                                        <i class="ph-bold ph-qr-code text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- JENIS PELANGGARAN --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Pelanggaran</label>
                                <div class="relative">
                                    <select name="discipline_type_id" required class="w-full rounded-xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-rose-500 focus:ring-rose-500 text-sm font-bold text-white py-3.5 pl-4 pr-10 appearance-none cursor-pointer transition-all [color-scheme:dark]">
                                        <option value="" class="bg-slate-900 text-slate-400">-- Pilih Kategori --</option>
                                        @foreach ($violationTypes as $type)
                                            <option value="{{ $type->id }}" class="bg-slate-900 text-white">{{ $type->name }} (-{{ $type->point_value }} Poin)</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            {{-- CATATAN --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kronologi / Catatan</label>
                                <textarea name="notes" rows="2" class="w-full rounded-xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-rose-500 focus:ring-rose-500 text-sm font-medium p-4 text-white placeholder:text-slate-500 transition-all [color-scheme:dark]" placeholder="Jelaskan singkat kejadiannya..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-rose-600/25 flex items-center justify-center gap-2 mt-2 active:scale-95 border border-rose-500/30">
                                <i class="ph-bold ph-warning-circle text-lg"></i>
                                Simpan Pelanggaran
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KANAN: Form Kebaikan -->
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-emerald-500/30 overflow-visible relative group hover:border-emerald-500/50 transition-all duration-300 backdrop-blur-xl">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500 rounded-t-[2rem]"></div>
                    <div class="p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-emerald-500/20 text-emerald-400 rounded-xl flex items-center justify-center text-2xl shadow-sm border border-emerald-500/30 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-medal"></i>
                            </div>
                            <div>
                                <h3 class="text-xl font-black text-white">Input Prestasi</h3>
                                <p class="text-xs font-bold text-emerald-400 uppercase tracking-wider">Tambah Poin (+)</p>
                            </div>
                        </div>

                        <form action="{{ route('discipline.store') }}" method="POST" class="space-y-5">
                            @csrf
                            <input type="hidden" name="date" value="{{ \Carbon\Carbon::today()->toDateString() }}">
                            
                            {{-- PILIH SISWA --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Pilih Siswa</label>
                                <div class="flex gap-2">
                                    <div class="relative flex-1">
                                        <select name="student_id" id="student_select_merit" required placeholder="Ketik nama atau kelas siswa...">
                                            <option value="">-- Cari / Pilih Nama Siswa --</option>
                                            @foreach ($students as $student)
                                                <option value="{{ $student->id }}" 
                                                        data-nis="{{ $student->nis ?? '' }}" 
                                                        data-nisn="{{ $student->nisn ?? '' }}"
                                                        data-student-id="{{ $student->student_id ?? '' }}"
                                                        data-rfid="{{ $student->rfid_id ?? '' }}"
                                                        data-class="{{ $student->schoolClass->name ?? '' }}">
                                                    {{ $student->name }} ({{ $student->schoolClass->name ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="button" onclick="startScanner('student_select_merit')" class="shrink-0 bg-slate-900 border border-white/10 text-sky-400 w-[52px] h-[52px] rounded-xl hover:bg-slate-800 transition-colors shadow-lg flex items-center justify-center" title="Scan QR Code">
                                        <i class="ph-bold ph-qr-code text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- JENIS PRESTASI --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Kebaikan</label>
                                <div class="relative">
                                    <select name="discipline_type_id" required class="w-full rounded-xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-bold text-white py-3.5 pl-4 pr-10 appearance-none cursor-pointer transition-all [color-scheme:dark]">
                                        <option value="" class="bg-slate-900 text-slate-400">-- Pilih Kategori --</option>
                                        @foreach ($meritTypes as $type)
                                            <option value="{{ $type->id }}" class="bg-slate-900 text-white">{{ $type->name }} (+{{ $type->point_value }} Poin)</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            {{-- CATATAN --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Detail Tambahan</label>
                                <textarea name="notes" rows="2" class="w-full rounded-xl border-white/10 bg-slate-900/90 focus:bg-slate-900 focus:border-emerald-500 focus:ring-emerald-500 text-sm font-medium p-4 text-white placeholder:text-slate-500 transition-all [color-scheme:dark]" placeholder="Keterangan prestasi..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-emerald-600/25 flex items-center justify-center gap-2 mt-2 active:scale-95 border border-emerald-500/30">
                                <i class="ph-bold ph-star text-lg"></i>
                                Simpan Kebaikan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: RIWAYAT / LOG -->
            @if(isset($historyRecords))
            <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] shadow-2xl border border-white/10 backdrop-blur-xl overflow-hidden mb-10">
                <div class="p-6 border-b border-white/10 bg-white/5 flex flex-col xl:flex-row justify-between items-start xl:items-center gap-5">
                    
                    {{-- Judul & Badge Jumlah Data --}}
                    <div class="shrink-0 flex items-center justify-between w-full xl:w-auto">
                        <div>
                            <h3 class="text-xl font-black text-white flex items-center gap-2">
                                <div class="w-2 h-6 bg-[#56bbf1] rounded-full"></div>
                                Log Aktivitas
                                <span class="text-xs font-bold text-sky-400 bg-white/5 px-2.5 py-1 rounded-lg border border-white/10 shadow-sm ml-2">
                                    {{ $historyRecords->total() }} Data
                                </span>
                            </h3>
                            <p class="text-sm font-medium text-slate-400 mt-1">Riwayat input poin terbaru.</p>
                        </div>
                    </div>
                
                    {{-- Form Filter --}}
                    <form action="{{ route('discipline.index') }}" method="GET" class="flex flex-wrap sm:flex-nowrap items-center gap-2 w-full xl:w-auto overflow-x-auto pb-2 sm:pb-0 custom-scrollbar">
                        
                        {{-- Filter Tanggal --}}
                        <input type="date" name="filter_date" value="{{ request('filter_date') }}" title="Pilih Tanggal"
                            class="h-[42px] rounded-xl border-white/10 bg-slate-900/90 text-sm px-3 text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] w-full sm:w-auto shrink-0 font-medium cursor-pointer [color-scheme:dark]">
                        
                        {{-- Filter Kelas --}}
                        <select name="filter_class" class="h-[42px] rounded-xl border-white/10 bg-slate-900/90 text-sm px-3 text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] w-full sm:w-auto shrink-0 font-medium cursor-pointer [color-scheme:dark]">
                            <option value="" class="bg-slate-900 text-slate-400">Semua Kelas</option>
                            @foreach($classes ?? [] as $cls)
                                <option value="{{ $cls->id }}" class="bg-slate-900 text-white" {{ request('filter_class') == $cls->id ? 'selected' : '' }}>{{ $cls->name }}</option>
                            @endforeach
                        </select>

                        {{-- Filter Jenis Kejadian --}}
                        <select name="filter_type" class="h-[42px] rounded-xl border-white/10 bg-slate-900/90 text-sm px-3 text-white focus:ring-[#56bbf1] focus:border-[#56bbf1] w-full sm:w-auto shrink-0 font-medium cursor-pointer [color-scheme:dark]">
                            <option value="" class="bg-slate-900 text-slate-400">Semua Jenis</option>
                            <option value="Pelanggaran" class="bg-slate-900 text-rose-300" {{ request('filter_type') == 'Pelanggaran' ? 'selected' : '' }}>🔴 Pelanggaran</option>
                            <option value="Kebaikan" class="bg-slate-900 text-emerald-300" {{ request('filter_type') == 'Kebaikan' ? 'selected' : '' }}>🟢 Prestasi</option>
                        </select>
                        
                        {{-- Input Pencarian --}}
                        <div class="relative w-full sm:w-auto shrink-0 group">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
                                class="h-[42px] rounded-xl border-white/10 bg-slate-900/90 text-sm pl-9 pr-3 text-white placeholder:text-slate-500 focus:ring-[#56bbf1] focus:border-[#56bbf1] w-full sm:w-[180px] font-medium [color-scheme:dark]">
                            <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400 group-focus-within:text-sky-400"></i>
                        </div>
                        
                        {{-- Grup Tombol Aksi --}}
                        <div class="flex items-center gap-2 w-full sm:w-auto shrink-0">
                            <button type="submit" class="h-[42px] flex-1 sm:flex-none bg-gradient-to-r from-sky-500 to-blue-600 hover:from-sky-400 hover:to-blue-500 text-white px-5 rounded-xl text-sm font-bold transition-all flex items-center justify-center gap-2 border border-sky-400/30">
                                Cari
                            </button>
                            
                            @if(request('search') || request('filter_date') || request('filter_class') || request('filter_type'))
                                <a href="{{ route('discipline.index') }}" class="h-[42px] flex-1 sm:flex-none bg-white/5 hover:bg-white/10 text-slate-300 px-5 rounded-xl text-sm font-bold transition-colors flex items-center justify-center border border-white/10">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </form>
                </div>            
                <div class="overflow-x-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-white/5 border-b border-white/10">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Poin</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Petugas</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse ($historyRecords as $record)
                                @php
                                    $isViolation = optional($record->disciplineType)->type == 'Pelanggaran';
                                    $color = $isViolation ? 'rose' : 'emerald';
                                    $sign = $isViolation ? '-' : '+';
                                @endphp
                                <tr class="hover:bg-white/5 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-white">{{ $record->created_at->format('d/m H:i') }}</div>
                                        <div class="text-[10px] font-bold text-slate-500">{{ $record->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-white group-hover:text-sky-400 transition-colors">{{ $record->student?->name ?? '*Siswa Telah Dihapus*' }}</div>
                                        <div class="text-xs text-slate-400 font-medium">{{ $record->student?->schoolClass?->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-white">{{ $record->disciplineType?->name ?? '*Kategori Dihapus*' }}</div>
                                        @if($record->notes) 
                                            <div class="text-xs text-slate-400 italic mt-0.5 truncate max-w-xs">"{{ $record->notes }}"</div> 
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($isViolation)
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-black bg-rose-500/20 text-rose-300 border border-rose-500/30 shadow-sm">
                                                -{{ $record->disciplineType?->point_value ?? 0 }}
                                            </span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 shadow-sm">
                                                +{{ $record->disciplineType?->point_value ?? 0 }}
                                            </span>
                                        @endif
                                    </td>
                                   
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs font-bold text-slate-400">{{ $record->recorder?->name ?? 'Sistem' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">                                       
                                        <form action="{{ route('discipline.destroy', $record->id) }}" method="POST" class="form-delete-record">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-500 hover:text-rose-400 transition-colors p-2 rounded-lg hover:bg-rose-500/10" title="Hapus Riwayat">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-500">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 bg-white/5 border border-white/10 rounded-2xl flex items-center justify-center text-sky-400 mb-3">
                                                <i class="ph-duotone ph-clipboard-text text-3xl"></i>
                                            </div>
                                            <span class="font-bold text-slate-400">Belum ada data aktivitas.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-white/10 bg-white/5">
                    {{ $historyRecords->links() }}
                </div>
            </div>
            @endif

            <!-- BAGIAN 4: STATISTIK & KLASEMEN -->
            @if(isset($classSummaries) && isset($topViolators) && isset($topMerits))
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                {{-- A. REKAP PER KELAS --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-white/10 overflow-hidden shadow-2xl backdrop-blur-xl xl:col-span-1">
                    <div class="px-6 py-5 border-b border-white/10 bg-white/5 flex justify-between items-center">
                        <h3 class="text-sm font-black text-white uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-chalkboard-teacher text-sky-400"></i> Rekap Per Kelas
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-900/90 sticky top-0 z-10 border-b border-white/10">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Kelas</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Minus</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Plus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach ($classSummaries as $summary)
                                    <tr class="hover:bg-white/5 transition-colors">
                                        <td class="px-4 py-3 font-bold text-white text-sm">{{ $summary->class_name }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($summary->total_violation > 0)
                                                <span class="text-rose-300 text-xs font-bold bg-rose-500/20 px-1.5 py-0.5 rounded border border-rose-500/30">-{{ $summary->total_violation }}</span>
                                            @else <span class="text-slate-600 text-xs">-</span> @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($summary->total_merit > 0)
                                                <span class="text-emerald-300 text-xs font-bold bg-emerald-500/20 px-1.5 py-0.5 rounded border border-emerald-500/30">+{{ $summary->total_merit }}</span>
                                            @else <span class="text-slate-600 text-xs">-</span> @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- B. TOP 10 PELANGGARAN --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-rose-500/30 overflow-hidden shadow-2xl backdrop-blur-xl xl:col-span-1">
                    <div class="px-6 py-5 border-b border-rose-500/30 bg-rose-950/20 flex justify-between items-center">
                        <h3 class="text-sm font-black text-rose-400 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-warning-octagon"></i> Top 10 Pelanggaran
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-900/90 sticky top-0 z-10 border-b border-white/10">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase w-8">#</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Siswa</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach ($topViolators as $index => $summary)
                                    <tr class="hover:bg-rose-500/10 transition-colors">
                                        <td class="px-4 py-3 font-black text-rose-400 text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-white block text-sm">{{ $summary->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $summary->class_name ?? $summary->class }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-rose-400 font-black text-sm">-{{ $summary->total_violation }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- C. TOP 10 PRESTASI --}}
                <div class="bg-gradient-to-b from-[#031d3d]/90 via-[#021124]/95 to-[#020b18] rounded-[2rem] border border-emerald-500/30 overflow-hidden shadow-2xl backdrop-blur-xl xl:col-span-1">
                    <div class="px-6 py-5 border-b border-emerald-500/30 bg-emerald-950/20 flex justify-between items-center">
                        <h3 class="text-sm font-black text-emerald-400 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-medal"></i> Top 10 Prestasi
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-900/90 sticky top-0 z-10 border-b border-white/10">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase w-8">#</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Siswa</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @foreach ($topMerits as $index => $summary)
                                    <tr class="hover:bg-emerald-500/10 transition-colors">
                                        <td class="px-4 py-3 font-black text-emerald-400 text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-white block text-sm">{{ $summary->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $summary->class_name ?? $summary->class }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-emerald-400 font-black text-sm">+{{ $summary->total_merit }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- MODAL SCANNER QR CODE --}}
    <div id="qrModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen p-4 text-center">
            <div class="fixed inset-0 bg-slate-950/80 backdrop-blur-md transition-opacity" onclick="closeScanner()"></div>

            <div class="inline-block align-bottom bg-[#031d3d] rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:align-middle sm:max-w-md w-full relative border border-white/10 text-white">
                <div class="p-8">
                    <div class="absolute top-0 right-0 pt-6 pr-6">
                        <button onclick="closeScanner()" class="text-slate-400 hover:text-white bg-white/5 hover:bg-white/10 rounded-full p-2 transition border border-white/10">
                            <i class="ph-bold ph-x text-lg"></i>
                        </button>
                    </div>
                    <div class="text-center mt-2">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-2xl bg-white/5 border border-white/10 text-sky-400 mb-4">
                            <i class="ph-duotone ph-qr-code text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-black text-white mb-2">Scan Kartu Siswa</h3>
                        
                        <div class="relative w-full rounded-2xl overflow-hidden aspect-square bg-slate-950 border-4 border-white/10 shadow-inner mt-4">
                            <div id="reader" class="w-full h-full object-cover"></div>
                            <div id="scanner-status" class="absolute inset-0 flex items-center justify-center text-white text-xs font-bold z-10 pointer-events-none bg-slate-950/70">
                                Menunggu Kamera...
                            </div>
                        </div>

                        <div id="error-message" class="text-rose-400 text-xs font-bold mt-4 hidden bg-rose-950/40 p-3 rounded-xl border border-rose-500/30"></div>
                    </div>
                </div>
                <div class="bg-slate-900/90 px-8 py-5 border-t border-white/10">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-white/10 shadow-sm px-4 py-3 bg-white/5 text-base font-bold text-slate-300 hover:bg-white/10 hover:text-white transition-colors focus:outline-none sm:text-sm" onclick="closeScanner()">
                        Batal / Tutup Kamera
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT SCANNER & LOGIC --}}
    <script>
        let html5QrcodeScanner = null;
        let currentTargetInput = null;
        
        let tsViolation, tsMerit;

        document.addEventListener("DOMContentLoaded", function() {
            tsViolation = new TomSelect("#student_select_violation", { create: false, sortField: { field: "text", direction: "asc" }});
            tsMerit = new TomSelect("#student_select_merit", { create: false, sortField: { field: "text", direction: "asc" }});
            
            // SweetAlert Delete dengan Tema Gelap
            document.querySelectorAll('.form-delete-record').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Hapus Riwayat?',
                        text: 'Data poin siswa akan kembali disesuaikan. Yakin ingin menghapus?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e11d48',
                        cancelButtonColor: '#475569',
                        confirmButtonText: 'Ya, Hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true,
                        background: '#0f172a',
                        color: '#f8fafc',
                        customClass: {
                            popup: 'rounded-[2rem] font-sans border border-white/10 shadow-2xl',
                            confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-500 transition-colors mx-2 shadow-lg shadow-rose-950/40',
                            cancelButton: 'bg-slate-800 text-slate-300 px-6 py-3 rounded-xl font-bold hover:bg-slate-700 transition-colors mx-2'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        if (result.isConfirmed) this.submit();
                    });
                });
            });
        });

        function updateStatus(message) {
            const statusEl = document.getElementById('scanner-status');
            if(statusEl) statusEl.innerText = message;
        }

        function startScanner(targetInputId) {
            if (typeof Html5Qrcode === 'undefined') {
                Swal.fire({ icon: 'error', title: 'Error', text: 'Library Scanner belum siap.', background: '#0f172a', color: '#f8fafc', customClass: { popup: 'rounded-[2rem] border border-white/10' } });
                return;
            }

            currentTargetInput = targetInputId;
            const modal = document.getElementById('qrModal');
            const errorMsg = document.getElementById('error-message');
            const statusEl = document.getElementById('scanner-status');

            errorMsg.classList.add('hidden');
            modal.classList.remove('hidden');
            
            if(statusEl) {
                statusEl.style.display = 'flex';
                statusEl.innerText = "Memulai kamera...";
            }

            setTimeout(() => {
                if (html5QrcodeScanner === null) {
                    html5QrcodeScanner = new Html5Qrcode("reader");
                }

                Html5Qrcode.getCameras().then(devices => {
                    if (devices && devices.length) {
                        let cameraId = devices.length > 1 ? devices[devices.length - 1].id : devices[0].id;
                        updateStatus("Kamera aktif...");

                        html5QrcodeScanner.start(
                            cameraId, 
                            { fps: 10, qrbox: { width: 250, height: 250 } }, 
                            onScanSuccess
                        )
                        .then(() => {
                            if(statusEl) statusEl.style.display = 'none';
                        }).catch(err => {
                            showError("Gagal membuka kamera: " + err);
                        });
                    } else {
                        showError("Tidak ada kamera ditemukan.");
                    }
                }).catch(err => {
                    showError("Izin kamera ditolak atau tidak tersedia.");
                });
            }, 500);
        }

        function showError(msg) {
            const errorMsg = document.getElementById('error-message');
            if(errorMsg) {
                errorMsg.innerText = msg;
                errorMsg.classList.remove('hidden');
            }
        }

        function onScanSuccess(decodedText, decodedResult) {
            const scannedText = String(decodedText).trim();
            console.log("QR Terbaca:", scannedText);
            
            let selectElement = document.getElementById(currentTargetInput);            
            let found = false;
            let foundName = "";
            let foundValue = "";

            for (let i = 0; i < selectElement.options.length; i++) {
                const option = selectElement.options[i];
                if(option.value === "") continue; 
                
                const optValue = String(option.value).trim(); 
                const optNis = option.getAttribute('data-nis') ? String(option.getAttribute('data-nis')).trim() : '';
                const optNisn = option.getAttribute('data-nisn') ? String(option.getAttribute('data-nisn')).trim() : '';
                const optStudentId = option.getAttribute('data-student-id') ? String(option.getAttribute('data-student-id')).trim() : '';
                const optRfid = option.getAttribute('data-rfid') ? String(option.getAttribute('data-rfid')).trim() : '';

                if (optValue === scannedText || optNis === scannedText || optNisn === scannedText || optStudentId === scannedText || optRfid === scannedText) {
                    foundValue = optValue;
                    foundName = option.text;
                    found = true;
                    break;
                }

                if (/^\d+$/.test(scannedText)) {
                    const scanNum = parseInt(scannedText, 10);
                    const checkNum = (val) => val && /^\d+$/.test(val) && parseInt(val, 10) === scanNum;

                    if (checkNum(optNis) || checkNum(optNisn) || checkNum(optStudentId)) {
                        foundValue = optValue;
                        foundName = option.text;
                        found = true;
                        break;
                    }
                }
            }

            if (found) {
                playBeep();
                closeScanner();
                
                let targetSelect = (currentTargetInput === 'student_select_violation') ? tsViolation : tsMerit;
                targetSelect.setValue(foundValue);

                Swal.fire({
                    icon: 'success', 
                    title: 'Siswa Ditemukan!',
                    text: foundName, 
                    timer: 1500, 
                    showConfirmButton: false,
                    background: '#0f172a',
                    color: '#f8fafc',
                    customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' }
                });
            } else {
                if (navigator.vibrate) navigator.vibrate(200);
                
                console.warn("TIDAK DITEMUKAN. Pastikan data-nis/student-id di HTML sesuai dengan QR.");
                
                Swal.fire({
                    icon: 'error', 
                    title: 'Tidak Ditemukan',
                    text: `Kode terbaca: [${scannedText}] tidak ada di data siswa.`,
                    background: '#0f172a',
                    color: '#f8fafc',
                    customClass: { popup: 'rounded-[2rem] border border-white/10 shadow-2xl' }
                });
            }
        }

        function closeScanner() {
            const modal = document.getElementById('qrModal');
            
            if (modal) {
                modal.classList.add('hidden');
            }
            document.body.style.overflow = 'auto'; 

            if (html5QrcodeScanner) {
                try {
                    if (html5QrcodeScanner.getState() !== 1) { 
                        html5QrcodeScanner.stop().then(() => {
                            html5QrcodeScanner.clear();
                            console.log("Scanner cleaned up successfully.");
                        }).catch(e => {
                            console.warn("Library cleanup error (ignored):", e);
                            html5QrcodeScanner.clear();
                        });
                    }
                } catch (e) {
                    console.error("Scanner exception (ignored):", e);
                }
            }
            
            isScannerStopping = false;
        }

        function playBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const oscillator = audioCtx.createOscillator();
                const gainNode = audioCtx.createGain();
                oscillator.connect(gainNode);
                gainNode.connect(audioCtx.destination);
                oscillator.frequency.value = 880; 
                gainNode.gain.value = 0.1;
                oscillator.start();
                setTimeout(() => oscillator.stop(), 100);
            } catch (e) {
                console.log("Audio play failed");
            }
        }
    </script>
</x-app-layout>ayout>