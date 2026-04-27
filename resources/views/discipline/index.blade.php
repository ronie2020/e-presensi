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

        /* Customisasi TomSelect agar sesuai dengan tema Tailwind (Warna teks disesuaikan ke Elevate) */
        .ts-control { border-radius: 1rem !important; border: 1px solid #e2e8f0 !important; background-color: #f8fafc !important; padding: 0.875rem 1rem !important; font-size: 0.875rem !important; font-weight: 700 !important; color: #1e293b !important;}
        .ts-control.focus { border-color: #f43f5e !important; box-shadow: none !important; background-color: white !important;} /* Merah untuk pelanggaran */
        #student_select_merit-ts-control.focus { border-color: #10b981 !important; } /* Hijau untuk kebaikan */
        .ts-dropdown { border-radius: 1rem !important; overflow: hidden !important; border: 1px solid #e2e8f0 !important; font-size: 0.875rem !important; font-weight: 500 !important;}
    </style>

    <div class="py-8 sm:py-10 font-sans text-elevate-text">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
             {{-- HERO SECTION MICROSOFT ELEVATE THEME --}}
            <div class="relative rounded-[2rem] bg-elevate-gradient-main p-8 mb-8 text-elevate-dark shadow-xl shadow-elevate-accent/10 overflow-hidden border border-white/60">
                {{-- Abstract Shapes Ornaments --}}
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-elevate-primary/10 rounded-3xl rotate-12 pointer-events-none backdrop-blur-3xl"></div>
                <div class="absolute -bottom-20 -right-10 w-64 h-64 bg-elevate-peach/20 rounded-[3rem] -rotate-12 pointer-events-none backdrop-blur-2xl"></div>
                <div class="absolute top-10 right-32 w-24 h-24 bg-white/40 rounded-2xl rotate-45 pointer-events-none shadow-sm"></div>
                
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center md:items-start gap-6">
                    <div class="text-center md:text-left">
                        <h1 class="text-3xl font-extrabold tracking-tight mb-2 flex items-center justify-center md:justify-start gap-3 text-elevate-dark">
                            <div class="w-10 h-10 rounded-xl bg-elevate-accent/20 text-elevate-primary flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-clipboard-text text-xl"></i>
                            </div>
                            Catatan Kedisiplinan
                        </h1>
                        <p class="text-elevate-dark/80 text-sm font-medium leading-relaxed max-w-lg ml-0 md:ml-12">
                            Kelola poin siswa, pantau klasemen pelanggaran, dan lihat rekapitulasi per kelas dalam satu dashboard yang terintegrasi.
                        </p>
                    </div>
                    <div class="flex flex-wrap justify-center gap-3">
                        {{-- TOMBOL ANALITIK --}}
                        <a href="{{ route('discipline.analytics') }}" class="group bg-white text-elevate-dark px-5 py-3.5 rounded-2xl font-bold text-sm transition-all hover:bg-slate-50 flex items-center gap-2 shadow-lg shadow-elevate-dark/5 border border-white active:scale-95">
                            <div class="w-7 h-7 rounded-full bg-elevate-accent/20 text-elevate-primary flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="ph-bold ph-chart-line-up text-sm"></i>
                            </div>
                            <span>Statistik & Analitik</span>
                        </a>
                        {{-- TOMBOL PENGATURAN --}}
                        <a href="{{ route('discipline-types.index') }}" class="group bg-white/60 backdrop-blur-md hover:bg-white text-elevate-dark px-5 py-3.5 rounded-2xl font-bold text-sm border border-white transition-all flex items-center gap-2 shadow-sm active:scale-95">
                            <i class="ph-bold ph-gear text-lg text-elevate-primary group-hover:rotate-90 transition-transform"></i>
                            <span>Atur Poin</span>
                        </a>
                    </div>                    
                </div>
            </div>

            {{-- Pesan Flash Sukses --}}
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" class="mb-8 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[1.5rem] flex items-center justify-between shadow-sm animate-in slide-in-from-top-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600"><i class="ph-bold ph-check"></i></div>
                        <span class="font-bold text-sm">{{ session('success') }}</span>
                    </div>
                    <button @click="show = false" class="p-2 hover:bg-emerald-100 rounded-lg transition"><i class="ph-bold ph-x"></i></button>
                </div>
            @endif

            <!-- BAGIAN 1: FORM INPUT (GRID 2 KOLOM) -->
            <!-- Catatan: Tema warna Merah & Hijau dipertahankan karena merupakan warna semantik untuk UX Poin Kedisiplinan -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-10">
                
                <!-- KIRI: Form Pelanggaran (Tema Merah / Tetap Dipertahankan) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-rose-900/5 border border-slate-100 overflow-visible relative group hover:border-rose-100 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-rose-500 rounded-t-[2.5rem]"></div>
                    <div class="p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-rose-100 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-warning-octagon"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-elevate-dark">Input Pelanggaran</h3>
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
                                        <!-- Menambahkan placeholder agar rapi dengan TomSelect -->
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
                                    <button type="button" onclick="startScanner('student_select_violation')" class="shrink-0 bg-elevate-dark text-white w-[52px] h-[52px] rounded-2xl hover:bg-elevate-primary transition-colors shadow-lg flex items-center justify-center" title="Scan QR Code">
                                        <i class="ph-bold ph-qr-code text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- JENIS PELANGGARAN --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Pelanggaran</label>
                                <div class="relative">
                                    <select name="discipline_type_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm font-bold text-elevate-dark py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($violationTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }} (-{{ $type->point_value }} Poin)</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            {{-- CATATAN --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Kronologi / Catatan</label>
                                <textarea name="notes" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-rose-500 focus:ring-rose-500 text-sm font-medium p-4 text-elevate-dark" placeholder="Jelaskan singkat kejadiannya..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-rose-600 text-white font-bold rounded-2xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-200 flex items-center justify-center gap-2 mt-2 transform active:scale-95">
                                <i class="ph-bold ph-warning-circle text-lg"></i>
                                Simpan Pelanggaran
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KANAN: Form Kebaikan (Tema Hijau / Tetap Dipertahankan) -->
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-emerald-900/5 border border-slate-100 overflow-visible relative group hover:border-emerald-100 transition-all duration-300">
                    <div class="absolute top-0 left-0 w-full h-1.5 bg-emerald-500 rounded-t-[2.5rem]"></div>
                    <div class="p-8 relative z-10">
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-emerald-100 group-hover:scale-110 transition-transform duration-300">
                                <i class="ph-duotone ph-medal"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-elevate-dark">Input Prestasi</h3>
                                <p class="text-xs font-bold text-emerald-500 uppercase tracking-wider">Tambah Poin (+)</p>
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
                                    <button type="button" onclick="startScanner('student_select_merit')" class="shrink-0 bg-elevate-dark text-white w-[52px] h-[52px] rounded-2xl hover:bg-elevate-primary transition-colors shadow-lg flex items-center justify-center" title="Scan QR Code">
                                        <i class="ph-bold ph-qr-code text-xl"></i>
                                    </button>
                                </div>
                            </div>
                            
                            {{-- JENIS PRESTASI --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Jenis Kebaikan</label>
                                <div class="relative">
                                    <select name="discipline_type_id" required class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm font-bold text-elevate-dark py-3.5 pl-4 pr-10 appearance-none cursor-pointer">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($meritTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }} (+{{ $type->point_value }} Poin)</option>
                                        @endforeach
                                    </select>
                                    <div class="absolute inset-y-0 right-4 flex items-center pointer-events-none text-slate-400"><i class="ph-bold ph-caret-down"></i></div>
                                </div>
                            </div>
                            
                            {{-- CATATAN --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2 ml-1">Detail Tambahan</label>
                                <textarea name="notes" rows="2" class="w-full rounded-2xl border-slate-200 bg-slate-50 focus:bg-white focus:border-emerald-500 focus:ring-emerald-500 text-sm font-medium p-4 text-elevate-dark" placeholder="Keterangan prestasi..."></textarea>
                            </div>

                            <button type="submit" class="w-full py-3.5 bg-emerald-600 text-white font-bold rounded-2xl hover:bg-emerald-700 transition-all shadow-lg shadow-emerald-200 flex items-center justify-center gap-2 mt-2 transform active:scale-95">
                                <i class="ph-bold ph-star text-lg"></i>
                                Simpan Kebaikan
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- BAGIAN 3: RIWAYAT / LOG -->
            @if(isset($historyRecords))
            <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden mb-10">
                <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col lg:flex-row justify-between items-center gap-4">
                    <div>
                        <h3 class="text-xl font-black text-elevate-dark">Log Aktivitas</h3>
                        <p class="text-sm font-medium text-slate-400 mb-2 lg:mb-0">Riwayat input poin terbaru.</p>
                    </div>
                
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <form action="{{ route('discipline.index') }}" method="GET" class="flex flex-col sm:flex-row w-full gap-2">
                            <input type="date" name="filter_date" value="{{ request('filter_date') }}" 
                                class="rounded-xl border-slate-200 text-sm py-2 px-3 text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary w-full sm:w-auto">
                            
                            <div class="relative w-full sm:w-auto">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
                                    class="rounded-xl border-slate-200 text-sm py-2 pl-9 pr-3 text-elevate-dark focus:ring-elevate-primary focus:border-elevate-primary w-full">
                                <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 transform -translate-y-1/2 text-slate-400"></i>
                            </div>
                            
                            <button type="submit" class="bg-elevate-primary hover:bg-elevate-dark text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm transition-colors flex items-center justify-center gap-2">
                                Cari
                            </button>
                            
                            @if(request('search') || request('filter_date'))
                                <a href="{{ route('discipline.index') }}" class="bg-slate-200 hover:bg-slate-300 text-elevate-dark px-4 py-2 rounded-xl text-sm font-bold transition-colors flex items-center justify-center">
                                    Reset
                                </a>
                            @endif
                        </form>

                        <div class="text-xs font-bold text-slate-500 bg-white px-3 py-2 rounded-xl border border-slate-200 shadow-sm whitespace-nowrap">
                            {{ $historyRecords->total() }} Data
                        </div>
                    </div>
                </div>             
                <div class="overflow-x-auto w-full custom-scrollbar">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Waktu</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Siswa</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Keterangan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Poin</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-right">Petugas</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse ($historyRecords as $record)
                                @php
                                    $isViolation = optional($record->disciplineType)->type == 'Pelanggaran';
                                    $color = $isViolation ? 'rose' : 'emerald';
                                    $sign = $isViolation ? '-' : '+';
                                @endphp
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-elevate-dark">{{ $record->created_at->format('d/m H:i') }}</div>
                                        <div class="text-[10px] font-bold text-slate-400">{{ $record->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-elevate-dark">{{ $record->student->name }}</div>
                                        <div class="text-xs text-slate-500 font-medium">{{ $record->student->schoolClass->name ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-elevate-dark">{{ $record->disciplineType->name ?? '-' }}</div>
                                        @if($record->notes) 
                                            <div class="text-xs text-slate-500 italic mt-0.5 truncate max-w-xs">"{{ $record->notes }}"</div> 
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-black bg-{{ $color }}-100 text-{{ $color }}-700 border border-{{ $color }}-200 shadow-sm">
                                            {{ $sign }}{{ $record->disciplineType->point_value ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="text-xs font-bold text-slate-500">{{ $record->recorder->name ?? 'Sistem' }}</span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <form action="{{ route('discipline.destroy', $record->id) }}" method="POST" 
                                            onsubmit="event.preventDefault(); 
                                                        const form = this;
                                                        Swal.fire({
                                                            title: 'Hapus Riwayat?',
                                                            text: 'Data poin siswa akan kembali disesuaikan. Yakin ingin menghapus?',
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#e11d48',
                                                            cancelButtonColor: '#94a3b8',
                                                            confirmButtonText: 'Ya, Hapus!',
                                                            cancelButtonText: 'Batal',
                                                            reverseButtons: true,
                                                            customClass: {
                                                                popup: 'rounded-[2rem] font-sans border-0 shadow-2xl',
                                                                confirmButton: 'bg-rose-600 text-white px-6 py-3 rounded-xl font-bold hover:bg-rose-700 transition-colors mx-2 shadow-lg shadow-rose-900/20',
                                                                cancelButton: 'bg-slate-100 text-slate-600 px-6 py-3 rounded-xl font-bold hover:bg-slate-200 transition-colors mx-2'
                                                            },
                                                            buttonsStyling: false
                                                        }).then((result) => {
                                                            if (result.isConfirmed) form.submit();
                                                        });">
                                            @csrf 
                                            @method('DELETE')
                                            <button type="submit" class="text-slate-300 hover:text-rose-500 transition-colors p-2 rounded-lg hover:bg-rose-50" title="Hapus Riwayat">
                                                <i class="ph-bold ph-trash text-lg"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                        <div class="flex flex-col items-center">
                                            <i class="ph-duotone ph-clipboard-text text-3xl mb-2 text-slate-300"></i>
                                            <span>Belum ada data aktivitas.</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 border-t border-slate-50 bg-slate-50/30">
                    {{ $historyRecords->links() }}
                </div>
            </div>
            @endif

            <!-- BAGIAN 4: STATISTIK & KLASEMEN -->
            @if(isset($classSummaries) && isset($topViolators) && isset($topMerits))
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                {{-- A. REKAP PER KELAS --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm xl:col-span-1">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-elevate-dark uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-chalkboard-teacher"></i> Rekap Per Kelas
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Kelas</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Minus</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Plus</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($classSummaries as $summary)
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-4 py-3 font-bold text-elevate-dark text-sm">{{ $summary->class_name }}</td>
                                        <td class="px-4 py-3 text-center">
                                            @if($summary->total_violation > 0)
                                                <span class="text-rose-600 text-xs font-bold bg-rose-50 px-1.5 py-0.5 rounded border border-rose-100">-{{ $summary->total_violation }}</span>
                                            @else <span class="text-slate-300 text-xs">-</span> @endif
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            @if($summary->total_merit > 0)
                                                <span class="text-emerald-600 text-xs font-bold bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100">+{{ $summary->total_merit }}</span>
                                            @else <span class="text-slate-300 text-xs">-</span> @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- B. TOP 10 PELANGGARAN --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm xl:col-span-1">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-rose-600 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-warning-octagon"></i> Top 10 Pelanggaran
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase w-8">#</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Siswa</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($topViolators as $index => $summary)
                                    <tr class="hover:bg-rose-50/30 transition-colors">
                                        <td class="px-4 py-3 font-bold text-rose-300 text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-elevate-dark block text-sm">{{ $summary->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $summary->class_name ?? $summary->class }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-rose-600 font-black text-sm">-{{ $summary->total_violation }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- C. TOP 10 PRESTASI --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm xl:col-span-1">
                    <div class="px-6 py-5 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
                        <h3 class="text-sm font-bold text-emerald-600 uppercase tracking-wider flex items-center gap-2">
                            <i class="ph-bold ph-medal"></i> Top 10 Prestasi
                        </h3>
                    </div>
                    <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 sticky top-0 z-10 shadow-sm">
                                <tr>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase w-8">#</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase">Siswa</th>
                                    <th class="px-4 py-3 text-xs font-bold text-slate-400 uppercase text-center">Poin</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($topMerits as $index => $summary)
                                    <tr class="hover:bg-emerald-50/30 transition-colors">
                                        <td class="px-4 py-3 font-bold text-emerald-300 text-sm">{{ $loop->iteration }}</td>
                                        <td class="px-4 py-3">
                                            <span class="font-bold text-elevate-dark block text-sm">{{ $summary->name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase">{{ $summary->class_name ?? $summary->class }}</span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span class="text-emerald-600 font-black text-sm">+{{ $summary->total_merit }}</span>
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
            <div class="fixed inset-0 bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeScanner()"></div>

            <div class="inline-block align-bottom bg-white rounded-[2rem] text-left overflow-hidden shadow-2xl transform transition-all sm:align-middle sm:max-w-md w-full relative">
                <div class="bg-white p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-elevate-accent/20 mb-4">
                            <i class="ph-duotone ph-qr-code text-3xl text-elevate-primary"></i>
                        </div>
                        <h3 class="text-xl font-black text-elevate-dark mb-2">Scan Kartu Siswa</h3>
                        
                        <div class="relative w-full rounded-2xl overflow-hidden aspect-square bg-black border-4 border-slate-900 shadow-inner">
                            <div id="reader" class="w-full h-full object-cover"></div>
                            <div id="scanner-status" class="absolute inset-0 flex items-center justify-center text-white text-xs font-bold z-10 pointer-events-none bg-black/50">
                                Menunggu Kamera...
                            </div>
                        </div>

                        <div id="error-message" class="text-rose-500 text-xs font-bold mt-4 hidden bg-rose-50 p-2 rounded-lg"></div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-2">
                    <button type="button" class="w-full inline-flex justify-center rounded-xl border border-slate-200 shadow-sm px-4 py-3 bg-white text-base font-bold text-slate-700 hover:bg-slate-100 focus:outline-none sm:w-auto sm:text-sm" onclick="closeScanner()">
                        Batal / Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT SCANNER & LOGIC --}}
    <script>
        let html5QrcodeScanner = null;
        let currentTargetInput = null;
        
        // Deklarasi instance TomSelect
        let tsViolation, tsMerit;

        document.addEventListener("DOMContentLoaded", function() {
            // Aktifkan Fitur Search pada Dropdown
            tsViolation = new TomSelect("#student_select_violation", { create: false, sortField: { field: "text", direction: "asc" }});
            tsMerit = new TomSelect("#student_select_merit", { create: false, sortField: { field: "text", direction: "asc" }});
        });

        function updateStatus(message) {
            const statusEl = document.getElementById('scanner-status');
            if(statusEl) statusEl.innerText = message;
        }

        function startScanner(targetInputId) {
            if (typeof Html5Qrcode === 'undefined') {
                Swal.fire('Error', 'Library Scanner belum siap.', 'error');
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
            // 1. Bersihkan teks hasil scan
            const scannedText = String(decodedText).trim();
            
            console.log("QR Terbaca:", scannedText);
            
            // Kita baca dari select aslinya
            let selectElement = document.getElementById(currentTargetInput);            
            let found = false;
            let foundName = "";
            let foundValue = "";

            // 2. Loop mencari data di dropdown
            for (let i = 0; i < selectElement.options.length; i++) {
                const option = selectElement.options[i];
                if(option.value === "") continue; 
                
                // Ambil data atribut
                const optValue = String(option.value).trim(); // Student ID (PK)
                const optNis = option.getAttribute('data-nis') ? String(option.getAttribute('data-nis')).trim() : '';
                const optNisn = option.getAttribute('data-nisn') ? String(option.getAttribute('data-nisn')).trim() : '';
                const optStudentId = option.getAttribute('data-student-id') ? String(option.getAttribute('data-student-id')).trim() : '';
                const optRfid = option.getAttribute('data-rfid') ? String(option.getAttribute('data-rfid')).trim() : '';

                // --- LOGIKA 1: EXACT MATCH ---
                if (optValue === scannedText || optNis === scannedText || optNisn === scannedText || optStudentId === scannedText || optRfid === scannedText) {
                    foundValue = optValue;
                    foundName = option.text;
                    found = true;
                    break;
                }

                // --- LOGIKA 2: NUMBER MATCH ---
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
                
                // Set nilai ke UI TomSelect
                let targetSelect = (currentTargetInput === 'student_select_violation') ? tsViolation : tsMerit;
                targetSelect.setValue(foundValue);

                Swal.fire({
                    icon: 'success', 
                    title: 'Siswa Ditemukan!',
                    text: foundName, 
                    timer: 1500, 
                    showConfirmButton: false,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            } else {
                if (navigator.vibrate) navigator.vibrate(200);
                
                console.warn("TIDAK DITEMUKAN. Pastikan data-nis/student-id di HTML sesuai dengan QR.");
                
                Swal.fire({
                    icon: 'error', 
                    title: 'Tidak Ditemukan',
                    text: `Kode terbaca: [${scannedText}] tidak ada di data siswa.`,
                    customClass: { popup: 'rounded-[2rem]' }
                });
            }
        }

         function closeScanner() {
            const modal = document.getElementById('qrModal');
            
            // 1. Prioritas Utama: Sembunyikan UI seketika
            if (modal) {
                modal.classList.add('hidden');
            }
            document.body.style.overflow = 'auto'; // Kembalikan scroll body

            // 2. Bersihkan scanner dengan aman (tanpa menghambat penutupan UI)
            if (html5QrcodeScanner) {
                try {
                    // Gunakan try-catch agar jika library gagal/error saat stop, UI tetap tertutup
                    if (html5QrcodeScanner.getState() !== 1) { // 1 = NOT_STARTED
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
</x-app-layout>