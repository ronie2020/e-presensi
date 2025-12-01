{{-- Halaman ini adalah tampilan untuk resources/views/students/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8 px-4 sm:px-0">
            <h1 class="text-3xl font-black text-slate-800 tracking-tight leading-tight flex items-center gap-3">
                <i class="ph-duotone ph-student text-blue-600"></i> Data Induk Siswa
            </h1>
            <p class="text-slate-500 mt-2 text-lg">
                Kelola data siswa, registrasi cepat, dan cetak buku induk.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 mx-4 sm:mx-0 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <i class="ph-bold ph-check"></i>
                    </div>
                    <span class="font-bold">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600 p-1 rounded-lg hover:bg-emerald-100"><i class="ph-bold ph-x"></i></button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" x-transition class="mb-6 mx-4 sm:mx-0 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                        <i class="ph-bold ph-warning"></i>
                    </div>
                    <span class="font-bold">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-400 hover:text-rose-600 p-1 rounded-lg hover:bg-rose-100"><i class="ph-bold ph-x"></i></button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-4 sm:px-0 items-start">

            {{-- KOLOM KIRI: QUICK REGISTER --}}
            <div class="lg:col-span-1 space-y-6">
                
                <!-- CARD: PENDAFTARAN CEPAT -->
                <div class="bg-white rounded-3xl shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] border border-slate-100 overflow-hidden relative group hover:shadow-lg hover:shadow-blue-500/5 transition-all duration-300 sticky top-24">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-blue-600"></div>
                    <div class="p-6 md:p-8 relative z-10">
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl shadow-sm border border-blue-100">
                                <i class="ph-duotone ph-user-plus"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-slate-800">Registrasi Cepat</h3>
                                <p class="text-xs text-slate-500 font-medium">Input data dasar siswa</p>
                            </div>
                        </div>

                        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4" x-data="{ photoPreview: null }">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">NIS / NISN *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-identification-card"></i>
                                    </div>
                                    <input type="text" name="student_id" value="{{ old('student_id') }}" required placeholder="Nomor Induk"
                                           class="w-full pl-10 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 font-bold text-slate-700 transition-colors placeholder:font-normal">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-user"></i>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Sesuai Ijazah"
                                           class="w-full pl-10 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 font-bold text-slate-700 transition-colors placeholder:font-normal">
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Kelas *</label>
                                    <select name="class_id" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 font-bold text-slate-700 transition-colors cursor-pointer">
                                        <option value="">Pilih</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Gender *</label>
                                    <select name="gender" required class="w-full rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 font-bold text-slate-700 transition-colors cursor-pointer">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- INPUT FOTO DENGAN PREVIEW --}}
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">Foto Siswa (Opsional)</label>
                                <div class="flex items-center gap-3">
                                    <div class="shrink-0">
                                        <div class="w-10 h-10 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center">
                                            <template x-if="photoPreview">
                                                <img :src="photoPreview" class="w-full h-full object-cover">
                                            </template>
                                            <template x-if="!photoPreview">
                                                <i class="ph-duotone ph-camera text-slate-400 text-xl"></i>
                                            </template>
                                        </div>
                                    </div>
                                    <input type="file" name="photo" accept="image/*" 
                                        @change="const file = $event.target.files[0]; const reader = new FileReader(); reader.onload = (e) => { photoPreview = e.target.result }; reader.readAsDataURL(file)"
                                        class="block w-full text-xs text-slate-500
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-full file:border-0
                                        file:text-xs file:font-bold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100 border border-slate-200 rounded-xl cursor-pointer
                                    "/>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">ID Kartu RFID (Opsional)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-scan"></i>
                                    </span>
                                    <input type="text" name="rfid_id" value="{{ old('rfid_id') }}" placeholder="Tempel Kartu..."
                                           class="w-full pl-10 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 font-mono font-bold text-slate-700 transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-1.5">WA Orang Tua</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-emerald-500">
                                        <i class="ph-bold ph-whatsapp-logo"></i>
                                    </span>
                                    <input type="text" name="parent_wa_number" value="{{ old('parent_wa_number') }}" placeholder="628..."
                                           class="w-full pl-10 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-sm py-2.5 font-mono font-bold text-slate-700 transition-colors">
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full py-3 px-6 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg flex items-center justify-center gap-2 group-hover:translate-y-0.5 transform active:scale-95">
                                    <i class="ph-bold ph-floppy-disk"></i>
                                    Simpan Data Dasar
                                </button>
                                <p class="text-[10px] text-center text-slate-400 mt-3">
                                    *Data lengkap (Buku Induk) diisi melalui menu <strong>Edit</strong>.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- CARD: IMPORT -->
                <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-3xl border border-blue-100 p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-sm font-black text-blue-900 mb-1 flex items-center gap-2">
                            <i class="ph-bold ph-microsoft-excel-logo text-emerald-600"></i> Import Massal
                        </h3>
                        <p class="text-[10px] text-blue-600/70 mb-3">Gunakan file Excel untuk input banyak data sekaligus.</p>
                        
                        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-2 items-center">
                            @csrf
                            <label class="flex-1 cursor-pointer group">
                                <div class="bg-white border border-dashed border-blue-300 rounded-xl py-2 px-3 text-center transition-all group-hover:border-blue-500 group-hover:bg-white/80 truncate">
                                    <span class="text-xs font-bold text-blue-600 truncate flex items-center justify-center gap-1">
                                        <i class="ph-bold ph-upload-simple"></i> Pilih File...
                                    </span>
                                </div>
                                <input type="file" name="file" id="file" required class="hidden">
                            </label>
                            <button type="submit" class="py-2.5 px-4 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 transition-colors text-xs shadow-md">
                                Upload
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: DAFTAR SISWA --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-slate-100 overflow-hidden h-full flex flex-col sticky top-6">
                    
                    {{-- Toolbar --}}
                    <div class="p-6 border-b border-slate-50 bg-white flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-black text-slate-800 flex items-center gap-2">
                                <i class="ph-duotone ph-users text-blue-500"></i> Daftar Siswa
                            </h3>
                            <span class="text-xs font-bold bg-slate-100 px-2 py-1 rounded border border-slate-200 text-slate-500">{{ $students->total() }} Data Ditemukan</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                            <form action="{{ route('students.index') }}" method="GET" class="flex gap-2 flex-1">
                                <div class="relative flex-1">
                                    <input type="text" name="search" placeholder="Cari nama / NISN..." value="{{ request('search') }}"
                                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-xs font-bold text-slate-700 shadow-sm">
                                    <div class="absolute left-3 top-2.5 text-slate-400">
                                        <i class="ph-bold ph-magnifying-glass"></i>
                                    </div>
                                </div>
                                
                                <select name="filter_class_id" onchange="this.form.submit()" class="rounded-xl border-slate-200 bg-slate-50 focus:bg-white focus:border-blue-500 focus:ring-blue-500 text-xs font-bold text-slate-700 py-2.5 shadow-sm cursor-pointer">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('filter_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <a href="{{ route('students.export') }}" class="flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors shadow-sm font-bold text-xs gap-2" title="Download Excel">
                                <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                                <span class="hidden sm:inline">Export</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Table --}}
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Identitas Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-wider">Status Data</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-slate-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse ($students as $student)
                                    <tr class="hover:bg-blue-50/30 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm bg-slate-100 flex-shrink-0">
                                                    @if($student->photo_path)
                                                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center font-bold text-xs 
                                                            {{ $student->gender == 'L' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600' }}">
                                                            {{ substr($student->name, 0, 2) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-800 text-sm">{{ $student->name }}</div>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <span class="text-[10px] text-slate-400 font-mono bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $student->student_id }}</span>
                                                        @if($student->rfid_id)
                                                            <span class="text-[10px] text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-100 flex items-center gap-1 font-bold">
                                                                <i class="ph-bold ph-wifi-high"></i> RFID
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600 border border-slate-200 group-hover:bg-white group-hover:border-blue-200 group-hover:text-blue-700 transition-colors">
                                                {{ $student->schoolClass->name ?? 'Unassigned' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $isComplete = $student->pob && $student->dob && $student->address && $student->father_name;
                                            @endphp

                                            @if($isComplete)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                    <i class="ph-fill ph-check-circle"></i> Lengkap
                                                </span>
                                            @else
                                                <div class="flex flex-col items-start gap-1">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                                        <i class="ph-fill ph-warning-circle"></i> Belum Lengkap
                                                    </span>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end gap-2">
                                                
                                                {{-- Cetak --}}
                                                <a href="{{ route('students.show', $student->id) }}" target="_blank" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Cetak Buku Induk">
                                                    <i class="ph-bold ph-printer text-lg"></i>
                                                </a>

                                                {{-- Edit --}}
                                                <a href="{{ route('students.edit', $student->id) }}" class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Data">
                                                    <i class="ph-bold ph-pencil-simple text-lg"></i>
                                                </a>
                                                
                                                {{-- Menu Lainnya (Dropdown Alpine) --}}
                                                <div x-data="{ open: false }" class="relative">
                                                    <button @click="open = !open" @click.away="open = false" class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                                                        <i class="ph-bold ph-dots-three-vertical text-lg"></i>
                                                    </button>
                                                    
                                                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-slate-100 z-50 overflow-hidden py-1" style="display: none;">
                                                        <!-- TOMBOL INPUT ABSEN (MODAL) -->
                                                        <button type="button" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-blue-600 flex items-center gap-2 open-absen-modal"
                                                            data-student-id="{{ $student->id }}" data-student-name="{{ $student->name }}">
                                                            <i class="ph-bold ph-user-check"></i> Input Absen
                                                        </button>
                                                        
                                                        <!-- TOMBOL LIHAT QR (MODAL) -->
                                                        <button type="button" class="w-full text-left px-4 py-2.5 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-green-600 flex items-center gap-2 open-qr-modal"
                                                            data-student-id="{{ $student->student_id }}" data-student-name="{{ $student->name }}">
                                                            <i class="ph-bold ph-qr-code"></i> Lihat QR Code
                                                        </button>
                                                        
                                                        <div class="border-t border-slate-100 my-1"></div>
                                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini? Data buku induk akan hilang.');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-bold text-rose-500 hover:bg-rose-50 flex items-center gap-2">
                                                                <i class="ph-bold ph-trash"></i> Hapus Siswa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-3 text-slate-300">
                                                    <i class="ph-duotone ph-users-three text-4xl"></i>
                                                </div>
                                                <p class="text-sm font-bold text-slate-500">Belum ada data siswa.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4 border-t border-slate-100">
                        {{ $students->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ABSEN & QR CODE (DIPERBARUI SESUAI REQUEST) --}}
    <div id="absen-manual-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-md shadow-2xl rounded-2xl bg-white overflow-hidden">
            <div class="bg-blue-600 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white text-lg">Input Absensi Manual</h3>
                <button type="button" id="absen-modal-close" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
            <form id="absen-manual-form" action="{{ route('reports.storeManual') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <input type="hidden" name="student_id" id="absen-modal-student-id">
                <input type="hidden" name="attendance_type" value="Harian">
                
                <div class="text-center mb-2">
                    <p class="text-xs text-slate-400 uppercase font-bold tracking-wide">Siswa</p>
                    <h4 id="absen-modal-student-name" class="text-xl font-black text-slate-800">Nama Siswa</h4>
                </div>

                {{-- FIELD BARU: TANGGAL --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Tanggal</label>
                    <input type="date" name="date" value="{{ date('Y-m-d') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-700">
                </div>

                {{-- FIELD: STATUS --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Status Kehadiran</label>
                    <select name="status" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500 focus:border-blue-500 font-bold text-slate-700">
                        <option value="Hadir">Hadir (Manual)</option>
                        <option value="Sakit">Sakit</option>
                        <option value="Izin">Izin</option>
                        <option value="Alfa">Alfa</option>
                        <option value="Terlambat">Terlambat</option>
                    </select>
                </div>

                {{-- FIELD BARU: JAM MASUK & PULANG --}}
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Waktu Masuk</label>
                        <input type="time" name="time_in" value="{{ now()->format('H:i') }}" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500 focus:border-blue-500 text-center font-mono font-bold text-slate-700">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Waktu Pulang</label>
                        <input type="time" name="time_out" class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500 focus:border-blue-500 text-center font-mono font-bold text-slate-700">
                    </div>
                </div>
                
                {{-- FIELD: KETERANGAN --}}
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase mb-1.5">Keterangan Tambahan (Opsional)</label>
                    <textarea name="notes" rows="2" placeholder="Contoh: Datang terlambat karena ban bocor..." class="w-full rounded-xl border-slate-200 bg-slate-50 focus:ring-blue-500 focus:border-blue-500 text-sm"></textarea>
                </div>

                <button type="submit" class="w-full py-3 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30 transition-transform active:scale-95">Simpan Data</button>
            </form>
        </div>
    </div>

    <div id="qr-code-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-sm shadow-2xl rounded-2xl bg-white overflow-hidden text-center p-8">
            <h3 class="text-lg font-black text-slate-800 mb-1" id="qr-modal-student-name">QR Code</h3>
            <p class="text-xs text-slate-400 font-bold uppercase mb-6">Identitas Digital Siswa</p>
            <div class="bg-white p-4 border-2 border-dashed border-blue-200 rounded-2xl inline-block mb-6">
                <img id="qr-modal-image" src="" alt="QR Code" class="w-48 h-48 object-contain">
            </div>
            <div class="flex gap-3 justify-center">
                <button type="button" id="qr-modal-close" class="px-6 py-2 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200">Tutup</button>
                <a id="qr-modal-download" href="#" download="qrcode.png" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-xl hover:bg-blue-700 shadow-lg">Unduh</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Modal Logic (Simplified)
            const absenModal = document.getElementById('absen-manual-modal');
            const qrModal = document.getElementById('qr-code-modal');

            document.querySelectorAll('.open-absen-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('absen-modal-student-name').innerText = this.dataset.studentName;
                    document.getElementById('absen-modal-student-id').value = this.dataset.studentId;
                    absenModal.classList.remove('hidden');
                });
            });

            document.querySelectorAll('.open-qr-modal').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.studentId;
                    const name = this.dataset.studentName;
                    const url = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(id)}`;
                    document.getElementById('qr-modal-student-name').innerText = name;
                    document.getElementById('qr-modal-image').src = url;
                    document.getElementById('qr-modal-download').href = url;
                    qrModal.classList.remove('hidden');
                });
            });

            document.getElementById('absen-modal-close').onclick = () => absenModal.classList.add('hidden');
            document.getElementById('qr-modal-close').onclick = () => qrModal.classList.add('hidden');
        });
    </script>

</x-app-layout>