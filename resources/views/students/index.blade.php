{{-- Halaman ini adalah tampilan untuk resources/views/students/index.blade.php --}}
<x-app-layout>
    <div class="py-6 sm:py-8">
        
        {{-- Header Page --}}
        <div class="mb-8">
            <h1 class="text-3xl font-black text-gray-800 tracking-tight leading-tight">
                Data Induk Siswa
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola data siswa, registrasi cepat, dan status kelengkapan buku induk.
            </p>
        </div>

        {{-- Flash Messages --}}
        @if (session('success'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-emerald-100 rounded-full text-emerald-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-emerald-400 hover:text-emerald-600">&times;</button>
            </div>
        @endif

        @if (session('error'))
            <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-rose-100 rounded-full text-rose-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
                <button @click="show = false" class="text-rose-400 hover:text-rose-600">&times;</button>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            {{-- KOLOM KIRI: QUICK REGISTER --}}
            <div class="lg:col-span-1 space-y-8">
                
                <!-- CARD: PENDAFTARAN CEPAT -->
                <div class="bg-white rounded-3xl shadow-sm border border-violet-100 overflow-hidden relative group hover:shadow-lg hover:shadow-violet-100/50 transition-all duration-300 h-fit">
                    <div class="absolute top-0 left-0 w-1.5 h-full bg-violet-500"></div>
                    <div class="p-6 md:p-8 relative z-10">
                        
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center text-xl shadow-sm border border-violet-100">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-black text-gray-800">Registrasi Cepat</h3>
                                <p class="text-xs text-gray-500">Input data dasar & foto</p>
                            </div>
                        </div>

                        {{-- Tambahkan enctype agar bisa upload foto --}}
                        <form action="{{ route('students.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf
                            
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">NIS / NISN *</label>
                                <input type="text" name="student_id" value="{{ old('student_id') }}" required placeholder="Nomor Induk"
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5 font-bold text-gray-700 transition-colors">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Nama Lengkap *</label>
                                <input type="text" name="name" value="{{ old('name') }}" required placeholder="Nama Sesuai Ijazah"
                                       class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5 font-bold text-gray-700 transition-colors">
                            </div>

                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Kelas *</label>
                                    <select name="class_id" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5 font-medium transition-colors">
                                        <option value="">Pilih</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->id }}" {{ old('class_id') == $class->id ? 'selected' : '' }}>
                                                {{ $class->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Gender *</label>
                                    <select name="gender" required class="w-full rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5 font-medium transition-colors">
                                        <option value="L">Laki-laki</option>
                                        <option value="P">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            {{-- INPUT FOTO DI SINI --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">Foto Siswa (Opsional)</label>
                                <input type="file" name="photo" accept="image/*" class="block w-full text-xs text-gray-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-xs file:font-semibold
                                    file:bg-violet-50 file:text-violet-700
                                    hover:file:bg-violet-100 border border-gray-200 rounded-xl
                                "/>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">ID Kartu RFID (Opsional)</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    </span>
                                    <input type="text" name="rfid_id" value="{{ old('rfid_id') }}" placeholder="Tempel Kartu..."
                                           class="w-full pl-10 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5 font-mono transition-colors">
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1.5">WA Orang Tua</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </span>
                                    <input type="text" name="parent_wa_number" value="{{ old('parent_wa_number') }}" placeholder="628..."
                                           class="w-full pl-10 rounded-xl border-gray-200 bg-gray-50 focus:bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5 font-mono transition-colors">
                                </div>
                            </div>

                            <div class="pt-2">
                                <button type="submit" class="w-full py-3 px-6 bg-gray-900 text-white font-bold rounded-xl hover:bg-gray-800 transition-all shadow-lg flex items-center justify-center gap-2 group-hover:translate-y-0.5">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    Simpan Data Dasar
                                </button>
                                <p class="text-[10px] text-center text-gray-400 mt-2">
                                    *Data lengkap (Buku Induk) diisi melalui menu <strong>Edit</strong>.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- CARD: IMPORT -->
                <div class="bg-gradient-to-br from-indigo-50 to-violet-50 rounded-3xl border border-violet-100 p-6 relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="text-sm font-black text-gray-800 mb-1">Import Massal (Excel)</h3>
                        <p class="text-[10px] text-gray-500 mb-3">Gunakan file Excel untuk input banyak data.</p>
                        
                        <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data" class="flex gap-2">
                            @csrf
                            <div class="relative flex-1 group">
                                <input type="file" name="file" id="file" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                                <div class="bg-white border border-dashed border-violet-300 rounded-lg py-2 px-3 text-center transition-all group-hover:border-violet-500 group-hover:bg-white/80 truncate">
                                    <span class="text-xs font-bold text-violet-600 truncate">Pilih File...</span>
                                </div>
                            </div>
                            <button type="submit" class="py-2 px-3 bg-violet-600 text-white font-bold rounded-lg hover:bg-violet-700 transition-colors text-xs">
                                Upload
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            {{-- KOLOM KANAN: DAFTAR SISWA --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col sticky top-6">
                    
                    {{-- Toolbar --}}
                    <div class="p-6 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-black text-gray-800">Daftar Siswa</h3>
                            <span class="text-xs font-bold bg-white px-2 py-1 rounded border border-gray-200 text-gray-500">{{ $students->total() }} Siswa</span>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2 w-full md:w-auto">
                            <form action="{{ route('students.index') }}" method="GET" class="flex gap-2 flex-1">
                                <div class="relative flex-1">
                                    <input type="text" name="search" placeholder="Cari nama / NISN..." value="{{ request('search') }}"
                                           class="w-full pl-9 pr-4 py-2.5 rounded-xl border-gray-200 bg-white focus:border-violet-500 focus:ring-violet-500 text-sm shadow-sm">
                                    <svg class="w-4 h-4 text-gray-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                
                                <select name="filter_class_id" onchange="this.form.submit()" class="rounded-xl border-gray-200 bg-white focus:border-violet-500 focus:ring-violet-500 text-sm py-2.5 shadow-sm">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ request('filter_class_id') == $class->id ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>

                            <a href="{{ route('students.export') }}" class="flex items-center justify-center px-4 py-2.5 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition-colors shadow-sm font-bold text-sm gap-2" title="Download Excel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                <span class="hidden sm:inline">Export</span>
                            </a>
                        </div>
                    </div>
                    
                    {{-- Table --}}
                    <div class="overflow-x-auto flex-1">
                        <table class="min-w-full text-left border-collapse">
                            <thead class="bg-white border-b border-gray-100">
                                <tr>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Identitas Siswa</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Kelas</th>
                                    <th class="px-6 py-4 text-xs font-bold text-gray-400 uppercase tracking-wider">Data Buku Induk</th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-400 uppercase tracking-wider">Aksi</th>
                                </div>
                            </thead>
                            <tbody class="divide-y divide-gray-50">
                                @forelse ($students as $student)
                                    <tr class="hover:bg-violet-50/30 transition-colors group">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center gap-3">
                                                {{-- LOGIKA AVATAR / FOTO DI SINI --}}
                                                <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-white shadow-sm bg-gray-100 flex-shrink-0">
                                                    @if($student->photo_path)
                                                        <img src="{{ asset('storage/' . $student->photo_path) }}" alt="{{ $student->name }}" class="w-full h-full object-cover">
                                                    @else
                                                        <div class="w-full h-full flex items-center justify-center font-bold text-sm 
                                                            {{ $student->gender == 'L' ? 'bg-blue-100 text-blue-600' : 'bg-pink-100 text-pink-600' }}">
                                                            {{ substr($student->name, 0, 2) }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 text-sm">{{ $student->name }}</div>
                                                    <div class="flex items-center gap-2 mt-0.5">
                                                        <span class="text-xs text-gray-400 font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $student->student_id }}</span>
                                                        @if($student->rfid_id)
                                                            <span class="text-[10px] text-green-600 bg-green-50 px-1.5 py-0.5 rounded border border-green-100 flex items-center gap-1">
                                                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                                                RFID
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 group-hover:bg-white group-hover:border-violet-200 group-hover:text-violet-700 transition-colors">
                                                {{ $student->schoolClass->name ?? 'Tanpa Kelas' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{-- Indikator Kelengkapan Data --}}
                                            @php
                                                $isComplete = $student->pob && $student->dob && $student->address && $student->father_name;
                                            @endphp

                                            @if($isComplete)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-600 border border-emerald-100">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Lengkap
                                                </span>
                                            @else
                                                <div class="flex flex-col items-start gap-1">
                                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-50 text-amber-600 border border-amber-100">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                        Belum Lengkap
                                                    </span>
                                                    @if(!$student->dob) <span class="text-[10px] text-gray-400 pl-1">- Tgl Lahir Kosong</span> @endif
                                                    @if(!$student->father_name) <span class="text-[10px] text-gray-400 pl-1">- Data Ortu Kosong</span> @endif
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
    <div class="flex items-center justify-end gap-1">
        
        {{-- TOMBOL VIEW/CETAK (BARU) --}}
        <a href="{{ route('students.show', $student->id) }}" 
           target="_blank"
           class="flex items-center gap-1 px-3 py-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all text-xs font-bold border border-blue-100" 
           title="Cetak Buku Induk">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak
        </a>

        {{-- Tombol Edit --}}
        <a href="{{ route('students.edit', $student->id) }}" 
           class="flex items-center gap-1 px-3 py-1.5 bg-violet-50 text-violet-600 hover:bg-violet-600 hover:text-white rounded-lg transition-all text-xs font-bold border border-violet-100" 
           title="Lengkapi Buku Induk">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
            Edit
        </a>
        
        {{-- Dropdown Menu --}}
        <div x-data="{ open: false }" class="relative">
                                                    <button @click="open = !open" @click.away="open = false" class="p-1.5 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path></svg>
                                                    </button>
                                                    
                                                    <div x-show="open" class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden" style="display: none;">
                                                        <button type="button"
                                                           @click="open = false"
                                                           data-student-id="{{ $student->id }}"
                                                           data-student-name="{{ $student->name }}"
                                                           class="w-full text-left px-4 py-2.5 text-xs font-medium text-gray-600 hover:bg-gray-50 hover:text-blue-600 flex items-center gap-2 open-absen-modal">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                            Input Absen Manual
                                                        </button>
                                                        <button type="button"
                                                           @click="open = false"
                                                           data-student-id="{{ $student->student_id }}"
                                                           data-student-name="{{ $student->name }}"
                                                           class="w-full text-left px-4 py-2.5 text-xs font-medium text-gray-600 hover:bg-gray-50 hover:text-green-600 flex items-center gap-2 open-qr-modal">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4h2v-4zM6 19v-4H4v4h2zM6 12V7a1 1 0 011-1h10a1 1 0 011 1v5m-4 0h4"></path></svg>
                                                            Lihat QR Code
                                                        </button>
                                                        <div class="border-t border-gray-100 my-1"></div>
                                                        <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini? Data buku induk akan hilang.');">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="w-full text-left px-4 py-2.5 text-xs font-medium text-rose-500 hover:bg-rose-50 flex items-center gap-2">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                                Hapus Siswa
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-400">
                                            <div class="flex flex-col items-center justify-center">
                                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mb-3">
                                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                                </div>
                                                <p class="text-sm font-medium">Belum ada data siswa.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{-- Pagination --}}
                    <div class="p-4 border-t border-gray-100">
                        {{ $students->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL ABSEN MANUAL (MODERN) --}}
    <div id="absen-manual-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-10 mx-auto p-0 border-0 w-full max-w-lg shadow-2xl rounded-2xl bg-white overflow-hidden">
            <div class="bg-violet-600 px-6 py-4 flex justify-between items-center">
                <h3 class="font-bold text-white text-lg">Input Absensi Manual</h3>
                <button type="button" id="absen-modal-close" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>

            <form id="absen-manual-form" action="{{ route('reports.storeManual') }}" method="POST" class="p-6">
                @csrf
                <input type="hidden" name="student_id" id="absen-modal-student-id">
                <input type="hidden" name="attendance_type" value="Harian">

                <div class="mb-6">
                    <p class="text-sm text-gray-500 mb-1">Siswa</p>
                    <p id="absen-modal-student-name" class="text-lg font-bold text-gray-800">Nama Siswa</p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Tanggal</label>
                        <input type="date" name="date" id="absen-tanggal" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-violet-500 focus:border-violet-500">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Status</label>
                        <select name="status" id="absen-status" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-violet-500 focus:border-violet-500" onchange="toggleTimeInput()">
                            <option value="Hadir">Hadir</option>
                            <option value="Sakit">Sakit</option>
                            <option value="Izin">Izin</option>
                            <option value="Alfa">Alfa</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-2 gap-4 p-4 bg-gray-50 rounded-xl border border-gray-100" id="time-input-container">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Masuk</label>
                            <input type="time" name="time_in" id="absen-time-in" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Pulang</label>
                            <input type="time" name="time_out" id="absen-time-out" class="block w-full rounded-lg border-gray-300 shadow-sm text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Catatan</label>
                        <textarea name="notes" id="absen-notes" rows="2" class="block w-full rounded-xl border-gray-300 shadow-sm focus:ring-violet-500 focus:border-violet-500"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" id="absen-modal-cancel" class="px-4 py-2 bg-white border border-gray-300 text-gray-700 font-bold rounded-xl hover:bg-gray-50">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-md">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL QR CODE (MODERN) --}}
    <div id="qr-code-modal" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm overflow-y-auto h-full w-full hidden z-50 transition-opacity">
        <div class="relative top-20 mx-auto p-0 border-0 w-full max-w-sm shadow-2xl rounded-2xl bg-white overflow-hidden">
            <div class="p-8 text-center">
                <h3 class="text-lg font-black text-gray-800 mb-1" id="qr-modal-student-name">QR Code Siswa</h3>
                <p class="text-xs text-gray-400 uppercase tracking-widest mb-6">Kartu Identitas Digital</p>
                
                <div class="bg-white p-4 border-2 border-dashed border-violet-200 rounded-2xl inline-block mb-6">
                    <img id="qr-modal-image" src="" alt="QR Code" class="w-48 h-48 object-contain">
                </div>
                
                <div class="flex gap-3 justify-center">
                    <button type="button" id="qr-modal-close" class="px-4 py-2 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200">Tutup</button>
                    <a id="qr-modal-download" href="#" download="qrcode.png" class="px-4 py-2 bg-violet-600 text-white font-bold rounded-xl hover:bg-violet-700 shadow-lg shadow-violet-200 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Unduh
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleTimeInput() {
            const status = document.getElementById('absen-status').value;
            const timeContainer = document.getElementById('time-input-container');
            
            if (status === 'Hadir') {
                timeContainer.classList.remove('hidden', 'opacity-50', 'pointer-events-none');
            } else {
                timeContainer.classList.add('hidden', 'opacity-50', 'pointer-events-none');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Absen Manual Modal
            const absenModal = document.getElementById('absen-manual-modal');
            const absenModalName = document.getElementById('absen-modal-student-name');
            const absenModalInputId = document.getElementById('absen-modal-student-id');
            const absenModalTimeIn = document.getElementById('absen-time-in');
            
            document.querySelectorAll('.open-absen-modal').forEach(button => {
                button.addEventListener('click', function() {
                    absenModalName.textContent = this.dataset.studentName;
                    absenModalInputId.value = this.dataset.studentId;
                    document.getElementById('absen-status').value = 'Hadir';
                    toggleTimeInput();
                    
                    const now = new Date();
                    const hours = String(now.getHours()).padStart(2, '0');
                    const minutes = String(now.getMinutes()).padStart(2, '0');
                    absenModalTimeIn.value = `${hours}:${minutes}`;
                    
                    absenModal.classList.remove('hidden');
                });
            });

            const closeAbsenFn = () => absenModal.classList.add('hidden');
            document.getElementById('absen-modal-close').addEventListener('click', closeAbsenFn);
            document.getElementById('absen-modal-cancel').addEventListener('click', closeAbsenFn);

            // QR Code Modal
            const qrModal = document.getElementById('qr-code-modal');
            const qrName = document.getElementById('qr-modal-student-name');
            const qrImage = document.getElementById('qr-modal-image');
            const qrDownload = document.getElementById('qr-modal-download');

            document.querySelectorAll('.open-qr-modal').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.dataset.studentId;
                    const name = this.dataset.studentName;
                    // Menggunakan Student ID (NISN) untuk QR Code
                    const url = `https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=${encodeURIComponent(id)}`;
                    
                    qrName.textContent = name;
                    qrImage.src = url;
                    qrDownload.href = url;
                    qrDownload.download = `QR_${name.replace(/\s+/g, '_')}.png`;
                    
                    qrModal.classList.remove('hidden');
                });
            });

            document.getElementById('qr-modal-close').addEventListener('click', () => qrModal.classList.add('hidden'));
        });
    </script>
</x-app-layout>